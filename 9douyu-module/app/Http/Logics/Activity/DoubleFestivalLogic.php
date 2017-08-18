<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/26
 * Time: 上午11:48
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Dbs\Activity\LotteryConfigDb;
use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Bonus\BonusLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;
use App\Tools\ToolMoney;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use Cache;

class DoubleFestivalLogic extends Logic
{

    const
        DEFAULT_ACTIVITY_PROJECT    =   "three,six",            //设置默认的活动项目

        EVERY_DAY_LOTTERY_KEY       =   "LOTTERY_CACHE_EVERY_DAY_CACHE",  //每天中奖的数据
        DEFAULT_INCOME_PROFIT       =   'CACHE_BASE_PROFIT_%d';   //记录收益的缓存

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
     * @return bool
     * @desc 活动链接指向问题
     */
    public static function setActivityPlanType()
    {
        $config     =   self::setActivityConfig();

        if( $config['ACTIVITY_PLAN_NUMBER'] == 1 ){

            return true;
        }

        return false;
    }
    /**
     * @return mixed
     * @desc
     */
    public static function getActivityProject($isFirst = true)
    {
        $activityProjectList    =   self::setActivityProjectList();

        if( $isFirst == true ){

            $activityProjectList    =   current($activityProjectList);

            $activityProjectList['base_profit'] =   self::setIncomeBaseProfit($activityProjectList);
        }

        return $activityProjectList;

    }

    /**
     * @param array $project
     * @return string
     * @desc 用户Pc端按钮状态展示
     */
    public static function getProjectStatus($project = array())
    {
        if($project['status'] ==ProjectDb::STATUS_INVESTING && $project['publish_at'] >= date('Y-m-d H:i:s',time())) {

            return ['css'=>'disable','note'=>'敬请期待'];

        }elseif($project['status'] ==ProjectDb::STATUS_INVESTING){

            return ['css'=>'','note'=>'投资即送'];;

        }else{

            return ['css'=>'disable','note'=>$project['status_note']];
        }
    }

    /**
     * @return array
     * @desc 抽奖区域信息的定位
     */
    public static function getLotteryInfo()
    {
        $lotteryList    =   self::setFormatLotteryConfig();

        $winnerList     =   self::setFormatWinningUser();

        $showKey        =   self::setVeryDayPrizeNumber();

        $lotteryName    =   $lotteryList[$showKey];

        $winnerName     =   self::getWinnerName($winnerList,$showKey);

        $date           =   self::setYesterdayDate();

        return ["winner" => $winnerName, "lottery" => $lotteryName, "img" => $showKey,'date'=> $date];
    }

    /**
     * @return bool|string
     * @desc 格式化昨天的数据
     */
    protected static function setYesterdayDate()
    {
        $startTime  =   date('Y.m.d',self::setStartTime());

        $endTime    =   date('Y.m.d',self::setEndTime());

        $nowTime    =   date('Y.m.d',time());

        if( $startTime >= $nowTime){

            return substr($nowTime,5);
        }
        if( $endTime < $nowTime ){

            return substr($endTime,5);
        }

        return date("m.d",strtotime("-1 day"));

    }
    /**
     * @return array
     * @desc 昨日中奖数据(只显示一天)
     *  日期,奖品,中奖者
     */
    public static function getYesterdayLotteryInfo()
    {
        $cacheKey           =   self::EVERY_DAY_LOTTERY_KEY;

        $statisticsCache= Cache::get($cacheKey);

        if( !empty($statisticsCache) ){

            return json_decode($statisticsCache,true);
        }

        $yesterdayLotteryList   =   self::setYesterdayWinnerList();

        if( empty($yesterdayLotteryList) ){

            return false;
        }

        $listLength     =   count($yesterdayLotteryList)-1;

        $lotteryList    =   [];

        $endListKey     =   $listLength-3 >0 ? $listLength-3: 0;

        for ( $i = $listLength; $i >=$endListKey ;$i--){

            $lotteryList[$i]    =  $yesterdayLotteryList[$i];
        }

        ksort($lotteryList);

        Cache::put($cacheKey,json_encode($lotteryList), 60);  //加入缓存

        return $lotteryList;
    }

