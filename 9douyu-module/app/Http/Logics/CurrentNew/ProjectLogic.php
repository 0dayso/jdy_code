<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/21
 * Time: 下午3:51
 */

namespace App\Http\Logics\CurrentNew;

use App\Http\Dbs\CurrentNew\CurrentNewAccountDb;
use App\Http\Dbs\CurrentNew\ProjectNewDb;
use App\Http\Dbs\CurrentNew\UserCurrentNewFundHistoryDb;
use App\Http\Dbs\Fund\FundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\CurrentNew\AccountModel;
use App\Http\Models\CurrentNew\ProjectModel;
use App\Http\Models\CurrentNew\RateModel;
use App\Http\Models\Fund\FundHistoryModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Models\User\UserModel;
use App\Http\Models\CurrentNew\UserModel as CurrentNewUserModel;
use App\Http\Models\Common\CoreApi\UserModel as CoreUserModel;
use App\Http\Models\Credit\UserCreditModel;
use App\Jobs\Refund\CurrentJob;
use App\Jobs\CurrentNew\CurrentJob as CurrentNewJob;
use App\Tools\ToolStr;
use Illuminate\Support\Facades\Redirect;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Log;
use Queue;
use Cache;

class ProjectLogic extends Logic
{

    const
        BASE_YEAR_DAY   = 365,
        BASE_PERCENTAGE = 100,
        MIN_INTEREST    = 0.005;

