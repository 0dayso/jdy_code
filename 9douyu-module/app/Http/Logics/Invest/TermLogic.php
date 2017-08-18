<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/8
 * Time: 下午2:37
 * Desc: 定期投资logic
 */

namespace App\Http\Logics\Invest;



use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\Contract\ContractDb;
use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Activity\SpikeLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Notice\NoticeLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\PasswordLogic;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Common\CoreApi\SystemConfigModel;
use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Invest\CurrentModel;
use App\Http\Models\Invest\TermModel;
use App\Http\Models\Project\ProjectLinkCreditNewModel;
use App\Http\Models\User\UserModel;
use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;
use App\Http\Models\Invest\InvestModel;
use App\Http\Models\Bonus\UserBonusModel;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Http\Dbs\Invest\InvestDb;
use App\Http\Dbs\Current\InvestDb as CurrentInvestDb;
use App\Http\Models\Common\ValidateModel;
use App\Lang\AppLang;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use Cache;
use Event;
use Log;

class TermLogic extends Logic
{

    /**
     * @param $projectId
     * @param $cash
     * @param $profit
     * @return float
     * @desc 预期收益
     */
    public function getProfit($projectId,$cash,$profit){

        $termModel = new TermModel();

        $res = $termModel->getProfit($projectId,$cash,$profit);

        $cashInterest = !empty($res['cash_interest']) ? $res['cash_interest'] : 0;

        $rateInterest = !empty($res['rate_interest']) ? $res['rate_interest'] : 0;

        return round(($cashInterest + $rateInterest), 2);
    }

    /**
     * @param $projectId
     * @param $cash
     * @param $profit
     * @return float
     * @desc 预期收益
     */
    public function getInterestInfo($projectId,$cash,$profit){

        $termModel = new TermModel();

        $res = $termModel->getProfit($projectId,$cash,$profit);

        $res['cash_interest'] = !empty($res['cash_interest']) ? $res['cash_interest'] : 0;

        $res['rate_interest'] = !empty($res['rate_interest']) ? $res['rate_interest'] : 0;

        return $res;

    }

    /**
     * @param $projectId
     * @param $cash
     * @param string $invest_time
     * @return array
     * @desc 根据项目ID和投资金额获取首次回款记录
     */
    public function getFirstRefund($projectId,$cash,$invest_time = ''){

        $projectModel = new \App\Http\Models\Project\ProjectModel();

        $res  = $projectModel->getFirstRefund($projectId,$cash,$invest_time);

        return $res;
    }

    /**
     * @param int   $userId  用户id
     * @param array $project 项目信息
     * @param int   $cash
     * @param float $balance
     * @param int   $bonusMoney
     * @param int   $bonusRate
     * @return array
     */
    public function checkInvest($userId, $project, $cash, $balance, $bonusMoney=0, $bonusRate=0, $isUseCurrent=false)
    {

        try{

            //检测用户
            ValidateModel::isUserId($userId);

            //检测金额
            ValidateModel::isCash($cash);

            //检测项目id
            ValidateModel::isProjectId($project['id']);

            $newCash = $cash + $bonusMoney;

            //检测项目可投状态
            \App\Http\Models\Project\ProjectModel::checkCanInvest($project, $newCash);

            if( !$isUseCurrent ){

                //检测用户余额
                ValidateModel::checkBalance($balance,$cash);

            }

            //检测当前项目是否能使用加息券
            \App\Http\Models\Project\ProjectModel::checkProjectRateLimit($project['project_line'],$project['profit_percentage'],$bonusRate, $bonusMoney);

            $return = self::callSuccess('信息正确');

        }catch (\Exception $e){

            $return = self::callError($e->getMessage());

        }

        return $return;

    }

