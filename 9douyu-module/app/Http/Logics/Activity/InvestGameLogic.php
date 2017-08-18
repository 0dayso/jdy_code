<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/13
 * Time: 下午6:53
 */

namespace App\Http\Logics\Activity;

use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\Activity\LotteryConfigLogic;
use App\Http\Logics\Activity\LotteryRecordLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Invest\InvestModel;
use App\Http\Models\User\UserModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Cache;

class InvestGameLogic extends Logic
{

    const
            ACTIVITY_STATISTIC_CACHE_KEY    =   'SHOW_EVERY_DAY_',
            DEFAULT_INVEST_GAME_RULE        =   1,  //PK的周期
            ACTIVITY_INVEST_WITH_BONUS      =   1,  //表示使用红包
            ACTIVITY_NOT_OPEN               =   1,  //活动未开始
            ACTIVITY_IS_OVER                =   2,  //活动已经结束
            ACTIVITY_PADDING                =   3;  //活动进行中

    private static $objectExample;  //数据对象

    public static function getActivityTime()
    {
        return [
            'start' =>  self::setStartTime(),
            'end'   =>  self::setEndTime(),
        ];
    }
    public static function getActToken()
    {
        return time() . '_' . self::setActivityEventId();
    }
    /**
     * @return bool
     * @desc 投资PK记录展示规则的调整条件
     */
    public static function getInvestGameRule()
    {
        $ruleStatus =   self::setInvestGameRule();

        if( $ruleStatus  == self::DEFAULT_INVEST_GAME_RULE ){

            return true;
        }

        return  false;
    }
    /**
     * @return array
     * @desc 活动奖品文字说明
     */
    public static function getActivityLottery()
    {
        $ruleStatus     =   self::getInvestGameRule();

        $projectLine    =   self::setActivityProjectLine();

        $lotteryList    =   [];

        if( $ruleStatus == true || empty($projectLine)){

            $lotteryList=   self::setLotteryConfig();

            return [ 'all'=>$lotteryList ];
        }

        foreach ( $projectLine as $key => $value ){

            $lotteryList[$value]    =   self::setLotteryConfig($value);
        }

        return $lotteryList;
    }
    /**
     * @param $key
     * @return int
     * @desc 读取活动项目
     */
    public static function getActivityProject()
    {
//        $projectLine =   self::setActivityProjectLine();

//        array_filter($projectLine);
//
//        $projectStr  =   implode(",",$projectLine);
//
//        return ProjectModel::getAppointJsxProject($projectStr);

//        $projectList    =   ProjectModel::getNewestProjectEveryType();
//
//        $formatProject  =   self::getFormatProjectType($projectList);
//
//        return $formatProject;
//        $object =   self::getObject();

//        return $object['project'];

        $config =   self::config();

        return ProjectLogic::getActivityProject($config['ACTIVITY_PROJECT']);
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
     * @return int
     * @desc 活动的状态
     */
    public static function setActivityStatus()
    {
        $startTime      =   self::setStartTime();

        $endTime        =   self::setEndTime();

        $nowTime        =   time();

        if( $startTime > $nowTime ){

            return self::ACTIVITY_NOT_OPEN;
        }

        if( $nowTime > $endTime ){

            return self::ACTIVITY_IS_OVER;
        }

        return self::ACTIVITY_PADDING;
    }

    /**
     * @return mixed
     * @desc 所有的项目id 集合
     */
    public static function getInvestmentRankingList()
    {
        $projectIds     =   self::getAllProjectIdStatistics();

        $time           =   date("Y-m-d",self::setStartTime());

        return  self::setActivityStatisticsByTime($time,$projectIds,3,true);
    }
    /**
     * @return mixed
     * @当天的中奖数据
     */
    public static function getActivityStatisticsNowDate()
    {

        $activityStatus =   self::setActivityStatus();

        if( $activityStatus == self::ACTIVITY_NOT_OPEN ){

            return false;
        }

        //活动结束则读取最后一天的数据
        if ( $activityStatus == self::ACTIVITY_IS_OVER ){

            $nowTime    =   date("Y-m-d",self::setEndTime());

        }else{

            $nowTime        =   ToolTime::dbDate();
        }

        $projectIds     =   self::getProjectIdsStatistics();

        return  self::setActivityStatisticsByTime($nowTime,$projectIds,6);
    }

    /**
     * @return array
     * @desc 展示每一天的数据
     */
    public static function getEveryDayLotteryStatistics()
    {
        $showTimeList    =   self::setActivityEveryTime();

        if( empty($showTimeList) ){

            return [];
        }

        $projectIds     =   self::getProjectIdsStatistics();

        $statisticsList =   [];

        foreach ( $showTimeList as $key => $time ){

            $statisticsList[$time]  =   self::setActivityStatisticsByTime($time,$projectIds,3,true);
        }

        return $statisticsList;
    }


    /**
     * @param $times
     * @return mixed
     * @desc 根据时间读取中奖的数据
     * @desc 增加缓存的功能
     */
    protected static function setActivityStatisticsByTime($times,$projectIds,$line ,$cache = false)
    {
        $cacheKey       =   self::ACTIVITY_STATISTIC_CACHE_KEY.$times;

        $statisticsCache= Cache::get($cacheKey);

        if( $cache == true && !empty($statisticsCache) ){

            return json_decode($statisticsCache,true);
        }

        $userIds        =  [];

        $statistics     =  [];

        if( empty($projectIds) ) return $statistics;

        foreach ( $projectIds as $key  =>  $projectId ){

            $investList        =   self::setInvestStatisticsByDate($times,$line,$projectId);

            $statistics[$key]  =   $investList;

            $userIds           =   array_merge(array_column($investList,'user_id'),$userIds);
        }

        $userInfoList   =   self::setInvestUserList($userIds);

        $statisticsList =   ['statistics'=>$statistics,'user'=>$userInfoList];

        if( $cache == true ){

            Cache::put($cacheKey,json_encode($statisticsList), 60);
        }

        return $statisticsList;
    }

    /**
     * @param $userIds
     * @return array
     * @desc 合并用户id
     */
    protected static function setMergeUserIdArray($userIds)
    {
        if( empty($userIds) ){

            return [];
        }
        $idGather       =   [];

        foreach ($userIds as $key => $userId ){

            $idGather   =   array_merge($idGather,$userId);
        }

        return $idGather;
//        $userOneIds     =   isset($userIds['one']) ? $userIds['one'] : [];
//
//        $userThreeIds   =   isset($userIds['three']) ? $userIds['three'] : [];
//
//        $userSixIds     =   isset($userIds['six']) ? $userIds['six'] : [];
//
//        $userTwelveIds  =   isset($userIds['twelve']) ? $userIds['twelve'] : [];
//
//        $userJaxIds     =   isset($userIds['jax']) ? $userIds['jax'] : [];
//
//        return array_merge($userOneIds,$userThreeIds,$userSixIds,$userTwelveIds,$userJaxIds);
    }
    /**
     * @return array
     * @desc 格式化时间
     */
    public static function setActivityEveryTime()
    {
        $startTime      =   self::setStartTime();

        $endTime        =   self::setEndTime();

        $endTime        =   $endTime >= time()  ? time() : $endTime;

        $formatDate     =   [];

        for ( $start = $startTime ;$start <=$endTime;$start +=86400 ){

            $showTime       =   date("Y-m-d",$start);

            if( $showTime != date('Y-m-d')){

                $formatDate[]   =   date("Y-m-d",$start);
            }
        }

        rsort($formatDate);

        return $formatDate;
    }

    /**
     * @param array $userIds
     * @return array
     * @desc 获取用户数据
     */
    protected static function setInvestUserList( $userIds = array() )
    {
        if( empty($userIds) ) return [];

        $userModel  =   new UserModel();

        $userList   =   $userModel->getCoreUserListByIds($userIds);

        return ToolArray::arrayToKey($userList,'id');
    }
    /**
     * @param $times
     * @return mixed
     * @desc 获取同一天内的投资总额
     */
    protected static function setInvestStatisticsByDate( $times ,$line,$projectId=array())
    {
        $investLogic    =   new TermLogic();

        $statistics     =   self::setStatisticsQuery($times,$projectId,$line);

        $rankingList    =    $investLogic->getInvestStatisticsExist($statistics);

        return self::setFormatInvestRanking($rankingList,$line);
    }

    /**
     * @param array $investList
     * @return array
     * @desc 去除并列的问题
     */
    protected static function setFormatInvestRanking( $investList = array() ,$line = 3)
    {
        if (empty( $investList ) ) return [];

        return array_slice($investList,0,$line);
    }

    /**
     * @return array
     * @desc 根据项目的奖励的奖励
     */
    protected static function setLotteryConfig( $configKey  ='default' )
    {
        $getConfigKey =   strtoupper('LOTTERY_NAME_'.$configKey);

        $config       =   self::config();

        $lotteryConfig=   isset($config[$getConfigKey]) ? $config[$getConfigKey] : $config['LOTTERY_NAME_DEFAULT'];

        if( empty($lotteryConfig)){

            return '';
        }

         return self::doFormatLotteryConfig($lotteryConfig);
    }
    /**
     * @param string $lotteryConfig
     * @return array
     * @desc 解析奖品数据
     */
    protected static function doFormatLotteryConfig( $lotteryConfig ='' )
    {
        if( empty( $lotteryConfig )) return [];

        $lotteryConfigArray  =   explode(",",$lotteryConfig);

        $formatLottery       =   [];

        foreach ($lotteryConfigArray as $key    =>  $lottery ){

            $lotteryInfo     = explode("=",$lottery);

            $formatLottery[$lotteryInfo[0]] =   $lotteryInfo[1];
        }

        return $formatLottery;
    }
    /**
     * @param $times
     * @return array
     * @desc 格式查询的条件
     */
    protected static function setStatisticsQuery( $times ,$projectId = array(),$limit = 3)
    {
        $startTime      =   date("Y-m-d H:i:s",ToolTime::getUnixTime($times));

        $ruleStatus     =   self::getInvestGameRule();

        $endTime        =   date("Y-m-d H:i:s",ToolTime::getUnixTime($times,'end'));

        if( $ruleStatus == true ){

            $endTime        =   date("Y-m-d H:i:s",self::setEndTime());
        }

        $returnArr      =   [
            'start_time'    =>  $startTime,
            'end_time'      =>  $endTime,
            'size'          =>  $limit
        ];

        $isCanUseBonus  =   self::isInvestWithUseBonus();
        //是否可以使用红包
        if( $isCanUseBonus != true ){

            $returnArr['bonusId']   =  "0";
        }

        if( !empty($projectId) ){

            $returnArr['p_ids'] = $projectId;
        }

        return $returnArr;
    }

    //格式化时间
    public static function doMatTimeToSecond(){

        $nowTime    =   time();

        $lastTime   =   ToolTime::getUnixTime(date("Y-m-d"),'end');

        $seconds    =   $lastTime - $nowTime;

        //<span>0</span><span>0</span><em>时</em><span>0</span><span>0</span><em>分</em><span>0</span><span>0</span><em>秒</em>
        $hours = intval($seconds/3600);

        $days_num   =   '';

        if( $hours < 10 ){

            $days_num   .= '<span id="t_h1">0</span><span id=t_h>'.$hours.'</span><em>时</em>';

        }else{

            $firstHour  = substr($hours,0,1);

            $secondHour = substr($hours,1,1);

            $days_num   .='<span id="t_h1">'.$firstHour.'</span><span id=t_h>'.$secondHour.'</span><em>时</em>';
        }
        $minutes = intval(($seconds%3600)/60);//取余下秒数

        if( $minutes < 10 ){

            $days_num   .= '<span id=t_m1>0</span><span id=t_m>'.$minutes.'</span><em>分</em>';

        }else{
            $firstMin  = substr($minutes,0,1);

            $secondMin = substr($minutes,1,1);

            $days_num   .='<span id="t_m1">'.$firstMin.'</span><span id=t_m>'.$secondMin.'</span><em>分</em>';
        }
        $second         = ($seconds%3600)%60;

        if( $second < 10 ){

            $days_num   .= '<span id="t_s1">0</span><span id=t_s>'.$second.'</span>';

        }else{

            $firstSec  = substr($second,0,1);

            $secondSec = substr($second,1,1);

            $days_num  .='<span id="t_s1">'.$firstSec.'</span><span id=t_s>'.$secondSec.'</span><em>秒</em>';
        }

        return $days_num;
    }

    /**
     * @return int
     * @desc 活动活动的项目id集合
     */
    public static function getProjectIdsStatistics( )
    {
        $startTime      =   date("Y-m-d H:i:s",(self::setStartTime()-86400*7));

        $endTime        =   date("Y-m-d H:i:s",self::setEndTime());

        $projectLine    =   self::setActivityProjectLine();

        $projectIds     =   [];

        $projectList    =   ProjectModel::getAllProjectIdByTime($startTime,$endTime);

//        foreach ($projectLine as $key => $line ){
//
//            $projectIds[$line]  =    ProjectModel::getProjectIdsStatistics($startTime,$endTime,$line);
//        }
//        return $projectIds;
        $ruleStatus     =   self::getInvestGameRule();

        if( $ruleStatus == true ){

            foreach ($projectList as $key => $project ){

                if( in_array($key,$projectLine) ){

                    $projectIds =   array_merge($projectIds,$project);
                }
            }

            return ['all'=>$projectIds];
        }
        //循环组装数组
        foreach ($projectList as $key => $project ){

            if( in_array($key,$projectLine) ){

                $projectIds[$key]   =  $project;
            }
        }

        return $projectIds;
    }

    /**
     * @return int
     * @desc 活动活动的所有项目id集合
     */
    public static function getAllProjectIdStatistics( )
    {
        $startTime      =   date("Y-m-d H:i:s",(self::setStartTime()-86400*7));

        $endTime        =   date("Y-m-d H:i:s",self::setEndTime());

        $projectLine    =   self::setActivityProjectLine();

        $projectIds     =   [];

        $projectList    =   ProjectModel::getAllProjectIdByTime($startTime,$endTime);

        foreach ($projectList as $key => $project ){

            if( in_array($key,$projectLine) ){

                $projectIds =   array_merge($projectIds,$project);
            }
        }

        return  [ 'all' =>  $projectIds ];
    }
    /**
     * @return bool
     * @desc 投资pk活动是否可以使用红包的判断
     */
    protected static function isInvestWithUseBonus()
    {
        $config     =   self::config();

        if( $config['INVEST_WITH_BONUS'] && $config['INVEST_WITH_BONUS'] == self::ACTIVITY_INVEST_WITH_BONUS ){

            return true;
        }

        return false;
    }

    /**
     * @return int|mixed
     * @desc  投资PK的记录展示规则
     */
    protected static function setInvestGameRule()
    {
        $config     =   self::config();

        return isset($config['INVEST_GAME_RULE']) ? $config['INVEST_GAME_RULE'] :self::DEFAULT_INVEST_GAME_RULE;
    }
    /**
     * @param $key
     * @return array
     * @desc 活动活动的项目
     */
    protected static function setActivityProjectLine( )
    {
        $config     =   self::config();

        return  $config['ACTIVITY_PROJECT'];
    }
    /**
     * @return mixed
     * @desc 所有的项目id 集合
     */
    public static function getRealTimeRanking()
    {
        $projectIds     =   self::getAllProjectIdStatistics();

        $time           =   date("Y-m-d",self::setStartTime());

        $rankingList    =   self::setActivityStatisticsByTime($time,$projectIds,5,true);

        if( empty($rankingList['statistics']) ){

            return [];
        }
        $ranking        =   [];

        foreach ($rankingList['statistics']['all'] as $key => $statistics){

            $phone  =   isset($rankingList['user'][$statistics['user_id']]) ? $rankingList['user'][$statistics['user_id']]['phone'] : '';

            $name   =   isset($rankingList['user'][$statistics['user_id']]) ? $rankingList['user'][$statistics['user_id']]['real_name'] : '';

            $ranking[]  =   [
                'user_id'   =>  $statistics['user_id'],
                'total'     =>  $statistics['total'],
                'max_time'  =>  $statistics['max_invest_time'],
                'phone'     =>  $phone,
                'real_name' =>  $name,
            ];
        }

        return $ranking;
    }
    /**
     * @return array
     * @desc 返回展示的奖品相关的数据
     */
    public static function getLotteryMessage()
    {
        return [
            'lottery'   =>  self::setActivityLotteryList(),
            'record'    =>  self::setLotteryWinner(),
            'word'      =>  self::setNumberToWord(),
        ];
    }

    /**
     * @return array
     * @desc 解析数学文字
     */
    protected static function setNumberToWord()
    {
        return [0=>'零',1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',7=>'七',8=>'八',9=>'九',10=>'十',];
    }
    /**
     * @return array
     * @desc 获取本次活动的奖品信息
     */
    protected static function setActivityLotteryList()
    {
        $lotteryGroup   =   self::setActivityLotteryGroup();

        $lotteryList    =   LotteryConfigLogic::getLotteryByGroup($lotteryGroup);

        return ToolArray::arrayToKey($lotteryList,'order_num');
    }

    /**
     * @return mixed
     * @desc 获取中奖者的数据
     */
    protected static function setLotteryWinner()
    {
        $recordLogic    =   new  LotteryRecordLogic();

        $statistics     =   [
            'start_time'=>  date("Y-m-d H:i:s",self::setStartTime()),
            'activity_id'=> self::setActivityEventId(),
        ];

        $recordResult   =   $recordLogic->getRecordByConnection($statistics);

       return  isset($recordResult['list']) && !empty($recordResult['list']) ? ToolArray::arrayToKey($recordResult['list'],'prizes_id'):[];
    }
    /**
     * @return int
     * @desc 获取本次活动的奖品分组
     */
    protected static function setActivityLotteryGroup()
    {
        $activityNote    =  LotteryRecordLogic::getLotteryActivityEventNote();

        $activityEventId =  self::setActivityEventId();

        return isset($activityNote[$activityEventId]) ? $activityNote[$activityEventId]['group'] : 0;
    }
    /**
     * @param $key
     * @return int
     * @desc 读取活动的结束时间
     */
    protected static function setEndTime(  )
    {
        $config     =   self::config( );

        return $config['END_TIME'];
    }
    /**
     * @param $key
     * @return int
     * @desc  活动开始时间
     */
    protected static function setStartTime(  )
    {
        $config     =   self::config();

        return $config['START_TIME'];
    }
    /**
     * @return mixed
     * @desc 获取活动的项目id
     */
    public static function setProjectLine()
    {
        $config     =   self::config();

        return $config['ACTIVITY_PROJECT'];
    }
    /**
     * @return array|mixed
     * @desc  加币活动的配置文件
     */
    private static function config()
    {
        $object =   self::getObject();

        return $object['config'];
    }
    /**
     * @return int
     * @desc 返回活动的唯一性标示
     */
    private static function setActivityEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_INVEST_GAME;
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

            self::$objectExample = new AnalysisConfigLogic('ACTIVITY_INVEST_GAME');
        }

        return self::$objectExample;
    }
}
