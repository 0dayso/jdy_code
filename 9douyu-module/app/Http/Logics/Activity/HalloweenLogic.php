<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/20
 * Time: 上午10:27
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use Cache;

class HalloweenLogic extends Logic
{

    const
        DEFAULT_LOTTERY_TOTAL   =   7,
        EVERY_DAY_LOTTERY_DEFAULT=  1,
        ACTIVITY_NOT_OPEN        =   1,  //活动未开始
        ACTIVITY_IS_OVER         =   2,  //活动已经结束
        ACTIVITY_PADDING         =   3,  //活动进行中
        EVERY_DAY_LOTTERY_KEY   =   "LOTTERY_CACHE_EVERY_DAY_CACHE",  //每天中奖的数据
        TODAY_LOTTERY_KEY       =   "TODAY_LOTTERY_CACHE_CACHE",      //今日奖品缓存
        LOTTERY_RECORD_KEY      =   "LOTTERY_RECORD_KEY";              //中奖记录

    /**
     * @return int
     * @desc 活动状态
     */
    public static function getActivityStatus()
    {
        $startTime  =   self::setStartTime();

        $endTime    =   self::setEndTime();

        if( time() > $endTime){

            return self::ACTIVITY_IS_OVER;
        }

        if( time() < $startTime){

            return self::ACTIVITY_NOT_OPEN;
        }

        return self::ACTIVITY_PADDING;
    }
    /**
     * @return array
     * @desc 活动时间
     */
    public static function getActivityCycle()
    {
        return [
            'start' =>  self::setStartTime(),
            'end'   =>  self::setEndTime(),
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
     * @return array
     * @desc 输出中奖数据
     */
    public static function getFormatEveryDayPrize()
    {

        $cacheKey           =   self::EVERY_DAY_LOTTERY_KEY;

        $statisticsCache= Cache::get($cacheKey);

        if( !empty($statisticsCache) ){

            return json_decode($statisticsCache,true);
        }

        $activityTimeCycle  =   self::setActivityEveryTime();

        $activityPrizeList  =   self::setFormatLotteryConfig();

        $winningUserList    =   self::setFormatWinningUser();

        $prizeTotal         =   count($activityPrizeList);

        $formatLotteryList  =   [];

        foreach ($activityTimeCycle as $key => $activityTime ){

            $number     =   $key%$prizeTotal !=0? $key%$prizeTotal : $prizeTotal;

            $userPhone  =   isset($winningUserList[$key-1]) && $winningUserList[$key-1] !="未开奖" ? ToolStr::hidePhone($winningUserList[$key-1],3,3): "未开奖";

            $formatLotteryList[$key] = [
                'time'      =>  $activityTime,
                'lottery'   =>  $activityPrizeList[$number],
                'user'      =>  $userPhone
            ];

        }

        Cache::put($cacheKey,json_encode($formatLotteryList), 60);  //加入缓存

        return $formatLotteryList;

    }

    /**
     * @return int
     * @desc 输出每天展示的奖品数据
     */
    public static function setEveryDayPrize()
    {

        $cacheKey           =   self::TODAY_LOTTERY_KEY;

        $statisticsCache= Cache::get($cacheKey);

        if( !empty($statisticsCache) ){

            return json_decode($statisticsCache,true);
        }
        $maxLotteryTotal    =   self::DEFAULT_LOTTERY_TOTAL;

        $activityTimeCycle  =   self::setActivityEveryTime();

        $activityPrizeList  =   self::setFormatLotteryConfig();

        $activityTimeCycle  =   array_flip($activityTimeCycle);

        $nowTime            =   ToolTime::dbDate();

        if( $nowTime < date('Y-m-d' ,self::setStartTime()) ){

            $nowTime        =   date('Y-m-d' ,self::setStartTime());
        }
        if( $nowTime > date('Y-m-d' ,self::setEndTime()) ){

            $nowTime        =   date('Y-m-d' ,self::setEndTime());
        }

        $lotteryNumber      =   $activityTimeCycle[$nowTime] != $maxLotteryTotal ? $activityTimeCycle[$nowTime]%$maxLotteryTotal : $activityTimeCycle[$nowTime];

        $lotteryName        =   $activityPrizeList[$lotteryNumber];

        $formatLotteryList  =   ['number'=> $lotteryNumber , 'name'=>$lotteryName];
        
        Cache::put($cacheKey,json_encode($formatLotteryList), 60);  //加入缓存

        return $formatLotteryList;
    }

    /**
     * @return array
     * @desc 中奖记录查询
     */
    public static function getLotteryRecord()
    {
        $cacheKey           =   self::LOTTERY_RECORD_KEY;

        $statisticsCache= Cache::get($cacheKey);

        if( !empty($statisticsCache) ){

            return json_decode($statisticsCache,true);
        }

        $lotteryLogic   =   new LotteryRecordLogic();

        $statistics     =   self::setStatisticsParams();

        $recordList     =   $lotteryLogic->getRecordByConnection($statistics);

        if( $recordList['lotteryNum'] == 0){

            return [];
        }

        Cache::put($cacheKey,json_encode($recordList['list']), 60);  //加入缓存

        return $recordList['list'];
    }

    /**************************************活动执行逻辑部分************************************************/

    /**
     * @return array
     * @desc 判断抽奖的状态
     */
    public static function isCheckLotteryStatus($userId)
    {

        if( empty($userId) || $userId ==0){

            return self::callError('您还未登录!请登录后参与抽奖活动');
        }

        $startTime      =   self::setStartTime();

        $nowTime        =   time();

        if( $nowTime < $startTime ){

            return self::callError("万圣节活动在".date('m.d',$startTime)."号准时开启!<br>敬请期待!");
        }

        $endTime        =   self::setEndTime();

        if( $nowTime > $endTime ){

            return self::callError("万圣节活动已经结束!!<br>谢谢参与!");
        }

        return self::callSuccess();
    }

    /***
     * @param $userId
     * @return array
     * @desc 判断用户是否可以抽奖
     */
    public static function isCheckLotteryInvestStatus( $userId )
    {
        $statistics     =   self::setLotteryUserEveryDay($userId);

        $lotteryLogic   =   new LotteryRecordLogic();

        $recordList     =   $lotteryLogic->getRecordByConnection($statistics);

        $maxLotteryNum  =   self::setActivityLotteryMax();

        if( $recordList['lotteryNum'] >= $maxLotteryNum ){

            $errorMsg   =   '每天只有 '.$maxLotteryNum.' 次砸南瓜机会, <br/>谢谢参与!';

            return self::callError($errorMsg);
        }

        return self::callSuccess();
    }

    /**
     * @param $data
     * @return array
     * @desc 执行抽奖的程序
     */
    public static function doLuckDraw( $data )
    {
        $lotteryLogic           =   new LotteryLogic();

        $data['activity_id']    =   ActivityFundHistoryDb::SOURCE_ACTIVITY_HALLOWEEN;

        return $lotteryLogic->doLuckDrawWithRate($data);
    }

    /**************************************活动数据处理部分************************************************/
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

        $activityProjectLine   =   self::setActivityProjectLine();

        $activityProject       =   [];

        foreach ($projectList as $key => $project ){

            if( in_array($key,$activityProjectLine) ){

                $activityProject[$key]=  $project;
            }
        }

        return $activityProject;
    }
    /**
     * @return array
     * @desc 格式化时间
     */
    protected static function setActivityEveryTime()
    {
        $startTime      =   self::setStartTime();

        $endTime        =   self::setEndTime();

        $formatDate     =   [];

        $i              =   1;

        for ( $start = $startTime ;$start <=$endTime;$start +=86400 ){

            $formatDate[$i]   =   date("Y-m-d",$start);

            $i++;
        }

        return $formatDate;
    }

    /**************************************设置活动参数部分************************************************/

    /**
     * @return array
     * @desc 中奖记录参数
     */
    protected static function setStatisticsParams()
    {
        $startTime      =   self::setStartTime();

        $endTime        =   self::setEndTime();
        return [
            'start_time'    =>  date('Y-m-d H:i:s',$startTime),
            'end_time'      =>  date('Y-m-d H:i:s',$endTime),
            'limit'         =>  30,
            'activity_id'   =>  ActivityFundHistoryDb::SOURCE_ACTIVITY_HALLOWEEN,
        ];
    }

    /**
     * @return array
     * @desc 每一天中奖的查询条件
     */
    protected static function setLotteryUserEveryDay($userId)
    {
        return [
            'start_time'    =>  date('Y-m-d 00:00:00',time()),
            'end_time'      =>  date('Y-m-d 23:59:59',time()),
            'activity_id'   =>  ActivityFundHistoryDb::SOURCE_ACTIVITY_HALLOWEEN,
            'user_id'       =>  $userId,
        ];
    }
    /**
     * @return array
     * @desc 解析中奖者的号码
     */
    protected static function setFormatWinningUser()
    {
        $config         =   self::setActivityConfig();

        $winningConfig  =   explode(",",$config['LOTTERY_WINNER']);

        return $winningConfig;
    }
    /**
     * @param string $lotteryConfig
     * @return array
     * @desc 解析奖品数据
     */
    protected static function setFormatLotteryConfig()
    {
        $config             =   self::setActivityConfig();

        $lotteryConfig      =   explode(",",$config['LOTTERY_NAME_CONFIG']);

        $formatLottery      =   [];

        foreach ($lotteryConfig as $key    =>  $lottery ){

            $lotteryInfo    = explode("=",$lottery);

            $formatLottery[$lotteryInfo[0]] =   $lotteryInfo[1];
        }

        return $formatLottery;
    }

    /**
     * @param $key
     * @return array
     * @desc 获取活动的项目类型
     */
    protected static function setActivityProjectLine( )
    {
        $config     =   self::setActivityConfig();

        return  explode(",",$config['ACTIVITY_PROJECT']);
    }

    /**
     * @return int|mixed
     * @desc 每天最大的抽奖次数
     */
    protected static function setActivityLotteryMax()
    {
        $config     =   self::setActivityConfig();

        $maxLottery =   isset($config['EVERY_DAY_LOTTERY_MAX']) ? $config['EVERY_DAY_LOTTERY_MAX'] : self::EVERY_DAY_LOTTERY_DEFAULT;

        return $maxLottery;
    }
    /**
     * @param $key
     * @return int
     * @desc 读取活动的结束时间
     */
    protected static function setEndTime(  )
    {
        $config     =   self::setActivityConfig( );

        return ToolTime::getUnixTime($config['END_TIME'],'end');
    }
    /**
     * @param $key
     * @return int
     * @desc  活动开始时间
     */
    protected static function setStartTime(  )
    {
        $config     =   self::setActivityConfig(  );

        return ToolTime::getUnixTime($config['START_TIME']);
    }
    
    /**
     * @return array|mixed
     * @desc 读取活动配置
     */
    protected static function setActivityConfig()
    {
        $config     =   ActivityConfigModel::getConfig('ACTIVITY_HALLOWEEN_CONFIG');

        if( empty($config) ){

            return  SystemConfigModel::getConfig('ACTIVITY_HALLOWEEN_CONFIG');
        }

        return $config;
    }
}