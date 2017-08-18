<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/6
 * Time: 上午11:57
 */
namespace App\Http\Logics\Order;

use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Dbs\OrderDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Module\SystemConfig\SystemConfigLogic;
use App\Http\Logics\Notice\NoticeLogic;
use App\Http\Logics\Recharge\OrderLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\ThirdApi\PfbLogic;
use App\Http\Models\Common\CoreApi\BankCardModel;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Http\Models\Common\TradingPasswordModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Order\WithdrawModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Models\User\UserModel;
use App\Lang\LangModel;
use App\Tools\ExportFile;
use App\Tools\ToolMoney;
use App\Tools\ToolOrder;
use App\Tools\ToolTime;
use EasyWeChat\Payment\Order;
use App\Http\Dbs\Bank\BankDb;
use App\Tools\ToolArray;

class WithdrawLogic extends Logic
{

    /**
     * 处理订单并发送处理消息
     * @param $result
     * @return array
     */
    public function sendDoneMsg($id = ''){
        $withdrawModel = new WithdrawModel();
        if(empty($id)){
            //批量处理订单并发送短信
            $return = $withdrawModel->batchSendDoneMsg();
        }else{
            //指定订单处理并发送短信
            $return = $withdrawModel->sendDoneMsg($id);
        }
        if($return)
            return self::callSuccess([],LangModel::getLang('SUCCESS_WITHDRAW'));
        return self::callError(LangModel::getLang('ERROR_WITHDRAW'));
    }


    /**
     * @param $userId
     * @param $cash
     * @return array
     * App端的提现预览
     */
    public function preWithdraw($userId,$cash){

        try{

            ValidateModel::isUserId($userId);
            ValidateModel::isDecimalCash($cash);

            //判断用户是否实名 + 设置交易密码
            $userInfo = UserModel::getUserInfo($userId);
            UserModel::checkUserAuthStatus($userInfo);

            $result = $this->getHandingFee($userId);

            $handingFee = $result['handing_fee'];

            $data = [
                'cash'      => ToolMoney::formatDbCashDelete(($cash - $handingFee)),//到账金额
                'fee'       => ToolMoney::formatDbCashDelete($handingFee),  //手续费
                'inputCash' => ToolMoney::formatDbCashDelete($cash),        //输入金额
                'oriCash'   => ToolMoney::formatDbCashDelete($cash),        //原始金额
                'free_num'  => $result['free_num'],                         //本月还剩余免手续费的提现次数
                //'desc'      => 'T+0提现(节假日顺延)',                         //提现说明
                'desc'      => 'T+1提现(节假日顺延)',
            ];

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }
        

        return self::callSuccess($data);

    }


    /**
     * 计算提现手续费
     * @param $type
     * @param $bank_id
     * @return int
     */
    public function getHandingFee($userId){

        $handingFee = 0;
        $withDrawNum = 0;

        $withDrawConfig = SystemConfigModel::getConfig("NEW_USER_WITHDRAW_CONFIG");

        if(!empty($withDrawConfig)){

            //当月可以免费提现次数
            $freeNum = $withDrawConfig['MAX_FREE_NUM'];
            //本月已提现次数
            $result = OrderModel::getUserMonthWithdrawNum($userId);

            if(!empty($result)){
                $withDrawNum = $result['total'];
            }
            //计算手续费
            if($withDrawNum>=$freeNum) {
                $handingFee = $withDrawConfig['HANDING_FREE'];

                $freeNum = 0;
            }else{

                $freeNum -= $withDrawNum;
            }


        }
        return [
                    'handing_fee' => ToolMoney::formatDbCashAdd($handingFee),
                    'free_num'         => $freeNum,
                ];
    }

