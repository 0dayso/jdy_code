<?php
/**
 * * 用户的零钱计划限制列表
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/22
 * Time: 上午10:55
 */

namespace App\Http\Dbs\Current;


use App\Http\Dbs\JdyDb;

class CashLimitDb extends JdyDb
{
    protected $table    =   'current_out_limit';

    const
            DEFAULT_OUT_CASH  =   '100000',
            DEFAULT_IN_CASH   =   '100000',

            STATUS_ACTIVATE   =    '20',      //开启
            STATUS_CLOSED     =    '10'        //关闭

        ;

    /**
     * @param $userId
     * @return array
     * @desc 通过用户ID
     */
    public function getByUserId( $userId )
    {
        $result =   $this->select("*")
                        ->where("user_id",$userId)
                        ->where('status',self::STATUS_ACTIVATE)
                        ->orderBy('cash','asc')
                        ->first();

        return $this -> dbToArray($result);
    }
    /**
     * @param $id
     * @return mixed
     * @desc 通过ID
     */
    public function getById( $id )
    {
        $result =   self::find( $id );

        return $this->dbToArray($result);
    }
    /**
     * @param $data
     * @return bool
     * @desc 添加记录
     */
    public function doAdd( $data )
    {
        $this->user_id  =   $data['user_id'];

        $this->cash     =   $data['cash'];

        $this->in_cash  =   $data['in_cash'];
        
        $this->admin_id =   $data['admin_id'];

        return $this->save();
    }


    /**
     * @return mixed
     */
    public function getLimitList($userId,$page, $size)
    {
        $start  = $this->getLimitStart($page, $size);

        $total  = $this->count('id');

        $obj    = $this->select("*");
        
        if( !empty($userId) ){

            $obj= $obj->where('user_id',$userId);
        }
        $list   = $obj->orderBy('id', 'desc')
                    ->skip($start)
                    ->take($size)
                    ->get()
                    ->toArray();

        return [ 'total' => $total, 'list' => $list];
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function doEdit( $id , $data )
    {
        return $this->where('id',$id)
                    ->update($data);
    }

}