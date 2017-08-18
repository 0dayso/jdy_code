<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/3/21
 * Time: Pm 11:25
 * 新分散债权DB类
 */

namespace App\Http\Dbs\Credit;

use App\Tools\ToolTime;

class CreditDisperseDb extends CreditDb{

    protected $table = 'credit_disperse';

    /**
     * @desc 创建债权
     * @param $attributes
     * @reurn mixed
     */
    public static function add($attributes = []){

        $model = new static($attributes, array_keys($attributes));
        $model->save();
        return $model->id;
    }



    public function del($id){

    }


    /**
     * @desc 获取新债权的列表
     * @author linguanghui
     * @param $size int
     * @param $condition array
     * @return mixed
     */
    public function getCreditListByStatus($status = self::STATUS_CODE_ACTIVE, $size){

        return self::where('status', $status)
            ->orderBy('id',' desc')
            ->paginate($size);
    }

    /**
     * @desc 通过多个ID获取债权的信息
     * @param $creditIds
     * @return mixed
     */
    public function getCreditListByIds($creditIds){

        return self::whereIn('id', $creditIds)
            ->get()
            ->toArray();
    }

    /**
     * @desc 获取可用的新分散债权列表
     * @author linguanghui
     * @return mixed
     */
    public function getAbleCreditList(){

        return self::select('id', 'usable_amount')
            ->where('status', CreditDb::STATUS_CODE_ACTIVE)
            ->orderBy('end_time', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * @desc 初始化债权数据
     * @return mixed
     */
    public function initCreditData(){

        $dbPrefix = env('DB_PREFIX');

        $sql = 'update '.$dbPrefix.$this->table.' set usable_amount = amounts where  status = '.CreditDb::STATUS_CODE_ACTIVE;

        return \DB::statement($sql);
    }

    /**
     * @desc 设置到期的债权的状态为到期
     * @return mixed
     */
    public function setCreditExpireStatus( )
    {

        $dbPrefix = env('DB_PREFIX');

        $sql = 'update '.$dbPrefix.$this->table.' set status = '.self::STATUS_CODE_EXPIRE.' where end_time <\''.ToolTime::dbDate().'\' and status <>'. self::STATUS_CODE_EXPIRE;

        return \DB::statement($sql);
    }

    /**
     * @param array $ids
     * @return bool
     * @desc 重置已匹配的债权列表
     */
    public function resetHadMatchFullByIds($ids=[]){

        if( empty($ids) ){

            return false;

        }

        return \DB::table('credit_disperse')
            ->whereNotIn('id', $ids)
            ->where( 'status', self::STATUS_CODE_ACTIVE )
            ->update(array(
                'usable_amount'         => 0,
                'status'                => CreditDisperseDb::STATUS_CODE_ACTIVE
            ));

    }

    /**
     * @desc 设置债权为可匹配的发布状态
     * @param array $ids
     * @return bool
     */
    public function doCreditOnline( $ids = [] )
    {
        if( empty( $ids ) )
        {
            return false;
        }

        return $this->whereIn( 'id', $ids )
            ->update( ['status' => self::STATUS_CODE_ACTIVE ] );
    }

    /**
     * @desc 获取可匹配的债权金额
     * return string
     */
    public function getCreditAbleAmount( )
    {
        return $this->select( \DB::raw( 'sum( amounts ) as total_amount') )
            ->where( 'status', self::STATUS_CODE_ACTIVE )
            ->first();
    }

}
