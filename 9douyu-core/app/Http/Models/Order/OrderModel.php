<?php
/**
 * User: zhangshuang
 * Date: 16/4/22
 * Time: 08:20
 * Desc: 订单相关model层基类
 */
namespace App\Http\Models\Order;
use App\Http\Dbs\OrderDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Http\Dbs\UserDb;
use App\Http\Dbs\OrderExtendDb;
use App\Http\Models\Common\UserModel;

use App\Http\Dbs\BankDb;

use Illuminate\Support\Facades\DB;

class OrderModel extends Model
{

    public static $codeArr = [
        'checkOrderIsExist'                 => 1,
        'checkOrderNotExist'                => 2,
        'checkUserIsExist'                  => 3,
        'beforeUpdateErrorType'             => 4,
        'beforeUpdateErrorStatus'           => 5,
        'beforeUpdateLockOrder'             => 6,
        'beforeUpdateErrorOrderStatus'      => 7,
        'makeExtendOrder'                   => 8,
        'updateExtendOrder'                 => 9
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_ORDER;

    //提现状态码
    public static $withdrawCoderArr  = [
        OrderDb::STATUS_SUCCESS  => OrderDb::WITHDRAW_SUCCESS_NOTE,
        OrderDb::STATUS_ING      => OrderDb::WITHDRAW_ING_NOTE,
        OrderDb::STATUS_DEALING  => OrderDb::WITHDRAW_SUBMIT_TO_BANK,
        OrderDb::STATUS_CANCLE   => OrderDb::WITHDRAW_NOTE,
        OrderDb::STATUS_FAILED   => OrderDb::WITHDRAW_NOTE
    ];


    /**
     * @param $orderId
     * 获取订单信息
     */
     private static function getOrderInfo($orderId){

        $orderDb = new OrderDb();
        //获取订单信息
        $order = $orderDb->getOrderInfo($orderId);
        return $order;
    }


    /**
     * @param $orderId
     * 检查订单号是否存在,若不存在抛异常,主要用于修改订单时的判断
     */
    public static function checkOrderIsExist($orderId){

        $order = self::getOrderInfo($orderId);

        if(empty($order)){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_NOT_EXIST'),self::getFinalCode('checkOrderIsExist'));
        }

        return $order;
    }

    /**
     * @param $orderId
     * 检查订单号是否存在,若存在抛异常,主要用于添加订单时的判断
     */
    public static function checkOrderNotExist($orderId){

        $order = self::getOrderInfo($orderId);

        if($order){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_IS_EXIST'),self::getFinalCode('checkOrderNotExist'));
        }
    }


    /**
     * @param $userId
     * 判断用户是否存在,若不存在则抛出异常
     */
    public static function checkUserIsExist($userId){

        /*
        //判断用户是否存在
        $userDb = new UserDb();
        $user = $userDb->getInfoById($userId);

        if(!$user){
            throw new \Exception(LangModel::getLang('ERROR_USER_NOT_EXIST'),self::getFinalCode('checkUserIsExist'));
        }
        */

        $userModel = new UserModel();
        $userModel->checkUserExitsByUserId($userId);
    }


    /**
     * @param $userId
     * @param $orderId
     * @throws \Exception
     * 创建订单前判断订单号是否存在 、用户是否存在
     */
    public static function beforeInsert($userId = 0 ,$orderId = 0){

        //创建订单前,订单不能存在
        self::checkOrderNotExist($orderId);
        //用户必须存在
        self::checkUserIsExist($userId);



    }
    /**
     * @param int $orderId
     * @param int $userId
     * @param int $cash
     * @throws \Exception
     * 修改订单状态前的参数验证
     */
    public static function beforeUpdate($orderId,$orderStatus,$orderType, $status = OrderDb::STATUS_SUCCESS, $type = OrderDb::TYPE_RECHARGE)
    {
        
        /**
         * 1、充值订单
         * 2、提现订单
         * 充值订单不能调用提现订单处理接口，反之也一样
         */
        if ((int)$orderType !== $type) {
            throw new \Exception(LangModel::getLang('ERROR_ORDER_TYPE_NOT_MATCH'), self::getFinalCode('beforeUpdateErrorType'));
        }


        //该订单是否已处理
        if ((int)$orderStatus === $status) {

            throw new \Exception(LangModel::getLang('ERROR_ORDER_HAVE_DEALED'), self::getFinalCode('beforeUpdateErrorStatus'));
        }

        //可处理的订单状态，其他状态直接忽略
        $dealStatusList = [

            OrderDb::STATUS_DEALING,    //处理中，提现状态
            OrderDb::STATUS_ING,        //待处理，订单创建后的状态
            OrderDb::STATUS_TIMEOUT     //超时，支付状态
        ];

        //判断订单状态是否属于以上几种状态
        if(!in_array($orderStatus,$dealStatusList)){

            throw new \Exception(LangModel::getLang('ERROR_ORDER_STATUS_IS_NOT_DEALING'), self::getFinalCode('beforeUpdateErrorOrderStatus'));
            
        }

        //处理成这几种状态的时候不需要锁定订单,否刚再修改成其他状态时会出现无法修改的问题
        $unlockStatus = [
            OrderDb::STATUS_DEALING, //处理中
            OrderDb::STATUS_TIMEOUT,  //超时
        ];

        $random = 0;   //随机锁

        if(!in_array($status,$unlockStatus)){
            //生成随机的锁
            $random = rand(10000000, 99999999);

            $db = new OrderDb();

            //锁定订单号
            $lock = $db->lockOrder($orderId, $random);

            if (!$lock) {
                throw new \Exception(LangModel::getLang('ERROR_ORDER_LOCK_FAILED'), self::getFinalCode('beforeUpdateLockOrder'));
            }
        }


        return $random;

    }

    /**
     * @param $orderId
     * @param $bankId
     * @param $cardNo
     * @param $type
     * @param $from
     * @throws \Exception
     * 创建订单扩展信息
     */
    public static function makeExtendOrder($orderId,$bankId,$cardNo,$type,$from,$version){

        //订单扩展表添加记录
        $db = new OrderExtendDb();
        $result  = $db->makeOrder($orderId,$bankId,$cardNo,$type,$from,$version);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_EXTEND_ADD_FAILED'),self::getFinalCode('makeExtendOrder'));
        }
    }


