<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/13
 * Time: 19:58
 */

namespace App\Http\Logics\Invest;

use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\Bonus\UserBonusDb;
use App\Http\Dbs\Current\InvestDb;
use App\Http\Dbs\Current\ProjectDb;
use App\Http\Dbs\Current\RateDb;
use App\Http\Logics\Logic;
use App\Http\Logics\User\PasswordLogic;
use App\Http\Models\Bonus\UserBonusModel;
use App\Http\Models\Common\DbKvdbModel;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\Common\TradingPasswordModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Current\RateModel;
use App\Http\Models\Invest\CurrentModel;
use App\Http\Models\Project\CurrentModel as CurrentProjectModel;
use App\Http\Models\User\UserModel;
use App\Http\Models\Common\PasswordModel;
use App\Http\Dbs\Current\FundStatisticsDb;
use App\Lang\LangModel;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Cache;

class CurrentLogic extends Logic{


    private $investModel    = null;

    private $projectModel   = null;
    /**
     * 1.零钱计划限额 最低 最高
     * 2.项目可投资金额
     * 3.帐户余额
     * 4.交易密码
     * 5.若有加息券,判断加息券使用情况
     */
    public function doInvest($data, $isTrade=true){

        $this->investModel    = new CurrentModel();

        $projectId = 0;

        try{


            $userId         = $data['user_id'];          //用户ID
            $cash           = $data['cash'];             //投资金额
            $password       = empty($data['trading_password'])?'':$data['trading_password'];  //交易密码
            $bonusId        = $data['bonus_id'];        //加息券ID
            $from           = $data['from'];

            self::beginTransaction();

            $this->projectModel   = new CurrentProjectModel();

            if($isTrade){

                $passwordLogic = new PasswordLogic();

                $checkRes = $passwordLogic->checkTradingPasswordForApp($password, $userId);

                if( !$checkRes['status'] ){

                    return self::callError($checkRes['msg']);

                }

            }

            //投资前的条件检查
            $projectInfo          = $this->checkInvest($userId,$cash,$password,$bonusId,$from, $isTrade);

            $projectId            = $projectInfo['id'];
            //项目锁定
            $this->investModel->addLock($projectId);

            //调用核心接口进行零钱计划投资
            $this->investModel->doInvest($userId,$cash);

            $leftAmount           = $projectInfo['left_amount'];
            //更新零钱计划项目投资金额
            $this->projectModel->editProjectInvestAmount($projectId,$cash);

            //记录零钱计划投资记录
            $currentInvestDb = new InvestDb();
            $currentInvestDb->doInvest($userId,$cash,$from);
            $params = [
                'event_name'    => 'App\Events\Invest\CurrentSuccessEvent',
                'event_desc'    => '零钱计划投资成功事件',
                'bonus_id'      => $bonusId,                    //红包ID
                'user_id'       => $userId,                     //用户ID
                'cash'          => $cash,                       //用户零钱计划转入金额
                'left_amount'   => $leftAmount, //项目剩余可投金额
            ];

            \Event::fire(new \App\Events\Invest\CurrentSuccessEvent($params));

            self::commit();

            //解锁
            $this->investModel->releaseLock($projectId);

        }catch(\Exception $e){

            self::rollback();

            if($projectId > 0){
                //解锁
                $this->investModel->releaseLock($projectId);
            }

            
            return self::callError($e->getMessage());
        }
        
        return self::callSuccess();
    }

    /**
     * @param $userId
     * @param $cash
     * @param $password
     * @param bonusId
     * @param $from
     * @throws \Exception
     * 零钱计划投资检查
     */
    private function checkInvest($userId,$cash,$password,$bonusId,$from, $isTrade=true){

        $this->investModel    = new CurrentModel();

        $this->projectModel   = new CurrentProjectModel();

        //数据验证
        ValidateModel::isUserId($userId);

        ValidateModel::isCash(ToolMoney::formatDbCashDelete($cash));

        //获取用户信息
        $userInfo       = UserModel::getUserInfo($userId);

        if($isTrade){

            //判断交易密码格式
            PasswordModel::validationPassword($password);

            //检测是否实名 + 设置交易密码
            UserModel::checkUserAuthStatus($userInfo);

            //验证交易密码是否正确
            $this->investModel->checkTradingPassword($password,$userInfo['trading_password']);

        }else{

            //检测是否实名
            $this->checkUserAuth($userInfo);

        }

        //比对账户金额
        $this->investModel->checkUserBalance($userInfo['balance'],$cash);

        //项目可投金额是否大于等于用户投资金额
        $projectInfo     = $this->projectModel->checkCanInvest($cash);

        //检查零钱计划转入限额
        $this->investModel->checkInvestLimit($userId,$cash);
        
        $params = [
            'event_name'    => 'App\Events\Invest\CurrentBeforeEvent',
            'event_desc'    => '零钱计划投资前事件',
            'bonus_id'      => $bonusId,                    //红包ID
            'user_id'       => $userId,                     //用户ID
            'cash'          => $cash,                       //用户零钱计划转入金额
            'from'          => $from,
        ];

        \Event::fire(new \App\Events\Invest\CurrentBeforeEvent($params));

        return $projectInfo;
    }

