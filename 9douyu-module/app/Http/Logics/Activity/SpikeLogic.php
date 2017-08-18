<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/5
 * Time: 下午5:17
 */

namespace App\Http\Logics\Activity;


use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolTime;

class SpikeLogic extends Logic
{

    const
        ACTIVITY_ONGOING    =   2,      //活动进行中
        ACTIVITY_NOSTART    =   1,      //活动未开始
        ACTIVITY_ENDED      =   3       //活动已经结束

    ;

    /*
     * 读取秒杀活动的配置
     */
    public static function defineConfig()
    {
        $config     =   ActivityConfigModel::getConfig('SECOND_KILL_ACTIVITY');

        if( empty($config) ){
//            return [
//                //活动开始时间
//                "START_TIME"    =>  self::setDefaultStartTime(),
//                //活动结束时间
//                "END_TIME"      =>  self::setDefaultEndTime(),
//                //高息区域的项目
//                "HIGH_RATE"     =>  "six",
//                //低息区域的项目
//                "LOW_RATE"      =>  "three",
//                //秒杀的时间点
//                "POINT_TIME"    =>  "10:00,14:00,20:00"
//            ];
            return SystemConfigModel::getConfig('SECOND_KILL_ACTIVITY');
        }
        return $config;
    }

    /**
     * @return array
     * @desc 活动活动项目数据
     */
    public static function getSpikeActivityProject()
    {
        $projectList        =   self::getNewestProjectEveryType();

        return self::dataClassification($projectList);

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
     * @desc 获取加息秒杀的项目
     * @author linguanghui <lin.guanghui@9douyu.com>
     * @param $killActivityConfig array 活动配置
     * @param $killTime string 秒杀时间
     * @param $time array 项目类型
     * date 2016-06-29 Time 15:06 PM
     * @return array
     */
    public static function getSecondKillProject(){
        $status         =   self::setSpikeStatus();
        $projectList    =   [];
        switch ($status){
            case self::ACTIVITY_ONGOING;

                //活动中
                $projectList=   self::getActivityProject();
                break;
            case self::ACTIVITY_NOSTART;

                //活动未开始
                $projectList=   self::getActivityProject();
                break;
            case self::ACTIVITY_ENDED;

                //活动已结束
                $projectList=   self::setNotInActivityTimeParam();
                break;
        }
        return self::dataClassification($projectList);
    }

    /*
     * 读取秒杀的项目
     * @return array $project
     */
    public static function getActivityProject()
    {
        //定点时间
        $publishTimes   =   self::formatSpikeTime();
        //项目期数
        $investTimes    =   self::setActivityProjectPeriod();

        $investTime     =   implode(",",$investTimes);
        //获取项目
        $projectList    =   ProjectModel::getTimingProject($publishTimes,$investTime);

        return   $projectList ;

    }

    /**
     * 获取下一次秒杀时间点,跳秒的条件
     * @return $string time;
     */
    public static function setNextSpikeTime()
    {
        $status         =   self::setSpikeStatus();

        $spikeTime      =   self::getSpikeTime();

        $currentTime    =   date("Y-m-d",time());

        $nextTime       =   "";
        switch ($status){

            case self::ACTIVITY_NOSTART;

                //活动未开始
                $nextTime   =   $currentTime." ".current($spikeTime);
                break;
            case self::ACTIVITY_ONGOING;

                //活动中
                $nextTime   =   self::setNextSpikeTimePoint();

                break;
            case self::ACTIVITY_ENDED;

                //活动已结束
                $nextTime   =   $currentTime." ".end($spikeTime);;
                break;
        }
        return strtotime($nextTime);
    }

    /***************************************数据处理部分*************************************************/

    /*
     * 检测当前秒杀的状态
     */
    public static function checkProjectPublishTime( $project )
    {
        if( self::setSpikeStatus() != self::ACTIVITY_ONGOING ){

            return ['status'=>true];
        }

        $projectTime    =   self::getProjectPublishTime($project);

        $pointTime      =   self::setFormatPointTime();

        if( !empty($projectTime) && in_array($projectTime ,$pointTime)){

            return ['status'=>false,'msg'=>'秒杀项目不支持使用红包和加息券'];
        }

        return [ 'status'=>true ];
    }

    /*
    * 对数据进行分类
    * @return $projectList
    */
    protected static function dataClassification( $projectList )
    {
        if( empty($projectList) ){

            return [];
        }

        $highGroup      =   self::setHighRateGroup();

        $lowGroup       =   self::setLowRateGroup();

        $returnArr      =   [];
        foreach ( $projectList as $key  =>  $project ){

            //加息高的部分
            if( in_array($key,$highGroup) ){
                $returnArr['high'][$key]   =   $project;
                //$returnArr['high']   =   $project;
            }

            //加息低的部分
            if( in_array($key,$lowGroup) ){
                $returnArr['low'][$key]    =   $project;
            }

        }
        return $returnArr;
    }

    /*
     * 返回当前秒杀的时间点
     * @param 活动时间内的秒杀时间点
     * @return $spikeTime
     */
    public static function setSpikeTimePoint()
    {

        $nowDate        =   date("Y-m-d",time());

        $spikeTimePoint =   self::getSpikeTime();

        //第一个秒杀的时间点
        $firstTime      =   current($spikeTimePoint);

        if( strtotime($nowDate." ".$firstTime) >= time() ){

            return $firstTime;
        }

        //最后一个秒杀的时间点
        $lastTime       =   end($spikeTimePoint);

        if(  strtotime($nowDate." ".$lastTime) <= time() ){

            return $lastTime;
        }
        //进行中的秒杀时间点
        $pointTimeNum   =   count($spikeTimePoint)-1;

        for( $i=0; $i < $pointTimeNum; $i++ ){

            $nextNum    =   $i+1;

            $pointTime  =   strtotime($nowDate." ".$spikeTimePoint[$i]);

            $nextTime   =   strtotime($nowDate." ".$spikeTimePoint[$nextNum]);

            if( $pointTime < time() && $nextTime >time()){
                //活动的时间点
                return $spikeTimePoint[$i];
            }
        }

    }

    /**
     * 获取下一个秒杀的时间点
     * @param  活动时间内下一个秒杀的时间点
     */
    protected static function setNextSpikeTimePoint()
    {
        $nowDate        =   date("Y-m-d",time());

        $spikeTimePoint =   self::getSpikeTime();

        //第一个秒杀的时间点
        $firstTime      =   current($spikeTimePoint);

        //始终每天第一个秒杀的时间点
        if( strtotime($nowDate." ".$firstTime) >= time() ){

            return  $nowDate." ".$firstTime;
        }

        $lastTime       =   end($spikeTimePoint);

        if(  strtotime($nowDate.' '.$lastTime) < time() ){

            return  date("Y/m/d ",strtotime("+1 day"))." ".$firstTime;
        }

        $pointTimeNum   =   count($spikeTimePoint)-1;
        //下一个时间点
        for( $i=0; $i < $pointTimeNum; $i++ ){

            $nextNum    =   $i+1;

            $pointTime  =   strtotime($nowDate." ".$spikeTimePoint[$i]);

            $nextTime   =   strtotime($nowDate." ".$spikeTimePoint[$nextNum]);

            if( $pointTime < time() && $nextTime >time()){
                //活动的时间点
                return $nowDate." ".$spikeTimePoint[$nextNum];
            }
        }
    }

    /**
     * @desc 检查是否活动的最后一次秒
     * @return int;
     */
    public function checkLastSpikeTime()
    {
        $endTime    =   date("Y-m-d",self::getEndTime());

        $spikeTime  =   self::getSpikeTime();

        $lastTime   =   strtotime($endTime." ".end($spikeTime));

        $isLast     =   false;

        if( $lastTime < time() ){

            $isLast =   true;
        }
        return $isLast;
    }

    /***************************************参数部分*************************************************/

    /**
     * 获取高息的项目期限组合
     * @return array
     */
    protected static function setHighRateGroup()
    {
        $config     =   self::defineConfig();

        return array_filter(explode(",",$config['HIGH_RATE']));
    }

    /**
     *  获取低息的项目期限组合
     * @return array
     */
    protected static function setLowRateGroup()
    {
        $config     =   self::defineConfig();

        return array_filter( explode(",",$config['LOW_RATE']) );
    }

    /**
     * @return date $times
     * @desc 当前的秒杀的时间点
     */
    public static function formatSpikeTime()
    {
        $pointTime   =   self::setSpikeTimePoint();

        $nowDate     =   date("Y-m-d",time());

        return date("Y-m-d H:i:s",strtotime($nowDate." ".$pointTime));
    }

    /*
     * @获取当前所有的秒杀时间点
     */
    public static function getProjectPublishTime( $project )
    {
        if( !empty( $project ) ){

            return strtotime($project['publish_at']);

        }

        return false;
    }
    /*
     * 不在活动时间的数据格式
     * @return $projectList
     */
    public static function setNotInActivityTimeParam()
    {
        $projectWay     =   self::setActivityProjectPeriod();

        $returnParam    =   [];

        foreach ($projectWay as $key => $period  ){

            $returnParam[$period]   =   [];
        }
        return $returnParam;
    }


    /*
     * 秒杀活动项目期数
     * @return $projectWay
     */

    public static function setActivityProjectPeriod()
    {
        $config     =   self::defineConfig();

        $paramArr   =   explode(",",$config['HIGH_RATE'].",".$config['LOW_RATE']);

        return  array_filter($paramArr);
    }
    /*
     * 秒杀活动的状态值
     * @return int $status
     */
    public static function setSpikeStatus()
    {
        $currentTime    =   time();

        $startTime      =   self::getStartTime();

        $endTime        =   self::getEndTime();

        if( $currentTime >  $endTime ){
            //活动已经结束
            $status     =   self::ACTIVITY_ENDED;

        }elseif($currentTime < $startTime){
            //活动未开始
            $status     =   self::ACTIVITY_NOSTART;

        }else{
            //活动进行中
            $status     =   self::ACTIVITY_ONGOING;

        }

        return $status;
    }

    /*
     * 获取秒杀的时间点,并进行排序
     * @return array
     */
    public static function getSpikeTime()
    {
        $config   =   self::defineConfig();

        $times    =   explode(",",$config['POINT_TIME']);

        array_multisort($times,SORT_NUMERIC);

        return $times;
    }

    /*
     * 格式所有的秒杀时间点
     */
    public static function setFormatPointTime()
    {
        $pointTimes     =   self::getSpikeTime();

        $nowDate        =   date('Y-m-d',time());

        $returnTime     =   [];

        foreach ($pointTimes as $key =>  $time ){

            $returnTime[$key]   =   strtotime($nowDate.' '.$time);
        }

        return $returnTime;
    }

    /*
     * 获取开始时间的时间戳
     * @return unix time
     */
    public static function getStartTime()
    {
        $config     =   self::defineConfig();

        return ToolTime::getUnixTime($config['START_TIME']);
    }

    /*
     * 获取结束的时间戳
     * @return unix time
     */

    public static function getEndTime()
    {
        $config     =   self::defineConfig();

        return ToolTime::getUnixTime($config['END_TIME'],'end');
    }

    /*
     * 设置活动的默认开始时间
     */
    protected static function setDefaultStartTime()
    {
        return date("Y-m-d",strtotime("-4 day"));
    }

    /*
     * 设置活动默认结束时间
     */
    protected static function setDefaultEndTime()
    {
        return date("Y-m-d",strtotime("-1 day"));
    }
}