    /**
     * @return array
     * @desc 格式化数据
     */
    protected static function setYesterdayWinnerList()
    {
        $dateList       =   self::setYesterdayLotteryInfo();

        $winnerList     =   self::setFormatWinningUser();

        $lotteryList    =   self::setFormatLotteryConfig();

        $prizeTotal         =   count($lotteryList);

        $dateFormatList =   [];

        foreach ($dateList as $key => $date ){

            if( $key== 0 || $key%$prizeTotal ==0){

                $number =   0;

            }elseif ($key%$prizeTotal !=0){

                $number =   $key % $prizeTotal;
            }else{
                $number = $prizeTotal;
            }

            $dateFormatList[$key]   =[
                'date'      =>  $date,
                'lottery'   =>  isset($lotteryList[$number]) ? $lotteryList[$number]: "未设置奖品",
                'winner'    =>  isset($winnerList[$key]) ? ToolStr::hidePhone($winnerList[$key],3,3): "未开奖",
            ];
        }

        return $dateFormatList;
    }
    /**
     * @return array
     * @desc 设置每一天的数据
     */
    protected static function setYesterdayLotteryInfo()
    {

        $startTime      =   self::setStartTime();

        $endTime        =   self::setEndTime();

        $formatDate     =   [];

        if( $endTime >= time() ){

            $endTime    =   time();
        }

        for ( $start = $startTime ;$start <$endTime;$start +=86400 ){

            $lotteryDate        =   date("Y.m.d",$start);

            if( $lotteryDate    != date("Y.m.d")){

                $formatDate[]   =   date("m.d",$start);
            }
        }

        return $formatDate;
    }
    /**
     * @param $version
     * @return bool
     * @desc 判断当前的app版本号是否正常
     */
    public static function isNotUserAppVersion( $version = '' )
    {
        $versionArr =   self::setNotUsedAppWithVersion();

        if( empty($version) || empty($versionArr)){

            return true;
        }

        if( in_array($version,$versionArr) ){

            return false;
        }

        return true;
    }
    /**
     * @param array $winnerList
     * @param int $day
     * @return string
     * @desc 格式化每天的中奖名词
     */
    protected static function getWinnerName($winnerList = array(),$day = 0 )
    {
        $winnerName =   "none";

        if( empty($winnerList) || $day == 0 ){

            return $winnerName;
        }

        $endTime    =   self::setEndTime();

        if( $endTime < time() ){

            $winnerName=isset($winnerList[$day]) ? $winnerList[$day] : 'none';

        }else{

            $winnerName=isset($winnerList[$day-1]) ? $winnerList[$day-1] : 'none';
        }

        return $winnerName == "none" ? $winnerName:  ToolStr::hidePhone($winnerName,3,4) ;
    }
    /**
     * @param array $projectList
     * @return array
     * @desc  获取本次活动的项目
     */
    protected static function setActivityProjectList( )
    {

        $projectList    =   self::getNewestProjectEveryType();

        if( empty($projectList) ) return [];

        $activityProjectLine    =   self::setActivityProjectLine();

        $activityProjectList      =   [];

        foreach ($projectList as $key   => $project ){

            if( in_array($key,$activityProjectLine) ){

                $activityProjectList[$key]= $project;
            }
        }

        return $activityProjectList;
    }

    /**
     * @param array $project
     * @return array
     * @desc 计算收益
     */
    protected static function setIncomeBaseProfit( $project = array())
    {
        if( empty($project) ) return [];

        $cacheKey           =   sprintf(self::DEFAULT_INCOME_PROFIT,$project['id']);

        $statisticsCache= Cache::get($cacheKey);

        if( !empty($statisticsCache) ){

            return json_decode($statisticsCache,true);
        }

        $setIncomeBaseList  =   self::setIncomeBase();

        $incomeBaseProfit   =   [];

        foreach ($setIncomeBaseList as $key => $base){

            $incomeBaseProfit[$key]=[
                "base"      =>  $base,      //基数
                'profit'    =>  $base*(IncomeModel::getInterestPlan($project['profit_percentage'],$project['format_invest_time']))//收益
                ];
        }

        Cache::put($cacheKey,json_encode($incomeBaseProfit), 60*12);  //加入缓存

        return $incomeBaseProfit;
        
    }

    /**
     * @return array
     * @desc 获取每一个产品线最新的产品
     */
    protected static function getNewestProjectEveryType()
    {
        return ProjectModel::getNewestProjectEveryType();
    }

    /**
     * @return array
     * @desc 设置活动的产品线
     */
    protected static function setActivityProjectLine()
    {
        $config     =  self::setActivityConfig();

        $projectLine=   isset($config['ACTIVITY_PROJECT']) ? $config['ACTIVITY_PROJECT'] : self::DEFAULT_ACTIVITY_PROJECT;

        return explode(",",$projectLine);
    }

    /**
     * @return array
     * @desc 获取需要计算的收益基数
     */
    protected static function setIncomeBase()
    {
        $config         =   self::setActivityConfig();

        $incomeBase     =   isset($config['INCOME_BASE_CONFIG']) ? $config['INCOME_BASE_CONFIG'] : self::setDefaultIncomeBase();

        $incomeBase     =    explode(",",$incomeBase);

        $incomeBaseList =   [];

        foreach ($incomeBase as $key=> $base ){

            $baseCash   =   explode("=>",$base);

            $incomeBaseList[$baseCash[0]]=$baseCash[1];
        }
        return $incomeBaseList;
    }

