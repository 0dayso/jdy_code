<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/1/6
 * Time: 下午7:14
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\Bonus\UserBonusDb;
use App\Http\Logics\Bonus\BonusLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\Activity\ActivitySignModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;

class SpringFestivalLogic extends Logic
{

    const
        DEFAULT_INVEST_NUMBER   =   0,      //不存在投资数据
        DEFAULT_LOTTERY_NUMBER  =   0       //不存在抽奖次数

    ;


    /**
     * @return array
     * @desc 活动时间
     */
    public static function getActivityTime()
    {
        return [
            'start' =>  self::setStartTime(),
            'end'   =>  self::setEndTime(),
        ];
    }

    /**
     * @return array
     * @desc 签到的活动的时间
     */
    public static function setSignTime()
    {
        return [
            'start' =>  self::setSpringStartTime(),
            'end'   =>  self::setSpringEndTime(),
        ];
    }
    /**
     * @return int
     * @desc 活动活动的项目
     */
    public static function getActivityProject()
    {
        $projectList    =   ProjectModel::getNewestProjectEveryType();

        $formatProject  =   self::getFormatProjectType($projectList);

        return $formatProject;
    }

    /**
     * @param int $userId
     * @return array
     * @desc 返回签到的数据
     */
    public static function getSignStatistics($userId = 0)
    {
        return[
            'signDay'       =>  self::setSpringDayToLunar(),
            'recordList'    =>  self::setUserSignRecord($userId),
            'exchange'      =>  self::getExchangeMessage($userId),
        ];
    }

    /**
     * @param $data
     * @return array
     * @desc 执行抽奖的程序
     */
    public static function doLuckDraw( $data )
    {
        $lotteryLogic           =   new LotteryLogic();

        $data['activity_id']    =   self::setActivityEventId();

        return $lotteryLogic->doLuckDrawWithRate($data);
    }

    /**
     * @param $data
     * @return array
     * @desc 执行签到,并返回结果
     */
    public static function doSignIn($data)
    {
        $signLogic   =   new ActivitySignLogic();

        $data['type']=   self::setActivityEventId();

        $data['note']=   "春节签到活动";

        $return      =   $signLogic->doRecordSign($data);

        if( $return['status'] == false){

            return $return;
        }

        $signRecord =    $signLogic->getUserSign($data['user_id'],$data['type']);

        return self::callSuccess($signRecord);
    }

    /**
     * @param int $userId
     * @return array
     * @desc 用户领取红包
     */
    public static function doExchange($userId = 0)
    {
        $signRecord     =   self::setUserSignRecord($userId);

        $signBonus      =   self::setSignAward();

        $signNumber     =   isset($signRecord['sign_num']) ? $signRecord['sign_num'] : '0';

        $bonusId        =   isset($signBonus[$signNumber]) ? $signBonus[$signNumber] : false;

        if( $bonusId == false ){

            return self::callError('领取红包的条件错误!');
        }

        $bonusLogic     =   new UserBonusLogic();

        return $bonusLogic->doSendBonusByUserIdWithBonusId($userId,$bonusId);
    }

    /**
     * @param int $userId
     * @return array
     * @desc 整个签到活动判断
     */
    public static function setExchangeStatus( $userId = 0)
    {
        $timeStatus     =   self::setSignTimeStatus();

        if( $timeStatus['status'] == false){

            return $timeStatus;
        }

        return self::setUserExchangeStatus($userId);
    }

