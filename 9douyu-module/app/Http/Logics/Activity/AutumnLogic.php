<?php
/**
 * Created by PhpStorm.
 * User: scofie
 * Date: 7/31/17
 * Time: 4:29 PM
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Activity\Common\ActivityLogic;
use App\Http\Logics\Activity\Common\AnalysisConfigLogic;
use App\Http\Logics\Activity\Statistics\ActivityStatisticsLogic;
use App\Tools\ToolArray;

class AutumnLogic extends ActivityLogic
{

    const
        GRADE_LOTTERY_LIST  =   'GRADE_LOTTERY_LIST_CACHE' ,
        DEFAULT_DRAW_NUMBER =   3,

        END     =   true ;


    public static function getActivityTime ()
    {
        $config     =   self::config ();

        return self::getTime ( $config['START_TIME'] , $config['END_TIME'] ) ;
    }


    /**
     * @return mixed
     * @desc created activity project list data
     */
    public static function getActivityProjectList()
    {
        $projectList    =   self::getProject(self::config ()['ACTIVITY_PROJECT']) ;

        if( !empty($projectList) ) {

            foreach ($projectList as $key => &$project ) {

                $project['act_token']    =  self::setActivityActToken () .  "_" . $project['id'] ;
            }
        }
        return $projectList;
    }


    /**
     * @param int $userId
     * @return Common\error|Common\result|Common\success
     * @desc valid user join activity time && login status
     */
    public static function validTimeCondition( $userId=0 )
    {
        $config     =   self::config();

        return self::isCanJoinActivity( $config['START_TIME'] , $config['END_TIME'] , self::setActivityActToken() , $userId );
    }

    /**
     * @param $gradeLevel
     * @return array
     * @desc valid lottery jackpot
     */
    public static function isValidGradeLevel( $gradeLevel )
    {
        $gradeLevelArray    =   array_keys ( self::setGradeLotteryConfig() );

        if( !in_array ( $gradeLevel , $gradeLevelArray) ) {

            return self::callError ('无效的奖池等级') ;
        }

        return self::callSuccess () ;
    }

    /**
     * @param int $grade
     * @return mixed
     * @desc get grade lottery group
     */
    public static function getGradeLotteryGroup ( $grade=1 )
    {
        $config =   self::setGradeLotteryConfig () ;

        return $config[$grade]['lottery_group'] ;
    }

    /**
     * @param int $userId
     * @param int $gradeLevel
     * @return array
     * @desc 返回用户抽奖的条件
     */
    public static function validUserLotteryCondition( $userId=0 , $gradeLevel = 1 )
    {
        $validNumber            =    self::getUserInvestToLotteryTimes( $userId ) ;

        if( $validNumber <= 0 || empty($validNumber) ) {

            return self::callError( self::setGradeWarningMessage ($gradeLevel) );
        }

        $userLotteryTotal       =   self::getLotteryTotalByUserId($userId) ;

        if( $validNumber <= $userLotteryTotal ) {

            return self::callError( '十分抱歉，您的抽奖机会已经用完！');
        }

        $actTokenStaticsId  =   self::getUserActInRecordByBaseCashLimitOne($userId ,$gradeLevel) ;

        if( !$actTokenStaticsId ) {

            return self::callError(self::setGradeWarningMessage ($gradeLevel));
        }

        return self::callSuccess( ['statics_id' => $actTokenStaticsId ]);
    }

    /**
     * @param $gradeLevel
     * @return string
     * @desc 格式化不满足抽奖的提示
     */
    protected static function setGradeWarningMessage( $gradeLevel )
    {
        $config =   self::setGradeLotteryConfig () ;

        return '单笔投资在' . $config[$gradeLevel]['min_invest']/10000 . '万！可参与' . $config[$gradeLevel]['grade_name'] . '奖池抽奖！' ;
    }

    /**
     * @param $userId
     * @param int $gradeLevel
     * @return array
     * @desc search activity statistics record
     */
    protected static function getUserActInRecordByBaseCashLimitOne($userId ,$gradeLevel=1)
    {
        $config     =   self::config ();

        $params     =   [
            'start_time'    =>  date( 'Y-m-d H:i:s' ,$config['START_TIME']) ,
            'end_time'      =>  date( 'Y-m-d H:i:s' ,$config['END_TIME']) ,
            'user_id'       =>  $userId ,
            'act_id'        =>  self::setActivityEventId () ,
            'base_cash'     =>  self::getMinInvestCashByLevel($gradeLevel) ,
        ];

        return ActivityStatisticsLogic::getUserActInRecordByBaseCashLimitOne($params) ;
    }

    /**
     * @param $gradeLevel
     * @return int
     * @desc get min invest cash by grade level
     */
    protected static function getMinInvestCashByLevel($gradeLevel)
    {
        $gradeConfig    =   self::setGradeLotteryConfig();

        return  isset($gradeConfig[$gradeLevel]) ? $gradeConfig[$gradeLevel]['min_invest'] : 5000;
    }
    /**
     * @param $userId
     * @return mixed
     * @desc search user valid invest times
     */
    protected static function getUserInvestToLotteryTimes( $userId , $gradeLevel=1 ,$status=false)
    {
        $config         =   self::config();

        $timeArr        =   self::setReceiveBetweenTime( $config['START_TIME'] , $config['END_TIME'] );

        $startTime      =   strtotime( $timeArr['start'] );

        $endTime        =   strtotime( $timeArr['end'] );

        $baseCash       =   self::getMinInvestCashByLevel($gradeLevel) ;

        return self::getUserActInRecordByBaseCash( $userId , self::setActivityEventId() , $startTime , $endTime , $baseCash ,$status);
    }

    /**
     * @param int | user_id
     * @return can lottery number
     * @desc search user left lottery times
     */
    public static function getLotteryTotalByUserId( $userId=0 )
    {
        $config         =   self::config();

        $timeArr        =   self::setReceiveBetweenTime( $config['START_TIME'] , $config['END_TIME'] );

        $startTime      =   strtotime( $timeArr['start'] );

        $endTime        =   strtotime( $timeArr['end'] );

        return  self::getUserLotteryInfo( $startTime , $endTime , self::setActivityEventId() , $userId );
    }
    /**
     * @param int $userId
     * @param int $baseCash
     * @return int
     * @desc search user used lottery times
     */
    protected static function getUserLotteryTotal( $groupId  ,$userId = 0 )
    {

        if( $userId == 0 || empty($userId) ) { return 0; }

        $config             =   self::config();

        $lotteryRecordLogic =   new LotteryRecordLogic();

        $lotteryLogic       =   new LotteryConfigLogic();

        $lotteryList        =   $lotteryLogic->getLotteryByGroup( $groupId );

        $statistics         =   [
            'user_id'       =>  $userId ,
            'activity_id'   =>  self::setActivityEventId() ,
            'start_time'    =>  date('Y-m-d H:i:s' , $config['START_TIME']) ,
            'end_time'      =>  date('Y-m-d H:i:s' , $config['END_TIME']) ,
        ];

        if( !empty($lotteryList) ) {

            $statistics['prizes_id']     =   array_column( $lotteryList , 'id' );
        }

        $lotteryRecordList  =   $lotteryRecordLogic->getRecordByConnection( $statistics );

        return (int)$lotteryRecordList['lotteryNum'];
        }
    /**
     * @return array
     * @desc 设置活动的奖池和奖品的信息
     */
    protected static function setGradeLotteryConfig()
    {
        $config     =   self::config();

        $gradeArr   =   explode( "|" , $config['USER_GRADE_LOTTERY_GROUP'] );

        $gradeConfig=   [];

        if( !empty($gradeArr) ) {
            foreach ( $gradeArr as $key => $gradeStr ) {

                $explodeArr     =   explode( "=>" , $gradeStr );

                $explodeMsg     =   explode( "," , $explodeArr['1'] );

                $gradeConfig[$explodeArr[0]] = [
                    'lottery_group' =>  $explodeMsg[0] ,  //奖品分组
                    'grade_level'   =>  $explodeArr[0] ,  //奖池等级
                    'min_invest'    =>  $explodeMsg[1] ,  //最小充值金额
                    'grade_name'    =>  $explodeMsg[2] ,  //奖池名称
                ];
            }
        }

        ksort($gradeConfig);

        return  $gradeConfig;
    }

    /**
     * @return mixed
     * @desc invest use bonus
     */
    protected  function setUseBonusStatus()
    {
        return self::config()['INVEST_WITH_BONUS'];
    }

    /**
     * @return string
     * @desc act_token only label
     */
    public static function setActivityActToken()
    {
        return time() . '_' . self::setActivityEventId();
    }
    /**
     * @return int
     * @desc set activity only event id
     */
    public static function setActivityEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_AUTUMN_LOTTERY ;
    }
    /**
     * @return Common\格式化后的活动配置文件|array
     * @desc make activity config
     */
    private static function config ()
    {
        return AnalysisConfigLogic::make ('ACTIVITY_AUTUMN_CONFIG');
    }
}