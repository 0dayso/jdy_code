<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/10/18
 * Time: 17:07
 * Desc: 宝付快捷支付
 */

namespace App\Http\Logics\Pay;

use App\Http\Dbs\OrderDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\NationalModel;
use App\Http\Models\Pay\RechargeModel;
use Config;

class BFAuthLogic extends Logic{

    /**
     * 宝付连连支付订单数据
     * @param $userId
     * @param $cardNo
     * @param $cash
     * @param $orderId
     * @param $from
     * @return array
     */
    public function submit($userId,$cardNo,$cash,$orderId,$from,$bankId){



        $list = Config::get('bankcode.bfCardBin');

        $bankCode = isset($list[$bankId]) ? $list[$bankId] : '';

        $platform = strtoupper($from);

        $user = $this->getUser($userId);

        $param = [
            'method'=>'encrypt',
            'driver'=>'BFAuth',
            'order_id'=>$orderId,
            'cash'=>$cash,
            'card_no'=>$cardNo,
            'name'=>$user['real_name'],
            'id_card'=>$user['identity_card'],
            'bank_code' => $bankCode,
            'notify_url'=>NationalModel::createNoticeUrl('BFAuth'),
            'return_url'=>NationalModel::createReturnUrl('BFAuth',$from),
            'platform'=>$platform
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);

        return $result;
    }

    /**
     * 连连认证支付同步页面跳转处理方法
     * @return bool
     */
    public function toReturn($request,$from = ''){
        
        if(!$request){

            return self::callError();

        }
        
        $param = [
            'method'=>'decrypt',
            'driver'=>'BFAuth',
        ];

        $param = array_merge($param,$request);

        return \App\Http\Logics\Pay\RechargeLogic::returnDecrypt($param);


    }

    /**
     * 连连认证支付异步通知页面方法
     */
    public function toNotice($request){

        $ok     = "OK";
        $error  = "FAIL";

        $param = [
            'method'=>'decrypt',
            'driver'=>'BFAuth',
        ];

        $param = array_merge($param,$request);
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);

        //TODO: 处理订单
        $orderDone  = RechargeLogic::toNotifyOrder($result);

        //通知状态
        if($orderDone)
            return $ok;
        else
            return $error;
    }


    /**
     * 查单
     * @param $orderId
     * @return array
     */
    public function search($orderId){
        $param = [
            'method'=>'search',
            'driver'=>'BFAuth',
            'order_id'=>$orderId,
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }

}