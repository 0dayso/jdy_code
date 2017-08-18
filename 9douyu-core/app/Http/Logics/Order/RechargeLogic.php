<?php
/**
 * User: zhangshuang
 * Date: 16/4/14
 * Time: 13:57
 * Desc: 充值订单相关逻辑层
 */

namespace App\Http\Logics\Order;
use App\Http\Dbs\OrderDb;
use App\Http\Dbs\RechargeOrderDb;
use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Order\OrderModel;
use App\Http\Models\Order\RechargeModel;
use App\Http\Models\Common\UserFundModel;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Dbs\PayLimitDb;
use App\Jobs\Order\RechargeSuccessJob;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Http\Dbs\OrderExtendDb;
use App\Http\Logics\Order\OrderLogic;
use App\Http\Logics\Warning\OrderLogic as WarningOrderLogic;
use Log;
use Queue;
use Illuminate\Support\Facades\Lang;

class RechargeLogic extends OrderLogic{


    /**
     * @param $orderId
     * @param $userId
     * @param $cash
     * @param $bankId
     * @param $cardNo
     * @param $type
     * @param $from
     * @param $version
     * @return array
     * 创建支付订单
     */
    public function makeOrder($orderId,$userId,$cash,$bankId,$cardNo,$type,$from,$version){


        try {

            self::beginTransaction();

            /**
             * 创建订单前的处理
             * 1.数据的验证
             * 2.金额转化成分
             */
            //验证订单号
            ValidateModel::isOrderId($orderId);
            //验证用户ID
            ValidateModel::isUserId($userId);
            //验证金额
            ValidateModel::isCash($cash);
            //验证银行ID
            ValidateModel::isBankId($bankId);

            //网银支付,没有银行卡号,不验卡号
            if($type >= OrderExtendDb::RECHARGE_LLPAY_AUTH_TYPE){
                //验证银行卡号
                ValidateModel::isBankCard($cardNo);
            }else{
                //网银支付,卡号置为空
                $cardNo = '';
            }

            //支付 提现标识类型
            ValidateModel::isOrderType($type);
            //三端来源
            ValidateModel::isOrderFrom($from);

            //创建充值订单
            RechargeModel::makeOrder($userId,$cash,$orderId);
            //创建订单扩展信息
            OrderModel::makeExtendOrder($orderId,$bankId,$cardNo,$type,$from,$version);

            self::commit();


            //记录成功日志
            $this->logInfo($orderId,__METHOD__,'创建支付订单成功');


        } catch(\Exception $e) {

            self::rollback();

            //记录错误日志
            $this->logError($orderId,__METHOD__,$e);

            return self::callError($e->getMessage(),self::CODE_ERROR,['order_id' => $orderId]);
        }

        return self::callSuccess(['order_id'=>$orderId]);
    }


    /**
     * @param $orderId
     * @param $tradeNo
     * @return array
     * 充值成功订单处理
     */
    public function succOrder($orderId,$tradeNo){

        /*
         * 1、修改订单状态
         * 2、更新用户帐户余额
         * 3、创建资金流水
         */

        try {
            self::beginTransaction();
            
            //检查订单是否存在 && 数据处理
            $this->beforeDeal($orderId);

            //修改订单状态及扩展信息
            RechargeModel::succOrder($this->orderId,$this->orderStatus,$this->orderType);

            //更新订单扩展信息
            OrderModel::updateExtendOrder($this->orderId,OrderDb::RECHARGE_SUCCESS_NOTE,$tradeNo);
            
            //修改用户金额并生成资金流水
            $fundModel = new UserFundModel();
            $fundModel->increaseUserBalance($this->userId,$this->cash,FundHistoryDb::RECHARGE_ORDER, $orderId);

            self::commit();

            //记录成功日志
            $this->logInfo($orderId,__METHOD__,'成功支付订单回调处理成功');


        } catch(\Exception $e) {

            self::rollback();

            $this->logError($orderId,__METHOD__,$e);

            //成功充值的订单处理失败，发送短信通知掉单
            $params = [
                'event_name' => 'App\Events\Order\RechargeNoticeHandleFailedEvent',
                'user_id'    => $this->userId,
                'order_id'   => $this->orderId,
                'cash'       => $this->cash,
                'msg'        => $e->getMessage()
            ];
            
            \Event::fire('App\Events\Order\RechargeNoticeHandleFailedEvent',[$params]);

            //订单已处理,不做报警
            if( $e->getMessage() != LangModel::getLang('ERROR_ORDER_HAVE_DEALED') ){

                WarningOrderLogic::failOrderWarning($params);

            }

            return self::callError($e->getMessage(),self::CODE_ERROR,['order_id'=>$orderId]);

        }

        //充值成功入队列
        $params = [
            'order_id'  => $this->orderId,
            'cash'      => $this->cash,
            'user_id'   => $this->userId,
        ];
        Queue::pushOn('rechargeSuccessJob',new RechargeSuccessJob($params));

        return self::callSuccess(['order_id'=>$orderId]);


    }