    /**
     * @return float|int|string
     * @desc 定位奖品输出的名称
     */
    protected static function setVeryDayPrizeNumber()
    {
        $startTime       =  self::setStartTime();

        $endTime         =  self::setEndTime();

        $prizeNumber     =  count(self::setFormatLotteryConfig())-1;

        if( $startTime >= time() ){

            return "0";
        }

        if( $endTime <=  time() ){

            return $prizeNumber ;
        }

        $startDay       =   date('Y-m-d',$startTime);

        $nowDay         =   date("Y-m-d",time());

        $diffNumber     =   ToolTime::getDayDiff($startDay,$nowDay);

        if( $diffNumber <= $prizeNumber ){

            return $diffNumber;
        }

        return $diffNumber%$prizeNumber;
    }
    /**
     * @return array
     * @desc 解析中奖者数据
     */
    protected static function setFormatWinningUser()
    {
        $config         =   self::setActivityConfig();

        $winningConfig  =   explode(",",$config['LOTTERY_WINNER']);

        return array_filter($winningConfig);

    }

    /**
     * @return array
     * @desc 解析奖品数据
     */
    protected static function setFormatLotteryConfig()
    {
        $config             =   self::setActivityConfig();

        $lotteryConfig      =   explode(",",$config['LOTTERY_NAME_CONFIG']);

        return array_filter($lotteryConfig);

    }
    /**
     * @return array
     * @desc 判断抽奖的状态
     */
    public static function isCheckLotteryStatus($userId)
    {

        if( empty($userId) || $userId ==0){

            return self::callError('您还没有登录哦');
        }

        $startTime    =   self::setStartTime();

        $nowTime      =   time();

        if( $nowTime < $startTime ){

            return self::callError("双诞活动在".date('m.d',$startTime)."号准时开启!<br/>敬请期待!");
        }

        $endTime        =   self::setEndTime();

        if( $nowTime > $endTime ){

            return self::callError("双诞活动已经结束!<br/>谢谢参与!");
        }

        return self::isCanLotteryTimes($userId);

    }
    /**
     * @param $data
     * @return array
     * @desc 执行抽奖的程序
     */
    public function doLuckDraw( $data )
    {
        $lotteryLogic           =   new LotteryLogic();

        $data['activity_id']    =   ActivityFundHistoryDb::SOURCE_ACTIVITY_DOUBLE_FESTIVAL;

        $lotteryReturn          =   $lotteryLogic->doLuckDrawWithRate($data);

        if( $lotteryReturn['status'] == true && $lotteryReturn['data']['type'] != LotteryConfigDb::LOTTERY_TYPE_ENTITY ) {

            $bonusId        =   $lotteryReturn['data']['foreign_id'];

            $lotteryReturn['bonus']=self::getLotteryBonusInfo($bonusId);
        }

        return $lotteryReturn;
    }

    /**
     * @param int $bonusId
     * @return mixed
     * @desc 获取红包奖品的信息
     */
    protected static function getLotteryBonusInfo($bonusId = 0)
    {
        $bonusDb        =   new BonusDb();

        $bonusInfo      =   $bonusDb->getById($bonusId);

        if($bonusInfo['type'] == BonusDb::TYPE_COUPON_CURRENT){

            $formatBonusInfo=[
                'rate'      =>  (int)$bonusInfo['rate'],
                'money'     =>  (int)$bonusInfo['money'],
                'min_money' =>  (int)$bonusInfo['min_money'],
                'use_desc'  =>  '可用于投资零钱计划项目',
            ];

            return $formatBonusInfo;
        }

        if( $bonusInfo['type'] == BonusDb::TYPE_COUPON_INTEREST ){

            $formatBonusInfo=[
                'rate'      =>  (int)$bonusInfo['rate'],
                'money'     =>  (int)$bonusInfo['money'],
                'min_money' =>  (int)$bonusInfo['min_money'],
                'use_desc'  =>  '<p>定期加息券</p><p>可用于投资九省心</p><p>及九安心项目</p>',
            ];

            return $formatBonusInfo;
        }

        $formatBonusInfo=[
            'rate'      =>  (int)$bonusInfo['rate'],
            'money'     =>  (int)$bonusInfo['money'],
            'min_money' =>  (int)$bonusInfo['min_money'],
            'use_desc'  =>  '<p>满'.(int)$bonusInfo['min_money'].'可用</p><p>可用于投资九省心</p><p>及九安心项目</p>',
        ];

        return $formatBonusInfo;

    }