    /**
     * @param $orderId
     * @param $tradeNo
     * @throws \Exception
     * 修改订单扩展信息
     */
    public static function updateExtendOrder($orderId,$note,$tradNo = ''){

        //订单扩展表添加记录
        $db = new OrderExtendDb();
        $result  = $db->updateOrder($orderId,$note,$tradNo);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_EXTEND_UPDATE_FAILED'),self::getFinalCode('updateExtendOrder'));
        }

    }


    /**
     * @param $orderId
     * @return array
     * @throws \Exception
     * 根据订单号获取订单信息
     */
    public static function getOrder($orderId){

        $orderDb  = new OrderDb();
        $order = $orderDb->getOrderInfo($orderId);

        if($order){

            $orderInfo = $order->toArray();
        }else{

            throw new \Exception(LangModel::getLang('ERROR_ORDER_NOT_EXIST'),self::getFinalCode('getOrder'));

        }
        $extendDb = new OrderExtendDb();
        $extend = $extendDb->getOrderInfo($orderId);

        if($extend){

            $extendInfo = $extend->toArray();
        }

        $orderData = [
            'order_id'      => $orderId,
            'user_id'       => $orderInfo['user_id'],
            'status'        => $orderInfo['status'],
            'cash'          => $orderInfo['cash'],
            'handling_fee'  => $orderInfo['handling_fee'],
            'created_at'    => $orderInfo['created_at'],
            'order_type'    => $orderInfo['type'],
            'pay_type'      => $extendInfo['type'],
            'bank_id'       => $extendInfo['bank_id'],
            'card_number'   => $extendInfo['card_number'],
            'note'          => $extendInfo['note']
        ];

        return $orderData;
    }

    /**
     * @desc 通过多个订单号获取订单信息
     * @param $orderIds
     * @return mixed
     */
    public static function getOrderByIds($orderIds){

        $orders = OrderDb::select('order.order_id as order_id','order.user_id as user_id','order.status as status','order.cash as cash','order.handling_fee as handling_fee','order.created_at as created_at','order.type as order_type','order_extend.type as pay_type','order_extend.bank_id as bank_id','order_extend.card_number as card_number','order_extend.note as note')
            ->join('order_extend', 'order.order_id', '=', 'order_extend.order_id')
            ->whereIn('order.order_id',$orderIds)
            ->get()
            ->toArray();

        return $orders;
    }


    /**
     * 获取项目记录
     *
     * @param array $data
     * @return array
     */
    public function getLists($data = []){
        $type       = $data['type'];
        $userId     = $data['userId'];

        $page       = $data['page'];
        $size       = $data['size'];

        $start_time = $data['start_time'];
        $end_time   = $data['end_time'];

        $obj = new OrderDb();

        // 用户ID
        if($userId) {
            $obj = OrderDb::where('user_id', '=', $userId);
        }

        // 类型
        if($type) {
            $obj = OrderDb::where('type', '=', $type);
        }

        // 时间范围
        if($start_time && $end_time){
            $obj = $obj->where('created_at', '>=', $start_time);
            $obj = $obj->where('created_at', '<=', $end_time);
        }elseif($start_time && !$end_time){
            $obj = $obj->where('created_at', '>=', $start_time);
        }elseif(!$start_time && $end_time){
            $obj = $obj->where('created_at', '<=', $end_time);
        }
        // 创建时间倒序
        $obj->orderBy('created_at', 'DESC');

        // 分页
        $obj =  $obj->paginate(
            $size,
            ['*'],
            'page',
            $page)->toArray();

        unset(
            $obj['per_page'],
            $obj['current_page'],
            $obj['last_page'],
            $obj['next_page_url'],
            $obj['prev_page_url'],
            $obj['from'],
            $obj['to']
        );

        return $obj;
    }

    /**
     * 获取扩展表信息
     *
     * @param array $orderIds
     * @return array
     */
    public static function getExtendList($orderIds = []){
        if($orderIds){
            return OrderExtendDb::whereIn('order_id', $orderIds)->get()->toArray();
        }
        return [];
    }

    /**
     * 获取用到的银行列表
     */
    public static function getBankList($ids = []){
        return BankDb::getBankList($ids);
    }


    /**
     * 获取指定用户充值提现 金额汇总
     */
    public static function getRechargeWithdrawSummary($userId = 0){

        $obj = OrderDb::select('type', DB::raw('SUM(cash) as total_cash'));

        $obj->where('user_id', $userId);

        $obj->where('status', OrderDb::STATUS_SUCCESS);

        $obj->groupBy('type');

        return $obj->get()->toArray();
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
        $type       = $data['type'];

        $obj = OrderDb::join('order_extend', 'order.order_id', '=', 'order_extend.order_id')
            ->select('order.order_id','order.cash','order.created_at',
                'order.updated_at','order.handling_fee','order.user_id','order.status',
                'order_extend.bank_id','order_extend.card_number','order_extend.app_request',
                'order_extend.version','order_extend.type','order_extend.note','order_extend.trade_no');
            //->where('order.type',OrderDb::TYPE_RECHARGE);

        if($type){
            $obj = $obj->where('order.type','=',$type);
        }
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

        $total_cash =   $obj->sum('order.cash');
        $handling_free_cash  =   $obj->sum('order.handling_fee');

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

        $list['total_cash'] =   $total_cash;

        $list['handling_fee_total'] =   $handling_free_cash;

        return $list;

    }


    /**
     * @param $orderId
     * @return array
     * @throws \Exception
     * 根据订单号获取订单信息
     */
    public static function statFailOrderData($startTime,$endTime,$clientArr=[]){

        $orderData    = OrderDb::statFailOrderData($startTime,$endTime,$clientArr);

        if(!$orderData){

            return [];

        }

        return $orderData;
    }


}