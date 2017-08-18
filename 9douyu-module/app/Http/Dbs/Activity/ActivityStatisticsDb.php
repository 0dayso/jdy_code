<?php
/**
 * Created by PhpStorm.
 * User: scofie-dev
 * Date: 17/5/8
 * Time: Morning 10:01
 * Desc: 活动投资统计表
 */

namespace App\Http\Dbs\Activity;

use App\Http\Dbs\JdyDb;

class ActivityStatisticsDb extends JdyDb{

    protected $table = "activity_statistics";

       const ACT_TOKEN_CACHE    =   'act_token_cache_key_' ,

            STATUS_USED     =   20 ,    //被使用的记录
            STATUS_UNUSED   =   10 ,    //未被使用的记录

            END     =   true;

    /**
     * @param $data
     * @return bool
     * @desc 增加记录
     */
    public function inRecord($data)
    {
        $this->user_id  =   isset( $data['user_id'] ) ? $data['user_id'] : 0;

        $this->act_id   =   isset( $data['act_id'] ) ? $data['act_id'] : 0;

        $this->invest_id=   isset( $data['invest_id'] ) ? $data['invest_id'] : 0;

        $this->project_id=  isset( $data['project_id'] ) ? $data['project_id'] : 0;

        $this->cash     =   isset( $data['cash'] ) ? $data['cash'] : '0.00';

        $this->note     =   isset( $data['note'] ) ? $data['note'] : '运营活动';

        $this->status   =   isset( $data['status'] ) ? $data['status'] : self::STATUS_UNUSED;

        return $this->save();

    }

    /**
     * @param $id
     * @return mixed
     * @desc change record is be used
     */
    public function doUpdateRecordUsed($id)
    {
        return self::where('id', $id)
            ->update(['status'         => self::STATUS_USED ]);
    }
    /**
     * @param $userId
     * @return array |list
     * @desc 通过用户Id 进行的索引
     */
    public function getByUserId( $userId = 0 )
    {
        return $this->where('user_id',$userId)
                    ->get()
                    ->toArray();
    }
    /**
     * @param $actId    活动的唯一标示
     * @return array | []
     */
    public function getByActId( $actId = 0 )
    {
        return $this->where( 'act_id' , $actId )
                    ->get()
                    ->toArray();

    }

    /**
     * @param array $params
     * @param $params['start_time'], $params['end_time'] , $params['user_id']  ,$params['act_id'],
     * @desc 获取用户参与某个活动的记录
     */
    public function getUserActInRecord( $params = [] )
    {
        return $this->where( 'user_id' , $params['user_id'] )
                    ->where( 'act_id' , $params['act_id'] )
                    ->where( 'created_at' , '>=' , $params['start_time'] )
                    ->where( 'created_at' , '<=' , $params['end_time'])
                    ->get()
                    ->toArray();
    }

    /**
     * @param array $params
     * @param   $params['start_time'], $params['end_time'] , $params['user_id']  ,$params['act_id'],
     * @group group by user_id,
     * @order order by invest_cash,max_invest_time
     * @return mixed
     * @desc  获取某个类型活动的排名
     */
    public function getUserCheckInActRanking( $params = [] )
    {
        return $this->select( \DB::raw('sum(cash) as invest_cash'), \DB::raw('max(created_at) as max_invest_time') , 'user_id' ,'act_id')
                    ->where( 'act_id' , $params['act_id'] )
                    ->where( 'created_at' , '>=' , $params['start_time'] )
                    ->where( 'created_at' , '<=' , $params['end_time'] )
                    ->orderBy( 'invest_cash' , 'desc' )
                    ->orderBy('max_invest_time' , 'desc' )
                    ->groupBy( 'user_id' )
                    ->take( $params['limit'] )
                    ->get()
                    ->toArray();
    }

    /**
     * @param array $params
     * $params['start_time'], $params['end_time'] , $params['user_id']  ,$params['act_id'],$params['base_cash'],
     * @return array
     * @desc  获取符合条件的活动记录 条数和总数
     */
    public function getUserActInRecordByBaseCash( $params = [] )
    {
        $actObj =   $this->where( 'user_id' , $params['user_id'] )
                        ->where( 'act_id' , $params['act_id'] )
                        ->where('cash' , '>=' , $params['base_cash'] )
                        ->where( 'created_at' , '>=' , $params['start_time'] )
                        ->where( 'created_at' , '<=' , $params['end_time']);
        if( isset($params['status']) && $params['status'] ==true ) {
            $actObj->where('status' , self::STATUS_UNUSED) ;
        }
        return ['list'  =>  $actObj->get()->toArray() , 'total' =>  $actObj->count('id') ];

    }

    /**
     * @param array $params
     * $params['start_time'], $params['end_time'] , $params['user_id']  ,$params['act_id'],$params['base_cash'],
     * @return array
     * @desc  search one record order by cash asc  limit 1
     */
    public function getUserActInRecordByBaseCashLimitOne( $params = [] )
    {
        $return  =   $this->where( 'user_id' , $params['user_id'] )
                        ->where( 'act_id' , $params['act_id'] )
                        ->where('cash' , '>=' , $params['base_cash'] )
                        ->where( 'created_at' , '>=' , $params['start_time'] )
                        ->where( 'created_at' , '<=' , $params['end_time'])
                        ->where( 'status' , self::STATUS_UNUSED)
                        ->orderBy( 'cash' , 'asc' )
                        ->take(1)
                        ->first();

        return $this->dbToArray($return) ;
    }
}
