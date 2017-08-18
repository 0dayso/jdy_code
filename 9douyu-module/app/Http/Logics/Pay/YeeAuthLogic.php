<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/5/25
 * Time: 18:07
 */
namespace App\Http\Logics\Pay;

use App\Http\Dbs\OrderDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\NationalModel;
use App\Http\Models\Pay\RechargeModel;
use Session;

class YeeAuthLogic extends Logic{

    /**
     * 提交易宝支付订单数据
     * @param $userId
     * @param $cardNo
     * @param $cash
     * @param $orderId
     * @param $from
     * @return array
     */
    public function submit($userId,$cardNo,$cash,$orderId,$from){
        $user = $this->getUser($userId);
        $param = [
            'method'=>'encrypt',
            'driver'=>'YeeAuth',
            'card_no'=>$cardNo,
            'user_id'=>$user['id'],
            'cash'=>$cash,
            'name'=>$user['real_name'],
            'id_card'=>$user['identity_card'],
            'notify_url' => NationalModel::createNoticeUrl('YeeAuth'),
            'return_url'=>NationalModel::createReturnUrl('YeeAuth',$from),
            'order_id'=>$orderId
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }

    /**
     * 易宝认证支付同步页面跳转处理方法
     * @return bool
     */
    public function toReturn($request,$platform = ''){
        $param = [
            'method'=>'decrypt',
            'driver'=>'YeeAuth',
        ];
        $param = array_merge($param,$request);
        
        /*
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);

        //Session::put('payCash',$result['amount']);

        
        if(isset($result['trade_status']) &&
            $result['trade_status'] == OrderDb::TRADE_SUCCESS){
            return true;
        }
        return false;
        */

        return \App\Http\Logics\Pay\RechargeLogic::returnDecrypt($param);


    }

    /**
     * 易宝认证支付异步通知页面方法
     * @param $request
     * @return string
     */
    public function toNotice($request){
        $param = [
            'method'=>'decrypt',
            'driver'=>'YeeAuth',
        ];
        $param = array_merge($param,$request);
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);

        //TODO: 处理订单
        $orderDone  = RechargeLogic::toNotifyOrder($result);

        //通知状态
        if($orderDone)
            return 'success';
        else
            return 'fail';
    }


    /**
     * 查单
     * @param $orderId
     * @return array
     */
    public function search($orderId){
        $param = [
            'method'=>'search',
            'driver'=>'YeeAuth',
            'order_id'=>$orderId,
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }
    
}