    /**
     * @param $data
     * @param $isTrade
     * @return array
     * @desc 零钱计划转出接口
     */
    public  function doInvestOut($data, $isTrade=true){

        $cash       = $data['cash'];
        $password   = empty($data['trading_password'])?'':$data['trading_password'];

        $userId     = $data['user_id'];
        $from       = $data['from'];
        try{

            //数据验证
            ValidateModel::isUserId($userId);

            ValidateModel::isDecimalCash($cash);

            //获取用户信息
            $userInfo       = UserModel::getUserInfo($userId);

            //是否检测交易密码
            if($isTrade){

                //检测是否实名 + 设置交易密码 + 检测交易密码
                $this->checkTradingPassword($userInfo, $password);

            }else{

                //检测是否实名
                $this->checkUserAuth($userInfo);
            }

            $investModel    =  new CurrentModel();

            //检查零钱计划转出限额
            $investModel->checkInvestOutLimit($userId,$cash);

            //调用核心接口进行零钱计划投资
            $investModel->doInvestOut($userId,$cash);

            //记录零钱计划投资记录
            $currentInvestDb = new InvestDb();
            $currentInvestDb->doInvestOut($userId,$cash,$from);

        }catch(\Exception $e){
            
            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @param $userInfo
     * @param $password
     * @return bool
     * @throws \Exception
     * @desc 检测是否实名 + 设置交易密码
     */
    public function checkTradingPassword($userInfo, $password){

        //检测是否实名 + 设置交易密码
        UserModel::checkUserAuthStatus($userInfo);

        $investModel    =  new CurrentModel();
        //验证交易密码是否正确
        $investModel->checkTradingPassword($password,$userInfo['trading_password']);

        return true;

    }

    /**
     * @param $userInfo
     * @return bool
     * @desc 检测实名
     */
    public function checkUserAuth($userInfo){

        //检测是否实名
        UserModel::checkUserAuth($userInfo);

        return true;

    }
    

    /**
     * @param $userId
     * @return array
     * 零钱计划投资页面信息
     */
    public function projectDetail($userId,$from){


        $projectModel   = new CurrentProjectModel();
        //零钱计划项目信息
        $project        = $projectModel->getProject();
        $investModel    = new CurrentModel();
        //项目剩余可投金额
        $freeAmount     = $project['total_amount'] - $project['invested_amount'];
        $rateModel      = new RateModel();
        //获取利率
        $rateData       = $rateModel->getRate();
        //零钱计划投资总人数
        $count          = $investModel->getUserNum();

        //用户最多可转入零钱计划金额
        $model          = new CurrentModel();
        $config         = $model->getConfig();
        $investMax      = $config['INVEST_CURRENT_MAX'];

        

        $investAmount = $this->getTotalInvestAmount();
        //万元收益
        $interest       = IncomeModel::getTenThousandInterest($rateData['rate']);


        $bonusInfo = [];
        $balance   = 0;
        $isLogin   = false;
        $addRate   = 0;


        //用户已登录
        if($userId > 0){
            $investMax      = $model->getMaxInvestInCash($userId);
            $data = $this->getUserCurrentBonusList($userId,$from);

            $bonusInfo = $data['bonus_list'];
            $balance   = $data['balance'];
            $addRate   = $data['add_rate'];

            $isLogin   = true;
        }

        $list =  [
            'freeAmount'        => ToolMoney::formatDbCashDelete($freeAmount),              //零钱计划项目剩余可投金额
            'rateInfo'          => $rateData,                                               //零钱计划利率
            'balance'           => ToolMoney::formatDbCashDelete($balance),                 //帐户余额
            'investUserNum'     => $count,                                                  //零钱计划投资总人数
            'investAmount'      => ToolMoney::formatDbCashDelete($investAmount),    //零钱计划投资总金额
            'interest'          => ToolMoney::formatDbCashDelete($interest),                //万元利息
            'bonus_list'        => $bonusInfo,//加息券列表
            'isLogin'           => $isLogin,
            'addRate'           => $addRate,                                               //使用中的加息券利率
            'investMax'         => $investMax,

        ];


        return self::callSuccess($list);

    }

    /**
     * @return array|int
     * 获取零钱计划总的转入金额
     */
    private function getTotalInvestAmount(){

        //先使用统计数据
        $fundDb = new FundStatisticsDb();

        $date = ToolTime::getDateBeforeCurrent();
        $fundData = $fundDb->getByDate($date);
        //若不存正则使用资金流水统计
        if(empty($fundData)){
            //零钱计划投资总金额
            $investAmount = \App\Http\Models\Common\CoreApi\CurrentModel::getCurrentInvestAmount();

        }else{

            $totalInvestIn = $fundData['total_invest_in'];
            $todayInvestAmount = \App\Http\Models\Common\CoreApi\CurrentModel::getTodayInvestAmount();

            $investAmount = $todayInvestAmount + $totalInvestIn;
            
        }

        return $investAmount;
    }

    /**
     * @param $userId
     * @param $from
     * @throws \Exception
     * 获取零钱计划用户加息券列表及账户余额
     */
    public function getUserCurrentBonusList($userId,$from){

        //用户信息
        $userInfo       = \App\Http\Models\Common\CoreApi\UserModel::getCoreApiUserInfo($userId);

        if(empty($userInfo)){

            return [
                'balance'       => 0,
                'bonus_list'    => [],
                'add_rate'      => 0
            ];
        }else{
            $balance        = $userInfo['balance'];
            $bonusModel     = new UserBonusModel();
            //获取用户使用中加息券列表
            $bonusList      = $bonusModel->getUsingCurrentBonusList($userId);

            //若正在使用加息券,前端页面不显示可用的加息券列表
            if($bonusList){
                $bonusDb   = new BonusDb();
                $bonus     = $bonusDb->getById($bonusList['bonus_id']);
                $bonusInfo = [];
                $addRate   = $bonus['rate'];
                $period    = $bonus['current_day'];
                $usedTime  = ToolTime::getDate($bonusList['used_time']);
                $rateUsedTime = $bonusList['rate_used_time'];

            }else{
                //获取零钱计划用户可用的加息券列表
                $bonusInfo = $bonusModel->getCurrentAbleUserBonusList($userId,$from);
                $addRate   = 0;
                $period    = 0;
                $usedTime  = '';
                $rateUsedTime = '';

            }

            $rateModel = new RateModel();

            $baseRate = $rateModel->getRate();

            return [
                'balance'       => $balance,
                'bonus_list'    => $bonusInfo,
                'add_rate'      => $addRate,
                'period'        => $period,
                'used_time'     => $usedTime,
                'rate_used_time'=> $rateUsedTime,
                'base_rate_day' => empty($baseRate['rate'])?0:$baseRate['rate'],
            ];
        }



    }


    /**
     * @param $userId
     * @return array
     * 零钱计划投资前的项目详情
     */
    public function projectInfo($userId){


        try{

            ValidateModel::isUserId($userId);

            //获取用户信息
            $userInfo       = UserModel::getUserInfo($userId);

            //检测用户是否实名 + 设置交易密码
            //UserModel::checkUserAuthStatus($userInfo);

            //获取零钱计划配置信息
            $model          = new CurrentModel();
            $config         = $model->getConfig();

            $currentModel   = new CurrentProjectModel();
            $project        = $currentModel->getProject();

            //当前项目剩余可投金额
            $freeAmount                 = $project['total_amount'] - $project['invested_amount'];
            //用户最多可转入零钱计划金额
            //$investMax                  = $config['INVEST_CURRENT_MAX'];
            $investMax                  = $model->getMaxInvestInCash($userId);

            //获取零钱计划帐户信息
            $accountInfo    = $model->getCurrentAccount($userId);

            //计算用户剩余可转入零钱计划的金额
            if(!empty($accountInfo)){
                $freeInvestAmount = max(0, ($investMax - $accountInfo['cash']));
            }else{
                $freeInvestAmount = $investMax;
            }

            //金额转化成元
            $investMax            = ToolMoney::formatDbCashDelete($investMax);
            $projectInfo['project_info'] = [
                'info_url' => "",
                "user_current_left_max"     =>  ToolMoney::formatDbCashDelete(floor($freeInvestAmount)),
                "invest_min"                =>  ToolMoney::formatDbCashDelete($config['INVEST_CURRENT_MIN']),      //零钱计划最小转入金额
                "invest_max"                =>  ToolMoney::formatDbCashDelete($freeAmount),                        //当前项目剩余可投金额
                "invest_project_max_note"   =>  sprintf(LangModel::getLang('CURRENT_INVEST_MAX_NOTE'),ToolMoney::formatDbCashDeleteTenThousand($investMax)),//转化成万元显示
                "user_cash"                 =>  ToolMoney::formatDbCashDelete($userInfo['balance']),                //用户账户余额
            ];

        }catch(\Exception $e){

            return self::callError($e->getMessage());

        }

        return self::callSuccess($projectInfo);
    }


    /**
     * @param $cash
     * @param $tradingPassword
     * @param $bonusId
     * App端零钱计划投资逻辑
     */
    public function doAppInvest($data, $isTrade=true){

        $return = $this->doInvest($data, $isTrade);

        if($return['status']){

            //零钱计划加息券ID
            $bonusId = $data['bonus_id'];
            //零钱计划转入金额
            $cash   = $data['cash'];
            /*

            //加息利率初始值
            $addRate = 0;

            if($bonusId > 0){
                $bonusLogic = new UserBonusModel();
                $bonusInfo  = $bonusLogic->getUserBonusById($bonusId);
                //零钱计划加息券利率
                $addRate    = $bonusInfo['bonus_info']['rate'];
            }


            //零钱计划当前利率
            $rateModel  = new RateModel();
            $rateInfo   = $rateModel->getRate();
            $rate       = $rateInfo['rate'];
            //零钱计划利息
            $realRate   = $addRate + $rate;
            $income = IncomeModel::getCurrentInterest($cash,$realRate);

            $info = [
                'add_rate'              => $addRate,
                'rate'                  => $rate,
                'cash'                  => ToolMoney::formatDbCashDelete($cash),
                'income'                => ToolMoney::formatDbCashDelete($income),
                'interest_start'        => ToolTime::dbDate(),
                'interest_end'          => ToolTime::getDateAfterCurrent(),
            ];

            $result['info'] = $info;
            */

            $info               = $this->getInvestData($bonusId,$cash,$data['user_id'],$data['from']);
            $info['income']     = $info['day_interest'];

            $info['interest_start'] = ToolTime::dbDate();
            $info['interest_end']   = ToolTime::getDateAfterCurrent();
            unset($info['income']);


            $result['info'] = $info;
            $result['trade_password_status']  = 'on';

            return self::callSuccess($result);

        }else{

            return self::callError($return['msg']);
        }

    }

    /**
     * @param $data
     * @param bool $isTrade
     * @return array
     * App端零钱计划转出逻辑
     */
    public function doAppInvestOut($data, $isTrade = true){

        $result = $this->doInvestOut($data, $isTrade);

        if($result['status']){

            $result =  [
                'cash'  => ToolMoney::formatDbCashDelete($data['cash']),
            ];

        }else{
            return self::callError($result['msg']);
        }

        return self::callSuccess($result);

    }

    /**
     * @param $userId
     * @return mixed
     * App端零钱计划转出页面
     */
    public function investOutDetail($userId, $isTrade=true){


        try{

            ValidateModel::isUserId($userId);

            //获取用户信息
            $userInfo       = UserModel::getUserInfo($userId);

            if($isTrade){

                //判断是否实名 + 设置交易密码
                UserModel::checkUserAuthStatus($userInfo);

            }else{

                //检测是否实名
                $this->checkUserAuth($userInfo);

            }


            $accountInfo    = \App\Http\Models\Common\CoreApi\CurrentModel::getCurrentUserInfo($userId);

            //获取零钱计划配置信息
            $model          = new CurrentModel();

            $result['max_out_cash'] = $model->getUserLeftInvestOutCashByUserId($userId);

            $info = [];

            if(!empty($accountInfo)){

                $info   = [
                    'id'    => $accountInfo['id'],
                    'cash'  => $accountInfo['cash'],
                ];
            }

            $result['info'] = $info;


        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }
        return self::callSuccess($result);
    }

    /**
     * @param $userId
     * @return array
     * 获取零钱计划投资协议
     */
    public function getAgreementInfo($userId){

        $userInfo = \App\Http\Models\Common\CoreApi\UserModel::getCoreApiUserInfo($userId);

        $result = [
            'name'      => '',
            'id_card'   => '',
            'date'      => date('Y年m月d日'),
        ];

        if(!empty($userInfo)){
            $result['name']     = $userInfo['real_name'];
            $result['id_card']  = $userInfo['identity_card'];
        }

        return self::callSuccess($result);
    }

    /**
     * @param $bonusId
     * @param $cash
     * @param $userId
     * @param $from
     * @return array
     * 零钱计划投资前的数据准备
     */
    public function getInvestData($bonusId,$cash,$userId,$from){

        //加息利率初始值
        $addRate = 0;

        $info = [
            'bonus_id' => $bonusId,
            'cash'      => $cash,
        ];

        if($bonusId > 0){
            $bonusLogic = new UserBonusModel();
            $bonusInfo  = $bonusLogic->getUserBonusById($bonusId);
            //零钱计划加息券利率
            $addRate                = $bonusInfo['bonus_info']['rate'];
            $info['period']         = $bonusInfo['bonus_info']['current_day'];
        }else{
            $addInfo                = $this->getUserCurrentBonusList($userId,$from);
            $addRate                = $addInfo['add_rate'];
            $info['period']         = $addInfo['period'];
        }

        //零钱计划当前利率
        $rateModel  = new RateModel();
        $rateInfo   = $rateModel->getRate();
        $rate       = $rateInfo['rate'];
        //零钱计划利息
        $realRate   = $addRate + $rate;
        $income = IncomeModel::getCurrentInterest($cash,$realRate);

        $db             = new ProjectDb();
        $projectInfo    = $db->getShowProject();


        $info['day_interest']   = $income;
        $info['rate']           = $rate;
        $info['id']             = $projectInfo['id'];
        $info['add_rate']       = $addRate;

        return $info;

    }

    /**
     * @return array
     * wap端零钱计划投资详情页面
     */
    public function getCurrentBaseInfo(){

        $model = new CurrentModel();
        //零钱计划当前利率
        $rateModel  = new RateModel();
        $rateInfo   = $rateModel->getRate();


        $dayInterest = IncomeModel::getTenThousandInterest($rateInfo['rate']);

        return [
            'rate' => $rateInfo['rate'],
            'day_interest' => $dayInterest
        ];
    }

    /**
     * @param $userId
     * 微信端转出前的数据
     */
    public function getWapInvestData($userId){

        $model    = new CurrentModel();
        $config   = $model->getConfig();

        $accountInfo    = $model->getCurrentAccount($userId);

        $data = [
            'invest_out_max'        => $config['INVEST_CURRENT_OUT_MAX'],
            'current_cash'          => empty($accountInfo) ? 0 : $accountInfo['cash'],
        ];
        return $data;
    }

    /**
     * @param $userId
     * @param $cash
     * @return array
     * @throws \Exception
     * @desc 检测用户转入金额
     */
    public function checkAjax($userId, $cash){

        $this->investModel    = new CurrentModel();

        try{
            //检查零钱计划转入限额
            $this->investModel->checkInvestLimit($userId,$cash);

        }catch(\Exception $e){

            return self::callError($e->getMessage());

        }

        return self::callSuccess();

    }

    /**
     * @desc 零钱计划数据统计
     * @author lgh
     * @param $param
     * @param $type
     * @return mixed
     */
    public function getCurrentStatistics($param, $type){

        $currentInvestLogic = new CurrentModel();

        $where = $this->formatGetInput($param);

        $currentData = $currentInvestLogic->getCurrentStatistics($where, $type);

        return $currentData;
    }

    /**
     * @desc 格式化条件
     * @param $param
     * @return array
     */
    public function formatGetInput($param){
        $where   =  [];

        $where['start_time']       = isset($param['start_time']) ? $param['start_time'] : null;
        $where['end_time']         = isset($param['end_time']) ? $param['end_time'] : null;
        $where['app_request']      = isset($param['app_request']) ? $param['app_request'] : '';
        $where['base_cash']        = isset($param['base_cash']) ? $param['base_cash'] : '';
        $where['user_id']          = isset($param['user_id']) ? $param['user_id'] : '';
        $where['project_ids']      = isset($param['p_ids']) ? $param['p_ids'] : '';
        $where['group']            = isset($param['group']) ? $param['group'] : "";
        $where['bonusId']          = isset($param['bonusId']) ? $param['bonusId'] : "";
        $where['size']             = isset($param['size']) ? $param['size'] : '';
        $where['page']             = isset($param['page']) ? $param['page'] : '';

        return $where;

    }

    /**
     * @desc 获取零钱计划项目投资总人数
     * @author lgh
     * @return int
     */
    public function getUserNum(){

        $currentModel = new CurrentModel();

        return $currentModel->getUserNum();
    }

}