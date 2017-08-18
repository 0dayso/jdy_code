<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 16:26
 * Desc: 订单Db基类
 */

namespace App\Http\Dbs;

use App\Tools\ToolTime;

class OrderDb extends JdyDb{

    protected $table = "order";

    const TYPE_RECHARGE = 1, //充值订单
          TYPE_WITHDRAW = 2, //提现订单

          STATUS_SUCCESS = 200, //订单状态（成功）
          STATUS_ING     = 300,//等待处理
          STATUS_DEALING = 301,//处理中
          STATUS_TIMEOUT = 401,//超时取消
          STATUS_CANCLE  = 402,//取消提现
          STATUS_FAILED  = 500;  //订单状态（失败）


    const    //网银
        RECHARGE_CBPAY_TYPE         = 1000,//网银在线充值标记
        RECHARGE_REAPAY_TYPE        = 1001,//融宝网银充值标识
            //快捷
        RECHARGE_LLPAY_AUTH_TYPE    = 1101,//连连认证充值标记
        RECHARGE_YEEPAY_AUTH_TYPE   = 1102,//易宝认证充值标记
        RECHARGE_BFPAY_AUTH_TYPE    = 1103,//宝付认证充值标记
        RECHARGE_UCFPAY_AUTH_TYPE   = 1104,//先锋认证充值标记
        RECHARGE_SUMAPAY_AUTH_TYPE  = 1105,//丰付认证充值标记

            //代扣
        RECHARGE_QDBPAY_AUTH_TYPE   = 1201,//钱袋宝代扣充值标记
        RECHARGE_UMP_AUTH_TYPE      = 1202,//联动优势充值标记
        RECHARGE_BEST_AUTH_TYPE     = 1203,//翼支付充值标记
        RECHARGE_REAPAY_AUTH_TYPE   = 1204,//融宝支付充值标记

        WITHDRAW_ORDER_TYPE         = 2000;//提现订单类型


    const RECHARGE_ING_NOTE         = '支付处理中',
          RECHARGE_SUCCESS_NOTE     = '支付成功',
          RECHARGE_FAILED_NOTE      = '支付失败',
          RECHARGE_TIMEOUT_NOTE     = '支付超时',
          RECHARGE_CANCLE_NOTE      = '用户放弃支付',
          WITHDRAW_ING_NOTE         = '提现待处理',
          WITHDRAW_SUBMIT_TO_BANK   = '提现申请提交至银行',
          WITHDRAW_CANCLE_NOTE      = '取消提现',
          WITHDRAW_FAILED_NOTE      = '提现失败',
          WITHDRAW_SUCCESS_NOTE     = '提现成功',
          WITHDRAW_NOTE             = '提现';

    const INVALIED_ORDER_LIMIT      = 2,    //不成功的订单次数,某一支付通道,当天不成功的订单数超过此限制,切换通道
          WITHDRAW_SPLIT_LIMIT      = 45000, //提现拆单限额 4.5 超过此金额提现订单会被拆分为多笔,发送提现邮件时需要,单位为分
          WITHDRAW_UCF_LIMIT        = 4500000, //提现拆单限额 因为先锋代付-没有金额限制-给个很大的值
          WITHDRAW_EMAIL_MAX_NUM    = 1000, //提现邮件单个文件最多提现记录数
          DEFAULT_SUCCESS_TIME      = '0000-00-00 00:00:00';    //默认的处理数据

    /**
     * 获取关联的附加信息
     */
    public function getOrderExtendDb()
    {
        return $this->hasOne(
            'App\Http\Dbs\OrderExtendDb',
            'order_id',
            'order_id'
        );
    }

    /**
     * @param $orderId
     * 获取订单信息
     */
    public function getOrderInfo($orderId){

        return self::where('order_id',$orderId)->first();

    }


    /**
     * @param $orderId
     * @param $random
     * @return mixed
     * 锁定订单号
     */
    public function lockOrder($orderId,$random){
        $data = [
            'random'=>$random
        ];
        return self::where('order_id',$orderId)
            ->where('random','0')
            ->update($data);
    }


    /**
     * @param $orderId
     * @param string $note
     * @return mixed
     * 处理失败的充值订单
     */
    public function failedOrder($orderId,$random){

        $data = [
            'status'=>self::STATUS_FAILED
        ];
        return self::where('order_id',$orderId)
            ->where('random',$random)
            ->where('status','<>',self::STATUS_FAILED)
            ->update($data);

    }

    /**
     * @param $orderId
     * @param string $note
     * @return mixed
     * 处理失败的充值订单
     */
    public function succOrder($orderId,$random){

        $data = [
            'status'=>self::STATUS_SUCCESS,
            'success_time'=>ToolTime::dbNow()
        ];
        return self::where('order_id',$orderId)
            ->where('random',$random)
            ->where('status','<>',self::STATUS_SUCCESS)
            ->update($data);

    }


