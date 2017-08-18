<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/5
 * Time: 下午7:43
 */

namespace App\Http\Logics\CreditAssign;

use App\Http\Dbs\Current\InvestDb;
use App\Http\Dbs\Current\ProjectDb;
use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\Activity\Statistics\StatisticsLogic;
use App\Http\Logics\Agreement\AgreementLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Notice\NoticeLogic;
use App\Http\Logics\Project\ProjectAppLogic;
use App\Http\Models\Common\CoreApi\CreditAssignProjectModel;
use App\Http\Models\Common\PasswordModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Common\TradingPasswordModel;
use App\Http\Models\Invest\CurrentModel;
use App\Http\Models\Invest\InvestModel;
use App\Http\Models\Project\CreditAssignModel;
use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Cache;

class CreditAssignLogic extends Logic
{

    /**
     * @param $investId
     * @return array
     * @desc 确认转让信息页面数据
     */
    public function userPreCreditAssign( $investId ){

        $data = CreditAssignProjectModel::userPreCreditAssign($investId);

        return self::callSuccess($data);

        //return self::callError('系统升级中此功能暂不可用');

    }

    /**
     * @param $projectId
     * @param $cash
     * @param $tradingPassword
     * @return array
     * 创建债转项目
     */
    public function userDoCreditAssign($investId,$cash,$tradingPassword,$userInfo){

        try{

            //ValidateModel::isCash((int)$cash);
            PasswordModel::validationPassword($tradingPassword);
            ValidateModel::isProjectId($investId);

            //验证交易密码是否正确
            TradingPasswordModel::checkPassword($tradingPassword,$userInfo['trading_password']);

            $model = new CreditAssignModel();

            //根据项目ID匹配投资信息
            //$investId = $model->getUsableInvestId($projectId,$userInfo['id'],$cash);
            //创建项目失败
            $model->doCreateProject($investId,$cash);

            $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_ASSIGN_CREATE);

            //发送站内信
            $investModel = new InvestModel();

            $investInfo = $investModel->getInvestByInvestId($investId);

            $msg = sprintf($msgTpl, ToolTime::dbNow(), $investInfo['project_id'], $cash, 0, 0);

            //申请成功事件
            $param['notice'] = [
                'title'     => NoticeDb::TYPE_ASSIGN_CREATE,
                'user_id'   => $investInfo['user_id'],
                'message'   => $msg,
                'type'      => NoticeDb::TYPE_ASSIGN_CREATE
            ];

            \Event::fire(new \App\Events\Project\CreateCreditAssignProjectSuccessEvent($param));

        }catch (\Exception $e){

            \Log::Error(__CLASS__.__METHOD__.__LINE__.'Error', ['msg' => $e->getMessage(), 'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        $data = [
            'predictProfit' => $cash,
            'project_title' => "项目",
            'duration_str'  => "无时限",
            'duration'      => 3,
            'ad_info'       => [
                ['Purl' => '']
            ]
        ];

        return self::callSuccess($data);


    }

    /**
     * @param $projectId
     * @param $cash
     * @param $tradingPassword
     * @param $userId
     * 购买债权转让项目
     */
    public function doInvest($projectId,$cash,$tradingPassword,$userInfo, $client){

        $investData = [];

        $model = new CreditAssignModel();

        try{
            ValidateModel::isProjectId($projectId);
            ValidateModel::isCash($cash);
            ValidateModel::isUserId($userInfo['id']);

            PasswordModel::validationPassword($tradingPassword);
            //验证交易密码是否正确
            TradingPasswordModel::checkPassword($tradingPassword,$userInfo['trading_password']);


            //投资加锁
            $model->addLock($projectId);

            //购买债转项目
            $data["back"] = $model->doInvest($projectId,$userInfo['id'],$cash);

            $investModel = new InvestModel();

            $investData = [
                'invest_id'     => $data["back"]['invest_id'],
                'user_id'       => $userInfo['id'],
                'project_id'    => $data["back"]['originProjectId'],
                'cash'          => $cash,
                'bonus_id'      => '',
                'bonus_type'    => '',
                'bonus_value'   => '',
                'source'        => $client
            ];

            $investModel->addRecord($investData);

            $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_ASSIGN_SUCCESS);

            $msg = sprintf($msgTpl, ToolTime::dbNow(), $data["back"]['originProjectId'], $cash, 0, 0, $cash);

            //申请成功事件
            $param['notice'] = [
                'title'     => NoticeDb::TYPE_ASSIGN_SUCCESS,
                'user_id'   => $data["back"]['origin_user_id'],
                'message'   => $msg,
                'type'      => NoticeDb::TYPE_ASSIGN_SUCCESS
            ];

            \Event::fire(new \App\Events\Project\CreateCreditAssignProjectSuccessEvent($param));


        }catch (\Exception $e){

            //解锁
            $model->releaseLock($projectId);

            \Log::Error(__CLASS__.__METHOD__.'ERROR', ['msg' => $e->getMessage(), 'code' => $e->getCode(), 'data' => $investData]);

            return self::callError($e->getMessage());
        }

        //解锁
        $model->releaseLock($projectId);

        return self::callSuccess($data);
    }