    /**
     * @param $projectName
     * @param $cash
     * @param int $status
     * @param string $publishAt
     * @param int $admin
     * @return array
     * 创建零钱计划项目
     */
    public function create($projectName,$cash,$status=ProjectNewDb::STATUS_PUBLISH, $publishAt = '', $admin = 0){

        try{

            if(!$publishAt){

                $publishAt = ToolTime::dbNow();
            }

            //检查项目金额
            ValidateModel::isCash($cash);

            //检查零钱计划项目名称
            ValidateModel::isNullName($projectName);

            //验证发布时间
            ValidateModel::isDate($publishAt);

            $model = new ProjectModel();

            $model->create($projectName,$cash,$publishAt,$admin,$status);

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @param $id
     * @param $projectName
     * @param $cash
     * @param $status
     * @param $publishAt
     * @return array
     * 修改零钱计划项目
     */
    public function edit($id, $projectName, $cash, $status, $publishAt){

        try{

            ValidateModel::isProjectId($id);

            //检查项目金额
            ValidateModel::isCash($cash);

            //检查零钱计划项目名称
            ValidateModel::isNullName($projectName);

            //验证发布时间
            ValidateModel::isDate($publishAt);

            $model = new ProjectModel();

            $model->edit($id, $projectName,$cash, $publishAt, $status);

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @desc 获取[管理后台]零钱计划利率列表
     * @author lgh
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getAdminProjectList($page, $pageSize){

        $model = new ProjectNewDb();

        $list = $model->getProjectList($page, $pageSize);

        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 获取项目信息
     */
    public function getProjectInfoById( $id ){

        $db = new ProjectNewDb();

        $info = $db->getInfoById($id);

        return $info;

    }

    /**
     * @return array
     * @desc 显示数据
     */
    public function getProjectInfo(){

        $db = new ProjectNewDb();

        $info = $db->getShowProject();

        $rateModel = new RateModel();

        $rate = $rateModel->getRate();

        if(!empty($info)){

            $info['left_amount'] = $info['total_amount']-$info['invested_amount'];

            $info = array_merge($info, $rate);
        }

        return self::callSuccess($info);

    }

    /**
     * @return array
     * 详情
     */
    public function getDetail(){

        $info['name'] = '新版零钱计划';

        $rateModel = new RateModel();

        $rate = $rateModel->getRate();

        $model = new AccountModel();

        $info['left_amount'] = $model->getLeftAmount();

        $info = array_merge($info, $rate);

        return self::callSuccess($info);

    }

    /**
     * @param $userId
     * @param $cash
     * @return array
     * 投资
     */
    public function invest($userId, $cash){

        $accountModel = new AccountModel();

        $userModel = new CoreUserModel();

        $model = new CurrentNewUserModel();

        try{

            self::beginTransaction();
            //数据验证
            ValidateModel::isUserId($userId);

            ValidateModel::isCash(ToolMoney::formatDbCashDelete($cash));

            //检测限额是否可投
            CurrentNewUserModel::checkCanInvest( $userId, $cash );

            //获取用户信息
            $userInfo       = UserModel::getUserInfo($userId);

            //账户余额
            CurrentNewUserModel::checkUserBalance($userInfo['balance'],$cash);

            //是否可投
            $leftAmount = $accountModel->checkCanInvest($cash);

            //减账户余额
            $userModel->doDelBalance($userId, $cash, $userInfo['trading_password'], FundHistoryModel::getEventNoteByEvent(FundHistoryDb::INVEST_CURRENT_NEW), ToolStr::getRandTicket(), FundHistoryDb::INVEST_CURRENT_NEW);

            //执行投资
            $accountModel->doAdd($userId, $cash);

            $balance = $model->getUserAmount($userId);

            //添加流水
            $model->create($userId, $cash, $balance, UserCurrentNewFundHistoryDb::STATUS_INVEST, $leftAmount);

            self::commit();


        }catch(\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());

        }

        return self::callSuccess();

    }

    /**
     * @param $userId
     * @param $cash
     * @return array
     * 转出
     */
    public function investOut($userId, $cash){

        $accountModel = new AccountModel();

        $model = new CurrentNewUserModel();

        try{

            self::beginTransaction();

            //数据验证
            ValidateModel::isUserId($userId);

            ValidateModel::isDecimalCash(ToolMoney::formatDbCashDelete($cash));

            //账户减钱
            $accountModel->decreaseUserCash($userId, $cash);

            $balance = $model->getUserAmount($userId);

            //添加流水
            $model->create($userId, $cash, $balance, UserCurrentNewFundHistoryDb::STATUS_FROZEN);

            self::commit();

        }catch(\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());

        }

        return self::callSuccess();

    }


    /**
     * 零钱计划计算收益
     */
    public function doRefund($data=[])
    {

        $log = self::callError();

        if( empty($data) ){

            $log['msg'] = LangModel::getLang('ERROR_CURRENT_EMPTY_INTEREST_USER');

            Log::Error('currentDoRefundError', $log);

            return false;

        }

        $accountModel = new AccountModel();

        $userModel    = new CurrentNewUserModel();

        try{


            foreach( $data as $user ){

                self::beginTransaction();

                $interest = $this->getCurrentInterest($user['cash'],$user['rate']);

                $accountModel->updateInterest($user['user_id'], $interest);

                $userInfo = $accountModel->getUserInfo($user['user_id']);

                $afterBalance = $userInfo['cash'] + $interest;

                $userModel->create($user['user_id'], $interest, $afterBalance, UserCurrentNewFundHistoryDb::STATUS_INTEREST, $user['cash']);

                self::commit();

                $log = self::callSuccess($data);

                \Log::info('doCurrentRefundSuccess', [$log]);

            }

            $accountModel->getUseAmount(true);
            
        }catch (\Exception $e){

            self::rollback();

            $log['msg'] = $e->getMessage();

            $log['code'] = $e->getCode();

            $log['data'] = $data;

            Log::Error('doCurrentRefundError', [$log]);

            //RefundLogic::doRefundCurrentWarning($log);

        }

        return $log;

    }


    /**
     * 拆分计息用户
     */
    public function splitRefund($rate)
    {

        if( empty($rate) ){

            //短信报警
            $log['msg'] = LangModel::getLang('ERROR_CURRENT_RATE');

            Log::Error('currentDoRefundRateError', $log);

            return false;

        }

        Log::Info(__CLASS__.__METHOD__, [$rate]);

        $return = self::callError();

        //获取零钱计划账户总数
        $currentAccountDb = new CurrentNewAccountDb();

        $total = $currentAccountDb->getTotal();

        $size = 200;

        $pageNum = ceil($total/$size);

        //根据当日利率，计算最小金额
        $minCash = $this->getCurrentInterestMinCash($rate);

        $interestTime = ToolTime::dbDate();

        //$currentNewFundHistoryDb = new UserCurrentNewFundHistoryDb();

        for( $page = 1; $page <= $pageNum; $page++ ){

            //获取用户列表
            $accountList = $currentAccountDb->getList($page, $size);

            $userIds = ToolArray::arrayToIds($accountList, 'user_id');

            if( empty($userIds) ){

                continue;

            }

            //获取用户昨天本金
            //$fundList = $currentNewFundHistoryDb->getUsersAmountByUserIds($userIds);
            $fundList = UserCreditModel::getYesterdayMatchAccount(); //获取用户昨日已匹配本金接口 本金字段为'yesterday_cash'

            $fundList = ToolArray::arrayToKey($fundList, 'user_id');

            $interestData = [];

            foreach( $accountList as $accountKey => $account ){

                $accountCash = 0;

                //如果已经计息，直接continue；
                if( $account['interested_at'] >= $interestTime ){

                    continue;

                }

                if( isset($fundList[$account['user_id']]) ){

                    //$accountCash = $fundList[$account['user_id']]['after_balance'];
                    $accountCash = $fundList[$account['user_id']]['yesterday_cash'];

                }

                //如果计息金额小于最小计息金额，直接跳过
                if( $accountCash < $minCash ){

                    \Log::Info(__CLASS__.__METHOD__.'MIN_CASH_ACCOUNT', $account);

                    continue;

                }

                $interestData[] = [
                    'user_id'       => $account['user_id'],
                    'cash'          => $accountCash,
                    'rate'          => $rate
                ];

            }

            if( !empty( $interestData ) ){

                $data = [
                    'type'  => CurrentJob::TYPE_DO_REFUND,
                    'data'  => $interestData
                ];

                $res = Queue::pushOn('doRefundCurrent', new CurrentJob($data));

                if( !$res ){

                    \Log::error(__CLASS__.__METHOD__.'Error', $data);

                    return self::callError(LangModel::getLang('ERROR_CURRENT_REFUND_SPLIT'));

                }

            }

        }

        return self::callSuccess();

    }


    /**
     * @param $rate
     * @return array
     * @desc 加入队列任务
     */
    public function doRefundJob($rate)
    {

        if( empty($rate) ){

            return self::callError('零钱计划项目利率为空');

        }

        $data = [
            'rate'  => $rate,
            'type'  => CurrentJob::TYPE_SPLIT_REFUND
        ];

        $res = Queue::pushOn('doSplitRefund', new CurrentJob($data));

        if( $res ){

            return self::callSuccess($res, '加入队列任务成功');

        }else{

            //RefundLogic::doRefundCurrentJobWarning($data);

            return self::callError('加入队列任务失败');

        }

    }

    /**
     * @return array
     * 获取零钱计划用户总收益
     */
    public function getTotalInterest(){

        $db             = new CurrentNewAccountDb();
        $totalInterest  = $db->getTotalInterest();

        $totalInterest  = ToolMoney::formatDbCashDelete($totalInterest);

        return self::callSuccess(['totalInterest' => $totalInterest]);
    }

    /**
     * desc 清除昨日未计息用户的昨日收益
     */
    public function clearUserYesterdayInterest()
    {

        $db = new CurrentNewAccountDb();

        $res = $db->clearUserYesterdayInterest();

        if( !$res ){

            $data = ToolTime::dbNow();

            \App\Http\Logics\Warning\CurrentLogic::doClearYesterdayInterestWarning($data);

            Log::Error('clearUserYesterdayInterestError', $data);

        }

    }


    /**
     * @param $cash
     * @param $rate
     * @return float
     * @desc 新版活期收益计算
     */
    public function getCurrentInterest($cash, $rate){

        return round( ($cash * ($rate / self::BASE_PERCENTAGE) / self::BASE_YEAR_DAY), 2 );

    }

    /**
     * @param $rate
     * @return float
     * @desc 获取零钱计划计息的最小金额
     */
    public static function getCurrentInterestMinCash($rate)
    {

        return round( (self::MIN_INTEREST * self::BASE_YEAR_DAY / ($rate / self::BASE_PERCENTAGE)), 2 );

    }


    /**
     * @param $date
     * @return array
     * @desc 加入队列任务
     */
    public function doInvestOutJob( $date='' )
    {

        $date = !empty($date) ? $date : ToolTime::getDateBeforeCurrent();
        //$date = !empty($date) ? $date : ToolTime::dbDate();

        $data = [
            'type'  => CurrentNewJob::TYPE_SPLIT_INVEST_OUT,
            'data'  => $date,
        ];

        $res = Queue::pushOn('doSplitInvestOut', new CurrentNewJob( $data ));

        if( $res ){

            return self::callSuccess($res, '加入队列任务成功');

        }else{

            return self::callError('加入队列任务失败');

        }

    }

    /**
     * @param $date
     * @return array
     * 拆分转出
     */
    public function doSplitInvestOut($date){

        if(empty($date)){
            return self::callError(LangModel::getLang('ERROR_PARAMS'));
        }

        //1. 取总数
        $db = new UserCurrentNewFundHistoryDb();

        $total = $db->getUserInvestOutCount( $date );

        $size = 200;

        //2. 算拆分队列
        $pageNum = ceil($total/$size);

        if( !$total ){
            return self::callError('今日无转出数据处理');
        }

        //3. 格式化,入队列
        for( $page = 1; $page <= $pageNum; $page++ ){

            $list = $db->getUserInvestOutList($date, 0, $size);

            $investOutData = [];

            foreach($list as $key => $item){

                $investOutData[] = [
                    'id'                => $item['id'],
                    'user_id'           => $item['user_id'],
                    'cash'              => $item['change_balance'],
                ];

            }

            $res = true;

            if(!empty($investOutData)){

                $data = [
                    'type'  => CurrentNewJob::TYPE_DO_INVEST_OUT,
                    'data'  => $investOutData
                ];

                $res = Queue::pushOn('doCurrentNewInvestOut', new CurrentNewJob($data));
            }

            if(!$res){

                Log::info('splitCurrentInvestOutErr',[$investOutData]);

                $return['msg'] = LangModel::getLang('ERROR_CURRENT_NEW_INVEST_OUT');

                return self::callError(LangModel::getLang('ERROR_CURRENT_NEW_INVEST_OUT'));

            }

        }

        return self::callSuccess();

    }

    /**
     * @param $data
     * @return array|bool
     * 执行转出
     */
    public function doCurrentNewInvestOut( $data ){

        $log = self::callError();

        if( empty($data) ){

            $log['msg'] = LangModel::getLang('ERROR_PARAMS');

            Log::Error('currentDoInvestOutError', $log);

            return false;

        }

        $userModel = new CoreUserModel();

        try{

            self::beginTransaction();

            foreach( $data as $key => $item ){

                //增加账户余额
                $userModel->doIncBalance($item['user_id'], $item['cash'], '', FundHistoryModel::getEventNoteByEvent(FundHistoryDb::INVEST_OUT_CURRENT_NEW), ToolStr::getRandTicket(), FundHistoryDb::INVEST_OUT_CURRENT_NEW);

                $model = new CurrentNewUserModel();

                $model->updateInvestOutStatus( $item['id'] );

            }

            self::commit();

            $log = self::callSuccess($data);

            Log::info('doCurrentInvestOutSuccess', [$log]);

        }catch (\Exception $e){

            self::rollback();

            $log['msg'] = $e->getMessage();

            $log['code'] = $e->getCode();

            $log['data'] = $data;

            Log::Error('doCurrentInvestOutError', [$log]);

        }

        return $log;

    }

    /**
     * @param $userId
     * @return mixed
     * 检测用户是否可显示
     */
    public function checkIsShowByUserId( $userId ){

        $config = SystemConfigModel::getConfig('NEW_CURRENT_SHOW');

        $configUserArr = [];

        if(!empty($config) && !empty($config['USER_IDS'])){

            $configUserArr = explode(',', $config['USER_IDS']);

        }

        if(!in_array($userId, $configUserArr)){

            return false;

        }

        return true;

    }


}
