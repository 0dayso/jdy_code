<?php
/**
 * User: zhangshuang
 * Date: 16/4/21
 * Time: 11:28
 * Desc: 充值订单Db
 */

namespace App\Http\Dbs;
use App\Tools\ToolTime;

class RechargeOrderDb extends OrderDb{


    /**
     * @param $userId
     * @param $cash
     * @param int $type
     * 创建充值订单
     */
    public function makeOrder($userId,$cash,$orderId=''){
        //生成订单号
        //$this->beforeInsert();
        $this->cash = $cash;
        $this->user_id = $userId;
        $this->type = self::TYPE_RECHARGE;
        $this->order_id = $orderId;

        return $this->save();

    }


    /**
     * @param $orderId
     * @param string $note
     * @return mixed
     * 处理超时的充值订单
     */
    public function timeoutOrder($orderId,$random){

        $data = [
            'status'=>self::STATUS_TIMEOUT
        ];
        return self::where('order_id',$orderId)
            ->where('random',$random)
            ->where('status','<>',self::STATUS_TIMEOUT)
            ->update($data);

    }


    /**
     * 获取十分钟之前未处理的充值订单列表
     *
     */
    public function getDealingOrderList(){

        $time = date('Y-m-d H:i:s',strtotime("-10 minute"));

        return self::where('created_at','<=',$time)
            ->where('status',self::STATUS_ING)
            ->where('type',self::TYPE_RECHARGE)
            ->get()
            ->toArray();
    }


    /**
     * @param $orderId
     * @return mixed
     * 掉单处理
     */
    public function succMissOrder($orderId){

        $data = [
            'status'=>self::STATUS_SUCCESS,
            'success_time'=>ToolTime::dbNow()
        ];
        return self::where('order_id',$orderId)
            ->where('type',self::TYPE_RECHARGE)
            ->update($data);
    }

    /**
     * @desc 获取某段时间内充值失败的用户订单信息
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getFailRechargeOrderByTime($startTime, $endTime){

        $res = self::join('order_extend','order.order_id','=','order_extend.order_id')
            ->join('user','order.user_id','=','user.id')
            ->select('order.order_id','user.real_name', 'user.phone', 'order.cash', 'order.created_at as recharge_time','order_extend.card_number','order_extend.type as channel')
            ->whereIn('order.status',[self::STATUS_FAILED,self::STATUS_TIMEOUT])
            ->where('order.type',self::TYPE_RECHARGE)
            ->where('order.created_at', '>=', $startTime)
            ->where('order.created_at', '<=', $endTime)
            ->get();
        return $res;

    }

    /**
     * @param $statistics
     * @return mixed
     * @desc 查询订单总额
     */
    public function getRechargeOrderTotal( $statistics )
    {
        $startTime      =   $statistics['start_time'];

        $endTime        =   $statistics['end_time'];

        $status         =   $statistics['status'];

        $rechargeChannel=   $statistics['channel'];
        
        $obj            =   self::join('order_extend','order.order_id','=','order_extend.order_id')
                                ->select('order_extend.type as channel',\DB::raw('sum(cash) as total_cash'));
        //订单开始时间
        if( !empty($startTime) ){

            $obj        =   $obj->where('order.created_at', '>=', $startTime);
        }

        //订单结束时间
        if( !empty($endTime) ){

            $obj        =   $obj->where('order.created_at', '<=', $endTime);
        }
        //订单状态
        if( !empty($status)){

            $obj        =   $obj->where('order.status', $status);
        }

        //订单通道
        if( !empty($rechargeChannel)){

            $obj        =   $obj->where('order_extend.type', $rechargeChannel);
        }

        $result         =   $obj->where('order.type',self::TYPE_RECHARGE)
                                ->groupBy('order_extend.type')
                                ->get()
                                ->toArray();

        return $result;

    }
}