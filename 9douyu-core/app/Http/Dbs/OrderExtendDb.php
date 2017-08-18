<?php
/**
 * User: zhangshuang
 * Date: 16/4/19
 * Time: 13:09
 * Desc: 订单扩展表Db
 */


namespace App\Http\Dbs;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class OrderExtendDb extends JdyDb{

    protected $table = "order_extend";

    /*
     * 网银支付标识码 1000-1100
     * 快捷支付    1101-1200
     * 代扣 1201-1300
     * 提现 1400-1500
     */
    const

        STATUS_NORMAL = 1, //启用
        STATUS_FORBIDDEN = 0, //禁用

        //网银
        RECHARGE_CBPAY_TYPE         = 1000,//网银在线充值标记
        RECHARGE_REAPAY_TYPE        = 1001,//融宝网银充值标识
        //快捷
        RECHARGE_LLPAY_AUTH_TYPE    = 1101,//连连认证充值标记
        RECHARGE_YEEPAY_AUTH_TYPE   = 1102,//易宝认证充值标记

        //代扣
        RECHARGE_QDBPAY_AUTH_TYPE   = 1201,//钱袋宝代扣充值标记
        RECHARGE_UMP_AUTH_TYPE      = 1202,//联动优势充值标记
        RECHARGE_BEST_AUTH_TYPE     = 1203,//翼支付充值标记
        RECHARGE_REAPAY_AUTH_TYPE   = 1204,//融宝支付充值标记

        WITHDRAW_ORDER_TYPE         = 2000;//提现订单类型


        /**
     * @param $orderId
     * 获取订单信息
     */
    public static function getOrderInfo($orderId){

        return self::where('order_id',$orderId)->first();
    }


    /**
     * @param $orderId
     * @param $bankId
     * @param $cardNo
     * @param $type
     * @param $from
     * @return bool
     * 订单扩展表添加订单记录
     */
    public function makeOrder($orderId,$bankId,$cardNo,$type,$from,$version){

        $this->order_id     = $orderId;
        $this->bank_id      = $bankId;
        $this->card_number  = $cardNo;
        $this->type         = $type;
        $this->app_request  = $from;
        $this->version      = $version;

        return $this->save();

    }

    /**
     * @param $orderId
     * @param string $tradeNo
     * @param string $note
     * @return mixed
     *
     * 修改订单扩展信息
     */
    public function updateOrder($orderId,$note='',$tradeNo = ''){

        $data = [
            'trade_no' => $tradeNo,
            'note'     => $note
        ];
        return self::where('order_id',$orderId)
            ->update($data);
    }

    /**
     * @param $type
     * @return mixed
     * 获取今日某个支付通道的所有订单
     */
    public function getTodayOrderByType($type){

        return self::where('type',$type)
            ->where('created_at','>',ToolTime::dbDate())
            ->get()->toArray();
    }

    /**
     * @param $orderIds
     * 获取多个订单号的扩展信息
     */
    public function getOrderListByOrderIds($orderIds){

        return self::select('bank_id','order_id','card_number')
            ->whereIn('order_id',$orderIds)
            ->get()->toArray();
    }

}
