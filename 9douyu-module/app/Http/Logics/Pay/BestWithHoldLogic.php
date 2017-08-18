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
use App\Http\Models\Pay\RechargeModel;

class BestWithHoldLogic extends Logic{

    /**
     * 提交订单数据
     * @param $orderId
     * @param $cash
     * @param $cardNo
     * @return array
     */
    public function submit($orderId,$cash,$cardNo){
        $param = [
            'method'=>'submit',
            'driver'=>'BestWithholding',
            'order_id'=>$orderId,
            'cash'=>$cash,
            'card_no'=>$cardNo,
            'sign_id'=>$sign_id
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }

    /**
     * 异步通知页面方法
     */
    public function toNotice($request){
        $param = [
            'method'=>'decrypt',
            'driver'=>'BestWithholding',
        ];
        $param = array_merge($param,$request);
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        if($result['trade_status']==OrderDb::TRADE_SUCCESS){
            $rechargeModel->paySuccess($result['order_id'],$result['trade_no']);
        }else{
            $rechargeModel->payFail($result['order_id'],$result['msg']);
        }
    }


    /**
     * 查单
     * @param $orderId
     * @return array
     */
    public function search($orderId){
        $param = [
            'method'=>'search',
            'driver'=>'BestWithholding',
            'no_order'=>$orderId,
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }



}