    /**
     * @param $data
     * 创建提现订单号
     */
    public function doWithdraw($data){

        $userId         = $data['user_id'];
        $cash           = $data['cash'];
        $tradePasswd    = $data['trading_password'];
        $bankCardId     = $data['bank_card_id'];


        try{

            //数据验证
            ValidateModel::isUserId($userId);
            ValidateModel::isDecimalCash($cash);

            $userInfo = UserModel::getUserInfo($userId);

            //判断用户是否实名 + 设置交易密码
            UserModel::checkUserAuthStatus($userInfo);

            //判断账户余额是否充足
            ValidateModel::checkBalance($userInfo['balance'],$cash);

            //交易密码为空
            if(empty($userInfo['trading_password'])){

                return self::callError(LangModel::getLang('ERROR_EMPTY_TRADING_PASSWORD'));
            }

            //判断银行卡是否存在
            $cardInfo = BankCardModel::getWithdrawCardById($bankCardId);

            if(empty($cardInfo)){
                return self::callError(LangModel::getLang('ERROR_BANK_CARD_IS_NOT_EXIST'));
            }

            //难证交易密码
            TradingPasswordModel::checkPassword($tradePasswd,$userInfo['trading_password']);

            $feeInfo    = $this->getHandingFee($userId);
            
            $handingFee = $feeInfo['handing_fee'];//手续费


            \Log::info(__METHOD__ .'检测普付宝润徐提现额度:', [$cash, $handingFee, $data]);

            PfbLogic::checkPledgeAmount($cash);

            //真实提现金额(扣除手续费)
            $cash       -= $handingFee;


            $result = $this->createOrder($userId,$cash,$handingFee,$data['from'],$cardInfo);

            //创建订单成功
            if($result['status']){

                $data = [
                    'cash'          => ToolMoney::formatDbCashDelete($cash),
                    'maybe_time'    => date('Y年m月d日',strtotime('+1 day')),
                    'withDrawCard'  => ToolOrder::hideCardNumber($cardInfo['card_number']),
                ];

                $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_ORDER_WITHDRAW_CREATE);
                // $msgTpl为数组 抛出异常了
                if(is_string($msgTpl)) {
                    $msg = sprintf($msgTpl, ToolTime::dbNow(), $cash);

                    $event['notice'] = [
                        'title' => NoticeDb::TYPE_ORDER_WITHDRAW_CREATE,
                        'user_id' => $userId,
                        'message' => $msg,
                        'type' => NoticeDb::TYPE_ORDER_WITHDRAW_CREATE
                    ];

                    \Event::fire(new \App\Events\Order\WithdrawCreateSuccessEvent($event));
                }
                return self::callSuccess($data);

            }else{

                return self::callError($result['msg']);
            }

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }


    }


    /**
     * @param $userId
     * @param $cash
     * @param $handingFee
     * @param $from
     * @param $cardInfo
     * @return array
     * 创建提现订单
     */
    private function createOrder($userId,$cash,$handingFee,$from,$cardInfo){

        $params = [
            'order_id'      => ToolOrder::generateOrder(),
            'user_id'       => $userId,
            'bank_id'       => $cardInfo['bank_id'],
            'card_no'       => $cardInfo['card_number'],
            'handing_fee'   => $handingFee,
            'cash'          => $cash,
            'type'          => OrderDb::RECHARGE_WITHDROW_TYPE,
            'from'          => $from
        ];

        //创建支付订单
        $result = OrderModel::doCreateWithdrawOrder($params);

        return $result;
    }


    /**
     * @param $data
     * 后台提现列表
     */
    public function getAdminList($data){
        
        $data['type'] = OrderDb::WITHDRAW_TYPE;

        if(isset($data['phone']) && $data['phone']){

            $phone = $data['phone'];

            try{

                ValidateModel::isPhone($phone);
                $userInfo = UserModel::getCoreApiBaseUserInfo($phone);


            }catch (\Exception $e){

                return [
                    'total' => 0,
                    'data' => [],
                    'status_list' => OrderDb::orderStatusList(),
                ];
            }

            if($userInfo){
                $data['userId'] = $userInfo['id'];
            }else{
                return [ 'total' => 0, 'data' => [] ];
            }

        }

        //$data      =   OrderLogic::doSetAttrValue($data);
        
        $orderList = OrderModel::getAdminOrderList($data);

//        $total_cash         =   0;
//        $free_cash_total    =   0;
        if( isset($orderList['total']) && $orderList['total'] > 0){

            $bankDb = new BankDb();
            $bankList = $bankDb->getAllBank();
            $bankList = ToolArray::arrayToKey($bankList,'id');

            foreach($orderList['data'] as $k=>$val){
                if(isset($bankList[$val['bank_id']])){
                    $orderList['data'][$k]['bank_name'] = $bankList[$val['bank_id']]['name'];

                }else{
                    $orderList['data'][$k]['bank_name'] = '未知';

                }
//                $total_cash += $val['cash'];
//                $free_cash_total  += $val['handling_fee'];

            }
        }
//            $orderList['total_cash']    =   $total_cash;
//            $orderList['handling_fee_total']    =   $free_cash_total;

        return $orderList;
    }

    /**
     * @return array
     * @desc 设置订单状态
     */
    public static function setOrderStatus()
    {
        return  OrderDb::orderStatusList();
    }

    /**
     * @return array
     * 批量发送提现通知短信
     */
    public function sendBatchMsg($id){

        $params = [
            'id' => $id
        ];

        return OrderModel::doBatchSendWithdrawNoticeSms($params);
    }


    /**
     * @param $orderId
     * @return array
     * 根据订单号获取订单明细
     */
    public function getOrderInfo($orderId){

        $order =  OrderModel::getOrderInfo($orderId);
        if(!empty($order['created_at'])) {
            $order['isCanCancelWithDraw'] = $this->checkOrderCancel($order['created_at']);
        }

        return $order;

    }


    /**
     * @param $order
     * 是否显示可编辑按钮
     */
    public function showEditButton($order){

        $status = $order['status'];

        $isShowEdit = 0;

        if($status == OrderDb::STATUS_ING && $this->checkOrderCancel($order['created_at'])){

            $isShowEdit = 1;
        }

        $statusList = [

            OrderDb::STATUS_CACLE,
            OrderDb::STATUS_ERROR,
            OrderDb::STATUS_SUCCESS
        ];

        if(!in_array($status,$statusList)){

            $isShowEdit = 1;
        }
        return $isShowEdit;
    }


    /**
     * @param $data
     * 后台编辑订单
     */
    public function doEdit($data){

        $status     = $data['status'];
        $orderId    = $data['order_id'];
        $note       = $data['note'];

        $result = [
            'msg' => '',
            'status' => false
        ];

        switch ($status){

            case OrderDb::STATUS_CACLE:{

                if($this->checkOrderCancel($data['create_time'])){

                    $result = OrderModel::doCancelWithdrawOrder($orderId,$note);
                }else{

                    $result['msg'] = '09:30以后不能取消今日之前的订单';
                }

                break;
            }

            case OrderDb::STATUS_SUCCESS:{

                $result = OrderModel::doSuccWithdrawOrder($orderId,$note);

                break;
            }

            case OrderDb::STATUS_ERROR:{
                $result = OrderModel::doFailedWithdrawOrder($orderId,'',$note);
                break;
            }

            default:{

                $result['msg'] = '无效的订单状态';
            }
        }


        return $result;
    }


    /**
     * @param $createTime
     * @return int
     * 当天09:30以后无法取消前一天的提现订单
     */
    private function checkOrderCancel($createTime){

        $isCanCancelWithDraw = 1;

        /*if($createTime < date('Y-m-d 00:00:00') && date('H-i') > '09-30'){

            $isCanCancelWithDraw = 0;
        }*/

        return $isCanCancelWithDraw;
    }

    /**
     * @desc 获取充值数据统计
     * @author lgh
     * @param $param
     * @return array
     */
    public function getWithdrawStatistics($param){

        $withdrawStatistics =  OrderModel::getWithdrawStatistics($param);

        return $withdrawStatistics;
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return bool
     * @desc 数据导出
     */
    public function doExport( $statistics ){

        $list[] = [

            '订单ID','到账金额',	'手续费', '姓名',	'手机号码', '银行名称' , '银行卡号' ,'创建时间','处理时间' , '状态'
        ];

        $data       = $this->getAdminList($statistics);

        if( empty($data) ){return false;}

        $formatData = self::formatExportStatistics($data['data']) ;

        $list = array_merge($list, $formatData);

        ExportFile::csv($list, 'withdraw_order-'.ToolTime::dbDate());

    }

    /**
     * @param $statisticsData
     * @return array
     * @desc 格式化数据
     */
    protected static function formatExportStatistics($statisticsData)
    {
        $formatStatistics =   [];

        if( empty($statisticsData) ){

            return $formatStatistics;
        }
        $bankTypeList       =   OrderLogic::setBankList();

        foreach ($statisticsData as $key => $statistics ){
//'订单ID','到账金额',	'手续费', '姓名',	'手机号码', '银行名称' , '银行卡号' ,'创建时间','处理时间' , '状态' , '备注'
            $formatStatistics[$key] =   [
                'order_id'      =>  $statistics['order_id'],
                'cash'          =>  $statistics['cash'],
                'handling_fee'  =>  $statistics['handling_fee'],
                'name'          =>  isset($statistics['name']) ? $statistics['name'] : "",
                'phone'         =>  isset($statistics['phone']) ? $statistics['phone'] : '',
                'bank_name'     =>  isset($statistics['bank_name']) ? $statistics['bank_name'] : $bankTypeList[$statistics['bank_id']]['name'],
                'card_number'   =>  isset($statistics['card_number']) ? $statistics['card_number'] : "",
                'created_at'    =>  $statistics['created_at'],
                'updated_at'    =>  $statistics['updated_at'],
                'status_note'   =>  isset($statistics['status_note']) ? $statistics['status_note'] : "",
            ];
        }

        return array_filter($formatStatistics);
    }


    /**
     * @param $page
     * @param $size
     * @return array
     * T+0提现处理列表
     */
    public function getWithdrawRecord($page,$size){

        $parmas = [
            'page' => $page,
            'size' => $size
        ];

        return OrderModel::getWithdrawRecord($parmas);
    }


    /**
     * @param $id
     * @return null|void
     * T+0提现发送指定时间段的通知短信
     */
    public function sendWithdrawMsg($id){

        $params = [
            'id' => $id
        ];
        
        return OrderModel::sendWithdrawMsg($params);

    }

    /**
     * @param $email
     * @return null|void
     * T+0重新发送提现邮件
     */
    public function sendWithdrawEmail($id,$email){

        $params = [
            'email' => $email,
            'id'    => $id
        ];

        return OrderModel::sendWithdrawEmail($params);
    }

    /**
     * @param $email
     * @return null|void
     * T+0重新发送提现邮件
     */
    public function sendWithdrawEmailNew($id,$email,$type){

        $params = [
            'email' => $email,
            'id'    => $id,
            'type'  => $type,
        ];

        return OrderModel::sendWithdrawEmailNew($params);
    }

    /**
     * @param $params
     * @return array
     * @desc 导出数据
     */
    public static function doExportRecharge( $params )
    {
        $rechargeInfo   =   OrderModel::getRechargeOrderTotal($params);

        if( empty($rechargeInfo) ){

            return self::callError("数据不存在");
        }

        $list[] = [

            '平台名词',	'金额',
        ];

        $formatData = self::formatExportRecharge($rechargeInfo) ;

        $list = array_merge($list, $formatData);

        ExportFile::csv($list, 'recharge_total_order-'.ToolTime::dbDate());
    }

    /**
     * @param $rechargeInfo
     * @return array
     * @desc 格式化数据
     */
    protected static function formatExportRecharge($rechargeInfo = array())
    {
        if( empty($rechargeInfo) ){

            return[];
        }

        $typeList   =   OrderLogic::setOrderTypeList();

        $channelList=   $typeList['channel_list'];

        $formatInfo =   [];

        foreach ($rechargeInfo as $key => $item ){

            $formatInfo[$key]   =[

                "name"=>isset($channelList[$item['channel']]['name']) ? $channelList[$item['channel']]['name'] : $channelList[OrderDb::RECHARGE_REAPAY_WITHHOLD_OTHER]['name'],

                "cash"=>$item['total_cash'],
            ];
        }

        return $formatInfo;

    }
}