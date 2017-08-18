<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 2017/2/21
 * Time: 下午2:36
 */

namespace App\Http\Logics\Activity;

use App\Http\Logics\Activity\Common\ActivityLogic ;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Dbs\Bonus\UserBonusDb;
use App\Http\Logics\Bonus\BonusLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Cache;

class CouponLogic extends Logic
{
    protected static $objectExample;  //数据对象
    const DEFAULT_GROUP =   18;  //默认的奖品配置

    /*******************************展示数据********************************/

    /**
     * @return array
     * @desc 活动时间点
     */
    public static function setTime()
    {
        $config =   self::config();

        return [ 'start'=>$config['START_TIME'] , 'end' => $config['END_TIME'] ];
    }
    /**
     * @desc  设置当前活动的act_token
     */
    public static function setActToken()
    {
       return   time() . '_' . self::setActivityEventId() ;
    }
    /**
     * @return mixed
     * @desc  活动的项目
     */
    public static function getProject()
    {
//        $object =   self::getObject();

//        return $object['project'];
        $config =   self::config();

        return ProjectLogic::getActivityProject($config['ACTIVITY_PROJECT']);
    }

    /**
     * @return array
     * @desc 读取奖品的相关数据
     */
    public static function getCouponLottery()
    {
        $lotteryList    =   self::setCouponLotteryList();

        $lotteryIndex   =   self::setShowLotteryIndex(count($lotteryList));

        $lotteryInfo    =   isset($lotteryList[$lotteryIndex]) ? $lotteryList[$lotteryIndex] :[];

        return ['lottery' => $lotteryInfo,'record' => self::setCouponWinningList()];
    }
    /**
     * @return array|mixed
     * @desc 获取红包的数据
     */
    public static function getBonusList()
    {
        $bonusParam =   self::setBonus();

        $cacheKey   =   md5(json_encode($bonusParam));

        $bonusCacheList= Cache::get($cacheKey);

        if( !empty($bonusCacheList) ){

            return json_decode($bonusCacheList,true);
        }
        $bonusDb    =   new BonusDb();

        $bonusList  =   $bonusDb->getByIds($bonusParam);

        $logic      =   new BonusLogic();

        $bonusFormat=   $logic->doFormatBonusList($bonusList);

        $bonusParam =   array_flip($bonusParam);

        foreach ($bonusFormat as $key => &$bonus ){

            $bonus['custom_value'] = $bonusParam[$bonus['id']];
        }

        Cache::put($cacheKey,json_encode($bonusFormat), 10);

        return $bonusFormat;
    }

    /*******************************领取红包的位置********************************/

    /**
     * @param $userId
     * @param $bonusId
     * @return array
     * @desc 执行红包的领取
     */
    public static function doReceiveBonus($userId,$customValue = 'one')
    {
        $userBonusLogic =   new UserBonusLogic();

        $bonusConfig    =   self::setBonus();

        $bonusId        =   $bonusConfig[$customValue];

        return $userBonusLogic->doSendBonusByUserIdWithBonusId($userId,$bonusId);
    }
    /**
     * @param int $userId
     * @param int $bonusId
     * @return array
     * @desc 领取红包的条件判断
     */
    public static function isCanReceiveBonus($userId = 0,$customValue = 'ten')
    {
        if( empty($userId) || $userId ==0 ){

            return self::callError('您还没有登录,请登录后后领取');
        }
        $config         =   self::config();

        $startTime      =   $config['START_TIME'];

        $nowTime        =   time();

        if( $nowTime < $startTime ){

            return self::callError("领取红包在".date('m.d',$startTime)."号准时开启!<br/>敬请期待!");
        }

        $endTime        =   $config['END_TIME'];

        if( $nowTime > $endTime ){

            return self::callError("领取红包活动已经结束!<br/>谢谢参与!");
        }

        return self::isCanReceiveBonusTimes($userId,$customValue);
    }

    /**
     * @param $userId
     * @param $bonusId
     * @return array
     * @desc 判断是否可以领取红包
     */
    public static function isCanReceiveBonusTimes($userId ,$customValue='ten')
    {
        $bonusConfig    =   self::setBonus();

        if( !isset($bonusConfig[$customValue]) || empty($bonusConfig[$customValue])){

            return self::callError("红包信息错误,请确认后领取!");
        }

        $bonusId            =   $bonusConfig[$customValue];

        $userBonusTotalArr  =   self::setUserReceiveBonusTotal($userId);

        $maxCanBonusNumber  =   self::getMaxBonusNumber();

        $everyBonusReceive  =   self::everyBonusReceive();

        if($everyBonusReceive == true && array_sum($userBonusTotalArr) >=$maxCanBonusNumber){

            return self::callError("只可以领取一张红包,谢谢参与");
        }

        if( $userBonusTotalArr[$bonusId] >= $maxCanBonusNumber ){

            return self::callError("您已经领取过该红包,谢谢参与");
        }

        return self::callSuccess();
    }
    /**
     * @param int $userId
     * @return array
     * @desc 红包的领取数据
     */
    protected static function setUserReceiveBonusTotal($userId = 0)
    {
        $bonusParam =   self::setBonus();

        $userBonusDb=   new UserBonusDb();

        $betweenTime=   self::setReceiveBetweenTime();

        return self::setFormatUserBonusTotal($userBonusDb->getUserBonusUsedTotal($betweenTime['start'],$betweenTime['end'],$bonusParam,$userId));
    }
    /**
     * @param array $totalList
     * @return array
     * @desc 格式化红包的数量
     */
    protected static function setFormatUserBonusTotal($totalList = array() )
    {
        $bonusParam =   self::setBonus();

        $formatBonusTotal   =   [];

        $totalList  =   ToolArray::arrayToKey($totalList,'bonus_id');

        foreach ($bonusParam as $key => $bonusId ){

            $formatBonusTotal[$bonusId] =   isset($totalList[$bonusId]) ? $totalList[$bonusId]['total'] : 0;
        }

        return $formatBonusTotal;
    }
    /**
     * @return array
     * @desc  根据配置设置统计的时间段
     */
    protected static function setReceiveBetweenTime()
    {
        $isEveryDay =   self::setEveryDayReceiveStatus();

        if( $isEveryDay == false ){

            $config =   self::config();

            return [
                'start' =>  date("Y-m-d H:i:s" , $config['START_TIME']),
                'end'   =>  date("Y-m-d H:i:s" , $config['END_TIME']),
            ];
        }

        return [
            'start' =>  date("Y-m-d 00:00:00",time()),
            'end'   =>  date("Y-m-d 23:59:59",time()),
        ];
    }
    /*******************************获取奖品的位置********************************/