    /**
     * @param int $userId
     * @return array
     * @desc 带用户状态的条件判断
     */
    protected static function setUserExchangeStatus($userId = 0)
    {
        if( empty($userId) || $userId ==0){

            $alertMsg   =   '您还未登录,请登录后兑换红包!';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notLogged']);
        }

        $signLogic      =   new ActivitySignLogic();

        $activityId     =   self::setActivityEventId();

        $signRecord     =   $signLogic->getUserSign($userId,$activityId);

        if( empty($signRecord) ){

            $alertMsg   =   '请参加签到活动后,再来领取红包哦!';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notExchange']);
        }

        $userBonusStatus=   self::isCheckUserSignBonus($userId);

        if($userBonusStatus['status'] == false ){

            $alertMsg   =   '您已经领取过红包了,不能在领取了哦!';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'exchangeEd']);
        }

        return self::callSuccess(['status_type'=>'exchange']);
    }

    /**
     * @param int $userId
     * @return int
     * @desc 用户红包数据
     */
    protected static function getExchangeMessage($userId = 0)
    {
        if( empty($userId) || $userId == 0){

            return 0;
        }

        $signRecord     =   self::setUserSignRecord($userId);

        if( empty($signRecord)){

            return 0 ;
        }

        $signBonus      =   self::setSignAward();

        $signNumber     =   isset($signRecord['sign_num']) ? $signRecord['sign_num'] : '0';

        $bonusId        =   isset($signBonus[$signNumber]) ? $signBonus[$signNumber] : false;

        if($bonusId == false){

            return 0;
        }

        $bonusDb        =   new BonusDb();

        $bonusInfo      =    $bonusDb->getById($bonusId);

        return (int)$bonusInfo['money'];

    }
    
    /**
     * @param int $userId
     * @return array|bool
     * @desc 抽奖的状态
     */
    public static function setLotteryStatus($userId = 0)
    {
        $activityStatus     =   self::setActivityStatus();

        if( $activityStatus['status'] == false) {

            return $activityStatus;
        }

        $lotteryStatus      =   self::setUserLotteryStatus($userId);

        return $lotteryStatus;
    }

    /**
     * @param int $userId
     * @return array
     * @desc 签到的数据和状态
     */
    public static function setSignStatus($userId = 0)
    {
        $signStatus     =   self::setSignTimeStatus();

        if( $signStatus['status'] == false ){

            return $signStatus;
        }

        return self::setUserSignStatus($userId);

    }

    /**
     * @param int $userId
     * @return array|mixed
     * @desc 用户签到记录
     */
    protected static function setUserSignRecord($userId = 0)
    {
        if( $userId == 0 ){

            return [];
        }

        $signLogic  =   new ActivitySignLogic();

        $activityId =   self::setActivityEventId();

        return $signLogic->getUserSign($userId,$activityId);
    }
    /**
     * @param int $userId
     * @return array
     * @throws \Exception
     * @desc 判断用户是否可以签到
     * @desc 必须从第一天开始,签到中间不可以中断
     */
    protected static function setUserSignStatus($userId = 0)
    {
        if($userId == 0 || empty($userId) ){

            $alertMsg   =   '您还未登录,请登录后进行签到!';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notLogged']);
        }

        $userBonusStatus=   self::isCheckUserSignBonus($userId);

        if($userBonusStatus['status'] == false ){

            return $userBonusStatus;
        }

        $signLogic      =   new ActivitySignLogic();

        $activityId     =   self::setActivityEventId();

        $signRecord     =   $signLogic->getUserSign($userId,$activityId);

        $signDay        =   date('Y-m-d',time());

        $firstSignDay   =   date('Y-m-d',self::setSpringStartTime());

        $isSignFromOne  =   self::isSignFromOneDay();

        if( empty($signRecord) && $signDay != $firstSignDay && $isSignFromOne== true ){

            $alertMsg   =   '签到活动必须从年初一开始!谢谢参与!';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notSign']);

        }elseif( empty($signRecord) ){

            return self::callSuccess(['status_type'=>'sign']);

        }else{

            $signModel  =   new ActivitySignModel();

            $isContinue =   $signModel->checkSignContinue($signRecord['new_sign_day']);

            if( $isContinue == false ){

                $alertMsg   =   LangModel::ERROR_SIGN_NOT_CONTINUITY;

                return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notSign']);
            }
        }

        return self::callSuccess(['status_type'=>'sign']);

    }

    /**
     * @param $userId
     * @return array
     * @desc 判断用户是否领取过红包
     */
    protected static function isCheckUserSignBonus($userId)
    {
        $signBonusIds   =   self::setSignAward();

        $bonusDb        =   new UserBonusDb();

        $startTime      =   date("Y-m-d H:i:s",self::setSpringStartTime());
        
        $endTime        =   date("Y-m-d H:i:s",self::setSpringEndTime());

        $userBonusList  =   $bonusDb->getUserBonusUsedTotal($startTime,$endTime,$signBonusIds,$userId);
        
        $userBonusCount =   array_sum(array_column($userBonusList,'total'));

        if($userBonusCount >=1 ){

            $alertMsg   =   '您已经领取过红包,不可重复参加';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notSign']);
        }

        return self::callSuccess(['status_type'=>'sign']);
    }
    /**
     * @return array
     * @desc 签到活动的时间条件
     */
    protected static function setSignTimeStatus()
    {
        $startTime      =   self::setSpringStartTime();

        $endTime        =   self::setSpringEndTime();

        $dbTime         =   time();

        if($startTime > $dbTime){

            $alertMsg   =   date('Y年m月d日',$startTime).'(初一)开始签到';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notInTime']);
        }

        if($dbTime > $endTime ){

            $alertMsg   =   '签到活动现在已经结束!';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notInTime']);
        }

        return self::callSuccess();
    }

    /**
     * @param int $userId
     * @return bool
     * @desc 用户的抽奖次数和状态
     */
    protected static function setUserLotteryStatus($userId = 0)
    {
        if( $userId == 0 || empty($userId)){

            $alertMsg   =   '您还未登录,请登录后参与抽奖活动!';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notLogged']);
        }

        $investNumber   =   self::getUserSatisfyInvestNumber($userId);

        if( $investNumber  <= self::DEFAULT_INVEST_NUMBER ){

            $alertMsg   =   '单笔投资满'.self::setMinInvestCash()."可参与抽奖!";

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notLottery']);
        }

        $lotteryNumber  =   self::getUserLotteryEdNumber($userId);

        if( $investNumber <= $lotteryNumber){

            $alertMsg   =   '您的抽奖次数已用完,谢谢参与!';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notLottery']);
        }

        $lotteryCanNumber=   $investNumber - $lotteryNumber;

        return self::callSuccess(['status_type'=>'lotteryIng','lotteryNumber'=>$lotteryCanNumber]);
    }

    /**
     * @return array
     * @desc 设置活动的状态
     */
    protected static function setActivityStatus()
    {

        $startTime      =   self::setStartTime();

        $endTime        =   self::setEndTime();

        $dbTime         =   time();

        if($startTime > $dbTime){

            $alertMsg   =   '春节活动在'.date('m月d日',$startTime).'开始';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notInTime']);
        }

        if($dbTime > $endTime ){

            $alertMsg   =   '春节活动现在已经结束';

            return self::callError($alertMsg,self::CODE_ERROR,['status_type'=>'notInTime']);
        }

        return self::callSuccess();
    }

    /**
     * @param int $userId
     * @return int
     * @desc 用户在活动时间内的投资次数
     */
    protected static function getUserSatisfyInvestNumber($userId = 0)
    {
        if( $userId == 0) return self::DEFAULT_INVEST_NUMBER ;

        $lotteryTime        =   self::setLotteryBetweenTime();

        $params             =   [
            'user_id'       =>  $userId,
            'start_time'    =>  $lotteryTime['start'],
            'end_time'      =>  $lotteryTime['end'],
            'base_cash'     =>  self::setMinInvestCash(),
        ];

        $isCanUsedBonus     =   self::isCanUsedBonus();

        if( $isCanUsedBonus == false ){

            $params['bonusId'] = '0';
        }

        $investLogic    =   new TermLogic();

        $investMsg      =   $investLogic->getInvestStatistics($params);

        return (int) $investMsg['investTotal'];

    }

    /**
     * @param $userId
     * @return int
     * @desc 活动期间用户的抽奖次数
     */
    protected static function getUserLotteryEdNumber( $userId = 0 )
    {
        $lotteryLogic       =   new LotteryRecordLogic();

        $lotteryTime        =   self::setLotteryBetweenTime();

        $statistics         =   [
            'user_id'       =>  $userId,
            'activity_id'   =>  self::setActivityEventId(),
            'start_time'    =>  $lotteryTime['start'],
            'end_time'      =>  $lotteryTime['end'],
        ];

        $lotteryList        =   $lotteryLogic->getRecordByConnection($statistics);

        return (int)$lotteryList['lotteryNum'];
    }

    /**
     * @param array $projectList
     * @return array
     * @desc 项目分类
     */
    protected static function getFormatProjectType($projectList = array())
    {
        if( empty($projectList) ){

            return [];
        }

        $activityProjectLine   =   self::setProjectLine();

        $activityProject       =   [];

        foreach ($projectList as $key => $project ){

            if( in_array($key,$activityProjectLine) ){

                $activityProject[$key]=  $project;
            }
        }

        return $activityProject;
    }

    protected static function setUserSignDate($userId = 0)
    {
        if( $userId == 0){
            return [];
        }
    }

    /**
     * @param string $awardList
     * @return array
     * @desc  格式化签到红包
     */
    protected static function formatAwardArray($awardList = '')
    {
        $awardListArr  =   explode(",",$awardList);

        $awardArray    =   [];

        foreach ($awardListArr as $key => $value ){

            $award     =   explode("=>",$value);

            $awardArray[$award[0]]=$award[1];
        }

        return $awardArray;
    }
    /**
     * @return array
     * @desc  根据配置设置统计的时间段
     */
    protected static function setLotteryBetweenTime()
    {
        $isEveryDayLottery =   self::isEveryDayLottery();

        if( $isEveryDayLottery == false ){

            return [
                'start' =>  date("Y-m-d H:i:s" , self::setStartTime() ),
                'end'   =>  date("Y-m-d H:i:s" , self::setEndTime() ),
            ];
        }

        return [
            'start' =>  date("Y-m-d 00:00:00",time()),
            'end'   =>  date("Y-m-d 23:59:59",time()),
        ];
    }

    /**
     * @return array
     * @desc 活动签到红包
     */
    public static function setSignAward()
    {
        $config     =   self::getConfig();

        return self::formatAwardArray($config['SIGN_ACTIVITY_AWARD']);
    }

    /**
     * @return bool
     * @desc 是否每天都可以抽奖条件
     */
    protected static function isEveryDayLottery()
    {
        $config     =   self::getConfig();

        if( isset($config['IS_EVERY_LOTTERY']) && $config['IS_EVERY_LOTTERY'] == 1 ){

            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @desc 判断是否从第一天开始签到
     */
    protected static function isSignFromOneDay()
    {
        $config     =   self::getConfig();

        if(isset($config['IS_SIGN_FROM_ONE']) && $config['IS_SIGN_FROM_ONE'] == 1){

            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @desc 判断用户投资使用可以使用红包
     */
    public static function isCanUsedBonus()
    {
        $config     =   self::getConfig();

        if(isset($config['IS_CAN_USED_BONUS']) && $config['IS_CAN_USED_BONUS'] == 1){

            return true;
        }

        return false;
    }

    /**
     * @return int
     * @desc 参与抽奖的单笔投资的最小额度
     */
    protected static function setMinInvestCash()
    {
        $config     =   self::getConfig();

        return (int) $config['MIN_INVEST_CASH'];
    }

    /**
     * @return array
     * @desc 活动的项目
     */
    protected static function setProjectLine()
    {
        $config     =   self::getConfig();

        return explode(",",$config['ACTIVITY_PROJECT']);
    }

    /**
     * @return array
     * @desc 定义好春节七天的格式
     */
    protected static function setSpringDayToLunar()
    {
        return [
            '2017-01-28'    =>  '初一',
            '2017-01-29'    =>  '初二',
            '2017-01-30'    =>  '初三',
            '2017-01-31'    =>  '初四',
            '2017-02-01'    =>  '初五',
            '2017-02-02'    =>  '初六',
            '2017-02-03'    =>  '初七',
        ];
//        return [
//            '2017-01-16'    =>  '初一',
//            '2017-01-17'    =>  '初二',
//            '2017-01-18'    =>  '初三',
//            '2017-01-19'    =>  '初四',
//            '2017-02-20'    =>  '初五',
//            '2017-02-21'    =>  '初六',
//            '2017-02-22'    =>  '初七',
//        ];
    }

    /**
     * @return int
     * @DESC 春节开始的时间,签到的开始时间
     */
    protected static function setSpringStartTime()
    {
        $config =   self::getConfig();

        return ToolTime::getUnixTime($config['SIGN_START_TIME']);
    }
    /**
     * @return int
     * @DESC 春节结束的时间,签到的结束时间
     */
    protected static function setSpringEndTime()
    {
        $config =   self::getConfig();

        return ToolTime::getUnixTime($config['SIGN_END_TIME'],'end');
    }

    /**
     * @return int
     * @desc 活动结束时间
     */
    protected static function setEndTime()
    {
        $config     =   self::getConfig();

        return ToolTime::getUnixTime($config['END_TIME'],'end');
    }
    /**
     * @return int
     * @desc 开始时间
     */
    protected static function setStartTime()
    {
        $config     =   self::getConfig();

        return ToolTime::getUnixTime($config['START_TIME']);
    }

    /**
     * @return int
     * @desc 春节活动的唯一性标示
     */
    protected static function setActivityEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_SPRING_FESTIVAL;
    }
    /**
     * @return array|mixed
     * @desc  读取春节活动的配置文件
     */
    private static function getConfig()
    {
        $config     =   ActivityConfigModel::getConfig('SPRING_FESTIVAL_CONFIG');

        if( empty($config) ){

            return SystemConfigModel::getConfig('SPRING_FESTIVAL_CONFIG');
        }

        return $config;
    }
}