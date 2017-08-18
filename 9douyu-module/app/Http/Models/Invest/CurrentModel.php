<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/13
 * Time: 20:11
 */
namespace App\Http\Models\Invest;

use App\Http\Dbs\Current\FundStatisticsDb;
use App\Http\Dbs\Current\InvestDb;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Current\CashLimitModel;
use App\Http\Models\Model;
use App\Http\Models\Common\TradingPasswordModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Models\User\UserModel;
use App\Tools\ToolMoney;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Dbs\Current\ProjectDb;
use App\Lang\LangModel;
use App\Http\Dbs\Bonus\UserBonusDb;
use App\Tools\ToolTime;
use Cache;

class CurrentModel extends Model{


    public static $codeArr = [
        'checkUserBalance'                          => 1,
        'checkInvestLimitMinInvestAmount'           => 2,
        'checkInvestLimitMaxInvestAmount'           => 3,
        'checkInvestLimitFreeInvestAmount'          => 4,
        'checkInvestOutLimitMinInvestAmount'        => 5,
        'checkInvestOutLimitUserNotExist'           => 6,
        'checkInvestOutLimitCashGreaterAccount'     => 7,
        'checkInvestOutLimitMaxInvestAmount'        => 8,
        'checkInvestOutLimitFreeInvestAmount'       => 9,
        'doInvest'                                  => 10,
        'doInvestOut'                               => 11,
        'checkTodayInvestOutLimitFreeInvestAmount'  => 12,
        'checkLeftMoneyCanInvestOut'                => 13,
        'getUserAccountExistByUserId'               => 14,
        'checkUserTodayInvestOutCash'               => 15,
        'checkPlatformInvestOutLimit'               => 16
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_INVEST_CURRENT;


    /**
     * @param $password
     * @param $dbPassword
     * @throws \Exception
     * 验证交易密码
     */
    public function checkTradingPassword($password,$dbPassword){

        //验证交易密码是否正确
        TradingPasswordModel::checkPassword($password,$dbPassword);

    }

    /**
     * @param $userId
     * @return array
     * 获取零钱计划帐户信息
     */
    public function getCurrentAccount($userId){

        $result = \App\Http\Models\Common\CoreApi\CurrentModel::getCurrentUserInfo($userId);

        return $result;
    }


    /**
     * @return int
     * 获取今日零钱计划已转出的总金额
     */
    public function getTodayCurrentTotalInvestOut(){

        $todayInvestOutAmount = \App\Http\Models\Common\CoreApi\CurrentModel::getTodayCurrentInvestOutAmount();
        return $todayInvestOutAmount;
    }

    /**
     * @param $userInfo
     * @param $cash
     * @param $tradingPasswd
     * @throws \Exception
     * 检查用户帐户金额是否充足
     */
    public function checkUserBalance($balance,$cash){

        if($balance < $cash){
            //帐户余额不足
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_USER_BALANCE_NOT_ENOUGH'), self::getFinalCode('checkUserBalance'));
        }

        return true;

    }

    /**
     * @return array
     * 获取零钱计划配置信息
     * 后台配置完成后调用方法
     */
    public function getConfig(){

        //获取零钱计划的配置信息
        return SystemConfigModel::getConfig('CURRENT_CONF');

    }

    /**
     * @param $userId
     * @param $cash
     * 零钱计划转入限额
     */
    public function checkInvestLimit($userId,$cash){


        $currentConfig = $this->getConfig();

        $minInvestCash = $currentConfig['INVEST_CURRENT_MIN'];
        //$maxInvestCash = $currentConfig['INVEST_CURRENT_MAX'];

        $maxInvestCash = $this->getMaxInvestInCash($userId);
        //最少投资1元
        if($cash < $minInvestCash){

            $msg = sprintf(LangModel::getLang('ERROR_CURRENT_INVEST_MIN_AMOUNT'),ToolMoney::formatDbCashDelete($minInvestCash));
            throw new \Exception($msg, self::getFinalCode('checkInvestLimitMinInvestAmount'));

        }
        //获取零钱计划用户信息
        $accountInfo = $this->getCurrentAccount($userId);

        if(!empty($accountInfo)){
            //零钱计划帐户金额v
            $userCash = $accountInfo['cash'];

            //零钱计划帐户最大金额
            if($userCash > $maxInvestCash || $cash > $maxInvestCash){

                $msg = sprintf(LangModel::getLang('ERROR_CURRENT_INVEST_MAX_AMOUNT'),ToolMoney::formatDbCashDelete($maxInvestCash));
                throw new \Exception($msg, self::getFinalCode('checkInvestLimitMaxInvestAmount'));
            }

            //提示剩余可投金额为多少元
            if(($userCash + $cash) > $maxInvestCash){

                $freeAmount = sprintf('%d',floor($maxInvestCash - $userCash));
                $msg = sprintf(LangModel::getLang('ERROR_CURRENT_INVEST_FREE_AMOUNT'),ToolMoney::formatDbCashDelete($freeAmount),ToolMoney::formatDbCashDelete($maxInvestCash));
                throw new \Exception($msg, self::getFinalCode('checkInvestLimitFreeInvestAmount'));

            }
        }
    }

    /**
     * @param $userId
     * @param $cash
     * @return bool
     * @throws \Exception
     * @desc 零钱计划转出限制条件,详见子方法,转出规则比较复杂,但是为了防止用户挤兑,增加平台风险,需要作出相应的策略避免
     */
    public function checkInvestOutLimit($userId,$cash){

        //获取零钱计划账户信息
        $accountInfo = $this->getUserAccountExistByUserId($userId);

        $userCash = $accountInfo['cash'];

        //检测转出金额是否大于账户余额
        $this->checkUserBalance($userCash, $cash);

        $userLeftInvestOutCash = $this->getUserLeftInvestOutCashByUserId($userId);

        //针对个人用户限制,用户当前可转出金额 = 系统限制10万 + 当日自动转入金额 - 当日已经转出金额
        $this->checkUserTodayInvestOutCash($cash, $userLeftInvestOutCash);

        //针对整个平台,转出额度限制: 当日转出总额 = (昨日零钱计划总额 + 今日自动转入额度) * 20%
        //$this->checkPlatformInvestOutLimit($userId, $cash);

        return true;

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户剩余可以转出的金额
     */
    public function getUserLeftInvestOutCashByUserId($userId){

        //$currentConfig = $this->getConfig();

        $maxInvestOutCash = $this->getMaxInvestOutCash($userId);

        //获取用户今日转出总金额
        $userInvestOutArr = $this->getTodayInvestOutAmount($userId);

        $userTodayInvestOutCash = isset($userInvestOutArr['amount']) ? $userInvestOutArr['amount'] : 0;

        //获取用户当日自动转入总额
        $autoInvestAmount = \App\Http\Models\Common\CoreApi\CurrentModel::getTodayAutoInvestAmountByUserId($userId);

        return max(0, round(($maxInvestOutCash + $autoInvestAmount - $userTodayInvestOutCash), 2));

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 返回最大的金额
     */
    protected function getMaxInvestOutCash($userId)
    {
        $currentConfig = $this->getConfig();

        $userLimitCash  =   CashLimitModel::getLimitByUserId($userId);

        if( isset($userLimitCash['cash']) && $userLimitCash['cash'] ){

            return max( $userLimitCash['cash'],$currentConfig['INVEST_CURRENT_OUT_MAX']);
        }

        return $currentConfig['INVEST_CURRENT_OUT_MAX'];
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户零钱计划转入的最大金额
     */
    public function getMaxInvestInCash( $userId )
    {
        $currentConfig  = $this->getConfig();

        $userLimitCash  =   CashLimitModel::getLimitByUserId($userId);

        if( isset($userLimitCash['in_cash']) && $userLimitCash['in_cash'] ){

            return max( $userLimitCash['in_cash'],$currentConfig['INVEST_CURRENT_MAX']);
        }

        return $currentConfig['INVEST_CURRENT_MAX'];
    }
    /**
     * @param $cash
     * @param $autoInvestAmount
     * @param $configPercent
     * @throws \Exception
     * @desc 针对整个平台,转出额度限制: 当日转出总额 = (昨日零钱计划总额 + 今日自动转入额度) * 20%
     */
    public function checkPlatformInvestOutLimit($userId, $cash){

        $fundDb = new FundStatisticsDb();

        $date = ToolTime::getDateBeforeCurrent();

        $fundData = $fundDb->getByDate($date);

        $fundDataCash = isset($fundData['cash']) ? $fundData['cash'] : 0;

        //今日零钱计划转出总金额
        $todayInvestOutAmount = $this->getTodayCurrentTotalInvestOut();

        $currentConfig = $this->getConfig();

        $investOutPercent = $currentConfig['INVEST_CURRENT_OUT_PERCENT'];

        //获取用户当日自动转入总额
        $autoInvestAmount = \App\Http\Models\Common\CoreApi\CurrentModel::getPlatformTodayAutoInvestCurrentTotal();

        //零钱计划转出总金额
        $totalInvestAmount = (($fundDataCash + $autoInvestAmount) * $investOutPercent);

        //$totalInvestAmount = $totalInvestAmount < $currentConfig['INVEST_CURRENT_OUT_BASE'] ? $currentConfig['INVEST_CURRENT_OUT_BASE'] : $totalInvestAmount;

        if(($todayInvestOutAmount + $cash) > $totalInvestAmount){

            $freeAmount = max(0, bcsub($totalInvestAmount,$todayInvestOutAmount,2));

            $msg = sprintf(LangModel::getLang('ERROR_CURRENT_TODAY_INVEST_OUT_FREE_AMOUNT'),$freeAmount);

            throw new \Exception($msg, self::getFinalCode('checkPlatformInvestOutLimit'));
        }

    }

    /**
     * @param $cash
     * @param $userTodayInvestOutCash
     * @param $autoInvestAmount
     * @param $configInvestOutCash
     * @return bool
     * @throws \Exception
     * @desc 针对个人用户限制,用户当前可转出金额 = 系统限制10万 + 当日自动转入金额 - 当日已经转出金额
     */
    public function checkUserTodayInvestOutCash($cash, $userLeftInvestOutCash ){


        if( $cash > $userLeftInvestOutCash ){

            $msg = sprintf(LangModel::getLang('ERROR_CURRENT_INVEST_OUT_FREE_AMOUNT'),$userLeftInvestOutCash);

            throw new \Exception($msg, self::getFinalCode('checkUserTodayInvestOutCash'));

        }

        return true;

    }

    /**
     * @param $userId
     * @return array
     * @throws \Exception
     * @desc 检测零钱计划账户是否存在
     */
    public function getUserAccountExistByUserId($userId){

        $accountInfo = $this->getCurrentAccount($userId);

        if(empty($accountInfo)){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_USER_NOT_EXIST'), self::getFinalCode('getUserAccountExistByUserId'));

        }

        return $accountInfo;

    }

    /**
     * @param $investOutCash
     * @param $leftCash
     * @throws \Exception
     * @desc 检测余额是否大于操作金额
     */
    public function checkLeftMoneyCanInvestOut($investOutCash, $leftCash){

        if($leftCash < $investOutCash){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_OUT_CASH_GREATER_THAN_ACCOUNT_FUND'), self::getFinalCode('checkLeftMoneyCanInvestOut'));

        }

        return false;

    }

    /**
     * @param $userId
     * @return array
     * 获取用户零钱计划今日转出金额
     */
    private function getTodayInvestOutAmount($userId){


        $result = \App\Http\Models\Common\CoreApi\CurrentModel::getCurrentUserTodayInvestOutAmount($userId);

        if($result){

            return $result;

        }else {
            return [];
        }
    }
    /**
     * @param $userId
     * @param $projectId
     * @param $cash
     * 调用核心零钱计划投资接口
     */
    public function doInvest($userId,$cash){

        $result = \App\Http\Models\Common\CoreApi\CurrentModel::doCurrrentInvest($userId,$cash);

        if(!$result['status']){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_FAILED'), self::getFinalCode('doInvest'));

        }
    }

    /**
     * @param $userId
     * @param $cash
     * @throws \Exception
     */
    public function doInvestOut($userId,$cash){

        $result = \App\Http\Models\Common\CoreApi\CurrentModel::doCurrrentInvestOut($userId,$cash);

        if(!$result['status']){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_OUT_FAILED'), self::getFinalCode('doInvestOut'));

        }
    }

    /**
     * 获取零钱计划项目投资总人数
     */
    public function getUserNum(){

        $result = \App\Http\Models\Common\CoreApi\CurrentModel::getCurrentInvestUserNum();

        if($result){

            return $result['num'];

        }else{
            return 0;
        }

    }


    /**
     * @param $projectId
     * 零钱计划项目投资加锁
     */
    public function addLock($projectId){

        $lockKey = $this->getLockKey($projectId);

        if(Cache::has($lockKey)){
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_FAILED'), self::getFinalCode('addLock'));
        }

        //零钱计划投资加锁,锁定30秒
        Cache::put($lockKey,1,0.5);
    }

    /**
     * @param $projectId
     * @return mixed
     * 获取锁定的key
     */
    private function getLockKey($projectId){

        //投资零钱计划项目加锁
        $baseKey = 'm_invest_current_lock_%s';

        $lockKey    = sprintf($baseKey,$projectId);

        return $lockKey;
    }

    /**
     * @param $projectId
     * 解锁
     */
    public function releaseLock($projectId){

        $lockKey = $this->getLockKey($projectId);

        Cache::forget($lockKey);

    }

    /**
     * @desc 零钱计划数据统计
     * @author lgh
     * @param     $where
     * @param int $type
     * @return mixed
     */
    public function getCurrentStatistics($where, $type = 1){

        $start_time = $where['start_time'];
        $end_time   = $where['end_time'];
        $app_request   = $where['app_request'];

        $currentInvestDb = new InvestDb();
        if($type == 1){
            $obj = $currentInvestDb->currentInvestIn();//零钱计划转入
        }elseif($type ==3){
            $obj = $currentInvestDb->currentInvestManualIn();//零钱计划手动转入
        }elseif($type ==4){
            $obj = $currentInvestDb->currentInvestAutoIn();//零钱计划自动转入

        }else{
            $obj = $currentInvestDb->currentInvestOut(); //零钱计划转出
        }

        //时间范围
        if($start_time && $end_time){
            $obj = $obj->where('created_at', '>=', $start_time);
            $obj = $obj->where('created_at', '<=', $end_time);
        }elseif($start_time && !$end_time){
            $obj = $obj->where('created_at', '>=', $start_time);
        }elseif(!$start_time && $end_time){
            $obj = $obj->where('created_at', '<=', $end_time);
        }
        if($app_request){
            $obj = $obj->where('app_request', '=', $app_request);
        }
        $data['cash'] = $obj->sum('cash');

        return $data;
    }
     /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 根据开始结束日期获取投资记录总额
     */
    public function getCurrentAmountByDate($start = '',$end = ''){

        $cacheKey = 'CURRENT_AMOUNT_DATE';

        $list     = Cache::get($cacheKey);

        $list     = json_decode($list,true);

        if(empty($list)){

            $db = new InvestDb();

            $list = $db->getCurrentAmountByDate($start,$end);

            if(!empty($list)){

                $listCache = json_encode($list);

                $expire    = Carbon::now()->addMinutes(5);

                Cache::put($cacheKey,$listCache,$expire);
            }
        }

        return $list;
    }

    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 获取零钱计划投资总额
     */
    public function getCurrentAmountTotal($start = '', $end=''){

        $db   = new InvestDb();

        $res  = $db->getCurrentAmountTotal($start,$end);

        $cash = empty($res) ? 0 : $res['cash'];

        return $cash;
    }
}
