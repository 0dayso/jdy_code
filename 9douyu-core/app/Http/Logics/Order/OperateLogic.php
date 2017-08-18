<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 13:42
 * Desc: 订单相关些的操作逻辑
 * 包括:定时发送提现邮件  提现订单自动对账等
 * 新建逻辑类,以免影响充值\提现正常逻辑
 */
namespace App\Http\Logics\Order;
use App\Http\Dbs\WithdrawRecordDb;
use App\Http\Logics\Logic;
use App\Http\Dbs\WithdrawOrderDb;
use App\Http\Logics\Warning\OrderLogic;
use App\Http\Models\Order\OperateModel;
use App\Jobs\Order\BatchSendWithdrawEmailJob;
use App\Jobs\Order\BatchSendWithdrawEmailSumaJob;
use App\Tools\ToolArray;
use App\Jobs\Order\BatchSendWithdrawMsgJob;
use App\Http\Models\Order\WithdrawModel;
use App\Http\Models\Order\OrderModel;
use App\Http\Dbs\OrderDb;
use App\Tools\ToolMoney;
use App\Lang\LangModel;
use App\Http\Models\Common\SmsModel;
use Illuminate\Support\Facades\App;
use App\Jobs\Order\BatchWithdrawOrderHandleJob;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Models\Common\UserFundModel;
use Queue;
use Log;
use Illuminate\Support\Facades\Lang;


class OperateLogic extends Logic{



    /**
     * 批量修改提现订单的提现状态
     * 主要功能:财务从网银后台下载最新的提现表格上传九斗鱼
     * 后台,根据代付的状态自动处理九斗鱼的提现状态
     */
    public function batchCheckAccount($data){

        //提现申请提交至银行，给用户发送短信
        //直接入队列,加快响应速度
        $result = Queue::pushOn('batchWithdrawOrderHandle',new BatchWithdrawOrderHandleJob($data));
        //入队列成功
        if($result){
            return self::callSuccess([]);
        }

        OrderLogic::batchCheckAccountWarning($data);

        return self::callError('批量提现对账失败,请重试');

    }


    /**
     * 批量将提现请求发送至银行
     * 主要功能:财务将提现邮件提交到网银后台,
     * 在九斗鱼后台批量给用户发送提示短信,告知用户提现
     * 请求已提交到银行,并将订单状态改为处理中
     */
    public function batchWithdrawSubmitToBank($id){

        //提现申请提交至银行，给用户发送短信
        //直接入队列,加快响应速度
        $params = [
            'id' => $id
        ];
        $result = Queue::pushOn('batchSendWithdrawMsg',new BatchSendWithdrawMsgJob($params));
        //入队列成功
        if($result){
            return self::callSuccess([]);
        }

        OrderLogic::batchWithdrawSubmitToBankWarning([]);

        return self::callError('批量提现短信发送失败');

    }


    /**
     * @param $id
     * @param $email
     * @return array
     * T+0邮件可多次发送
     */
    public function sendWithdrawEmailById($id,$email){

        //直接入队列,加快响应速度
        $params = [
            'id'    => $id,
            'email' => $email
        ];
        $result = Queue::pushOn('batchSendWithdrawEmail',new BatchSendWithdrawEmailJob($params));
        //入队列成功
        if($result){
            return self::callSuccess([]);
        }

        //OrderLogic::batchWithdrawSubmitToBankWarning([]);

        return self::callError('批量提现邮件发送失败');

    }

    /**
     * @param   $id
     * @param   $email
     * @return  array
     * T+0邮件可多次发送
     */
    public function sendWithdrawEmailNewById($id,$email,$type){

        //直接入队列,加快响应速度
        $params = [
            'id'    => $id,
            'email' => $email,
            'payChannel' => $type,
        ];
        \Log::info(__METHOD__.' : '.__LINE__, $params);
        #$db    = new WithdrawRecordDb();
        #$record= $db->getRecord($id);
        #$result= $this->sendWithdrawEmailNew($record['start_time'],$record['end_time'],[ $email => 'admin'], $type);
        #$result= true;

        $result = Queue::pushOn('batchSendWithdrawEmail',new BatchSendWithdrawEmailJob($params));
        //入队列成功
        if($result){
            return self::callSuccess([]);
        }

        //OrderLogic::batchWithdrawSubmitToBankWarning([]);

        return self::callError('批量提现邮件发送失败');

    }


    /**********************************************发送提现邮件开始*******************************************************/

