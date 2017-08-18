<?php
/**
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 11:48
 * Desc: 提现订单相关逻辑层
 */
namespace App\Http\Logics\Order;

use App\Http\Dbs\FundHistoryDb;
use App\Http\Dbs\WithdrawOrderDb;
use App\Http\Models\Order\OrderModel;
use App\Jobs\Order\SendDealingWithdrawSmsJob;
use App\Http\Models\Common\UserFundModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Order\WithdrawModel;
use App\Jobs\Order\WithdrawOrderCreateSuccessJob;
use App\Jobs\Order\WithdrawSubmitToBankSuccessJob;
use App\Http\Dbs\OrderDb;
use Queue;


class WithdrawLogic extends OrderLogic{

    /**
     * @param $cash
     * @param $userId
     * 创建提现订单号
     */
    public function makeOrder($orderId,$userId,$handingFee,$cash,$bankId,$cardNo,$type,$from,$version){

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

            ValidateModel::isHandingFee($handingFee);
            //验证金额
            ValidateModel::isDecimalCash($cash);
            //验证银行ID
            ValidateModel::isBankId($bankId);
            //验证银行卡号
            ValidateModel::isBankCard($cardNo);
            //支付 提现标识类型
            ValidateModel::isOrderType($type);
            //三端来源
            ValidateModel::isOrderFrom($from);

            //提现的真实金额
            $totalCash  = $cash + $handingFee;
            //创建充值订单
            WithdrawModel::makeOrder($userId,$cash,$orderId,$handingFee);

            //添加订单扩展信息
            OrderModel::makeExtendOrder($orderId,$bankId,$cardNo,$type,$from,$version);

            //创建提现申请的资金流水
            $fundModel = new UserFundModel();
            $fundModel->decreaseUserBalance($userId,$totalCash,FundHistoryDb::WITHDRAW_ORDER,$orderId);

            self::commit();

            /*
             * 暂时禁用该队列
            //提现订单创建成功入队列，部分用户实时到账
            $params = [
                'order_id'  => $this->orderId,
                'cash'      => $this->cash,
                'user_id'   => $this->userId,
            ];
            Queue::pushOn('withdrawCreateSuccessJob',new WithdrawOrderCreateSuccessJob($params));
            */

            //记录成功日志

            $this->logInfo($orderId,__METHOD__,'提现订单创建成功');

        } catch(\Exception $e) {

            self::rollback();

            //记录错误日志
            $this->logError($orderId,__METHOD__,$e);

            return self::callError($e->getMessage(),self::CODE_ERROR,['order_id'=>$orderId]);
        }


