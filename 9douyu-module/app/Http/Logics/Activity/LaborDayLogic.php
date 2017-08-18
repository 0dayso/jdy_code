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
use App\Http\Logics\Activity\Common\ActivityLogic;
use App\Http\Logics\Activity\Common\AnalysisConfigLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivitySignModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;

class LaborDayLogic extends ActivityLogic
{

    const
        DEFAULT_INVEST_NUMBER   =   0,      //不存在投资数据
        DEFAULT_LOTTERY_NUMBER  =   0       //不存在抽奖次数

    ;


    /**
     * @return array
     * @desc 活动时间
     */
    public  function getActivityTime()
    {
        $config =   $this->getConfig();

        return  $this->getTime($config['START_TIME'],$config['END_TIME']);
    }

    /**
     * @return array
     * @desc 签到的活动的时间
     */
    public function setSignTime()
    {
        return [
            'start' =>  $this->setSignStartTime(),
            'end'   =>  $this->setSignEndTime(),
        ];
    }
    /**
     * @return int
     * @desc 活动活动的项目
     */
    public function getActivityProject()
    {
        return $this->getProject($this->getConfig()['ACTIVITY_PROJECT']);
    }

    /**
     * @param int $userId
     * @return array
     * @desc 返回签到的数据
     */
    public function getSignStatistics($userId = 0)
    {
        return[
            'signDay'       =>  $this->getLaborDayToLunar(),
            'recordList'    =>  $this->setUserSignRecord($userId),
            'exchange'      =>  $this->getExchangeMessage($userId),
        ];
    }

    /**
     * @param $data
     * @return array
     * @desc 执行抽奖的程序
     */
    public function doLuckDraw( $userId )
    {
        $lotteryLogic       =   new LotteryLogic();

        $eventId            =   $this->setActivityEventId();

        $lotteryParam       =[
            'group_id'      =>  $this->getActivityLotteryGroup($eventId),
            'activity_id'   =>  $this->setActivityEventId(),
            'note'          =>  $this->getActivityNote($eventId),
            'user_id'       =>  $userId,
        ];

        return $lotteryLogic->doLuckDrawWithRate($lotteryParam);
    }

    /**
     * @param $data
     * @return array
     * @desc 执行签到,并返回结果
     */
    public function doSignIn($userId)
    {
        $signLogic   =   new ActivitySignLogic();

        $eventId     =   $this->setActivityEventId();

        $data['type']=   $this->setActivityEventId();

        $data['note']=   $this->getActivityNote($this->setActivityEventId());

        $signParam       =[
            'type'   =>  $this->setActivityEventId(),
            'note'          =>  $this->getActivityNote($eventId),
            'user_id'       =>  $userId,
        ];

        $return      =   $signLogic->doRecordSign($signParam);

        if( $return['status'] == false){

            return $return;
        }

        $signRecord =    $signLogic->getUserSign($userId,$eventId);

        return $this->callSuccess($signRecord);
    }

    /**
     * @param int $userId
     * @return array
     * @desc 用户领取红包
     */
    public function doExchange($userId = 0)
    {
        $signRecord     =   $this->setUserSignRecord($userId);

        $signBonus      =   $this->setSignAward();

        $signNumber     =   isset($signRecord['sign_num']) ? $signRecord['sign_num'] : '0';

        $bonusId        =   isset($signBonus[$signNumber]) ? $signBonus[$signNumber] : false;

        if( $bonusId == false ){

            return $this->callError('领取红包的条件错误!');
        }

        $bonusLogic     =   new UserBonusLogic();

        return $bonusLogic->doSendBonusByUserIdWithBonusId($userId,$bonusId);
    }

    /**
     * @param int $userId
     * @return array
     * @desc 整个签到活动判断
     */
    public function setExchangeStatus( $userId = 0)
    {
        $config         =   $this->getConfig();

        $timeStatus     =   $this->isCanJoinActivity($config['START_TIME'],$config['END_TIME'],self::setActivityEventId(),$userId);

        if( $timeStatus['status'] == false){

            return $timeStatus;
        }

        return $this->setUserExchangeStatus($userId);
    }

    /**
     * @param int $userId
     * @return array
     * @desc 带用户状态的条件判断
     */
    protected function setUserExchangeStatus($userId = 0)
    {
        $signLogic      =   new ActivitySignLogic();

        $activityId     =   $this->setActivityEventId();

        $signRecord     =   $signLogic->getUserSign($userId,$activityId);

        if( empty($signRecord) ){

            $alertMsg   =   '请参加签到活动后,再来领取红包哦!';

            return $this->callError($alertMsg,self::CODE_ERROR,['type'=>'notExchange']);
        }

        $userBonusStatus=   $this->isCheckUserSignBonus($userId);

        if($userBonusStatus['status'] == false ){

            $alertMsg   =   '您已经领取过红包了,不能在领取了哦!';

            return $this->callError($alertMsg,self::CODE_ERROR,['type'=>'exchanged']);
        }

        return $this->callSuccess(['type'=>'exchange']);
    }