    /**
     * 定时以邮件发送未处理的提现请求
     * 每天9:30发送
     */
    public function sendWithdrawEmail($startDate,$endDate,$emails = []){
        
        $db = new WithdrawOrderDb();
        /*
        //通过时间获取十天之内未处理的提现订单总数,添加时间可以加快查询速度
        $startDate = date('Y-m-d',strtotime("-10 day"));
        $endDate   = date('Y-m-d 08:00:00');
        $endDate   = date('Y-m-d');
        */
        //获取指定日期之内的提现订单数
        $total = $db->getDealingOrderTotalByDate($startDate,$endDate);
        //没有提现请求
        if($total === 0){
            //处理逻辑待完善,发送邮件提醒,可能存在问题
            $data = [
                'start_time' => $startDate,
                'end_time'   => $endDate,
                'msg' => '未发现提现订单'
            ];

            OrderLogic::withdrawOrderEmailWarning($data);
        }else {
            $withdrawModel = new OperateModel();
            try{
                $withdrawModel->sendWithdrawEmail($startDate, $endDate,$total,$emails);
            }catch(\Exception $e){

                $data = [
                    'start_time' => $startDate,
                    'end_time'   => $endDate,
                    'msg' => $e->getMessage()
                ];

                OrderLogic::withdrawOrderEmailWarning($data);
            }

        }
    }

    /**
     * @desc    提现代付-发邮件
     * @date    2017-03-24
     * @author  @linglu
     *
     */
    public function sendWithdrawEmailNew($startDate,$endDate,$emails = [],$payChannel=""){

        $db = new WithdrawOrderDb();
        //获取指定日期之内的提现订单数
        $total = $db->getDealingOrderTotalByDate($startDate,$endDate);

        //没有提现请求
        if($total === 0){
            //处理逻辑待完善,发送邮件提醒,可能存在问题
            $data = [
                'start_time' => $startDate,
                'end_time'   => $endDate,
                'msg' => '未发现提现订单'
            ];

            OrderLogic::withdrawOrderEmailWarning($data);
        }else {
            $withdrawModel = new OperateModel();
            try{
                if($payChannel == "suma"){
                    $withdrawModel->sendWithdrawEmailSuma($startDate, $endDate,$total,$emails);
                }elseif($payChannel == "ucf"){
                    $withdrawModel->sendWithdrawEmailUcf($startDate, $endDate,$total,$emails);
                }
            }catch(\Exception $e){

                $data = [
                    'start_time' => $startDate,
                    'end_time'   => $endDate,
                    'msg' => $e->getMessage()
                ];

                OrderLogic::withdrawOrderEmailWarning($data);
            }

        }
    }

    /**********************************************发送提现邮件结束*******************************************************/



    /*********************************批量发磅提现通知短信逻辑开始***************************************/

    /**
     * 批量发送提现通知短信
     */
    public function batchSendWithdrawMsg($id){

        //提现已处理
        $recordDb = new WithdrawRecordDb();

        $record = $recordDb->getRecord($id);

        if(!$record || ($record['status'] == WithdrawRecordDb::STATUS_FINISHED)){

            return false;
        }

        $startDate = $record['start_time'];
        $endDate   = $record['end_time'];

        $db = new WithdrawOrderDb();

        /*
        //通过时间获取十天之内未处理的提现订单总数,添加时间可以加快查询速度
        $startDate = date('Y-m-d',strtotime("-10 day"));
        $endDate   = date('Y-m-d 08:00:00');
        $endDate   = date('Y-m-d');
        */
        //获取指定日期之内的提现订单数
        $total = $db->getUnDealOrderTotalByDate($startDate,$endDate);

        //如果没有提现请求
        if($total === 0){

        }else{

            $size = 10;
            $totalPage  = ceil($total / $size); //总页数

            //修改失败的订单列表
            $failedOrderList = [];

            //发送失败的手机号列表
            $failedPhoneList = [];
            //分页获取提现数据
            for($page = 1;$page <= $totalPage;$page++){

                //分页获取未处理的提现请求的用户信息
                $result = $this->sendWithdrawMsgByPage($startDate,$endDate,$size);

                //合并所有修改失败的订单号
                $failedOrderList = array_merge($failedOrderList,$result['order_list']);

                //合并所有发送失败的手机号
                $failedPhoneList = array_merge($failedPhoneList,$result['phone_list']);
            }

            //发送报警邮件,待完善
            //$this->sendWarningEmail($failedOrderList,$failedPhoneList);
            //修改T+0指定时间的状态为成功
            $recordDb->doDeal($id);
        }

    }


