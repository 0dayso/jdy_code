<?php
/**
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 19:03
 * Desc: 充值订单相关model层
 */
namespace App\Http\Models\Order;
use App\Http\Dbs\OrderExtendDb;
use App\Http\Dbs\RechargeOrderDb;
use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Dbs\OrderDb;
use Db;

class RechargeModel extends OrderModel{


    public static $codeArr = [
        'makeOrder'           => 1,
        'succOrder'           => 2,
        'failedOrder'         => 3,
        'timeoutOrder'        => 4,
        'checkMissOrder'      => 5,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_RECHARGE_ORDER;


    /**
     * @param $userId
     * @param $cash
     * @param $orderId
     * 创建充值订单
     */
    public static function makeOrder($userId,$cash,$orderId){


        //创建订单前的检查
        self::beforeInsert($userId,$orderId);

        //生成数据库订单数据
        $rechargeDb = new RechargeOrderDb();

        //订单核心表添加记录
        $result = $rechargeDb->makeOrder($userId,$cash,$orderId);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_RECHARGE_ORDER_ADD_FAILED'),self::getFinalCode('makeOrder'));
        }

    }

    /**
     * @param $orderId
     * @param $orderStatus
     * @param $orderType
     * 掉单信息验证
     */
    public static function checkMissOrder($orderId,$orderStatus,$orderType){

        $validStatus = [
            OrderDb::STATUS_FAILED,
            OrderDb::STATUS_ING,
            OrderDb::STATUS_TIMEOUT
        ];


        if($orderType != OrderDb::TYPE_RECHARGE){

            throw new \Exception(LangModel::getLang('ERROR_RECHARGE_MISS_ORDER_TYPE_CHECK_FAILED'),self::getFinalCode('checkMissOrder'));

        }

        if(!in_array($orderStatus,$validStatus)){

            throw new \Exception(LangModel::getLang('ERROR_RECHARGE_MISS_ORDER_STATUS_CHECK_FAILED'),self::getFinalCode('checkMissOrder'));

        }

    }