    /**
     * @param $userId
     * @return array
     * @desc 判断用户是否可以抽奖
     */
    public static function isCanLotteryTimes($userId)
    {
        $everyDayLotteryStatus  =   self::setEveryDayLotteryStatus();
        
        $lotteryEdNumber        =   self::getLotteryTimes($userId);
        
        $maxLotteryNumber       =   self::setMaxLotteryNumber();

        if( $everyDayLotteryStatus == true  && $lotteryEdNumber >= $maxLotteryNumber ){

            $msg        =   sprintf(LangModel::getLang('ERROR_ACTIVITY_LOTTERY_EVERY_DAY_TRAVEL'),$maxLotteryNumber);

            return self::callError($msg);

        }elseif( $lotteryEdNumber >= $maxLotteryNumber  ){

            $msg        =   sprintf(LangModel::getLang('ERROR_ACTIVITY_LOTTERY_ONLY_ONCE'),$maxLotteryNumber);

            return self::callError($msg);

        }

        return self::callSuccess();
    }

    /**
     * @return array
     * @desc 这里是这里是设置异常的App版本
     */
    protected static function setNotUsedAppWithVersion()
    {
        $config =   self::setActivityConfig();

        return  explode(",",$config['NOT_USED_APP_VERSION']);
    }
    /**
     * @param $userId
     * @return mixed
     * @desc  统计区间内的活动次数
     */
    protected static function getLotteryTimes( $userId )
    {
        $logic      =   new  LotteryRecordLogic();

        $lotteryTime=   self::setLotteryBetweenTime();

        $statistics     =   [
            'start_time'    =>  $lotteryTime['start'],
            'end_time'      =>  $lotteryTime['end'],
            'user_id'       =>  $userId,
            'activity_id'   =>  ActivityFundHistoryDb::SOURCE_ACTIVITY_DOUBLE_FESTIVAL,
        ];

        $recordList =   $logic->getRecordByConnection($statistics);

        return $recordList['lotteryNum'];
    }
    /**
     * @return array
     * @desc  根据配置设置统计的时间段
     */
    protected static function setLotteryBetweenTime()
    {
        $isEveryDayLottery =   self::setEveryDayLotteryStatus();

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
     * @return int|mixed
     * @desc 获取最大的抽奖次数(每个抽奖周期的最大次数)
     */
    protected static function setMaxLotteryNumber()
    {
        $config     =   self::setActivityConfig();

        $number     =   $config['EVERY_TIME_LOTTERY_MAX'];

        return  $number ? $number :1;
    }
    /**
     * @return bool
     * @desc 是否每天都可以抽奖条件
     */
    protected static function setEveryDayLotteryStatus()
    {
        $config     =   self::setActivityConfig();

        if( $config['IS_EVERY_LOTTERY'] == 1 ){
            
            return true;
        }
        
        return false;
    }
    /**
     * @return int
     * @desc 设置活动的结束时间
     * @desc 如果没有设置活动时间 则显示默认结束时间未当天的最后一刻
     * 确定是活动始终处于开启的状态
     */
    protected static function setEndTime()
    {
        $config     =   self::setActivityConfig();

        $timePoint  =   isset($config['END_TIME']) ? $config['END_TIME'] : date("Y-m-d",time());

        return ToolTime::getUnixTime($timePoint,'end');
    }
    /**
     * @return int
     * @desc 设置活动的开始时间
     * @desc 如果没有设置活动时间 则显示默认为前一天
     * 确定是活动始终处于开启的状态
     */
    protected static function setStartTime()
    {
        $config     =   self::setActivityConfig();

        $timePoint  =   isset($config['START_TIME']) ? $config['START_TIME'] : date("Y-m-d",strtotime("-1 day"));

        return ToolTime::getUnixTime($timePoint);
    }

    /**
     * @return array|mixed
     * @desc 设置活动的配置文件
     */
    protected static function setActivityConfig()
    {
        $config =   ActivityConfigModel::getConfig('DOUBLE_FESTIVAL_CONFIG');

        if( empty($config) ){

            return SystemConfigModel::getConfig('DOUBLE_FESTIVAL_CONFIG');
        }

        return $config;
    }

    /**
     * @return array
     * @desc 设置默认的奖品技术
     * 1=>65,2=>75,3=>2,4=>4,5=>6,6=>8,7=>20,8=>22
     */
    protected static function setDefaultIncomeBase()
    {
        return [
            1   =>  65,     //65万
            2   =>  75,     //75万
            3   =>  2,      //2万
            4   =>  4,      //4万
            5   =>  6,      //6万
            6   =>  8,      //8万
            7   =>  20,     //20万
            8   =>  22      //22万
        ];
    }
}