    /**
     * @param $startDate
     * @param $endDate
     * @param $page
     * @param $size
     * 分页获取指定时间内提现未处理的用户信息
     */
    public function sendWithdrawMsgByPage($startDate,$endDate,$size=100){

        $model = new OperateModel();
        //获取未处理的订单列表
        $orderList = $model->getListByPage($startDate,$endDate,$size);

        //根据订单列表获取相应的用户信息
        $userList  = $model->getUserListByOrderList($orderList);

        //发送失败手机号列表
        $sendFiledPhoneArr = [];

        //更新订单号失败列表
        $orderFailedArr    = [];

        //循环处理提现订单并发送短信
        foreach ($orderList as $key => $val){

            $cash       = $val['cash'];//提现金额处理成元
            $userId     = $val['user_id'];                  //用户ID
            $orderId    = $val['order_id'];                 //订单号

            $name       = $userList[$userId]['real_name'];  //姓名

            $phone      = $userList[$userId]['phone'];      //用户手机号

            $formatDate = date("Y年m月d日 H:i", strtotime($val["created_at"]));//提现时间

            //通知短信内容

            //$smsText    = sprintf(LangModel::getLang('PHONE_VERIFY_CODE_WITHDRAW_DEALING'),$name,$formatDate,$cash);
            $smsText    = '【九斗鱼】亲爱的'.$name.'，您于'.$formatDate.'申请'.$cash.'元提现已提交银行处理，到账时间以银行处理时间为准。客服4006686568';
            //修改订单状态为301-处理中状态
            $result = $this->updateOrder($orderId,$val['status'],$val['type']);

            //更新订单成功
            if( $result['status'] ){

                //发送短信
                $smsModel       = new SmsModel();

                $sendResult     = $smsModel->sendNotice($phone,$smsText);

                //发送提醒短信失败
                if($sendResult['status'] === false){

                    $sendFiledPhoneArr[] = $phone;

                }

            }else{

                $orderFailedArr[] = $orderId;

            }

            //记录日志
            Log::info('sendWithdrawMsgByPage',[[]]);

        }

        return [
            'order_list' => $orderFailedArr,
            'phone_list' => $sendFiledPhoneArr,
        ];
    }



    /**
     * @param $orderId
     * @param $orderStatus
     * @param $orderType
     * @return array
     * @desc 将提现订单状态改为301处理中状态
     */
    private function updateOrder($orderId,$orderStatus,$orderType){

        $return = [
            'status'    => true,
            'msg'       => '更新订单状态成功',
            'order_id'  => $orderId,
        ];

        try {

            self::beginTransaction();

            //创建提现订单
            WithdrawModel::submitToBank($orderId,$orderStatus,$orderType);

            OrderModel::updateExtendOrder($orderId,OrderDb::WITHDRAW_SUBMIT_TO_BANK);

            self::commit();

        } catch(\Exception $e) {

            self::rollback();

            $return['msg'] = $e->getMessage();

        }

        return $return;
    }


    /*********************************************批量发送提现通知短信结束*************************************************/


    /*********************************************批量批量对账逻辑开始****************************************************/

    /**
     * @param $orderData
     * 提现自动对账处理逻辑
     * 修改订单状态为成功或失败
     */
    public function batchWithdrawOrderHandle($orderData){

        //数据格式化处理
        $orderList  = json_decode($orderData,true);

        //数据格式错误
        if(empty($orderList) || !is_array($orderList)){

            exit('非法的数据,程序退出');

        }
        $orderArr = ToolArray::arrayToKey($orderList,'order_id');

        $orderIds = array_keys($orderArr);

        //获取多个提现订单的信息
        $orderDb  = new OrderDb();

        $list = $orderDb->getOrderByOrderIds($orderIds);

        //处理失败的订单列表
        $failedList = [];

        //循环处理提现结果
        foreach($list as $val){

            $handleStatus = false;
            
            $userId         = $val['user_id'];

            $orderId        = $val['order_id'];

            //网银处理的状态
            $status         = $orderArr[$orderId]['status'];

            $orderStatus    = $val['status'];   //数据库的订单状态

            $orderType      = $val['type'];     //订单类型 1-充值 2-提现

            switch($status){

                //提现成功
                case OrderDb::STATUS_SUCCESS:{

                    $handleStatus = $this->withdrawSuccessHandle($orderId,$orderStatus,$orderType);

                    break;

                }

                //提现失败
                /*case OrderDb::STATUS_FAILED:{
                    //提现失败原因
                    $note   = $orderArr[$orderId]['note'];
                    //提现金额,提现失败将钱返回用户账户
                    $cash   = $val['cash'];
                    $handleStatus = $this->withdrawFailedHandle($userId,$orderId,$orderStatus,$orderType,$cash,$note);

                    break;
                }*/

                //错误的订单状态
                default:{

                    $failedList[] = [
                        'order_id'  => $orderId,
                        'user_id'   => $userId,
                        'status'    => $orderStatus,
                        'cash'      => $val['cash'],
                        'note'      => isset($orderArr[$orderId]['note']) ? $orderArr[$orderId]['note'] : ''
                    ];

                    break;
                }

            }

            //处理失败,记录订单号,并发送报警邮件
            if($handleStatus === false){

                $failedList[] = [
                    'order_id'  => $orderId,
                    'user_id'   => $userId,
                    'status'    => $orderStatus,
                    'cash'      => $val['cash'],
                    'note'      => isset($orderArr[$orderId]['note']) ? $orderArr[$orderId]['note'] : ''
                ];

            }
        }

        //存在处理失败的订单号
        if(!empty($failedList)){
            //提现批量对账处理失败的订单,直接触发事件
            $params = [
                'event_name'      => 'App\Events\Api\Order\WithdrawHandleFailedEvent',
                'failed_order'    => json_encode($failedList),
            ];
            \Event::fire('App\Events\Api\Order\WithdrawHandleFailedEvent',[$params]);

        }

    }


