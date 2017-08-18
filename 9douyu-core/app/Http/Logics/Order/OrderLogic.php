<?php
/**
 * User: zhangshuang
 * Date: 16/4/21
 * Time: 23:41
 * Desc: 订单逻辑层基类
 */

namespace App\Http\Logics\Order;

use App\Http\Dbs\BankDb;
use App\Http\Dbs\OrderDb;
use App\Http\Dbs\WithdrawRecordDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\WarningLogic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Order\OrderModel;
use App\Tools\ToolMoney;
use Log;
use App\Tools\ToolArray;
use App\Http\Dbs\UserDb;
use Illuminate\Support\Facades\Lang;


class OrderLogic extends Logic{

    protected  $userId = 0; //用户ID

    protected  $cash = 0;  //订单金额

    protected  $orderId = '';     //订单号

    protected  $handlingFee = 0;

    protected  $orderType = 0; //手续费

    protected  $orderStatus = 0;

    protected  $createdAt   = ''; //提现时间

    protected  $log = [];       //日志数据
    

    /**
     * @throws \Exception
     * 订单处理前的数据验证及金额格式化
     */
    protected function beforeDeal($orderId){

        $this->orderId = $orderId;

        //验证订单号格式
        //ValidateModel::isOrderId($this->orderId);

        //判断订单是否已存在
        $order = OrderModel::checkOrderIsExist($this->orderId);

        //金额入库前的处理，转化成分
        $this->cash         = $order['cash'];
        $this->userId       = $order['user_id'];
        $this->orderType    = $order['type'];
        $this->orderStatus  = $order['status'];

        $this->handlingFee  = $order['handling_fee'];

        $this->createdAt    = $order['created_at'];
    }

    /**
     * @param $method
     * @param $msg
     * 记录操作成功日志
     */
    protected function logInfo($orderId,$method,$msg){

        $this->log['order_id']  = $orderId;
        $this->log['msg']       = $msg;
        Log::info($method.'Success',$this->log);

    }


    /**
     * @param $method
     * @param \Exception $e
     * 记录操作错误日志
     */
    protected function logError($orderId,$method,\Exception $e){

        $this->log['order_id']  = $orderId;
        $this->log['msg']       = $e->getMessage();
        $this->log['code']      = $e->getCode();
        Log::error($method.'Error',$this->log);
    }