    /**
     * @param $projectId
     * @param $cash
     * @param $tradingPassword
     * @param $userInfo
     * @param $client
     * @return array
     * @desc 通过零钱购买债权转让项目
     */
    public function doInvestByCurrent($projectId,$cash,$tradingPassword,$userInfo, $client){

        $investData = [];

        $model = new CreditAssignModel();

        try{
            self::beginTransaction();

            ValidateModel::isProjectId($projectId);
            ValidateModel::isCash($cash);
            ValidateModel::isUserId($userInfo['id']);

            PasswordModel::validationPassword($tradingPassword);
            //验证交易密码是否正确
            TradingPasswordModel::checkPassword($tradingPassword,$userInfo['trading_password']);

            /****************************************零钱计划操作********************************************************/

            $investModel    =  new CurrentModel();

            //检查零钱计划转出限额
            $investModel->checkInvestOutLimit($userInfo['id'],$cash);

            //投资加锁
            $model->addLock($projectId);

            /***********************************************************************************************************/

            //记录零钱计划取出记录
            $currentInvestDb = new InvestDb();
            $currentInvestDb->doInvestOut($userInfo['id'],$cash,$client);

            //购买债转项目
            $data["back"] = $model->doInvestByCurrent($projectId,$userInfo['id'],$cash);

            $investModel = new InvestModel();

            $investData = [
                'invest_id'     => $data["back"]['invest_id'],
                'user_id'       => $userInfo['id'],
                'project_id'    => $data["back"]['originProjectId'],
                'cash'          => $cash,
                'bonus_id'      => '',
                'bonus_type'    => '',
                'bonus_value'   => '',
                'source'        => $client
            ];

            $investModel->addRecord($investData);

            self::commit();

            $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_ASSIGN_SUCCESS);

            $msg = sprintf($msgTpl, ToolTime::dbNow(), $data["back"]['originProjectId'], $cash, 0, 0, $cash);

            //申请成功事件
            $param['notice'] = [
                'title'     => NoticeDb::TYPE_ASSIGN_SUCCESS,
                'user_id'   => $data["back"]['origin_user_id'],
                'message'   => $msg,
                'type'      => NoticeDb::TYPE_ASSIGN_SUCCESS
            ];

            \Event::fire(new \App\Events\Project\CreateCreditAssignProjectSuccessEvent($param));


        }catch (\Exception $e){
            self::rollback();

            //解锁
            $model->releaseLock($projectId);

            \Log::Error(__CLASS__.__METHOD__.'ERROR', ['msg' => $e->getMessage(), 'code' => $e->getCode(), 'data' => $investData]);

            return self::callError($e->getMessage());
        }

        //解锁
        $model->releaseLock($projectId);