    /**
     * @param $orderId
     * @param $status
     * @param $type
     * @return mixed
     * 提现成功
     */
    private function withdrawSuccessHandle($orderId,$orderStatus,$orderType){

        $status = true;
        try {

            self::beginTransaction();

            //创建提现订单
            WithdrawModel::succOrder($orderId,$orderStatus,$orderType);

            OrderModel::updateExtendOrder($orderId,OrderDb::WITHDRAW_SUCCESS_NOTE);

            self::commit();

        } catch(\Exception $e) {

            self::rollback();

            $status = false;
        }

        return $status;
    }

    /**
     * @param $orderId
     * @param $orderStatus
     * @param $orderType
     * @param $note
     * @return array
     */
    private function withdrawFailedHandle($userId,$orderId,$orderStatus,$orderType,$cash,$note=''){

        $status = true;

        try {

            self::beginTransaction();

            //创建提现订单
            WithdrawModel::failedOrder($orderId,$orderStatus,$orderType);

            $note = $note ? $note : OrderDb::WITHDRAW_FAILED_NOTE;
            //更新订单扩展信息
            OrderModel::updateExtendOrder($orderId,$note);

            //创建提现失败资金流水
            $fundModel = new UserFundModel();
            $fundModel->increaseUserBalance($userId,$cash,FundHistoryDb::WITHDRAW_ORDER_FAILED);

            self::commit();


        } catch(\Exception $e) {

            self::rollback();

            $status = false;
        }

        return $status;

    }

    /*********************************************提现批量对账逻辑结束****************************************************/


    /**
     * @param $page
     * @param $size
     * @return array
     * 提现列表
     */
    public function getWithdrawList($page,$size){


        $db = new WithdrawRecordDb();

        $total = $db->getTotal();

        $list = [];

        if($total > 0){
            $list = $db->getList($page,$size);

            foreach($list as $k=>$val){

                $list[$k]['status_note'] =  Lang::get('messages.WITHDRAWRECORD_STATUS.STATUS_'.$val['status']);
            }
        }


        $data = [
            'list' => $list,
            'total' => $total
        ];

        return self::callSuccess($data);
    }

    /**
     * 定时以邮件发送未处理的提现请求
     * 每天08:10或15:10创建每日提现数据
     */
    public function addWithdrawRecord($startDate,$endDate){

       $db = new WithdrawOrderDb();

       //获取指定日期之内的提现订单数
       $total = $db->getUnDealOrderTotalByDate($startDate,$endDate);
       //没有提现请求
       if($total === 0){
               //处理逻辑待完善,发送邮件提醒,可能存在问题
               $data = [
                       'start_time' => $startDate,
                       'end_time'   => $endDate,
                       'msg' => '未发现提现订单'
                       ];

               OrderLogic::withdrawOrderEmailWarning($data);
           }else {
               $withdrawModel = new OperateModel();
               try{
                       $withdrawModel->addWithdrawRecordsNew($startDate, $endDate,$total);
                   }catch(\Exception $e){

                       $data = [
                               'start_time' => $startDate,
                               'end_time'   => $endDate,
                               'msg' => $e->getMessage()
                               ];

                       OrderLogic::withdrawOrderEmailWarning($data);
                   }

        }
    }


}