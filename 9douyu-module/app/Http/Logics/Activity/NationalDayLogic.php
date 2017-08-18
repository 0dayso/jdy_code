<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/26
 * Time: 下午4:36
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Dbs\OrderDb;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;
use App\Tools\ToolTime;

class NationalDayLogic extends Logic
{
    const
        NATIONAL_ING         =   2,      //活动进行中
        NATIONAL_NO_START    =   1,      //活动未开始
        NATIONAL_END         =   3,       //活动已经结束

        NATIONAL_ACTIVITY_CACHE =   'NATIONAL_ACTIVITY'     //缓存标示
    ;

    /**
     * @return int
     * @desc 获取活动项目
     */
    public function getActivityProject()
    {

        $projectLine =   self::setProjectLine();

        array_filter($projectLine);

        $projectStr  =   implode(",",$projectLine);

        return ProjectModel::getAppointJsxProject($projectStr);

    }

    /**
     * @param $data
     * @return array
     * @desc 执行抽奖的程序
     */
    public function doLuckDraw( $data )
    {
        $lotteryLogic   =   new LotteryLogic();

        $data['activity_id']    =   ActivityFundHistoryDb::SOURCE_ACTIVITY_NATIONAL;

        return $lotteryLogic->doLuckDrawWithoutRate($data);
    }

    /**
     * @param $userId
     * @return array
     * @desc 抽奖的充值条件
     */
    public function isCheckLotteryInvestStatus( $userId )
    {
        $recharge   =   $this->getUserRechargeTotal($userId);

        $investInfo =   $this->getUserInvestProject($userId);

        if( empty($recharge['cash']) || $investInfo['investTotal']<=0 ){

            return self::callError('单笔投资定期≥5万,即可参与抽奖!100%中奖');
        }

        $minInvestCash  =   $this->setMinInvestCash();

        $satisfyTravel  =   (int)floor($recharge['cash']/$minInvestCash);

        $satisfyTravel  =   min($satisfyTravel , $investInfo['investTotal']);

        $lotteryTravel  =    $this->getUserLotteryTotal($userId);

        if( $satisfyTravel <= $lotteryTravel['lotteryNum'] ){

            return self::callError('单笔投资定期≥5万,即可参与抽奖!100%中奖');
        }

        $return         =   ["type"=>'doLuck','msg' =>$satisfyTravel - $lotteryTravel['lotteryNum']];

        return self::callSuccess($return);
    }

    /**
     * @param $userId
     * @return array|null|void
     * @desc 获取用户在活动期间的充值金额
     */
    public function getUserRechargeTotal( $userId )
    {
        $startTime      =   $this->setStartTime();

        $endTime        =   $this->setEndTime();

        $statistics     =   [
            'status'    =>  OrderDb::STATUS_SUCCESS,
            'start_time'=>  date("Y-m-d H:i:s",$startTime),
            'end_time'  =>  date("Y-m-d H:i:s",$endTime),
            'userId'   =>  $userId,
        ];

        $rechargeTotal      =   OrderModel::getRechargeStatistics($statistics);

        return $rechargeTotal;
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 单笔满5W的投资条件
     * '
     */
    public function getUserInvestProject( $userId )
    {
        $startTime      =   $this->setStartTime();

        $endTime        =   $this->setEndTime();

        $statistics     =   [
            'start_time'=>  date("Y-m-d H:i:s",$startTime),
            'end_time'  =>  date("Y-m-d H:i:s",$endTime),
            'user_id'   =>  $userId,
            'base_cash' =>  $this->setMinInvestCash(),
            'p_ids'     =>  $this->getProjectIdsStatistics(),
        ];

        $investLogic    =   new TermLogic();

        $investTotal    =   $investLogic->getInvestStatistics($statistics);

        return $investTotal;

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 用户已经使用的抽奖次数
     */
    public function getUserLotteryTotal( $userId )
    {
        $logic          =   new LotteryRecordLogic();

        $startTime      =   $this->setStartTime();

        $endTime        =   $this->setEndTime();

        $statistics     =   [
            'start_time'=>  date("Y-m-d H:i:s",$startTime),
            'end_time'  =>  date("Y-m-d H:i:s",$endTime),
            'user_id'       =>  $userId,
            'activity_id'   =>  ActivityFundHistoryDb::SOURCE_ACTIVITY_NATIONAL,
        ];
        
        return $logic->getRecordByConnection($statistics);
    }
    /**
     * @return array
     * @desc 判断抽奖的状态
     */
    public function isCheckLotteryStatus($userId)
    {

        if( empty($userId) || $userId ==0){

            return self::callError('您还未登录!请登录收操作');
        }

        $startTime    =   $this->setStartTime();

        $nowTime      =   time();

        if( $nowTime < $startTime ){

            return self::callError("国庆节活动在".date('m.d',$startTime)."号准时开启!<br>敬请期待!");
        }

        $endTime        =   $this->setEndTime();

        if( $nowTime > $endTime ){

            return self::callError('国庆节抽奖活动已经结束!<br>谢谢参与!');
        }

        return self::callSuccess();
    }

    /**
     * @return int
     * @desc 活动活动的项目id集合
     */
    protected function getProjectIdsStatistics()
    {
        $startTime  =   date("Y-m-d H:i:s",($this->setStartTime()-86400*7));

        $endTime    =   date("Y-m-d H:i:s",$this->setEndTime());

        $projectType=   implode(",",$this->setProjectLine());

        return ProjectModel::getProjectIdsStatistics($startTime,$endTime,$projectType);
    }
    /**
     * @return array
     * @desc 活动项目类型
     */
    public  function setProjectLine()
    {
        $config     =   self::getNationalConfig();

        return  explode(",",$config['ACTIVITY_PROJECT']);
    }
    /**
     * @return int
     * @desc 开始时间
     */
    public function setStartTime()
    {
        $config     =   self::getNationalConfig();

        return ToolTime::getUnixTime($config['START_TIME']);
    }

    /**
     * @return int
     * @desc 结束时间时间
     */
    public function setEndTime()
    {
        $config     =   self::getNationalConfig();

        return ToolTime::getUnixTime($config['END_TIME'],'end');
    }
    /**
     * @return bool|mixed
     * @desc 活动的配置文件
     */
    protected static function getNationalConfig()
    {
        $config     =   ActivityConfigModel::getConfig('NATIONAL_ACTIVITY');

        if( empty($config) ){

            return  SystemConfigModel::getConfig('NATIONAL_ACTIVITY');
        }

        return $config;
    }

    /**
     * @desc 获取活动的状态
     * @return int
     */
    public function getNationDayStatus(){

        if(time() < $this->setStartTime()){

            //未开始
            $status = self::NATIONAL_NO_START;

        }elseif(time() > $this->setEndTime()){

            //已结束
            $status = self::NATIONAL_END;

        }else{

            //进行中
            $status = self::NATIONAL_ING;
        }

        return $status;
    }
    /**
     * @return int
     * @抽奖的最小金额(单笔)
     */
    public function setMinInvestCash()
    {
        $config     =   self::getNationalConfig();

        return isset($config['MIN_INVEST_CASH']) ?  $config['MIN_INVEST_CASH'] : 50000;
    }
}