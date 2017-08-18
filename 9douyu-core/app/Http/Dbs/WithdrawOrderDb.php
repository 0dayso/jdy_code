<?php
/**
 * User: zhangshuang
 * Date: 16/4/21
 * Time: 11:28
 * Desc: 提现订单Db
 */

namespace App\Http\Dbs;
use DB;

class WithdrawOrderDb extends OrderDb{

    /**
     * @param $userId
     * @param $cash
     * @param int $type
     * @param int $handling_fee
     * 创建提现订单
     */
    public function makeOrder($userId,$cash,$handlingFee = 0,$orderId = ''){
        //生成订单号
        //$this->beforeInsert();

        $this->cash = $cash;
        $this->user_id = $userId;
        $this->type = self::TYPE_WITHDRAW;
        $this->handling_fee = $handlingFee;
        $this->order_id = $orderId;

        return $this->save();
    }



    /**
     * @param $orderId
     * @return mixed
     * 订单号提交银行处理
     */
    public function submitToBank($orderId,$random){

        $data = [
            'status' => self::STATUS_DEALING,
        ];
        return self::where('order_id',$orderId)
            ->where('random',$random)
            ->where('status',self::STATUS_ING)
            ->update($data);
    }

    /**
     * @param $date
     * 获取指定日期以内未处理的提现订单总数
     */
    public function getUnDealOrderTotalByDate($startDate,$endDate){

         return self::where('created_at','>=',$startDate)
            ->where('created_at','<',$endDate)
            ->where('status',self::STATUS_ING)
            ->where('type',self::TYPE_WITHDRAW)
            ->count();

    }

    /**
     * @desc    获取指定日期以内未处理的提现订单总金额
     * @param   $date
     * @author  linglu
     */
    public function getUnDealOrderCashByDate($startDate,$endDate){

        $result = self::select(\DB::raw('sum(`cash`) as totalCash'),\DB::raw('count(`id`) as totalNum'))
            ->where('created_at','>=',$startDate)
            ->where('created_at','<',$endDate)
            ->where('status',   self::STATUS_ING)
            ->where('type',     self::TYPE_WITHDRAW)
            ->first();

        return self::dbToArray($result);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $page
     * @param $size
     * @return mixed
     * 分页获取未处理的订单
     */
    public function getUnDealListByPage($startDate,$endDate,$page,$size){

         return self::select('order_id','cash','user_id','status','type','created_at')
            ->where('created_at','>=',$startDate)
            ->where('created_at','<',$endDate)
            ->where('status',self::STATUS_ING)
            ->where('type',self::TYPE_WITHDRAW)
            ->orderBy('created_at')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get()
            ->toArray();
    }



    /**
     * @param $startDate
     * @param $endDate
     * @param $page
     * @param $size
     * @return mixed
     * 分页获取未处理的订单
     */
    public function getListByPage($startDate,$endDate,$size){

        return self::select('order_id','cash','user_id','status','type','created_at')
            ->where('created_at','>=',$startDate)
            ->where('created_at','<',$endDate)
            ->where('status',self::STATUS_ING)
            ->where('type',self::TYPE_WITHDRAW)
            ->orderBy('created_at')
            ->take($size)
            ->get()
            ->toArray();
    }


    /**
     * @param $userId
     * 获取本月有效提现的次数
     */
    public function getValidWithDrawNumByMonth($userId){

        $validStatus = [
            self::STATUS_SUCCESS,
            self::STATUS_ING,
            self::STATUS_DEALING
        ];

        return self::where('user_id',$userId)
            ->whereIn('status',$validStatus)
            ->where('created_at','>=',date('Y-m-01'))
            ->where('type',self::TYPE_WITHDRAW)
            ->count();

    }

    /**
     * [提现列表信息]
     * @param  [int] $[size] 
     * @return [array]       
     */
    public function getWithdrawOrders($size){

        $res = DB::table('order')
                ->join('order_extend', 'order.order_id', '=', 'order_extend.order_id')
                ->join('user','order.user_id','=','user.id')
                ->select('order.order_id','order.cash','order.created_at','order.updated_at','order.handling_fee','order.user_id','order.status','order_extend.bank_id','order_extend.card_number','order_extend.app_request','order_extend.version','user.real_name','user.phone')
                ->where('order.type',self::TYPE_WITHDRAW)
                ->orderBy('order.id','desc')
                ->paginate($size)
                ->toArray();

        return $res;

    }

    /**
     * @desc 获取某时间段内提现大于5万的用户信息
     * @author lgh
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getWithdrawUserCashFive($startTime, $endTime){

        $res = DB::table('order')
            ->join('user','order.user_id','=','user.id')
            ->select('user.id','user.real_name', 'user.phone', 'order.cash', 'order.created_at as withdraw_time', 'user.created_at','user.identity_card')
            ->where('order.status',self::STATUS_ING)
            ->where('order.type',self::TYPE_WITHDRAW)
            ->where('order.created_at', '>=', $startTime)
            ->where('order.created_at', '<=', $endTime)
            ->where('order.cash','>=', self::WITHDRAW_SPLIT_LIMIT)
            ->get();
        return $res;
    }

    /**
     * @param $date
     * 获取指定日期以内未处理的提现订单总数
     */
    public function getDealingOrderTotalByDate($startDate,$endDate){

        return self::where('created_at','>=',$startDate)
            ->where('created_at','<',$endDate)
            ->where('status',self::STATUS_DEALING)
            ->where('type',self::TYPE_WITHDRAW)
            ->count();

    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $page
     * @param $size
     * @return mixed
     * 分页获取处理中的订单
     */
    public function getDealingListByPage($startDate,$endDate,$page,$size){

        return self::select('order_id','cash','user_id','status','type','created_at')
            ->where('created_at','>=',$startDate)
            ->where('created_at','<',$endDate)
            ->where('status',self::STATUS_DEALING)
            ->where('type',self::TYPE_WITHDRAW)
            ->orderBy('created_at')
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get()
            ->toArray();
    }


}