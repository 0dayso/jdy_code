<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/5/19
 * Time: 上午11:09
 */
namespace App\Http\Models\Pay;

use App\Http\Dbs\Bank\BankListDb;
use App\Http\Dbs\OrderDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\BankCardModel;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Common\NationalModel;
use App\Http\Models\Model;
use App\Http\Dbs\Order\UserPayListDb;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolMoney;
use Config;
use JDY\Model\Pay\CardModel;


class RechargeModel extends Model{

    /**
     * @param $userId
     * @param $orderId
     * 更新用户今日的成功充值记录
     */
    public function updateRecord($orderId){

        //$orderInfo = $this->getOrder($orderId);

        $orderInfo = OrderModel::getOrderInfo($orderId);
        
        if(!$orderInfo){

            return true;
        }
        $payType = $orderInfo['pay_type'];

        //网银 1000 - 1100 不需要记录
        if($payType >= OrderDb::RECHARGE_CBPAY_ONLINE_TYPE && $payType < OrderDb::RECHARGE_LLPAY_AUTH_TYPE){

            return true;
        }

        //银行ID
        $bankId = $orderInfo['bank_id'];
        //金额
        $cash   = $orderInfo['cash'];
        $userId = $orderInfo['user_id'];

        $db = new UserPayListDb();
        $result = $db->getUserPayList($userId,$bankId,$payType);

        if($result){
            $result = $db->updateRecord($userId,$bankId,$payType,$cash);
            if(!$result){
                return false;
            }
        }else{
            $db->addRecord($userId,$bankId,$payType,$cash);
        }

    }

    
    /**
     * 获取用户认证卡信息
     * @param int $userId
     * @return array
     */
    public function getAuthCard($userId){

        /*
        $authCard = array();
        $api = Config::get('coreApi.moduleBankCard.getUserBindCard');
        $res = HttpQuery::corePost($api,array('user_id'=>$userId));
        if($res['code']==Logic::CODE_SUCCESS){
            $authCard = $res['data'];
        }
        return $authCard;
        */

        $authCard = BankCardModel::getUserBindCard($userId);

        return $authCard;
    }

    /**
     * 获取用户认证卡限额--(已移除core)
     * @param int $userId
     * @return array
     */
//    public function getAuthCardLimit($userId){
//        $authCardLimit = array();
//        $api = Config::get('coreApi.moduleBankCard.getUserBindCard');
//        $res = HttpQuery::corePost('/recharge/limit/user',array('user_id'=>$userId));
//        if($res['code']==Logic::CODE_SUCCESS){
//            $authCardLimit = $res['data'];
//        }
//        return $authCardLimit;
//    }

    /**
     * 获取快捷支付银行列表及限额--(已移除core)
     * @return array
     */
//    public function getAuthBanks($userId){
//        $authBanks = array();
//        $res = HttpQuery::corePost('/recharge/limit/list',array('user_id'=>$userId));
//        if($res['code']==Logic::CODE_SUCCESS){
//            $authBanks = $res['data'];
//        }
//        return $authBanks;
//    }



    /**
     * 获取当前网银类型值
     * @return int
     */
    public function getUnionType(){
        $platForm = SystemConfigModel::getConfig('DEFAULT_ONLINE_PLATFORM');
        $config = array(
            "hnapay"    => OrderDb::RECHARGE_HNAPAY_ONLINE_TYPE,
            "cbpay"     => OrderDb::RECHARGE_CBPAY_ONLINE_TYPE,
            "reapay"    => OrderDb::RECHARGE_REAPAY_ONLINE_TYPE,
            "sumapay"   => OrderDb::RECHARGE_SUMAAPAY_ONLINE_TYPE,
        );
        return isset($config[$platForm]) ? $config[$platForm] : $config['cbpay'];
    }

    /**
     * 获取当前网银列表
     * $param int $type
     * @return array
     */
    public function getUnion($type){
        $db = new BankListDb();
        $bankList = $db->getBankList($type);
        return $bankList;
    }

    /**
     * 获取alias
     * $param int $type
     * $param int $bank_id
     * @return array
     */
    public function getAlias($type,$bank_id){
        $db = new BankListDb();
        $bank = $db->getAlias($type,$bank_id)->toArray();
        return $bank;
    }

    /**
     * 获取支付通道
     * @param $userId
     * @param $cash
     * @param $bankId
     * @return int
     */
    public function getUseChannel($userId,$cash,$bankId){
        $useChannel = array();
        $res = HttpQuery::corePost('/recharge/route',array('user_id'=>$userId,'cash'=>$cash,'bank_id'=>$bankId));
        if($res['code']==Logic::CODE_SUCCESS){
            $useChannel = $res['data'];
        }
        return $useChannel;
    }