    /**
     * @param $orderId
     * @return array
     * 根据订单号获取订单信息
     */
    public function getOrder($orderId){

        try{

            //ValidateModel::isOrderId($orderId);

            $result = OrderModel::getOrder($orderId);


            $result['cash']             = ToolMoney::formatDbCashDelete($result['cash']);
            $result['handling_fee']     = ToolMoney::formatDbCashDelete($result['handling_fee']);

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess($result);
    }


    /**
     * 获取资金流水-充值提现 列表数据
     * @param array $data
     * @return array
     */
    public function getLists($data = []){

        $data = $this->formatGetListsInput($data);

        try{
            $orderModel = new OrderModel;

            $return           = $orderModel->getLists($data);

            $return           = $this->getExtraList($return);

            $return           =  $this->formatGetListsOutput($return);

            $return = self::callSuccess($return);

        }catch (\Exception $e){

            $data['data'] = $data;

            $data['code'] = $e->getCode();
            $data['msg']  = $e->getMessage();

            $return = self::callError($e->getMessage());
        }
        return $return;

    }

    /**
     * 获取额外订单列表信息
     *
     * @param array $orderList
     * @return array
     */
    protected function getExtraList($orderList = []){
        if(isset($orderList['data']) && !empty($orderList['data'])){
            $array  = ToolArray::arrayToIds($orderList['data'], 'order_id');
            $orderList['extend'] = OrderModel::getExtendList($array);

            if(!empty($orderList['extend'])){
                $bank_ids = ToolArray::arrayToIds($orderList['extend'], 'bank_id');
                if($bank_ids){
                    $orderList['bank'] = OrderModel::getBankList($bank_ids);
                }
            }
        }

        return $orderList;
    }


    /**
     * 格式化输入
     *
     * @param array $data
     * @return mixed
     */
    protected function formatGetListsInput($data = []){

        $attribute                     = [];

        $attribute['page']             = isset($data['page']) ? $data['page'] : 0;
        $attribute['size']             = isset($data['size']) ? $data['size'] : 20;

        $attribute['userId']           = isset($data['userId']) ? $data['userId'] : 0;
        $attribute['type']             = isset($data['type']) ? $data['type'] : 0;

        $attribute['start_time']       = isset($data['start_time']) ? $data['start_time'] : null;
        $attribute['end_time']         = isset($data['end_time']) ? $data['end_time'] : null;

        $attribute['pay_type']         = isset($data['pay_type']) ? $data['pay_type'] : 0;
        $attribute['order_id']         = isset($data['order_id']) ? $data['order_id'] : '';
        $attribute['status']           = isset($data['status']) ? $data['status'] : 0;
        $attribute['app_request']      = isset($data['app_request']) ? $data['app_request'] : '';

        return $attribute;
    }

    /**
     * 格式化输出  【金额从分到元】
     *
     * @param array $return
     * @return array
     */
    protected  function formatGetListsOutput($return = []){
        if(isset($return['data']) && !empty($return['data'])){
            foreach($return['data'] as $key => $value){
                $value['cash'] = ToolMoney::formatDbCashDelete($value['cash']);
                $return['data'][$key] = $value;
            }
        }
        return $return;
    }


    /**
     * @param $all
     * @return array
     * 后台充值订单列表
     */
    public function getAdminList($all){

        $data = $this->formatGetListsInput($all);

        $model = new OrderModel();

        $orderList = $model->getAdminList($data);


        if($orderList['data']){

            $list = $orderList['data'];

            $userIds = ToolArray::arrayToIds($list,'user_id');

            $userDb = new UserDb();

            $userList = $userDb->getUserListByUserIds($userIds);

            $userList = ToolArray::arrayToKey($userList,'id');

            foreach ($list as $k => $val){

                $userId = $val['user_id'];

                $list[$k]['name'] = $userList[$userId]['real_name'];
                $list[$k]['phone'] = $userList[$userId]['phone'];

                $list[$k]['status_note'] =  Lang::get('messages.ORDER_STATUS.STATUS_'.$val['status']);
            }

            $orderList['data'] = $list;

        }

        return self::callSuccess($orderList);

    }

    /**
     * @desc    每小时统计失败订单，发送邮件
     * 渠道名称，订单号，银行卡，充值金额，订单时间，用户手机号，姓名，来源，版本号
     **/
    public function statOrderHour($clientArr=[]){

        $endTime    = date("Y-m-d H:00:00");
        $startTime  = date('Y-m-d H:00:00', (strtotime($endTime) - 3600));
        $resData    = OrderModel::statFailOrderData($startTime, $endTime,$clientArr);

        if(!empty($resData)){
            $payTypeArr = OrderDb::getPayTypeName();
            //获取所有银行名称
            $bankDb     = new BankDb();
            $bankList   = $bankDb->getAllBank();
            $bankList   = ToolArray::arrayToKey($bankList,'id');

            $sendMsg   = "<table border='1' cellspacing='0' cellpadding='0'><tr><td>支付渠道</td><td>订单号</td><td>订单金额</td><td>订单时间</td><td>用户ID</td><td>用户姓名</td><td>手机号</td><td>银行名</td><td>银行卡号</td><td>订单来源</td><td>版本号</td></tr>";
            foreach ($resData as $kk=>$value){

                $bankName   = isset($bankList[$value['bank_id']])?$bankList[$value['bank_id']]['name']:"";
                $sendMsg .= "<tr><td>".$payTypeArr[$value["pay_type"]]."</td><td>".$value["order_id"]."</td><td>".$value["cash"]." </td><td>".$value["created_at"]."</td><td>".$value["user_id"]."</td><td>".$value["real_name"]."</td><td>".$value['phone']."</td><td>".$bankName."</td><td>".$value['card_number']."</td><td>".$value['app_request']."</td><td>".$value['version']."</td></tr>";
            }
            $sendMsg    .= "</table>";
            #echo $sendMsg;exit;
            #获取邮件接收者
            $configData     = WarningLogic::getConfigDataByKey('ORDER_STAT_RECEIVE_ADMIN');
            $arr['subject'] = $sendMsg;

            $arr['title']   = '【ORDER】-开始时间：'.$startTime." 到 ".$endTime." 失败订单";

            WarningLogic::doSend($configData, $arr);

        }

    }

    /**
     * @desc    每日统计-支付渠道   总订单数，成功订单数，成功充值金额，失败订单数，失败充值金额
     ***/
     public function statOrderPayTypeDay($clientArr=[]){

         $startTime = date('Y-m-d 00:00:00', strtotime(' -1 day'));
         $endTime   = date("Y-m-d 00:00:00");

         $resData   = OrderDb::statOrderWithPayType($startTime, $endTime,$clientArr);

         if(!empty($resData)){
             $payTypeArr= OrderDb::getPayTypeName();
             $sendMsg   = "<table border='1' cellspacing='0' cellpadding='0'><tr><td>支付渠道</td><td>总订单数</td><td>成功订单数</td><td>成功充值金额</td><td>失败订单数</td><td>失败充值金额</td></tr>";
             foreach ($resData as $kk=>$value){
                 $sendMsg    .= "<tr><td>".$payTypeArr[$value["payType"]]."</td><td>".$value["totalCount"]."</td><td>".$value["succCount"]."</td><td>".$value["succCash"]."</td><td>".$value["failCount"]."</td><td>".$value["failCash"]."</td></tr>";
             }
             $sendMsg .="</table>";
             #echo $sendMsg;exit;
             #获取邮件接收者
             $configData     = WarningLogic::getConfigDataByKey('ORDER_STAT_RECEIVE_ADMIN');

             $arr['subject'] = $sendMsg;

             $arr['title']   = '【ORDER】-统计日期：'.$endTime." 各支付渠道-订单支付信息";

             WarningLogic::doSend($configData, $arr);
         }

     }
    
}