    /**
     * @return float|int
     * @desc 获取每天展示的奖品的索引
     */
    protected static function setShowLotteryIndex( $lotteryTotal = 0 )
    {
        if($lotteryTotal == 0){

            return 1;
        }
        $timeArr    =   self::setTime();

        if( time() < $timeArr['start'] ){

            return 1;
        }
        $startTime  =   date('Y-m-d',$timeArr['start']);

        $endTime    =   ToolTime::dbNow();

        if( time() >= $timeArr['end']){

            $endTime=   date('Y-m-d',$timeArr['end']);
        }

        $lotteryIndex=  ToolTime::getDayDiff($startTime,$endTime)+1;

        if( $lotteryIndex <= $lotteryTotal){

            return$lotteryIndex;
        }

        return ($lotteryIndex%$lotteryTotal)+1;
    }
    /**
     * @return array
     * @desc 获取奖品的信息
     */
    protected static function setCouponLotteryList()
    {
        $couponLotteryList  =   LotteryConfigLogic::getLotteryByGroup(self::getActivityLotteryGroup());

        if( !empty($couponLotteryList) ){

            return ToolArray::arrayToKey($couponLotteryList,'order_num');
        }

        return [];
    }
    /**
     * @return mixed
     * @desc 中奖的数据
     */
    protected static function setCouponWinningList()
    {
        $recordLogic        =   new  LotteryRecordLogic();

        $timeArr            =   self::setTime();

        $connection         =   [
            'start_time'    =>  date('Y-m-d H:i:s',$timeArr['start']),
            'end_time'      =>  date('Y-m-d H:i:s',$timeArr['end']),
            'activity_id'   =>  self::setActivityEventId(),
        ];

        return  $recordLogic->getRecordByConnection($connection);
    }
    /*******************************解析配置文件的位置********************************/
    /**
     * @param $version
     * @return bool
     * @desc 判断当前的app版本号是否正常
     */
    public static function isUnUseAppVersion( $version = '' )
    {
        $config =   self::config();

        if( in_array($version,$config['UNUSED_APP_VERSION']) ){

            return false;
        }

        return true;
    }
    /**
     * @return bool
     * @desc 周期内领取的次数
     */
    protected static function everyBonusReceive()
    {
        $config     =   self::config();

        if( $config['EVERY_BONUS_RECEIVE'] == 1 ){

            return true;
        }

        return false;
    }
    /**
     * @return bool
     * @desc 是否每天都可以领取
     */
    protected static function setEveryDayReceiveStatus()
    {
        $config     =   self::config();

        if( $config['IS_EVERY_DAY_RECEIVE'] ==1 ){

            return true;
        }

        return false;
    }
    /**
     * @return int
     * @desc 获取红包的领取的最大次数
     */
    protected static function getMaxBonusNumber()
    {
        $config     =   self::config();

        return isset($config['MAX_RECEIVE_TIMES']) ? (int)$config['MAX_RECEIVE_TIMES'] : 1;
    }
    /**
     * @return array
     * @desc  活动红包的数据
     */
    public static function setBonus()
    {
        $config     =   self::config();

        $bonusArr   =   explode('|',$config['BONUS_CONFIG']);

        $returnArr  =   [];

        if( !empty($bonusArr) ){

            $bonusArr   =   array_filter($bonusArr);

            foreach ($bonusArr as $key => $bonusStr ){

                $bonusRes       =    explode('=',$bonusStr);

                $returnArr[$bonusRes[0]] = trim($bonusRes[1]);
            }
        }

        return $returnArr;

    }
    /**
     * @return int
     * @desc   获取奖品分组
     */
    protected static function getActivityLotteryGroup()
    {
        $eventId    =   self::setActivityEventId();

        $eventNote  =   self::setActivityEventIdNote();

        return isset($eventNote[$eventId]) ? $eventNote[$eventId]['group'] : self::DEFAULT_GROUP;
    }
    /**
     * @return array
     * @desc 所有的包含抽奖活动的标示
     */
    protected static function setActivityEventIdNote()
    {
        return LotteryRecordLogic::getLotteryActivityEventNote();
    }
    /**
     * @return int
     * @DESC 活动的唯一性标示
     */
    protected static function setActivityEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_HONGKONG_DAY ;
    }
    /**
     * @return array|mixed
     * @desc  春风十里活动的配置文件
     */
    private static function config()
    {
        $object =   self::getObject();

        return $object['config'];
    }
    /**
     * @return mixed
     * @desc 获取解析的数据
     */
    private static function getObject()
    {
        return self::getInstance()->getObject();
    }
    /**
     * @return $object
     * @desc 单列模式
     */
    private static function getInstance(){

        if(!(self::$objectExample instanceof self)){

            self::$objectExample = new AnalysisConfigLogic('ACTIVITY_COUPON_CONFIG');
        }

        return self::$objectExample;
    }
}
