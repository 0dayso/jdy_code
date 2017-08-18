<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/9
 * Time: 上午11:09
 */

namespace App\Http\Dbs\Order;


use App\Http\Dbs\JdyDb;

class CheckOrderRecordDb extends JdyDb
{
    protected $table    =   'order_check';

    const
        CHECK_STATUS_PENDING      =   10,    //待处理的订单
        CHECK_STATUS_SUCCESS      =   20,    //已经处理的订单

        //快捷对账的通道标示
        RECHARGE_CBPAY_TYPE         = 1000, //网银在线充值标记
        RECHARGE_QDBPAY_AUTH_TYPE   = 1201, //钱袋宝代扣充值标记
        RECHARGE_YEEPAY_AUTH_TYPE   = 1102, //易宝认证充值标记
        RECHARGE_LLPAY_AUTH_TYPE    = 1101, //连连认证充值标记
        RECHARGE_UMP_AUTH_TYPE      = 1202, //联动优势充值标记
        RECHARGE_REAPAY_AUTH_TYPE   = 1204, //融宝支付充值标记
        RECHARGE_BEST_AUTH_TYPE     = 1203,  //翼支付充值标记

        CHECK_ORDER_SUCCESS         = 100,
        CHECK_ORDER_FAILED          = 200
    ;


    /**
     * @param $id
     * @return array
     * @desc 查询单一的数据
     */
    public function getById( $id )
    {
        $result     =   $this->find( $id );

        return $this->dbToArray($result);
    }

    /**
     * @param $data
     * @return bool
     * @desc 添加数据
     */
    public function doAdd( $data )
    {
        $this->order_id     =   $data['order_id'];

        $this->pay_channel  =   $data['pay_channel'];

        $this->cash         =   $data['cash'];

        $this->is_check     =   $data['is_check'];

        $this->note         =   $data['note'];

        $this->admin_id     =   $data['admin_id'];

        $this->user_id      =   $data['user_id'];

        return $this->save();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 更新数据
     */
    public function doUpdate( $id , $data )
    {
        return $this->where('id',$id)->update($data);
    }

    /**
     * @param $page
     * @param $size
     * @param $status
     * @return mixed
     * @desc 查询列表
     */
    public function getList( $page , $size ,$status)
    {
        $offset = $this->getLimitStart($page, $size);

        $total  = $this->whereIn('is_check', $status)->count('id');

        $list   =   $this->whereIn('is_check', $status)
                    ->orderBy('id', 'desc')
                    ->skip($offset)
                    ->take($size)
                    ->get()
                    ->toArray();
        
        return [ 'total' => $total, 'list' => $list];
    }

    /**
     * @return mixed
     * @desc 未处理的订单数据
     */
    public function getNotCheckRecordTotal()
    {
        $result     =   $this->where('is_check',self::CHECK_STATUS_PENDING)
                            ->count('id');

        return $result;

    }

    /**
     * @param $params
     * @return mixed
     * @desc 通过订单ID零钱计划异常的订单
     */
    public function getCheckOrderRecordByParam( $params )
    {
        $orderIds   =   $params['order_id'];

        $result     =   $this->where('is_check',self::CHECK_STATUS_PENDING)
                            ->whereIn("order_id",$orderIds)
                            ->get()
                            ->toArray();

        return $result;
    }
}