    /**
     * @param $orderId
     * @param string $note
     * @return mixed
     * 取消提现
     */
    public function cancelOrder($orderId,$random){

        $data = [
            'status'=>self::STATUS_CANCLE
        ];
        return self::where('order_id',$orderId)
            ->where('random',$random)
            ->where('status', self::STATUS_ING)
            ->update($data);

    }

    /**
     * @param $orderIds
     * @return mixed
     * 获取指定订单列表内不成功的订单数量
     */
    public function getInvalidNumByOrderIds($orderIds){

        return self::where('status','<>',self::STATUS_SUCCESS)
            ->whereIn('order_id', $orderIds)
            ->count();
    }

    /**
     * @param $orderIds
     * 查询多个订单号信息
     */
    public function getOrderByOrderIds($orderIds){

        return self::select('order_id','status','type','user_id','cash')
            ->whereIn('order_id',$orderIds)
            ->get()
            ->toArray();

    }

    /**
     * @param $userId
     * @return mixed
     * 获取用户除网银支付的成功充值订单总数
     */
    public function getUserSuccOrderNum($userId){

        $dbPrefix = env('DB_PREFIX');

        $sql = "select count(t1.id) as num from ".$dbPrefix."order as t1,".$dbPrefix."order_extend as t2 
                where t1.user_id = {$userId} and  t1.order_id = t2.order_id 
                and t1.type=".self::TYPE_RECHARGE." and t1.status = ".self::STATUS_SUCCESS." and 
                t2.type >=".self::RECHARGE_LLPAY_AUTH_TYPE." and t2.type < ".self::WITHDRAW_ORDER_TYPE;

        return app('db')->select($sql);

    }



    /**
     * @param $userId
     * @return mixed
     * 获取用户非网银支付成功的充值渠道列表
     */
    public function getLastedSuccPayChannel($userId){

        $dbPrefix = env('DB_PREFIX');

        $sql = "select t2.type from ".$dbPrefix."order as t1,".$dbPrefix."order_extend as t2 
                where t1.user_id = {$userId} and  t1.order_id = t2.order_id 
                and t1.type=".self::TYPE_RECHARGE." and t1.status = ".self::STATUS_SUCCESS." 
                and t2.type >=".self::RECHARGE_LLPAY_AUTH_TYPE." and t2.type < ".self::WITHDRAW_ORDER_TYPE." 
                group by t2.type";

        return app('db')->select($sql);

    }


    /**
     * @desc    账户资金统计
     * @date    2016年11月24日
     * @author  @llper
     */
    public function getOrderFundStatistics($startTime='', $endTime=''){

        $dbObj  = self::select(\DB::raw('SUM(if(type='.self::TYPE_RECHARGE.', cash,0)) AS totalRecharge , SUM(if(type='.self::TYPE_WITHDRAW.', cash, 0)) AS totalWithdraw '));
        $dbObj->whereIn('type', [self::TYPE_RECHARGE,self::TYPE_WITHDRAW]);
        $dbObj->where('status', self::STATUS_SUCCESS);
        $dbObj->where('success_time',"<>",self::DEFAULT_SUCCESS_TIME);

//        if( $startTime && $endTime ){
//
//            $dbObj->whereBetween('created_at',[$startTime,$endTime]);
//
//        }elseif( $startTime && !$endTime ){
//
//            $dbObj->where('created_at', '>=', $startTime);
//
//        }elseif( !$startTime && $endTime){
//
//            $dbObj->where('created_at', '<=', $endTime);
//
//        }

        if( $startTime ){

            $dbObj->where('success_time',">=",$startTime);
        }
        if( $endTime ){

            $dbObj->where('success_time',"<=",$endTime);
        }

        $result = $dbObj->first();

        return self::dbToArray($result);
    }

    /**
     * @desc    获取指定日期以内未处理的提现订单总金额
     * @param   $date
     * @author  linglu
     */
    public function getWithdrawOrderStat($startDate, $endDate){

        $result = self::select(\DB::raw('sum(`cash`) as totalCash'),\DB::raw('count(`id`) as totalNum'))
            ->where('created_at','>=',$startDate)
            ->where('created_at','<',$endDate)
            ->where('type',     self::TYPE_WITHDRAW)
            ->first();

        return self::dbToArray($result);
    }


    /**
     * @param $date
     * @return mixed
     * @desc 查询提现失败的订单总计
     */
    public static function getWithDrawFailedTotal( $date )
    {
        $obj    =   self::select("type",\DB::raw(' sum(cash+handling_fee) as balance_change'));

        if( $date ){

            $obj=   $obj->where("created_at","<=",$date);
        }

        $return =   $obj->where("type",self::TYPE_WITHDRAW)
                        ->where("status",self::STATUS_FAILED)
                        ->get()
                        ->toArray();

        return $return;
    }