        return self::callSuccess(['order_id'=>$orderId]);
    }


    /**
     * @return array
     * 提现成功
     */
    public function succOrder($orderId,$tradeNo){

        try {

            self::beginTransaction();

            //数据验证及金额格式化处理
            $this->beforeDeal($orderId);

            //创建提现订单
            WithdrawModel::succOrder($this->orderId,$this->orderStatus,$this->orderType);
            
            OrderModel::updateExtendOrder($this->orderId,OrderDb::WITHDRAW_SUCCESS_NOTE,$tradeNo);

            self::commit();

            //记录成功日志
            $this->logInfo($orderId,__METHOD__,'成功提现处理成功');

        } catch(\Exception $e) {

            self::rollback();

            $this->logError($orderId,__METHOD__,$e);

            return self::callError($e->getMessage(),self::CODE_ERROR,['order_id'=>$orderId]);
        }


        return self::callSuccess(['order_id'=>$orderId]);
    }

    /**
     * @param $cash
     * @param $userId
     * 创建提现订单号
     */
    public function failedOrder($orderId,$tradeNo,$note){


        try {

            self::beginTransaction();
            //数据验证及金额格式化处理
            $this->beforeDeal($orderId);

            //创建提现订单
            WithdrawModel::failedOrder($this->orderId,$this->orderStatus,$this->orderType);

            $note = $note ? $note : OrderDb::WITHDRAW_FAILED_NOTE;
            //更新订单扩展信息
            OrderModel::updateExtendOrder($this->orderId,$note,$tradeNo);

            //创建提现失败资金流水
            $fundModel = new UserFundModel();
            $fundModel->increaseUserBalance($this->userId,$this->cash + $this->handlingFee,FundHistoryDb::WITHDRAW_ORDER_FAILED, $orderId);

            self::commit();

            //记录成功日志

            $this->logInfo($orderId,__METHOD__,'失败提现处理成功');


        } catch(\Exception $e) {

            self::rollback();

            $this->logError($orderId,__METHOD__,$e);

            return self::callError($e->getMessage(),self::CODE_ERROR,['order_id'=>$orderId]);
        }


        return self::callSuccess(['order_id'=>$orderId]);
    }


    /**
     * @param $cash
     * @param $userId
     * 提现提交银行
     */
    public function submitToBank($orderId){


        try {

            self::beginTransaction();
            //数据验证及金额格式化处理
            $this->beforeDeal($orderId);

            //创建提现订单
            WithdrawModel::submitToBank($this->orderId,$this->orderStatus,$this->orderType);

            OrderModel::updateExtendOrder($this->orderId,OrderDb::WITHDRAW_SUBMIT_TO_BANK);

            //记录成功日志

            self::commit();
            $this->logInfo($orderId,__METHOD__,'提现申请提交银行处理成功');

            //提现申请提交至银行，给用户发送短信
            $params = [
                'order_id'  => $this->orderId,
                'cash'      => $this->cash,
                'user_id'   => $this->userId,
            ];
            Queue::pushOn('withdrawSubmitToBankJob',new WithdrawSubmitToBankSuccessJob($params));



        } catch(\Exception $e) {

            self::rollback();

            $this->logError($orderId,__METHOD__,$e);

            return self::callError($e->getMessage(),self::CODE_ERROR,['order_id'=>$orderId]);
        }

        return self::callSuccess(['order_id'=>$orderId]);
    }



    /**
     * @param $cash
     * @param $userId
     * 取消提现订单号
     */
    public function cancelOrder($orderId,$note){


        try {

            self::beginTransaction();
            //数据验证及金额格式化处理
            $this->beforeDeal($orderId);

            //判断取消提现原因是否为空,为空直接抛出异常
            ValidateModel::checkReasonIsEmpty($note);

            //取消提现订单
            WithdrawModel::canceldOrder($this->orderId,$this->orderStatus,$this->orderType);

            //更新订单扩展信息
            //$note = $note ? $note : OrderDb::WITHDRAW_CANCLE_NOTE;
            OrderModel::updateExtendOrder($this->orderId,$note);

            //取消提现更新每日提现数据
            WithdrawModel::cancelWithdraw($this->createdAt, $this->cash);

            //创建提现取消资金流水
            $fundModel = new UserFundModel();
            $fundModel->increaseUserBalance($this->userId,$this->cash + $this->handlingFee,FundHistoryDb::WITHDRAW_ORDER_CANCEL, $orderId);

            self::commit();

            //记录成功日志

            $this->logInfo($orderId,__METHOD__,'取消提现处理成功');

            //取消成功发送短信

            $params = [
                'event_name'    => 'App\Events\Order\WithdrawCancelSuccessEvent',
                'user_id'       => $this->userId,
                'order_id'      => $this->orderId
            ];
            \Event::fire('App\Events\Order\WithdrawCancelSuccessEvent',[$params]);


        } catch(\Exception $e) {

            self::rollback();

            $this->logError($orderId,__METHOD__,$e);

            return self::callError($e->getMessage(),self::CODE_ERROR,['order_id'=>$orderId]);
        }


        return self::callSuccess(['order_id'=>$orderId]);
    }


    /**
     * @param $userId
     * @return array
     * 获取用户当月的有效提现次数
     */
    public function getWithdrawNum($userId){
        
        try {

            //验证用户ID
            ValidateModel::isUserId($userId);
            //判断用户是否存在
            OrderModel::checkUserIsExist($userId);

            //获取用户本月有效提现次数
            $db = new WithdrawOrderDb();
            $total = $db->getValidWithDrawNumByMonth($userId);

            $data = ['total' => $total];

            return self::callSuccess($data);

        } catch(\Exception $e) {

            return self::callError($e->getMessage());
          
        }


    }

    /**
     * [获取提现订单]
     * @param  [int] $[size] [分页数]
     * @return [array]
     */
    public function getWithdrawOrders($size){

        try{

            $data = WithdrawModel::getWithdrawOrders($size);

            return self::callSuccess($data);

        } catch(\Exception $e) {

            return self::callError($e->getMessage());
          
        }
    }

    /**
     * @desc 获取提现的统计数据
     * @author lgh
     * @param $all
     * @return mixed
     */
    public function getWithdrawStatistics($all){

        //格式充值统计数据
        $where = $this->formatGetListsInput($all);

        $withdrawStatistics = WithdrawModel::getWithdrawStatistics($where);

        return self::callSuccess($withdrawStatistics);
    }

    /**
     * @desc 获取时间段内提现大于5万用户的信息
     * @author lgh
     * @param $all
     * @return array
     */
    public function getWithdrawUserCashFive($all){

        //格式充值统计数据
        $attribute = $this->formatGetListsInput($all);

        $withdrawModel = new WithdrawModel();

        $withdrawUserFive = $withdrawModel->getWithdrawUserCashFive($attribute);

        return self::callSuccess($withdrawUserFive);
    }
}