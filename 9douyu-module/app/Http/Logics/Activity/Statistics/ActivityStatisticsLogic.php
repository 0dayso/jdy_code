<?php
/**
 * Created by 9douyu Coder.
 * User: scofie wu.changming@9douyu.com
 * Date: 09/05/2017.
 * Time: 4:17 PM.
 * Desc: ActivityStatisticsLogic.php.
 */
namespace App\Http\Logics\Activity\Statistics;
use App\Http\Dbs\Activity\ActivityStatisticsDb;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityStatisticsModel;
use App\Http\Models\User\UserModel;
use Log;

class ActivityStatisticsLogic extends Logic
{
    /**
     * @param array $data
     * @desc  记录数据
     */
    public static function checkInRecord( $data =[] )
    {
        $actModel   =   new ActivityStatisticsModel() ;

        try{

            self::beginTransaction() ;

            //检测用户是否存在
            UserModel::getUserInfo($data['user_id']) ;
            
            //检测投资记录在不在
            $actModel->checkInvest( $data ) ;

            $actNote    =   $actModel->checkActType( $data['act_id'] ) ;

            //添加数据
            $actModel->inRecord(self::doFormatVariable( $data ,$actNote)) ;

            self::commit() ;

        }catch (\Exception $e){

            self::rollback() ;

            Log::error(__METHOD__.'Error',[ 'msg' => $e->getMessage() , 'code' => $e->getCode()] ) ;

            return self::callError($e->getMessage()) ;
        }

        return self::callSuccess() ;
    }

    /**
     * @param array $params
     * @desc  格式化数据
     */
    protected  static function  doFormatVariable( $params = [] ,$note = '' )
    {
        return [
            'user_id'  =>   isset( $params['user_id'] ) ? $params['user_id'] : 0 ,
            'act_id'   =>   isset( $params['act_id'] ) ? $params['act_id'] : 0 ,
            'invest_id'=>   isset( $params['invest_id'] ) ? $params['invest_id'] : 0 ,
            'project_id'=>  isset( $params['project_id'] ) ? $params['project_id'] : 0 ,
            'cash'     =>   isset( $params['cash'] ) ? $params['cash'] : '0.00' ,
            'note'     =>   isset( $params['note'] ) ? $params['note'] : $note ,
        ];
    }

    /**
     * @param int $userId
     * @desc  通过用户活动信息
     */
    protected static function getByUserId( $userId = 0 )
    {
        if( empty($userId) ) {

            return [];
        }

        $actDb   =   new ActivityStatisticsDb() ;

        return $actDb->getByUserId( $userId ) ;
    }

    /**
     * @param int $actId
     * @desc 通过活动Id
     */
    public static function getByActId( $actId = 0 )
    {
        if( empty($actId) ) {

            return [];
        }
        $actDb   =   new ActivityStatisticsDb() ;

        return $actDb->getByActId( $actId ) ;
    }

    /**
     * @param $userId
     * @param $actId
     * @param $startTime timestamp
     * @param $endTime timestamp
     * @desc 读取用在某个活动中的数据
     */
    public static function getUserActInRecord( $userId , $actId , $startTime , $endTime )
    {
        if( empty($userId) || empty($actId) || empty($startTime) || empty($endTime) ){

            return [];
        }

        $actDb      =   new ActivityStatisticsDb() ;

        $params     =   [
            'start_time'    =>  date( 'Y-m-d H:i:s' ,$startTime) ,
            'end_time'      =>  date( 'Y-m-d H:i:s' ,$endTime) ,
            'user_id'       =>  $userId ,
            'act_id'        =>  $actId
        ];

        return $actDb->getUserActInRecord( $params ) ;
    }

    /**
     * @param $actId
     * @param $startTime
     * @param $endTime
     * @param int $limit
     * @desc 排名
     */
    public static function getUserCheckInActRanking( $actId , $startTime , $endTime ,$limit =5 )
    {
        if(  empty($actId) || empty($startTime) || empty($endTime) ) {

            return [];
        }

        $actDb      =   new ActivityStatisticsDb() ;

        $params     =   [
            'start_time'    =>  date( 'Y-m-d H:i:s' ,$startTime) ,
            'end_time'      =>  date( 'Y-m-d H:i:s' ,$endTime) ,
            'act_id'        =>  $actId ,
            'limit'         =>  $limit
        ];

        return $actDb->getUserCheckInActRanking( $params ) ;
    }

    /**
     * @param $userId
     * @param $actId
     * @param $startTime
     * @param $endTime
     * @param int $baseCash
     * @return array
     * @desc search user join activity statistics
     */
    public static function getUserActInRecordByBaseCash( $userId , $actId , $startTime , $endTime ,$baseCash = 1000 ,$status=false)
    {
        if( empty($userId) || empty($actId) || empty($startTime) || empty($endTime) ){

            return [];
        }

        $actDb      =   new ActivityStatisticsDb() ;

        $params     =   [
            'start_time'    =>  date( 'Y-m-d H:i:s' ,$startTime) ,
            'end_time'      =>  date( 'Y-m-d H:i:s' ,$endTime) ,
            'user_id'       =>  $userId ,
            'act_id'        =>  $actId ,
            'base_cash'     =>  $baseCash
        ];

        if( $status == true ) {
            $params['status'] == $status ;
        }
        return $actDb->getUserActInRecordByBaseCash( $params ) ;
    }



    /**
     * @param $userId
     * @param $actId
     * @param $startTime
     * @param $endTime
     * @param $baseCash
     * @return array
     * @desc search one record order by cash asc  limit 1
     */
    public static function getUserActInRecordByBaseCashLimitOne( $params )
    {
        $actDb      =   new ActivityStatisticsDb() ;

        $return     =   $actDb->getUserActInRecordByBaseCashLimitOne($params) ;

        return !empty($return) ? $return['id'] : null ;
    }
}