    /**
     * @param $orderId
     * @param $tradeNo
     * @param $note
     * @return array
     * 充值失败订单处理
     */
    public function failedOrder($orderId,$tradeNo,$note){


        try {

            self::beginTransaction();

            $this->beforeDeal($orderId);

            //修改订单状态
            RechargeModel::failedOrder($this->orderId,$this->orderStatus,$this->orderType);


            $note = $note ? $note : OrderDb::RECHARGE_FAILED_NOTE;
            //更新订单扩展信息
            OrderModel::updateExtendOrder($this->orderId,$note,$tradeNo);

            self::commit();
            //记录成功日志
            $this->logInfo($orderId,__METHOD__,'支付失败订单回调处理成功');

        } catch(\Exception $e) {

            self::rollback();

            $this->logError($orderId,__METHOD__,$e);

            return self::callError($e->getMessage(),self::CODE_ERROR,['order_id'=>$orderId]);
        }

        return self::callSuccess(['order_id'=>$orderId]);
    }


    /**
     * @param $orderId
     * @return array
     * 充值超时订单处理
     */
    public function timeoutOrder($orderId,$note = ''){


        try {

            $this->beforeDeal($orderId);

            //修改订单状态msg
            RechargeModel::timeoutOrder($this->orderId,$this->orderStatus,$this->orderType);

            $note = $note ? $note : OrderDb::RECHARGE_TIMEOUT_NOTE;
            //更新订单扩展信息
            OrderModel::updateExtendOrder($this->orderId,$note);
            //记录成功日志
            $this->logInfo($orderId,__METHOD__,'订单超时处理成功');

        } catch(\Exception $e) {

            $this->logError($orderId,__METHOD__,$e);

            return self::callError($e->getMessage(),self::CODE_ERROR,['order_id'=>$orderId]);
        }

        return self::callSuccess(['order_id'=>$orderId]);
    }


    /**
     * @param $userId
     * @param $payType
     * @return array
     * 获取某个用户当前支付通道今日失败次数
     */
    public function getUserTodayInvalidOrderNumByPayChannel($userId,$payType){
        
        try{
            
            ValidateModel::isUserId($userId);
            
            ValidateModel::isOrderType($payType);
            
            $num = RechargeModel::getUserTodayInvalidOrderNumByPayChannel($userId,$payType);
            
        }catch (\Exception $e){
            
            return self::callError($e->getMessage());
        }

        return self::callSuccess(['num' => $num]);
    }