    /**
     * @desc    获取时间段内， 失败订单信息
     * @param   $startTime  开始时间
     * @param   $endTime    结束时间
     * @return  mixed
     *
     * 三表联查
     * core_order
     * core_order_extend
     * core_user
     *
     */
    public static function statFailOrderData($startTime='', $endTime='',$clientArr=[]){

        if( !$startTime || !$endTime ){
            $endTime    = date("Y-m-d H:00:00");
            $startTime  = date('Y-m-d H:00:00', (strtotime($endTime) - 3600));
        }
        $obj = self::select('order.order_id','order.user_id','order.status','order.cash','order.created_at','order_extend.type as pay_type','order_extend.bank_id','order_extend.card_number','order_extend.app_request','order_extend.version','user.real_name','user.phone')
            ->join('order_extend', 'order.order_id', '=', 'order_extend.order_id')
            ->join('user',  'order.user_id', '=', 'user.id')
            ->where('order.type',   self::TYPE_RECHARGE)
            ->where('order.status', '<>', self::STATUS_SUCCESS)
            ->whereBetween('order.created_at',[$startTime,$endTime]);

        #订单来源 pc，wab,ios,android
        if(!empty($clientArr)){
            $obj->whereIn('order_extend.app_request',$clientArr);
        }

         $resultData= $obj->get()->toArray();

        return $resultData;
    }


    /**
     * @desc  统计时间段内    各支付渠道，充值情况
     **/
    public static function statOrderWithPayType($startTime='', $endTime='',$clientArr=[]){

        if( !$startTime || !$endTime ){
            $startTime = date('Y-m-d', strtotime(' -1 day'));
            $endTime   = date("Y-m-d");
        }
        $obj = self::select(
                "order_extend.type AS payType",
                \DB::raw("
                        COUNT('order.id') AS totalCount,
                        SUM(cash)   AS totalCash,
                        sum(if(status=".self::STATUS_SUCCESS.",1,0))     AS succCount,
                        sum(if(status=".self::STATUS_SUCCESS.",cash,0))  AS succCash,                
                        sum(if(status<>".self::STATUS_SUCCESS.",1,0))    AS failCount,
                        sum(if(status<>".self::STATUS_SUCCESS.",cash,0)) AS failCash
                    ")
            )
            ->join('order_extend', 'order.order_id', '=', 'order_extend.order_id')
            ->where('order.type',   self::TYPE_RECHARGE)
            ->whereBetween('order.created_at',[$startTime,$endTime]);

        #订单来源 pc，wab,ios,android
        if(!empty($clientArr)){
            $obj->whereIn('order_extend.app_request',$clientArr);
        }
        $resultData= $obj->groupBy('order_extend.type')
                    ->get()
                    ->toArray();

        return $resultData;
    }


    /**
     * @desc 通过支付渠道-获取支付渠道名称
     **/
    public static function getPayTypeName(){
        return [
            self::RECHARGE_CBPAY_TYPE       => '网银充值',
            self::RECHARGE_REAPAY_TYPE      => '网银充值',
            self::RECHARGE_LLPAY_AUTH_TYPE  => '连连支付',
            self::RECHARGE_YEEPAY_AUTH_TYPE => '易宝支付',
            self::RECHARGE_BFPAY_AUTH_TYPE  => '宝付支付',
            self::RECHARGE_UCFPAY_AUTH_TYPE => '先锋支付',
            self::RECHARGE_SUMAPAY_AUTH_TYPE=> '丰付支付',

            self::RECHARGE_QDBPAY_AUTH_TYPE => '钱袋宝',
            self::RECHARGE_UMP_AUTH_TYPE    => '联通优势',
            self::RECHARGE_BEST_AUTH_TYPE   => '翼支付',
            self::RECHARGE_REAPAY_AUTH_TYPE => '融宝支付',
        ];

    }


    /**
     * @param   string $start
     * @param   string $end
     * @return  mixed
     * @desc    根据开始结束日期获取充值成功金额、笔数
     */
    public function getOrderAmountByDate($start = '', $end = ''){

        $obj = $this->select(
                        \DB::raw("DATE_FORMAT(created_at, '%Y%m%d') AS date"),
                        \DB::raw("
                            sum(if(status=".self::STATUS_SUCCESS." && type=".self::TYPE_RECHARGE.", 1, 0)) AS rechangeCount,
                            sum(if(status=".self::STATUS_SUCCESS." && type=".self::TYPE_RECHARGE.", cash,0)) AS rechangeCash,                
                            sum(if(type=".self::TYPE_WITHDRAW.", 1,   0)) AS withdrawCount,
                            sum(if(type=".self::TYPE_WITHDRAW.", cash,0)) AS withdrawCash
                        ")
                    );

        if(!empty($start) && !empty($end)){
            $end= date('Y-m-d', strtotime($end)+86400);
        }else{
            $start  = date('Y-m-d');
            $end    = date('Y-m-d', strtotime($start)+86400);
        }
        $obj= $obj->whereBetween('created_at',[$start,$end]);
        $res    = $obj->groupBy('date')
                ->orderBy('date','asc')
                ->get()
                ->toArray();

        return $res;

    }


}