    /**
     * @param $userId
     * @param $projectId
     * @param $cash
     * @param $tradePassword
     * @param int $bonusId
     * @param string $appRequest
     * @return array
     * @desc 执行投资
     */
    public function doInvest($userId, $projectId, $cash, $tradePassword, $bonusId=0, $appRequest='pc',$actToken = '')
    {
        $bonusMoney = 0;
        $bonusRate  = 0;
        $bonusType  = 0;
        if($bonusId > 0) {
            $userBonusModel = new UserBonusModel();
            $bonus          = $userBonusModel->getUserBonusById($bonusId);
            $bonusMoney     = $bonus['bonus_info']['money'];
            $bonusRate      = $bonus['bonus_info']['rate'];
            $bonusType      = $bonus['bonus_info']['type'];
        }

        $cash = (int)$cash;

        $totalCash          = $cash + $bonusMoney;

        //用户信息
        $userInfo           = UserModel::getCoreApiUserInfo($userId);

        //项目信息
        $projectLogic       = new ProjectDetailLogic();
        $project            = $projectLogic->get($projectId);

        //项目相关检测
        $project['project_line'] = $project['product_line'] + $project['type'];
        $checkResult        = $this->checkInvest($userId, $project, $cash, $userInfo['balance'], $bonusMoney, $bonusRate);

        if( !$checkResult['status'] ){

            return $checkResult;

        }

        //检测交易密码
        $tradePasswordLogic = new PasswordLogic();

        $checkTradeResult   = $tradePasswordLogic->checkTradingPassword($tradePassword, $userId);

        if( !$checkTradeResult['status'] ){

            return $checkTradeResult;

        }

        $cacheKey = 'project_lock_'.$projectId;

        if(Cache::has($cacheKey)){

            return self::callError('验证失败，请重新投资');
        }

        Cache::put($cacheKey,1,0.2);   //0.2为过期时间,单位为分钟

        try{
            self::beginTransaction();

            //红包加锁
            $data = array(
                'userId'        => $userId,
                'userBonusId'   => $bonusId,
                'source'        => $appRequest,
                'cash'          => $totalCash,
                'productLine'   => $project['product_line'],
                'type'          => $project['type'],
                'bonusType'     => $bonusType,
                'projectId'     => $projectId
            );

            Event::fire(new \App\Events\Invest\ProjectBeforeEvent($data));
            //投资调核心接口
            $invest = \App\Http\Models\Project\ProjectModel::doInvest($userId,$projectId,$cash,$bonusMoney,$bonusRate);

            self::commit();

            $bonusIsLock = true;

            Cache::put('invest_id',$invest['invest_id'],1);

            $param = [];

            //预期收益
            $fee = $this->getProfit($projectId,$totalCash,$bonusRate);

            $userBonusModel = new UserBonusModel();

            $userBonusModel->checkIsRefundType($bonusType, $project['refund_type']);

            //使用红包
            $param['bonus']    = [
                'bonus_id'  => $bonusId,
                'invest_id' => $invest['invest_id'],
            ];

            //生成投资记录
            $param['invest']   = [
                'invest_id'     => $invest['invest_id'],
                'user_id'       => $userId,
                'project_id'    => $projectId,
                'cash'          => $totalCash,
                'bonus_id'      => $bonusId>0?$bonusId:0,
                'bonus_type'    => $bonusType,
                'bonus_value'   => $bonusMoney > 0 ? $bonusMoney : $bonusRate,
                'source'        => $appRequest,
                'original_cash' => $cash,
            ];

            //发送短信
            $param['sms']      = [
                'project_id'            =>$projectId,
                'refunded_interest'     =>$fee,
                'cash'                  =>$totalCash,
                'phone'                 =>$userInfo['phone'],
                'interest_total'        =>$totalCash+$fee
            ];

            //生成加息券的回款记录
            $param['add_rate']   = [
                'invest_id'     => $invest['invest_id'],
                'rate'          => $bonusRate,
            ];

            //发送站内信
            $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_INVEST_PROJECT);

            $msg = sprintf($msgTpl, ToolTime::dbNow(), $projectId, $totalCash, $fee);

            $param['notice'] = [
                'title'     => NoticeDb::TYPE_INVEST_PROJECT,
                'user_id'   => $userId,
                'message'   => $msg,
                'type'      => NoticeDb::TYPE_INVEST_PROJECT
            ];

            $param['loan_user'] = [
                'project_id'        => $projectId,
                'invested_amount'   => $project['invested_amount'],
                'total_amount'      => $project['total_amount'],
                'invest_cash'       => $totalCash,
                'new'               => (isset($project['new']) && $project['new']) ? $project['new'] : '',
            ];
            //记录用户活动的数据
            $param['activity']  =   [
                'cash'          =>  $totalCash,
                'project_id'    =>  $projectId,
                'invest_id'     =>  $invest['invest_id'],
                'user_id'       =>  $userId,
                'act_token'     =>  $actToken,
                'project_line'  =>  $project['project_line'],
                'bonus_id'      =>  $bonusId,
            ];
            //调取事件使用红包，发送短信，本模块投资记录
            Event::fire(new \App\Events\Invest\ProjectSuccessEvent($param));

            $msg = "用户ID[$userId]投资项目ID[$projectId],投资$totalCash 元，".$bonusMoney>0?"红包金额:$bonusMoney 元，":""."投资成功。";

            Log::info($msg);

            Cache::forget($cacheKey);
            
            return self::callSuccess();

        }catch (\Exception $e){

            self::rollback();

            Cache::forget($cacheKey);

            $msg = "用户ID[$userId]投资项目ID[$projectId],投资$totalCash 元，".$bonusMoney>0?"红包金额:$bonusMoney 元，":""."投资失败，原因：".$e->getMessage();

            Log::error($msg);

            //调取事件解锁
            $param['bonus']  = $bonusId;

            if(!empty($bonusIsLock) && $bonusIsLock){
                Event::fire(new \App\Events\Invest\ProjectUnLockBonusEvent($param));
            }
            return self::callError($e->getMessage());

        }

    }

    /**
     * @param $userId
     * @param $projectId
     * @param $cash
     * @param $tradePassword
     * @param int $bonusId
     * @param string $appRequest
     * @return array
     * @desc 通过零钱进行投资
     */
    public function doInvestByCurrent($userId, $projectId, $cash, $tradePassword, $bonusId=0, $appRequest='pc',$actToken = '')
    {

        $bonusMoney = 0;
        $bonusRate  = 0;
        $bonusType  = 0;
        if($bonusId > 0) {
            $userBonusModel = new UserBonusModel();
            $bonus          = $userBonusModel->getUserBonusById($bonusId);
            $bonusMoney     = $bonus['bonus_info']['money'];
            $bonusRate      = $bonus['bonus_info']['rate'];
            $bonusType      = $bonus['bonus_info']['type'];
        }

        $cash = (int)$cash;

        $totalCash          = $cash + $bonusMoney;

        //用户信息
        $userInfo           = UserModel::getCoreApiUserInfo($userId);

        //项目信息
        $projectLogic       = new ProjectDetailLogic();
        $project            = $projectLogic->get($projectId);

        //项目相关检测
        $project['project_line'] = $project['product_line'] + $project['type'];
        $checkResult        = $this->checkInvest($userId, $project, $cash, $userInfo['balance'], $bonusMoney, $bonusRate, $isUseCurrent=true);

        if( !$checkResult['status'] ){

            return $checkResult;

        }

        //检测交易密码
        $tradePasswordLogic = new PasswordLogic();

        $checkTradeResult   = $tradePasswordLogic->checkTradingPassword($tradePassword, $userId);

        if( !$checkTradeResult['status'] ){

            return $checkTradeResult;

        }

        $cacheKey = 'project_lock_'.$projectId;

        if(Cache::has($cacheKey)){

            return self::callError('验证失败，请重新投资');
        }

        Cache::put($cacheKey,1,0.2);   //0.2为过期时间,单位为分钟

        try{
            self::beginTransaction();

            //检查零钱计划转出限额
            $investModel    =  new CurrentModel();

            $investModel->checkInvestOutLimit($userId,$cash);

            //红包加锁
            $data = array(
                'userId'        => $userId,
                'userBonusId'   => $bonusId,
                'source'        => $appRequest,
                'cash'          => $totalCash,
                'productLine'   => $project['product_line'],
                'type'          => $project['type'],
                'bonusType'     => $bonusType,
                'projectId'     => $projectId
            );

            Event::fire(new \App\Events\Invest\ProjectBeforeEvent($data));
            //投资调核心接口
            $invest = \App\Http\Models\Project\ProjectModel::doInvestByCurrent($userId,$projectId,$cash,$bonusMoney,$bonusRate);

            //记录零钱计划投资记录
            $currentInvestDb = new CurrentInvestDb();
            $currentInvestDb->doInvestOut($userId,$cash,$appRequest);

            self::commit();

            $bonusIsLock = true;

            Cache::put('invest_id',$invest['invest_id'],1);

            $param = [];

            //预期收益
            $fee = $this->getProfit($projectId,$totalCash,$bonusRate);

            $userBonusModel = new UserBonusModel();

            $userBonusModel->checkIsRefundType($bonusType, $project['refund_type']);

            //使用红包
            $param['bonus']    = [
                'bonus_id'  => $bonusId,
                'invest_id' => $invest['invest_id'],
            ];

            //生成投资记录
            $param['invest']   = [
                'invest_id'     => $invest['invest_id'],
                'user_id'       => $userId,
                'project_id'    => $projectId,
                'cash'          => $totalCash,
                'bonus_id'      => $bonusId>0?$bonusId:0,
                'bonus_type'    => $bonusType,
                'bonus_value'   => $bonusMoney > 0 ? $bonusMoney : $bonusRate,
                'source'        => $appRequest,
                'original_cash' => $cash,
            ];

            //发送短信
            $param['sms']      = [
                'project_id'            =>$projectId,
                'refunded_interest'     =>$fee,
                'cash'                  =>$totalCash,
                'phone'                 =>$userInfo['phone'],
                'interest_total'        =>$totalCash+$fee
            ];

            //生成加息券的回款记录
            $param['add_rate']   = [
                'invest_id'     => $invest['invest_id'],
                'rate'          => $bonusRate,
            ];

            //发送站内信
            $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_INVEST_PROJECT);

            $msg = sprintf($msgTpl, ToolTime::dbNow(), $projectId, $totalCash, $fee);

            $param['notice'] = [
                'title'     => NoticeDb::TYPE_INVEST_PROJECT,
                'user_id'   => $userId,
                'message'   => $msg,
                'type'      => NoticeDb::TYPE_INVEST_PROJECT
            ];

            $param['loan_user'] = [
                'project_id'        => $projectId,
                'invested_amount'   => $project['invested_amount'],
                'total_amount'      => $project['total_amount'],
                'invest_cash'       => $totalCash,
                'new'               => (isset($project['new']) && $project['new']) ? $project['new'] : '',
            ];
            //记录用户活动的数据
            $param['activity']  =   [
                'cash'          =>  $totalCash,
                'project_id'    =>  $projectId,
                'invest_id'     =>  $invest['invest_id'],
                'user_id'       =>  $userId,
                'act_token'     =>  $actToken,
                'project_line'  =>  $project['project_line'],
                'bonus_id'      =>  $bonusId,
            ];
            //调取事件使用红包，发送短信，本模块投资记录
            Event::fire(new \App\Events\Invest\ProjectSuccessEvent($param));

            $msg = "用户ID[$userId]投资项目ID[$projectId],投资$totalCash 元，".$bonusMoney>0?"红包金额:$bonusMoney 元，":""."投资成功。";

            Log::info($msg);

            Cache::forget($cacheKey);

            return self::callSuccess();

        }catch (\Exception $e){

            self::rollback();

            Cache::forget($cacheKey);

            $msg = "用户ID[$userId]投资项目ID[$projectId],投资$totalCash 元，".$bonusMoney>0?"红包金额:$bonusMoney 元，":""."投资失败，原因：".$e->getMessage();

            Log::error($msg);

            //调取事件解锁
            $param['bonus']  = $bonusId;

            if(!empty($bonusIsLock) && $bonusIsLock){
                Event::fire(new \App\Events\Invest\ProjectUnLockBonusEvent($param));
            }
            return self::callError($e->getMessage());

        }

    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户投资中项目
     */
    public function getInvesting($userId,$page,$size){
        try{
            $model = new TermModel();
            $result = $model->getInvesting($userId,$page,$size);
            return $result;
        }catch (\Exception $e){
            return [];
        }
    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户已还款项目
     */
    public function getRefunded($userId,$page,$size){
        try{
            $model = new TermModel();
            $result = $model->getRefunded($userId,$page,$size);
            return $result;
        }catch (\Exception $e){
            return [];
        }
    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户还款中项目
     */
    public function getRefunding($userId,$page,$size){
        try{
            $model = new TermModel();
            $result = $model->getRefunding($userId,$page,$size);
            return $result;
        }catch (\Exception $e){
            return [];
        }
    }

    /**
     * @param int $size
     * @return bool|mixed|string
     * @desc 全站投资风云榜，显示用户投资总额的排名12个
     */
    public function getFulWinList($size=10)
    {

        $termModel = new TermModel();

        $investList = $termModel->getCacheFulWinList($size);

        if( !empty($investList) ){

            $userIds = ToolArray::arrayToIds($investList, 'user_id');

            $userList = UserModel::getCoreUserListByIds($userIds);

            $userList = ToolArray::arrayToKey($userList);

            foreach( $investList as $key => $val ){

                $investList[$key]['cash'] = number_format($val['cash']);

                if( isset($userList[$val['user_id']]) ){

                    $investList[$key]['phone'] = ToolStr::hidePhone($userList[$val['user_id']]['phone']);

                }

            }

        }

        return $investList;

    }


    /**
     * @param $userId
     * @return array
     * @desc 获取用户未完结项目
     */
    public function getNoFinish($userId,$page,$size){
        try{
            $model = new TermModel();
            $result = $model->getNoFinish($userId,$page,$size);
            return $result;
        }catch (\Exception $e){
            return [];
        }
    }

    /**
     * 转化数据给app
     * @param  string  $client
     * @param  array   $data
     * @param  array   $creditAssignIds
     * @return array
     */
    public function formatTermDataForApp($client,$data, $creditAssignIds=[]){

        foreach ($data as $key => $value) {

            if(empty($value)) continue;

            //$isUsed = InvestModel::isUseBonus($value['invest_id']);

            $res = [
                'id'                        => $value['invest_id'],
                'refund_assign_project_id'  => in_array($value['invest_id'], $creditAssignIds)?1:0,
                'invest_time'               => $value['created_at'],
                'p_name'                    => $value['name'],
                //'type'                      => $isUsed ? 1 : 0,
                'type'                      => 1 ,// 1-定期项目 2-债转项目,新系统没有债转项目
                'cash'                      => $value['principal'],
                'plan_refunded_time_note'   => AppLang::APP_PLAN_REFUNDED_TIME,
                'interest_text'             => (ToolTime::dbDate()>=$value['end_at'])?AppLang::APP_END_INTEREST_TEXT:AppLang::APP_INTEREST_TEXT,
                'plan_refund_time'          => $value['end_at'],
                'times'                     => $value['type']==1 ? $value['invest_time'] : $value['type'],
                'project_id'                => $value['project_id'],
                'plan_refund_time_note'     => AppLang::APP_PLAN_REFUND_TIME,
                //'refund_interest'           => $value['total']+$value['principal'],
                'refund_interest'           => $value['total'],
            ];

            $date = date('Y-m',strtotime($value['created_at']));

            if($client == 'ios') {

                $return[$date][] = $res;

            } else {

                $res['date'] = $date;

                $return[] = $res;
            }
        }

        return empty($return) ? [[]] : $return;
    }


    /**
     * app投资详情页数据包
     * @param  [int] $userId
     * @param  [int] $investId
     * @return [array]
     */
    public function getInvestDetailByIdForApp($userId,$investId) {

        if(empty($investId)){

            return self::callError(AppLang::APP_INVEST_PARAM_ERROR);

        }

        //获取投资信息
        $investDb = new InvestDb();

        $invest = $investDb->getInfoByInvestId($investId);

        if ( empty($invest) || $invest['user_id'] != $userId ) {

            return self::callError(AppLang::APP_INVEST_INFO_ERROR);

        }

        //获取用户的项目回款计划
        $refundPlan = CoreApiUserModel::getRefundDetail($userId,$investId);

        if(empty($refundPlan)){

            return self::callError(AppLang::APP_INVEST_NOPLAN_ERROR);

        }

        $coupon_text = $coupon_tip = '';

        $ownInterest = $ingInterest = 0;

        $couponInterest = '';

        //格式化回款计划
        foreach ($refundPlan['plan'] as $key => $value) {

            $result['refund_list'][] = [
                "coupon_text"       => ($value['type'] == 1) ? $invest['bonus_value'].'%加息奖励' : '',
                "principal"         => $value['principal'],
                "status"            => $value['status'],
                "times"             => $value['times'],
                "interest"          => $value['interest'],
                "refund_time"       => $value['times'],
                "user_id"           => $userId,
                "invest_type"       => ($value['type'] == 1) ? 3 : 0, //是否使用加息券,app接口转换
                "interest_accrual"  => 0,
                "invest_id"         => $investId
            ];

            if( $value['status'] == 200 ){//已回款

                $ownInterest += $value['interest'];

            } else {//未回款

                $ingInterest += $value['interest'];

            }

            if( $value['type'] == 1 ){

                $couponInterest += $value['interest'];

                $coupon_text = $invest['bonus_value'].'%加息奖励';

            }

        }

        //格式化投资信息
        $result['invest_info'] = [
            'invest_time'     =>  $invest['created_at'],
            'done_interest'   =>  $ownInterest,
            'doing_interest'  =>  ($ingInterest > 0) ? $ingInterest - $couponInterest : $ingInterest,   //减去加息奖励的,分开计算
            'cash'            =>  $invest['cash'],
            'coupon_tip'      =>  $coupon_text,
            'doing_coupon'    =>  $couponInterest,
        ];

        //获取项目信息
        $assignProjectTitle = '';

        if( $invest['project_id'] == $refundPlan['plan'][0]['project_id'] ){

            $projectId = $invest['project_id'];

        }else{

            $projectId = $refundPlan['plan'][0]['project_id'];

            $assignProjectTitle = ' (变现宝)';

        }

        $projectDetailLogic = new ProjectDetailLogic();

        $project = $projectDetailLogic->appGet($projectId);

        $project = $project['data']['project'];

        $projectName        =   $project['project_name'];

        if( $invest['project_id'] != $refundPlan['plan'][0]['project_id']){

            $projectName    =   $project['product_line_note'].$assignProjectTitle;
        }

        //项目回款计划
        $projectLinkCreditModel = new ProjectLinkCreditModel();

        $projectPlan = $projectLinkCreditModel->getCoreProjectRefundPlan($projectId);

        $end_refund = array_pop($projectPlan);

        if($project['product_line'] == 300){
            $projectWay = 'pre';
        }else{
            $projectWay = $assignProjectTitle ? '40' : $project['project_way'];
        }


        //格式化项目信息
        $result['project_info'] = [
            "refund_type"       =>  $project['refund_type'],
            "id"                =>  $projectId,
            "refund_end_time"   =>  $end_refund['refund_time'],
            "project_way"       =>  $projectWay,
            "profit_percentage" =>  $project['profit_percentage'],
            "refund_type_note"  =>  $project['refund_type_name'],
            "name"              =>  $projectName
        ];

        return self::callSuccess($result);
    }


    /**
     * [获取app投资成功后返回的数据]
     * @param  [int] $projectId         [项目id]
     * @param  [int] $cash              [投资金额]
     * @param  [int] $userBonusIdsh     [使用红包加息券ID]
     * @return [array]
     */
    public function getInvestBackForApp($projectId,$cash,$userBonusId){

        $projectLinkCreditModel = new ProjectLinkCreditModel();

        $project = $projectLinkCreditModel->getCoreProjectDetail($projectId);

        //红包数据初始化
        $bonus = ['money'=>0,'rate'=>0];

        if($userBonusId>0){
            $userBonusModel = new UserBonusModel();
            $bonusRes = $userBonusModel->getUserBonusById($userBonusId);
            $bonus = $bonusRes['bonus_info'];
        }
        $fee = $this->getInterestInfo($projectId,$cash+$bonus['money'],$bonus['rate']);
        //收益到账时间
        $plans = $projectLinkCreditModel->getCoreProjectRefundPlan($projectId);

        if($project['refund_type'] != ProjectDb::REFUND_TYPE_ONLY_INTEREST){
            $refundText         = AppLang::APP_REFUND_DATE;
            $refundEndDataNote  = AppLang::APP_INVEST_OWNED;
        }else{
            $refundText         = AppLang::APP_FIRST_REFUND_DATE;
            $refundEndDataNote  = AppLang::APP_END_REFUND_DATE;
        }

        $cash = $cash + $bonus['money']; //添加红包金额

        return [
            'back'  => [
                'cash'              => $cash,
                'refundCash'        => $cash + $fee['cash_interest'] + $fee['rate_interest'],
                'refundText'        => $refundText,
                'refundDate'        => $plans[0]['refund_time'],
                'refundType'        => $project['refund_type'],
                'total'             => $fee['cash_interest'], //预期收益(不包括加息券收益)
                'investType'        => $userBonusId > 0 ? 1 : 0,
                'refundEndDataNote' => $refundEndDataNote,
                'refundEndData'     => end($plans)['refund_time'],
                'projectName'       => $project['name'],
                'bonus'             => [],
                'experience'        => [],
                'profit_percentage' => $project['profit_percentage'],
                'coupon_tip'        => $bonus['rate']>0?sprintf(AppLang::APP_INVEST_AWARD_RATE,$bonus['rate']):'',
                'coupon_status'     => $bonus['rate']>0?1:0,
                'coupon_interest'   => $bonus['rate']>0?sprintf(AppLang::APP_INVEST_AWARD_CASH, $fee['rate_interest']):'', //加息券收益
                'alert_message'     => '',
                'bonusShake'        => 0
            ],
            'ads'   => [],
            'share' => []
        ];

    }

    /**
     * @desc [管理后台]投资列表逻辑处理
     * @author lgh
     * @param       $page
     * @param       $pageSize
     * @param array $param
     * @return array
     */
    public function getAdminInvestList($page, $pageSize, $param =[]){
        $investDb = new InvestDb();

        //格式化搜索条件
        $where = $this->formatAdminInvestListWhere($param);
        //获取Model投资信息集合
        $investList = $investDb->getAdminInvestList($where, $page, $pageSize);
        //获取核心的信息列表
        $investIds = ToolArray::arrayToIds($investList['data'], 'invest_id',false);
        $coreInvestList = ProjectModel::getListByIds($investIds);
        $coreInvestInfo = ToolArray::arrayToKey($coreInvestList, 'id', false);
        //合并投资记录
        foreach($investList['data'] as $key=>$val){
            //core投资信息
            if(isset($coreInvestInfo[$val['invest_id']])){
                $investList['data'][$key]['project_id'] = $coreInvestInfo[$val['invest_id']]['project_id'];

                $investList['data'][$key]['invest_type'] = ($coreInvestInfo[$val['invest_id']]['invest_type'] == 1) ? "债转项目" : "直投项目";
                $investList['data'][$key]['assign_project_id'] = ($coreInvestInfo[$val['invest_id']]['invest_type'] == 1) ? $coreInvestInfo[$val['invest_id']]['assign_project_id'] : "";
            }
        }


        //获取投资人的信息集合
        $userIds = ToolArray::arrayToIds($investList['data'], 'user_id',false);
        $users = UserModel::getCoreUserListByIds($userIds);
        $userInfo = ToolArray::arrayToKey($users, 'id');

        //获取投资项目信息集合
        $projectIds = ToolArray::arrayToIds($investList['data'], 'project_id', false);
        $projects = ProjectModel::getProjectListByIds($projectIds);
        $projectInfo = ToolArray::arrayToKey($projects, 'id');
        $projectIds = ToolArray::arrayToIds($investList['data'], 'project_id');

        $projectDetailLogic = new ProjectDetailLogic;

        //多个项目ID获取项目债权关联信息
        $projectLinkCreditNewModel = new ProjectLinkCreditNewModel();
        $projectIdKeys           = $projectLinkCreditNewModel->getByProjectIds($projectIds);

        foreach($investList['data'] as $key=>$val){
            //用户信息
            if(isset($userInfo[$val['user_id']])){
                $investList['data'][$key]['userInfo'] = $userInfo[$val['user_id']];
            }
            //项目信息
            if(isset($projectInfo[$val['project_id']])){
                $investList['data'][$key]['projectInfo'] = $projectInfo[$val['project_id']];
            }


            $investList['data'][$key]['creditInfo']  = [];
            try {
                // 债权关联表
                //$projectLinkCredit = $projectDetailLogic->getProjectLineCredit($val['project_id']);

                $projectLinkCredit = isset($projectIdKeys[$val['project_id']]) ? $projectIdKeys[$val['project_id']] : [];

                // 债权信息
                $projectCredit     = $projectDetailLogic->getCreditDetailNew($projectLinkCredit);

                $projectWay       = ProjectLinkCreditNewModel::getProjectWay($projectLinkCredit);

                $projectCredit['source'] = $projectWay;
                $projectCredit['name']   = '';
                if(!empty($projectCredit[0]['company_name'])){
                    $projectCredit['name']   = $projectCredit[0]['company_name'];
                }elseif(!empty($projectCredit[0]['plan_name'])){
                    $projectCredit['name']   = $projectCredit[0]['plan_name'];
                }

                //获取债券信息
                $investList['data'][$key]['creditInfo'] = $projectCredit;

            }catch (\Exception $e){
                Log::info(__METHOD__, [$e->getCode(), $e->getMessage()]);
            }
        }

        return $investList;
    }

    /**
     * @desc [管理后台]格式化投资列表搜索条件
     * @param $param
     * @return array
     */
    public function formatAdminInvestListWhere($param){
        $where  = [];
        //时间区间
        if(!empty($param['startTime'])){
            $startTime = $param['startTime'];
            $where[]  = ['created_at','>=', $startTime];
        }

        if(!empty($param['endTime'])){
            $endTime = $param['endTime'];
            $where[]  = ['created_at','<=', $endTime." 23:59:59"];
        }

        if(!empty($param['phone'])){
            $userInfo =UserModel::getCoreApiBaseUserInfo($param['phone']);
            if(!empty($userInfo['id'])) {
                $userId = $userInfo['id'];
            }else{
                $userId = 0;
            }
            $where[] = ['user_id', '=', $userId];
        }

        return $where;
    }

    /**
     * @desc 定期投资数据统计
     * @author lgh
     * @param $param
     * @return mixed
     */
    public function getInvestStatistics($param){
        $currentLogic = new CurrentLogic();

        $investLogic = new InvestModel();

        $where = $currentLogic->formatGetInput($param);

        $investStatistics = $investLogic->getInvestStatistics($where);

        return $investStatistics;
    }
     /**
     * @param int $size
     * @return mixed
     * @desc 获取最新的投资列表
     */
    public function getNewInvest($size = 0){

        $model = new ProjectModel();

        $list  = $model->getNewInvest($size);

        return $list;
    }

    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 根据日期获取投资总额
     */
    public function getInvestAmountByDate($start = '',$end = ''){

        $model         = new ProjectModel();

        $currentModel  = new CurrentModel();

        //定期投资总额列表
        $termList      = $model->getInvestAmountByDate($start,$end);

        //零钱计划投资总额列表
        $currentList   = $currentModel->getCurrentAmountByDate($start,$end);

        //键位按日期索引
        $termAmount    = ToolArray::arrayToKey($termList,'date');
        $currentAmount = ToolArray::arrayToKey($currentList,'date');

        $result = [];
        foreach($termAmount as $key => $value)
        {
            $result[$key]['cash']   = 0;
            $result[$key]['total']  = 0;
            foreach([$termAmount,$currentAmount] as $array)
            {
                $result[$key]['cash']   += isset($array[$key]['cash']) ? (int)$array[$key]['cash'] : 0;
                $result[$key]['total']  += isset($array[$key]['total']) ? (int)$array[$key]['total'] : 0;
            }
            $result[$key]['date']   = $value['date'];
            $result[$key]['invest'] = isset($termAmount[$key]) ? $termAmount[$key] : null;
            $result[$key]['current']= isset($currentAmount[$key]) ? $currentAmount[$key] : null;
        }
        return $result;

    }

    /**
     * 得出定期,零钱计划的交易总额
     * @author zhuming
     * @param string|date start
     * @return array = [
     *    'total'   总额
     *    'invest'  定期投资总额
     *    'current' 零钱计划投资总额
     * ]
     */
    public function getInvestTotalAmounts($start = '',$end = '')
    {
        $projectModel   = new ProjectModel();
        $currentModel   = new CurrentModel();

        $investAmount   = $projectModel->getInvestTotalAmounts($start,$end);
        $currentAmount  = $currentModel->getCurrentAmountTotal($start,$end);

        $result = [
            'total'         =>  $investAmount+$currentAmount,
            'invest'        =>  $investAmount,
            'current'       =>  $currentAmount,
        ];

        return $result;
    }

    /**
     * @param $projectIds
     * @return array
     * @desc 根据项目id获取红包投资信息
     */
    public function getBonusInvestCashList($projectIds){

        if( empty($projectIds) ){

            return [];

        }

        $investDb = new InvestDb();

        $bonusInvestList = $investDb->getBonusCashListByProjectIds($projectIds);

        if( !empty($bonusInvestList) ){

            $bonusInvestList = ToolArray::arrayToKey($bonusInvestList, 'project_id');

        }

        return $bonusInvestList;

    }

    /**
     * @param $projectIds
     * @return array
     * @desc 获取项目最后一笔投资记录
     */
    public function getLastInvestListByProjectIds($projectIds){

        if( empty($projectIds) ){

            return [];

        }

        $investDb = new InvestDb();

        $investList = $investDb->getLastInvestListByProjectIds($projectIds);

        if( !empty($investList) ){

            return ToolArray::arrayToKey($investList, 'project_id');

        }

        return [];

    }

    /**
     * @param $startTime
     * @param $endTime
     * @return array|mixed
     * @desc 通过起始时间获取每个项目最后一笔的投资记录
     */
    public function getLastInvestListByStartTimeEndTime($startTime, $endTime){

        if( empty($startTime) || empty($endTime) ){

            return [];

        }

        $investDb = new InvestDb();

        $investList = $investDb->getLastInvestListByStartTimeEndTime($startTime, $endTime);

        return $investList;

    }

    /**
     * @param $where
     * @return mixed
     * @desc PK活动专属查询数据
     */
    public function getInvestStatisticsExist( $where )
    {
        $currentLogic   = new CurrentLogic();

        $investModel    = new InvestModel();

        $where          = $currentLogic->formatGetInput($where);

        return $investModel->getInvestStatisticsExist($where);

    }

    /**
     * @param array $projectIds
     * @return mixed
     * @desc 通过项目id 获取投资的笔数
     */
    public function getInvestTotalByProject( $projectIds = array())
    {
        $projectIds =   ToolArray::setVariableToArray($projectIds);

        $db         =   new InvestDb();

        return $db -> getInvestTotalByProject($projectIds);
    }

    /**
     * @param array $projectIds
     * @return array
     * @desc 获取正常投资投资列表
     */
    public function getNormalInvestByProjectIds( $projectIds = array() )
    {
        if( empty($projectIds) ){

            return[];
        }

        $investList     =   ProjectModel::getNormalInvestByProjectIds($projectIds);

        return $investList;
    }
    /**
     * @param $projectIds
     * @return array
     * @desc 从核心获取最后一次投资的数据(不包含原项目债转的记录)
     */
    public function getLastInvestTimeByProjectIdFromCore($projectIds)
    {
        $lastInvestList =   ProjectModel::getLastInvestTimeByProjectIdFromCore($projectIds);

        if( !empty($lastInvestList) ){

            return ToolArray::arrayToKey($lastInvestList, 'project_id');
        }

        return [];
    }

    /**
     * @return mixed
     * @desc 通过项目ids 获取投资的笔数
     */
    public function getInvestPeopleByProject( $projectIds = array())
    {
        $projectIds =   ToolArray::setVariableToArray($projectIds);

        $db     = new InvestDb();

        $result = $db -> getInvestPeopleByProject($projectIds);

        return $result ? $result["userNum"] : 0;
    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * @desc 获取投资记录列表
     */
    public static function getInvestListByUserId( $userId, $page, $size)
    {

        if(empty($userId)){

            return [];

        }

        $result = CoreApiUserModel::getInvestListByUserId($userId, $page, $size);

        return $result;

    }

    /**
     * @param $userId
     * @return array
     * @desc 获取投资记录(仅用来判断是否有数据)
     */
    public static function getUserInvestDataByUserId($userId){

        if(empty($userId)){
            return [];
        }

        $result = CoreApiUserModel::getUserInvestDataByUserId($userId);

        return $result;
    }

    /***APP-4.0 *****/
    /**
     * @desc    我的资产-定期资产
     * @param   $type   investing-持有中   finish-已完结  assignment-转让
     * @return  array
     */
    public function appV4UserTermRecord($userId, $type, $page,$size){
        try{
            $model = new TermModel();
            $result = $model->getAppV4UserTerm($userId,$type,$page,$size);
            \Log::info(__METHOD__.' : '.__LINE__.var_export($result,true));
            $resData    = [];

            if($result){
                switch ($type){
                    case 'investing':
                    case 'finish':
                        $resData = self::appV4FormTermRecord($result,$type);
                        break;
                    case 'assignment';
                        $resData = self::appV4FormatAssignmentRecord($result);
                        break;
                }
            }

            return $resData;
        }catch (\Exception $e){
            return [];
        }

    }

    /**
     * @desc    我的资产-定期资产-持有中|已完成 数据格式化
     **/
    public static function appV4FormTermRecord($termRecord,$type="investing"){
        #dd($termRecord);exit;
        $user_principal = isset($termRecord['user_principal'])?$termRecord['user_principal']:'';
        $user_interest  = isset($termRecord['user_interest']) ?$termRecord['user_interest'] :'';
        $total          = isset($termRecord['total']) ?$termRecord['total'] :'';
        if($type == "finish"){
            $user_principal_note= "已回款本金";
            $user_interest_note = "已回款收益";
            $invest_principal_note  = "买入金额";
            $invest_interest_note   = "实际收益";
        }else{
            $user_principal_note= "在投本金";
            $user_interest_note = "待收收益";
            $invest_principal_note  = "买入金额";
            $invest_interest_note   = "预期收益";
        }
        $interest_time_note     = "起息日";
        $end_at_note            = "到息日";

        $resData = [
            "user_principal_note"=>$user_principal_note,      //用户本金label
            "user_principal"    => $user_principal,           //用户本金
            "user_interest_note"=> $user_interest_note,       //用户收益label
            "user_interest"     => $user_interest,            //用户收益
            'total'             => $total,                    //数据条数
            'record'            => [],                        //数据记录
        ];
        if(!empty($termRecord['record'])){
            foreach ($termRecord['record'] as $value){
                $project_name       = ( $value['invest_type'] == 1) ? '变现宝'.$value['assign_project_id'] : $value['name'];
                $interest_time      = date('Y-m-d',strtotime($value['created_at']));
                $invest_id          = isset($value['invest_id'])    ? $value['invest_id']:'';
                $project_id         = ( $value['invest_type'] == 1) ?  ' ' : ( isset($value['project_id'])   ? $value['project_id']:'' );
                $invest_principal   = isset($value['invest_principal']) ? $value['invest_principal']:'';
                $invest_interest    = isset($value['invest_interest'])  ? $value['invest_interest']:'';
                $end_at             = isset($value['end_at'])       ? $value['end_at']:'';
                $assignment         = (isset($value['assignment']) && empty($value['invest_type']) ) ? $value['assignment']:'';
                $product_line_note  = isset($value['product_line_note'])   ? $value['product_line_note']:'';
                if($type == "finish"){
                    $show_date          = date('Y年m月',strtotime($value['end_at']));
                }else{
                    $show_date          = date('Y年m月',strtotime($value['created_at']));
                }
                $assignment_note    = "";
                switch ($assignment){
                    case 1:
                        $assignment_note= "可转让";
                        break;
                    case 100:
                        $assignment_note= "转让中";
                        break;
                    case 130:
                        $assignment_note= "已转让";
                        break;
                }
                $termInfo   =   [
                    'user_id'           => $value['user_id'],   //用户ID
                    'invest_id'         => $invest_id,          //投资ID
                    'project_id'        => $project_id,         //项目ID
                    'project_name'      => $project_name,       //项目名称
                    'product_line_note' => $product_line_note,          //项目产品线
                    'invest_principal_note' => $invest_principal_note,  //投资金额label
                    'invest_principal'      => $invest_principal,       //投资金额
                    'invest_interest_note'  => $invest_interest_note,   //预期收益|实际收益label
                    'invest_interest'       => $invest_interest,        //预期收益|实际收益
                    'interest_time_note'    => $interest_time_note,     //起息日期label
                    'interest_time'         => $interest_time,          //起息日期
                    'end_time_note'         => $end_at_note,            //到期日label
                    'end_time'              => $end_at,                 //到期日
                    'assignment_note'       => $assignment_note,        //转让状态 0 正常，1 可转让 ，100转让中，130-已转让
                    'assignment'            => $assignment,             //转让状态 0 正常，1 可转让 ，100转让中，130-已转让
                    'show_date'             => $show_date,              //显示日期
                    'cash_unit'             => "元",                     //金额单位
                ];
                $resData['record'][] = $termInfo;

            }
        }

        return $resData;
    }


    /**
     * @desc    我的资产-定期资产-转让中-数据格式化
     **/
    public  static function appV4FormatAssignmentRecord($termRecord){

        $assignment_cash    = isset($termRecord['assignment_cash'])?$termRecord['assignment_cash']:'';
        $total              = isset($termRecord['total']) ? $termRecord['total'] : '';
        $resData = [
            "assignment_cash"   => $assignment_cash,            //转让中金额
            "assignment_note"   => "转让中金额",
            'total'             => $total,                      //数据条数
            'record'            => [],                          //数据记录
        ];
        if(!empty($termRecord['record'])){
            foreach ($termRecord['record'] as $value){
                $assignment_id  = isset($value['assignment_id'])? $value['assignment_id']:'';
                $invest_id      = isset($value['invest_id'])    ? $value['invest_id']:'';
                $project_id     = isset($value['project_id'])   ? $value['project_id']:'';

                $credit_cash    = isset($value['credit_cash'])  ? $value['credit_cash']:'';
                $project_time   = isset($value['project_time']) ? $value['project_time']:'';
                $refund_time    = isset($value['refund_time'])  ? $value['refund_time'].'期':'';
                $rest_days      = isset($value['rest_days'])    ? $value['rest_days'].'天':'';
                $project_name   = isset($value['project_name']) ? $value['project_name']:'';
                $product_line_note  = isset($value['product_line_note'])   ? $value['product_line_note']:'';
                $show_date      = date('Y年m月',strtotime($value['created_at']));
                $termInfo   =   [
                    'user_id'           => $value['user_id'],   //用户ID
                    'assignment_id'     => $assignment_id,      //转让后ID
                    'project_id'        => $project_id,         //原项目ID
                    'invest_id'         => $invest_id,          //投资ID
                    'project_name'      => $project_name,       //项目名称
                    'product_line_note' => $product_line_note,          //项目产品线
                    #转让中
                    'credit_cash_note'  => "买入本金",           //债权本金label
                    'credit_cash'       => $credit_cash,        //债权本金
                    'project_time_note' => "项目期限",           //项目期限label
                    'project_time'      => $project_time,       //项目期限
                    'refund_time_note'  => "已回款期数",         //已回款期数label
                    'refund_time'       => $refund_time,        //已回款期数
                    'rest_days_note'    => "剩余期限",           //剩余期限label
                    'rest_days'         => $rest_days,          //剩余期限
                    'show_date'         => $show_date,          //显示日期
                    'cash_unit'         => "元",                //金额单位
                ];
                $resData['record'][] = $termInfo;

            }
        }
        return $resData;
    }



    /**
     * @desc    App4.0  我的资产-定期资产-用户投资详情
     * @param  [int] $userId
     * @param  [int] $investId
     * @param  [int] $assignment
     * @return [array]
     */
    public static function appV4UserTermDetail($userId, $investId) {
        //验证投资ID
        if(empty($investId)){
            return self::callError(AppLang::APP_INVEST_PARAM_ERROR);
        }

        //获取投资信息    module_invest 数据表
        $investDb   = new InvestDb();
        $invest     = $investDb->getInfoByInvestId($investId);
        if ( empty($invest) || $invest['user_id'] != $userId ) {
            return self::callError(AppLang::APP_INVEST_INFO_ERROR);
        }
        #投资信息 -项目信息，投资信息，回款信息
        $model      = new TermModel();
        $resultData = $model->getAppV4UserTermDetail($userId,$investId);
        \Log::info(__METHOD__.' : '.__LINE__.var_export($resultData,true));
        $result     = [];
        if($resultData){
            $refundRecord   = $resultData['refund_record'];
            $investInfo     = $resultData['invest_info'];
            $assignment     = $resultData['assignment'];
            $credit_assign_project_id = $resultData['credit_assign_project_id'];

            if(isset($investInfo['invest_type']) && $investInfo['invest_type'] ){
                $assignment = ContractDb::PROJECT_CREDIT_ASSIGN;
            }

            if(empty($refundRecord['plan'])){
                return self::callError(AppLang::APP_INVEST_NOPLAN_ERROR);
            }

            $coupon_text    = $coupon_tip = '';
            $ownInterest    = $ingInterest = 0;
            $couponInterest = '';
            $refund_end_time= $investInfo['end_at'];
            $refund_list    = [];
            //格式化回款计划
            foreach ($refundRecord['plan'] as $key => $value) {
                $interest   = $value['interest'];
                $principal  = $value['principal'];
                $type       = $value['type'];

                if( $type == 1 ){
                    $refund_cash    = $interest;
                    $refund_text    = '加息奖励';
                    $couponInterest += $value['interest'];
                    $coupon_text    = $invest['bonus_value'].'%加息奖励';
                }else{
                    #本金
                    if($principal > 0 && $type != 1){
                        $refund_cash    = $principal.'+'.$interest;
                        $refund_text    = '本金+利息';
                    }else{
                        $refund_cash    = $interest;
                        $refund_text    = '利息';
                    }
                }

                if( $value['status'] == 200 ){
                    //已回款
                    $ownInterest    += $interest;
                    $refund_status  = '已回款';
                } else {
                    //未回款
                    $ingInterest    += $interest;
                    $refund_status  = '未回款';
                }
                $refund_end_time    = $value['times'];      //回款日期
                $refund_list[] = [
                    "refund_time"   => $value['times'],     // 回款时间
                    "status"        => $value['status'],    // 回款状态
                    "refund_status" => $refund_status,      // 回款状态描述
                    "principal"     => $value['principal'], // 本金
                    "interest"      => $value['interest'],  // 利息
                    'refund_cash'   => $refund_cash."元",    // 回款记录-显示金额
                    'refund_text'   => $refund_text,    // 回款记录-显示描述
                ];
            }

            if( $invest['project_id'] == $refundRecord['plan'][0]['project_id'] ){
                $projectId      = $invest['project_id'];
            }else{
                $projectId      = $refundRecord['plan'][0]['project_id'];
            }
            $project_name       = ( $investInfo['invest_type'] == 1 ) ?  '变现宝'.$investInfo['assign_project_id'] : ( $investInfo['name'].' '.$projectId );
            $refund_type_name   = CreditModel::refundAppType($investInfo['refund_type']);
            $result    =   [
                'invest_id'         => $investId,           //投资ID
                'project_id'        => $projectId,          //投资项目ID
                'project_name'      => $project_name,       //项目名称
                'invest_cash'       => $invest['cash'],     //投资金额
                'profit_percentage' => $investInfo['profit_percentage'],   //收益利率
                'ownInterest'       => $ownInterest,        //已收利息
                'ingInterest'       => $ingInterest,        //预计待收利息
                'coupon_text'       => $coupon_text,        //加息券描述
                'doing_coupon'      => $couponInterest,     //加息奖励
                'assignment'        => $assignment,         //转让状态
                'credit_assign_project_id' => $credit_assign_project_id, //转让项目ID

                'invest_time'       => $invest['created_at'],       //投资日期
                'refund_end_time'   => $refund_end_time,            //预计完结日期
                'refund_type'       => $investInfo['refund_type'],  //回款方式
                'refund_type_text'  => $refund_type_name,           //回款方式描述
                'refund_list'       => $refund_list,

            ];

        }
        return self::callSuccess($result);
    }

    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * @return array
     * @desc 新手专属项目首投条件判断（项目为普付宝质押，用户未进行过定期投资，投资金额上限）
     */
    public function checkNoviceProjectLimit($projectId, $userId, $cash){

        try{
            //判断用户ID
            ValidateModel::isUserId($userId);

            //判断项目ID
            ValidateModel::isProjectId($projectId);

            //判断投资金额
            ValidateModel::isCash($cash);

            //判断项目是否为普付宝质押项目
            ProjectModel::checkProjectIsPledge($projectId);

            //判断投资限额
            ValidateModel::isAbleInvestCash($cash);

            //判断用户是否为首投
            ValidateModel::isNoviceInvestUser($userId);

            return self::callSuccess();

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }

    }
}