    /**
     * @param int $userId
     * @return int
     * @desc 用户红包数据
     */
    protected function getExchangeMessage($userId = 0)
    {
        if( empty($userId) || $userId == 0){

            return 0;
        }

        $signRecord     =   $this->setUserSignRecord($userId);

        if( empty($signRecord)){

            return 0 ;
        }

        $signBonus      =   $this->setSignAward();

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
    public function setLotteryStatus($userId = 0)
    {
        $config             =   self::getConfig();

        $activityStatus     =   $this->isCanJoinActivity($config['START_TIME'],$config['END_TIME'],self::setActivityEventId(),$userId);

        if( $activityStatus['status'] == false) {

            return $activityStatus;
        }

        $lotteryStatus      =   $this->setUserLotteryStatus($userId);

        return $lotteryStatus;
    }

    /**
     * @param int $userId
     * @return array
     * @desc 签到的数据和状态
     */
    public function setSignStatus($userId = 0)
    {
        $signStatus     =   $this->isCanJoinActivity($this->setSignStartTime(),$this->setSignEndTime(),self::setActivityEventId(),$userId);

        if( $signStatus['status'] == false ){

            return $signStatus;
        }

        return $this->setUserSignStatus($userId);

    }

    /**
     * @param int $userId
     * @return array|mixed
     * @desc 用户签到记录
     */
    protected function setUserSignRecord($userId = 0)
    {
        if( $userId == 0 ){

            return [];
        }

        $signLogic  =   new ActivitySignLogic();

        $activityId =   $this->setActivityEventId();

        return $signLogic->getUserSign($userId,$activityId);
    }
    /**
     * @param int $userId
     * @return array
     * @throws \Exception
     * @desc 判断用户是否可以签到
     * @desc 必须从第一天开始,签到中间不可以中断
     */
    protected function setUserSignStatus($userId = 0)
    {
        $userBonusStatus=   $this->isCheckUserSignBonus($userId);

        if($userBonusStatus['status'] == false ){

            return $userBonusStatus;
        }

        $signLogic      =   new ActivitySignLogic();

        $activityId     =   $this->setActivityEventId();

        $signRecord     =   $signLogic->getUserSign($userId,$activityId);

        $signDay        =   date('Y-m-d',time());

        $firstSignDay   =   date('Y-m-d',$this->setSignStartTime());

        $isSignFromOne  =   $this->isSignFromOneDay();

        if( empty($signRecord) ){

            return $this->callSuccess(['type'=>'sign']);
        }

        if( empty($signRecord) && $signDay != $firstSignDay && $isSignFromOne== true ){

            $alertMsg   =   '签到活动必须第一天开始!谢谢参与!';

            return $this->callError($alertMsg,self::CODE_ERROR,['type'=>'notSign']);
        }

        $signModel  =   new ActivitySignModel();

        $isContinue =   $signModel->checkSignContinue($signRecord['new_sign_day']);

        if( $isContinue == false ){

            $alertMsg   =   LangModel::ERROR_SIGN_NOT_CONTINUITY;

            return $this->callError($alertMsg,self::CODE_ERROR,['type'=>'notSign']);
        }

        return $this->callSuccess(['type'=>'sign']);
    }

    /**
     * @param $userId
     * @return array
     * @desc 判断用户是否领取过红包
     */
    protected function isCheckUserSignBonus($userId)
    {
        $signBonusIds   =   $this->setSignAward();

        $bonusDb        =   new UserBonusDb();

        $startTime      =   date("Y-m-d H:i:s",$this->setSignStartTime());

        $endTime        =   date("Y-m-d H:i:s",$this->setSignEndTime());

        $userBonusList  =   $bonusDb->getUserBonusUsedTotal($startTime,$endTime,$signBonusIds,$userId);

        $userBonusCount =   array_sum(array_column($userBonusList,'total'));

        if($userBonusCount >=1 ){

            $alertMsg   =   '您已经领取过红包,不可重复参加';

            return $this->callError($alertMsg,self::CODE_ERROR,['type'=>'notSign']);
        }

        return $this->callSuccess(['type'=>'sign']);
    }
    /**
     * @desc  获取最小投资金额
     */
    public function getMinInvest()
    {
        return $this->getMinInvestCash($this->getConfig());
    }
    /**
     * @param int $userId
     * @return bool
     * @desc 用户的抽奖次数和状态
     */
    protected function setUserLotteryStatus($userId = 0)
    {
        $config         =   $this->getConfig();

        $projectIds     =   $this->getJoinProjectIds();

        $investNumber   =   $this->getUserSatisfyInvestNumber($config['START_TIME'],$config['END_TIME'],$this->getMinInvestCash($config),$userId,$projectIds,$config['INVEST_WITH_BONUS']);

        if( $investNumber  <= self::DEFAULT_INVEST_NUMBER ){

            $alertMsg   =   '单笔投资满'.$this->getMinInvestCash($config)."可参与抽奖!";

            return $this->callError($alertMsg,self::CODE_ERROR,['type'=>'notLottery']);
        }

        $activityId     =   $this->setActivityEventId();

        $lotteryNumber  =   $this->getUserLotteryInfo($config['START_TIME'],$config['END_TIME'],$activityId,$userId);

        if( $investNumber <= $lotteryNumber){

            $alertMsg   =   '您的抽奖次数已用完,谢谢参与!';

            return $this->callError($alertMsg,self::CODE_ERROR,['type'=>'notLottery']);
        }

        $lotteryCanNumber=   $investNumber - $lotteryNumber;

        return $this->callSuccess(['type'=>'lotteryIng','lotteryNumber'=>$lotteryCanNumber]);
    }

    /**
     * @return array
     * @desc 屏蔽一月期的项目
     */
    protected  function getJoinProjectIds()
    {
        $config     =   $this->getConfig();

        $startTime  =   date('Y-m-d H:i:s' , ($config['START_TIME'] - 86400*7) );

        $endTime    =   date('Y-m-d H:i:s' , $config['END_TIME'] );

        $projectIds =   ProjectModel::getAllProjectIdByTime( $startTime , $endTime ) ;

        if( empty($projectIds) ) {

            return [];
        }
        unset($projectIds['one']);

        $formatIds  =   [];

        foreach ($projectIds as $key => $value ) {

            $formatIds  =   array_merge($formatIds,$value);
        }

        return $formatIds;

    }
    /**
     * @param string $awardList
     * @return array
     * @desc  格式化签到红包
     */
    protected function formatAwardArray($awardList = '')
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
     * @desc 活动签到红包
     */
    public function setSignAward()
    {
        $config     =   $this->getConfig();

        return $this->formatAwardArray($config['SIGN_ACTIVITY_AWARD']);
    }
    /**
     * @return bool
     * @desc 判断是否从第一天开始签到
     */
    protected function isSignFromOneDay()
    {
        $config     =   $this->getConfig();

        if(isset($config['IS_SIGN_FROM_ONE']) && $config['IS_SIGN_FROM_ONE'] == 1){

            return true;
        }

        return false;
    }

    /**
     * @return array
     * @desc 签到页面展示的数据
     */
    protected static function getLaborDayToLunar()
    {
        $startTime      =   self::setSignStartTime();

        $endTime        =   self::setSignEndTime();

        $lunarWord      =   self::setLaborDayToLunar();

        $signDate       =   [];

        $number         =   1;

        for ($i = $startTime ; $i <= $endTime ;$i+=86400 ){

            if($number <= count($lunarWord)){

                $signDate[date('Y-m-d',$i)] =   $lunarWord[$number];
            }
            $number++;
        }

        return $signDate;
    }
    /**
     * @return array
     * @desc 定义好的签到显示的名词
     */
    protected static function setLaborDayToLunar()
    {
        return [
            '1'    =>  '先锋奖章',
            '2'    =>  '先进奖章',
            '3'    =>  '模范奖章',
            '4'    =>  '敬业奖章',
            '5'    =>  '劳模奖章',
            '6'    =>  '爱心奖章',
            '7'    =>  '团结奖章',
        ];
    }

    /**
     * @return int
     * @DESC 五一开始的时间,签到的开始时间
     */
    protected static function setSignStartTime()
    {
        $config =   self::getConfig();

        return ToolTime::getUnixTime($config['SIGN_START_TIME']);
    }
    /**
     * @return int
     * @DESC 五一结束的时间,签到的结束时间
     */
    protected static function setSignEndTime()
    {
        $config =  self::getConfig();

        return ToolTime::getUnixTime($config['SIGN_END_TIME'],'end');
    }
    /**
     * @return int
     * @desc 五一活动的唯一性标示
     */
    protected function setActivityEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_LABOR_DAY;
    }
    /**
     * @return array|mixed
     * @desc  读取五一活动的配置文件
     */
    private static function getConfig()
    {
        return AnalysisConfigLogic::make('LABOR_DAY_CONFIG');
    }
}