    /**
     * 创建订单
     * @param $userId
     * @param $orderId
     * @param $cash
     * @param $bankId
     * @param $cardNo
     * @param $payChannel
     * @param $from
     * @param $version
     * @return array
     */
    public function createOrder($userId,$orderId,$cash,$bankId,$cardNo,$payChannel,$from,$version){

        $params = [
            'order_id'  => $orderId,
            'user_id'   => $userId,
            'cash'      => $cash,
            'bank_id'   => $bankId,
            'card_no'   => $cardNo,
            'type'      => $payChannel,
            'from'      => $from,
            'version'   => $version
        ];
        $result = OrderModel::doCreateRechargeOrder($params);

        return $result;

        /*
        $res = HttpQuery::corePost('/recharge/order/create',array('user_id'=>$userId,'order_id'=>$orderId,'cash'=>$cash,'bank_id'=>$bankId,'card_no'=>$cardNo,'type'=>$payChannel,'from'=>$from,'version'=>$version));
        return $res;
        */
    }


    /**
     * 获取通道method
     * @param int $payType
     * $return string
     */
    public function getPayMethod($payType){
        $method = array(
            OrderDb::RECHARGE_HNAPAY_ONLINE_TYPE        => "doHnaOnline",
            OrderDb::RECHARGE_REAPAY_ONLINE_TYPE        => "doReaOnline",
            OrderDb::RECHARGE_CBPAY_ONLINE_TYPE         => "doJdOnline",
            OrderDb::RECHARGE_SUMAAPAY_ONLINE_TYPE      => "doSumaOnline", //丰付网银支付
            OrderDb::RECHARGE_LLPAY_AUTH_TYPE           => "doLLAuth",
            OrderDb::RECHARGE_YEEPAY_AUTH_TYPE          => "doYeeAuth",
            OrderDb::RECHARGE_BFPAY_AUTH_TYPE           => "doBFAuth",
            OrderDb::RECHARGE_UCFPAY_AUTH_TYPE          => "doUCFAuth",
            OrderDb::RECHARGE_SUMAPAY_AUTH_TYPE         => "doSumaAuth",
            OrderDb::RECHARGE_QDBPAY_WITHHOLD_TYPE      => "doQdbWithHold",
            OrderDb::RECHARGE_UMPPAY_WITHHOLD_TYPE      => "doUmpWithHold",
            OrderDb::RECHARGE_BESTPAY_WITHHOLD_TYPE     => "doBestWithHold",
            OrderDb::RECHARGE_REAPAY_WITHHOLD_TYPE      => "doReaWithHold",
        );
        return $method[$payType] ? $method[$payType] : '';
    }


    /**
     * 调用支付服务
     * @param $parameter
     * @return mixed
     */
    public function payService($parameter){
        $server = array();
        $res = HttpQuery::serverPost('/recharge/index',$parameter);
        if($res['code']==Logic::CODE_SUCCESS){
            $server = $res['data'];
        }
        return $server;
    }

    /**
     * 支付成功订单
     * @param $orderId
     * @param $tradeNo
     * @return bool
     */
    public function paySuccess($orderId,$tradeNo){

        $result = OrderModel::doSuccRechargeOrder($orderId,$tradeNo);
        return $result['status'];
        /*
        $res = HttpQuery::corePost('/recharge/order/success',['order_id'=>$orderId,'trade_no'=>$tradeNo]);
        if($res['code']==Logic::CODE_SUCCESS){
            $server = $res['status'];

            $params = [
                'event_name'    => 'App\Events\Pay\RechargeSuccessEvent',
                'event_desc'    => '充值成功事件',
                'order_id'      => $orderId,                    //红包ID
            ];

            \Event::fire(new \App\Events\Pay\RechargeSuccessEvent($params));
        }
        return $server;
        */
    }

    /**
     * 支付失败订单
     * @param $orderId
     * @param $msg
     * @return bool
     */
    public function payFail($orderId,$msg){

        setcookie('failOrder',$orderId);

        $result = OrderModel::doFailedRechargeOrder($orderId,'',$msg);

        return $result['status'];
    }

    /**
     * 获取用户代扣最小充值额
     * @param  int $userId
     * @return float
     */
    public function getRechargeMinMoeny($userId){

        $minMoney = SystemConfigModel::getConfig("WITHHOLDING_RECHARGE_MIN_MONEY");

        $micropayList = SystemConfigModel::getConfig("PAY_MIN_MONEY_WITH_USER");

        if(!empty($userId) && isset($micropayList[$userId])){

            $minMoney = $micropayList["min_money"];

        }
        
        return $minMoney;
    }

    /**
     * 获取用户非代扣最小充值额
     * @return float
     */
    public function getAuthMinMoney(){

        $minMoney = SystemConfigModel::getConfig("RECHARGE_MIN_MONEY");
        
        return $minMoney;
    }

}