    /**
     * @param $orderId      订单号
     * @param $orderStatus  数据库订单状态
     * @param $orderType         订单类型 1-充值 2-提现
     * @param $tradeNo      第三方支付流水号
     * @throws \Exception
     *
     */
    public static function succOrder($orderId,$orderStatus,$orderType){

        //修改订单前的数据检查
        $random = OrderModel::beforeUpdate($orderId,$orderStatus,$orderType,OrderDb::STATUS_SUCCESS,OrderDb::TYPE_RECHARGE);

        $db = new RechargeOrderDb();

        //修改锁定订单号状态
        $result = $db->succOrder($orderId,$random);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_UPDATE_FAILED'),self::getFinalCode('succOrder'));
        }

    }


    /**
     * @param $orderId
     * @param $orderStatus
     * @param $orderType
     * @param $tradeNo
     * @param $note
     * @throws \Exception
     */
    public static function failedOrder($orderId,$orderStatus,$orderType){
        //修改订单状态前的检查
        $random = OrderModel::beforeUpdate($orderId,$orderStatus,$orderType,OrderDb::STATUS_FAILED,OrderDb::TYPE_RECHARGE);

        $db = new RechargeOrderDb();
        $result = $db->failedOrder($orderId,$random);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_UPDATE_FAILED'),self::getFinalCode('failedOrder'));

        }

    }


    /**
     * @param int $orderId
     * @param int $userId
     * @param int $cash
     * @throws \Exception
     * 失败回调订单处理
     */
    public static function timeoutOrder($orderId,$orderStatus,$orderType){
        //修改订单状态前的检查
        $random = OrderModel::beforeUpdate($orderId,$orderStatus,$orderType,OrderDb::STATUS_TIMEOUT,OrderDb::TYPE_RECHARGE);
        
        $db = new RechargeOrderDb();
        $result = $db->timeoutOrder($orderId,$random);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_UPDATE_FAILED'),self::getFinalCode('timeoutOrder'));
        }

    }

    /**
     * @param $userId
     * @param $payType
     * @return int|mixed
     * 获取用户今日某个支付通道充值失败的订单总数
     */
    public static function getUserTodayInvalidOrderNumByPayChannel($userId,$payType){

        $num = 0;

        //获取今天该通道的充值订单列表
        $extendDb = new OrderExtendDb();
        $orderList = $extendDb->getTodayOrderByType($payType);
        //没有充值记录,直接返回
        if(empty($orderList)){
            return $num;
        }

        //该支付通道存在充值订单
        foreach ($orderList as $val){
            $orderIds[] = $val['order_id'];
        }

        //判断不成功的订单数是否大于2笔,如果大于切换通道
        $orderDb = new OrderDb();
        $num = $orderDb->getInvalidNumByOrderIds($orderIds);
        return $num;
    }


    /**
     * @param $data
     * @return mixed
     * 后台充值列表
     */
    public static function getAdminList($data){

        $userId     = $data['userId'];

        $page       = $data['page'];
        $size       = $data['size'];
        $orderId    = $data['order_id'];

        $start_time = $data['start_time'];
        $end_time   = $data['end_time'];
        $status     = $data['status'];

        $payType    = $data['pay_type'];

        $obj = OrderDb::join('order_extend', 'order.order_id', '=', 'order_extend.order_id')
            ->select('order.order_id','order.cash','order.created_at',
                'order.updated_at','order.handling_fee','order.user_id','order.status',
                'order_extend.bank_id','order_extend.card_number','order_extend.app_request',
                'order_extend.version','order_extend.type','order_extend.note','order_extend.trade_no')
            ->where('order.type',OrderDb::TYPE_RECHARGE);

        // 用户ID
        if($userId) {
            $obj = $obj->where('user_id', '=', $userId);
        }
        //状态
        if($status){
            $obj = $obj->where('status', '=', $status);
        }
        //订单号
        if($orderId){
            $obj = $obj->where('order.order_id',$orderId);
        }
        // 支付类型
        if($payType) {
            $obj = $obj->where('order_extend.type', '=', $payType);
        }

        // 时间范围
        if($start_time && $end_time){
            $obj = $obj->where('order.created_at', '>=', $start_time);
            $obj = $obj->where('order.created_at', '<=', $end_time);
        }elseif($start_time && !$end_time){
            $obj = $obj->where('order.created_at', '>=', $start_time);
        }elseif(!$start_time && $end_time){
            $obj = $obj->where('order.created_at', '<=', $end_time);
        }

        $obj = $obj->orderBy('order.id','desc');
        // 分页
        $list =  $obj->paginate(
            $size,
            ['*'],
            'page',
            $page)->toArray();

        unset(
            $list['per_page'],
            $list['current_page'],
            $list['last_page'],
            $list['next_page_url'],
            $list['prev_page_url'],
            $list['from'],
            $list['to']
        );
        return $list;

    }

    /**
     * @desc 获取充值统计数据
     * @author lgh
     * @param $where
     * @return mixed
     */
    public function getRechargeStatistics($where){

        $startTime = $where['start_time'];
        $endTime   = $where['end_time'];
        $appRequest   = $where['app_request'];
        $payType   = $where['pay_type'];
        $userId   = $where['userId'];

        $obj =OrderDb::join('order_extend', 'order.order_id', '=', 'order_extend.order_id')
            ->where('order.type',OrderDb::TYPE_RECHARGE)
            ->where('order.status',OrderDb::STATUS_SUCCESS);

        // 时间范围
        if($startTime && $endTime){
            $obj = $obj->where('order.created_at', '>=', $startTime);
            $obj = $obj->where('order.created_at', '<=', $endTime);
        }elseif($startTime && !$endTime){
            $obj = $obj->where('order.created_at', '>=', $startTime);
        }elseif(!$startTime && $endTime){
            $obj = $obj->where('order.created_at', '<=', $endTime);
        }
        //平台来源
        if($appRequest){
            $obj = $obj->where('order_extend.app_request', '=', $appRequest);
        }
        //充值渠道
        if($payType){
            $obj = $obj->where('order_extend.type', '=', $payType);
        }
        //用户ID
        if($userId){
            $obj = $obj->where('order.user_id', '=', $userId);
        }

        $data['cash'] = $obj->sum('order.cash');
        $data['rechargeNum'] = $obj->distinct()->count('order.user_id');

        return $data;
    }

    /**
     * @desc 获取某段时间内充值失败的用户订单信息
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getFailRechargeOrderByTime($startTime, $endTime){

        $rechargeOrderDb = new RechargeOrderDb();

        $result = $rechargeOrderDb->getFailRechargeOrderByTime($startTime, $endTime);

        return $result;
    }
}