        return self::callSuccess($data);
    }

    /**
     * @return array
     * @desc 债权转让列表
     * @author liu.qiuhui
     */
    public function userCreditAssign( $userId ){

        $list = CreditAssignProjectModel::getUserCreditAssign($userId);

        return self::callSuccess($list);

        //return self::callError('系统升级中此功能暂不可用');

    }

    /**
     * @return array
     * @desc 取消转让中的项目
     */
    public function userCancelCreditAssign($creditAssignProjectId,$tradingPassword,$userInfo){

        try{

            ValidateModel::isProjectId($creditAssignProjectId);

            PasswordModel::validationPassword($tradingPassword);

            //验证交易密码是否正确
            TradingPasswordModel::checkPassword($tradingPassword,$userInfo['trading_password']);

            $model = new CreditAssignModel();

            $model->cancel($creditAssignProjectId, $userInfo['id']);

            $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_ASSIGN_CANCEL);

            $msg = sprintf($msgTpl, ToolTime::dbNow(), $creditAssignProjectId);

            //取消成功事件
            $param['notice'] = [
                'title'     => NoticeDb::TYPE_ASSIGN_CANCEL,
                'user_id'   => $userInfo['id'],
                'message'   => $msg,
                'type'      => NoticeDb::TYPE_ASSIGN_CANCEL
            ];

            \Event::fire(new \App\Events\Project\CancelCreditAssignProjectSuccessEvent($param));

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess();


    }

    /**
     * @return array
     * @param $size
     * @param $page
     * @desc 债权转让列表接
     * @author liu.qiuhui
     */
    public function assignProject( $page = 1, $size){

        $data['project_list'] = CreditAssignProjectModel::getCreditAssignList($page, $size);

        if(empty($data['project_list'])){

            return self::callError('没有更多债转项目信息了');

        }

        return self::callSuccess($data);

    }

    /**
     * @param $projectId
     * @return array
     * @desc 变现宝项目详情
     * @author liu.qiuhui
     */
    public function creditAssignDetail($projectId){

        $list = CreditAssignProjectModel::getCreditAssignDetail($projectId);

        return self::callSuccess($list);

        //return self::callError('系统升级中此功能暂不可用');

    }

    public function creditAssignDetailV4Format($projectId, $userId=0){

        $result = CreditAssignProjectModel::getCreditAssignDetail($projectId);

        $data = $result['project'];

        $projectLogic = new ProjectAppLogic();

        $interest = $projectLogic->getAppV4ProjectGetInterest($data['can_invest_amount'], $data['project_id'], '', 1);

        $result['project'] = [
            'project_id'        => $data['project_id'],
            'project_name'      => $data['project_name'],
            //'project_name'      => $data['project_name'].' '.$data['project_id'],
            'orig_project_id'   => $data['orig_project_id'],
            'orig_project_name' => $data['orig_project_name'],
            'profit_percentage' => $data['profit_percentage'],
            'refund_type'       => $data['refund_type'],
            'refund_type_name'  => $data['refund_type_name'],
            'left_amount'       => $data['can_invest_amount'],
            'project_type_note' => $data['project_type_note'],
            'publish_time'      => $data['publish_time'],
            'invest_time'       => $data['total_time'],
            'invest_time_note'  => $data['total_time_note'],
            'is_credit_assign'  => $data['is_credit_assign'],
            "safe"              => env('APP_URL_WX')."/article/safe",
            'account'           => '折让率0.00%',
            'can_credit_assign_note' => '不可转让',
            'profit_note'       => ProjectDb::INTEREST_RATE_NOTE.'(%)',
            'time_limit_note'   => '项目期限',
            "safe_note"         => "帐户资金享有银行级安全保障",//显示
            'min_invest'        => $data['can_invest_amount'],
            'interest'          => empty($interest['data']['interest']) ? 0 : $interest['data']['interest'],

        ];

        if($userId){

            $userInfo = $this->getUser($userId);

            //用户资产
            $userAccount = CoreApiUserModel::getCoreApiUserInfoAccount($userId);

            $model = new CurrentModel();
            //当日可用最大零钱
            $current_able_use = $model->getUserLeftInvestOutCashByUserId($userId);

            $result['user_info'] = [
                'balance'  => $userInfo['balance'],
                'current_cash' => empty($userAccount['current']['cash'])?0:ToolMoney::formatDbCashDelete($userAccount['current']['cash']),
                'current_able_use'     => $current_able_use,
                'user_id'  => $userId
            ];

        }

        //协议
        /*$type = 40;
        $titleArr = explode('-', AgreementLogic::getTitleByType($type));
        $result['agreement'][] = [
            'title' => !empty($titleArr[1]) ? '<<'.$titleArr[1].'>>' : '',
            'url'   => env('APP_URL_WX').'/agreement?type='.$type.'&project_id='.$projectId,
        ];*/
        $titleArr = explode('-', AgreementLogic::getTitleByType('argument'));

        $result['agreement'][] = [
            'title' => !empty($titleArr[1]) ? '《'.$titleArr[1].'》' : '',
            'url'   => env('APP_URL_WX').'/agreement?type=argument&project_id='.$projectId,
        ];
        $result['activity']       = $this->getV4RecommendActivity();
        return self::callSuccess($result);

    }

    /**
     * @param $projectId
     * @param $cash
     * @return array
     * @desc 债转利息
     */
    public function getInvestProfit($projectId,$cash,$type=0){

        //项目详情
        $list = CreditAssignProjectModel::getCreditAssignDetail($projectId);

        $profit_percentage = isset($list['project']['profit_percentage']) ? $list['project']['profit_percentage'] : 0;

        $total_time        = isset($list['project']['total_time']) ? $list['project']['total_time'] : 0;

        $interest  = round($cash * $total_time * $profit_percentage  / 100 / 365, 2);

        if($type){

            $result = $interest;

        }else{

            $result = array(
                'interest'          => $interest,                                           //原利率所得利息
                'principalInterest' => $cash+$interest,                                     //原利率所得本息
                'couponInterest'    => 0,                                           //使用加息券的额外奖励利息
                'couponRate'        => 0,                                                     //加息券利率
                'couponText'        => '',    //使用加息券文字提示
            );

        }

        return self::callSuccess($result);

    }

    /**
     * @return array
     * @desc
     */
    public function userCreditAssignDesc(){

        $info[1] = "1.转让本金：发起债权转让时，申请出售的债权本金;";
        $info[2] = "2.转让率：债权转让时本金折让比例，为方便转让人快速赎回资金，不支持溢价转让，规定折让率在“0%-10.0%”之间，由转让人进行选择，递增单位0.1%;";
        $info[3] = "3.转让价格：转让本金-转让本金*折让率;";
        $info[4] = "4.手续费：成功转让本金*0.5%；自项目满标审核日起，若用户持有债权90天及以上，转让时不收取手续费;";
        $info[5] = "5.转让时效：为发起债权转让后的72h，在转让有效期内可主动取消转让，取消转让后24h内不可再次发起转让.";
        $str = '';
        foreach($info as $k=>$v){
            if($k==5){
                $str .= $v;
            }else{
                $str .= $v."\n";
            }
        }

        return self::callSuccess($str);

    }
    /**
     * @return array
     * @param $size
     * @param $page
     * @desc  App4.0债权转让列表数据
     * @author  linguanghui
     */
    public function assignAppV4Project( $page = 1, $size){

        $data = CreditAssignProjectModel::getCreditAssignList($page, $size);

//        if(empty($data)){
//
//            return self::callError('没有更多债转项目信息了');
//
//        }

        return self::callSuccess($data);

    }

    /**
     * @desc App4.0债权转让数据格式化
     * @param $assignData
     * @return array
     */
    public function formatAppV4AssignProject($assignData){

        $creditAssingData = [];

        if(!empty($assignData)){
            foreach($assignData as $key=>$value){
                $titleArr = explode(' ',$value['default_title']);
                $creditAssingData[$key]['id'] = $value['id'];
                $creditAssingData[$key]['assign_name'] = $titleArr[0];
                $creditAssingData[$key]['default_title'] = $value['default_title'];
                $creditAssingData[$key]['project_type'] = $value['project_type'];
                $creditAssingData[$key]['except_year_profit'] = ProjectDb::INTEREST_RATE_NOTE.'(%)';
                $creditAssingData[$key]['percentage_float_one'] = number_format($value['percentage_float_one'],1);
                $creditAssingData[$key]['project_time_note'] = '项目期限';
                $creditAssingData[$key]['left_day'] = $value['left_day'];
                $creditAssingData[$key]['invest_time_unit'] = $value['invest_time_unit'];
                $creditAssingData[$key]['assign_amount_note'] = '转让金额';
                $creditAssingData[$key]['assign_principal'] = $value['assign_principal'];
                $creditAssingData[$key]['assign_amount_unit'] = '元';
            }
        }
        return $creditAssingData;
    }

    /**
     * @return mixed
     * @desc 返回推荐活动具体信息
     */
    public static function getV4RecommendActivity( $productLine = '',  $actToken ='')
    {
        return StatisticsLogic::recommendActivity( $productLine,  $actToken );
    }
}
