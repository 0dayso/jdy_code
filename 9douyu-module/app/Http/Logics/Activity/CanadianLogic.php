<?php

/** ****************************** 加币活动的LOGIC层 ******************************
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/02/10
 * Time: 上午10:01
 */
namespace  App\Http\Logics\Activity;

use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\Logic;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Tools\ToolTime;
use Swagger\Analysis;

class CanadianLogic extends Logic
{


    private static $objectExample;  //数据对象

    /**
     * @return mixed
     * @desc  活动的项目
     */
    public static function getProject()
    {
        $config         =   self::config();

        $projectList    =    ProjectLogic::getActivityProject($config['ACTIVITY_PROJECT']);

        if( empty($projectList) ) {

            return[];
        }
        foreach ($projectList as $key => &$project ) {

            $project['act_token']    =  self::getActToken () . "_" . $project['id'] ;
        }

        return $projectList;
    }
    public static function getActToken()
    {
        return   time() . '_' . self::setActivityEventId() ;
    }
    /**
     * @return mixed
     * @desc 文字说明
     */
    public static function getProjectLineNote()
    {
        $config =   self::config();

        return $config['PROJECT_LINE_NOTE'];
    }
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
     * @return array
     * @desc 读取奖励金额
     */
    public static function getAwardConfig()
    {
        $projectLine =   self::setProjectLine();

        if( empty($projectLine) ){

            return [];
        }

        $returnArr  =   [];

        foreach ($projectLine as $key  =>  $Line ){

            $returnArr[$Line]   =   self::setInvestAwardConfig($Line);
        }

        return $returnArr;
    }
    /**
     * @param int $userId
     * @return mixed
     * @desc 获取用户充值投资的有效金额
     */
    public static function getUserEffectiveAmount($userId = 0 )
    {
        $rechargeAmount =   self::setRechargeSumCashByUserId($userId);

        $investAmount   =   self::setInvestSumCashByUserId($userId);

        return min($rechargeAmount,$investAmount);
    }
    /**
     * @param int $userId
     * @return int
     * @desc 获取用户有效的充值金额
     */
    protected static function setRechargeSumCashByUserId( $userId = 0 )
    {
        if( empty($userId) || $userId == 0 ){

            return 0;
        }

        $timeArray  =   self::setTime();

        $startTime  =   date("Y-m-d H:i:s",$timeArray['start']);

        $endTime    =   date('Y-m-d H:i:s',$timeArray['end']);

        $params     =   [
            'start_time'    =>  $startTime,
            'end_time'      =>  $endTime,
            'userId'        =>  $userId,
        ];

        $recharge   =   OrderModel::getRechargeStatistics($params);

        return isset($recharge['cash']) ? (int)$recharge['cash'] : 0;
    }

    /**
     * @param int $userId
     * @return int
     * @desc 投资的有效金额
     */
    protected static function setInvestSumCashByUserId( $userId = 0)
    {
        if( empty($userId) || $userId == 0 ){

            return 0;
        }

        $useBonusStatus =   self::setUseBonusStatus();

        $timeArray      =   self::setTime();

        $params             =   [
            'user_id'       =>  $userId,
            'start_time'    =>  date("Y-m-d H:i:s",$timeArray['start']),
            'end_time'      =>  date('Y-m-d H:i:s',$timeArray['end']),
            'p_ids'         =>  self::getProjectIdsStatistics(),
        ];

        if( $useBonusStatus == false ){

            $params['bonusId'] = '0';
        }

        $investLogic    =   new TermLogic();

        $investMsg      =   $investLogic->getInvestStatistics($params);

        return (int) $investMsg['investTotal'];
    }

    /**
     * @param string $awardKey
     * @return array
     * @desc 解析加币的金额和奖金
     */
    protected static function setInvestAwardConfig($awardKey = 'six')
    {
        $config     =   self::config();

        $configKey  =   'AMOUNT_'.strtoupper($awardKey).'_AWARD';

        $awardStr   =   isset($config[$configKey]) ? $config[$configKey] : '';

        if( empty($awardStr)){

            return [];
        }

        $returnArr  =   [];

        foreach (explode(',',$awardStr) as $key => $value ){

            $configArr  =   explode('|',$value);

            $returnArr[]   = ['base'=>$configArr[0],'award'=>$configArr[1]]  ;
        }

        return $returnArr;
    }
    /**
     * @return mixed
     * @desc 投资是否可以使用红包
     */
    protected static function setUseBonusStatus()
    {
        $config     =   self::config();

        return $config['IS_CAN_USED_BONUS'];
    }

    /**
     * @return int
     * @desc 活动活动的项目id集合
     */
    protected static function getProjectIdsStatistics()
    {
        $timeArray      =   self::setTime();

        $startTime      =   date("Y-m-d H:i:s",$timeArray['start']-86400*7);

        $endTime        =   date('Y-m-d H:i:s',$timeArray['end']);

        $projectLine    =   self::setProjectLine();

        $projectIds     =   [];

        $projectAllIds  =   ProjectModel::getAllProjectIdByTime($startTime,$endTime);

        foreach ($projectAllIds as $key => $projectIdArr ){

            if( in_array($key,$projectLine) ){

                $projectIds =   array_merge($projectIds,$projectIdArr);
            }
        }

        return $projectIds;
    }
    public static function setActivityEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_GOLD_CHEST;
    }

    /**
     * @return mixed
     * @desc 获取活动的项目id
     */
    private static function setProjectLine()
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

            self::$objectExample = new AnalysisConfigLogic('ACTIVITY_INVEST_CANADIAN');
        }

        return self::$objectExample;
    }



}