    /**
     * @param $userId
     * 获取用户非网银支付成功的订单总数量
     */
    public function getUserSuccOrderNum($userId){

        try{

            ValidateModel::isUserId($userId);
            
            $db  = new OrderDb();
            $data = $db->getUserSuccOrderNum($userId);

            $result = (array)$data[0];
        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess($result);
    }


    /**
     * @param $userId
     * 获取用户非网银支付成功的充值渠道列表
     */
    public function getLastedSuccPayChannel($userId){

        try{

            ValidateModel::isUserId($userId);

            $db  = new OrderDb();
            $data = $db->getLastedSuccPayChannel($userId);

            if(!empty($data)){
                $data = ToolArray::arrayToIds($data,'type');
            }

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess($data);
    }
    

    /**
     * @desc 获取充值数据统计
     * @author lgh
     * @param $all
     * @return array
     */
    public function getRechargeStatistics($all)
    {

        //格式充值统计数据
        $where = $this->formatGetListsInput($all);

        $model = new RechargeModel();

        $rechargeStatistics = $model->getRechargeStatistics($where);

        return self::callSuccess($rechargeStatistics);
    }
     /**
      * 更新十分钟之前未处理的支付订单为超时状态
     **/
    public function updateDealingOrderTimeOut(){

        $db = new RechargeOrderDb();
        $list = $db->getDealingOrderList();

        if($list){

            foreach($list as $order){

                $result = $this->timeoutOrder($order['order_id']);

                $log = [
                    'order_id' => $order['order_id']
                ];
                if($result['status']){

                    $log['msg'] = '更新为超时状态成功';
                    Log::info(__METHOD__.'Success',$log);

                }else{
                    $log['msg'] = $result['msg'];
                    Log::error(__METHOD__.'Success',$log);

                }
            }
        }else{

            Log::error(__METHOD__.'Success',['msg' => '没有未处理的支付订单']);

        }
    }


    /**
     * @param $orderId
     * 掉单处理
     */
    public function missOrderHandle($orderId){

        try {

            self::beginTransaction();

            //检查订单是否存在 && 数据处理
            $this->beforeDeal($orderId);

            RechargeModel::checkMissOrder($this->orderId,$this->orderStatus,$this->orderType);

            //掉单处理
            $db = new RechargeOrderDb();
            $db->succMissOrder($orderId);

            //更新订单扩展信息
            OrderModel::updateExtendOrder($this->orderId,OrderDb::RECHARGE_SUCCESS_NOTE);

            //修改用户金额并生成资金流水
            $fundModel = new UserFundModel();
            $fundModel->increaseUserBalance($this->userId,$this->cash,FundHistoryDb::RECHARGE_ORDER, $orderId);

            self::commit();

            //记录成功日志
            $this->logInfo($orderId,__METHOD__,'掉单处理成功');


        } catch(\Exception $e) {

            self::rollback();

            return self::callError($e->getMessage());
           
        }

        return self::callSuccess();

    }

    /**
     * @desc 获取某段时间内充值失败的用户订单信息
     * @author lgh-dev
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public function getFailRechargeOrderByTime($startTime, $endTime){

        $rechargeModel = new RechargeModel();

        $failRecharge = $rechargeModel->getFailRechargeOrderByTime($startTime, $endTime);

        return self::callSuccess($failRecharge);
    }

    /**
     * @param $statistics
     * @return array
     * @desc 充值总额统计
     */
    public function getRechargeOrderTotal($statistics)
    {
        $statistics     =   self::doFormatTraceValue( $statistics );

        $rechargeDb     =   new RechargeOrderDb();

        $rechargeList   =   $rechargeDb->getRechargeOrderTotal($statistics);

        return self::callSuccess($rechargeList);
    }

    /**
     * @param $statistics
     * @return array
     * @desc 格式化查询条件
     */
    protected static function doFormatTraceValue($statistics)
    {
        $status     =   isset($statistics['status']) && $statistics['status']!=0? $statistics['status'] : OrderDb::STATUS_SUCCESS;

        $channel    =   isset($statistics['channel']) && $statistics['channel'] =!0 ? $statistics['channel'] : null;

        return[

            'start_time'    =>  isset($statistics['start_time'] ) ? $statistics['start_time'] : null,
            'end_time'      =>  isset($statistics['end_time']) ? $statistics['end_time'] : null,
            'status'        =>  $status,
            'channel'       =>  $channel,
        ];
    }
}