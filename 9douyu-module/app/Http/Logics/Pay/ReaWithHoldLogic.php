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

class ReaWithHoldLogic extends Logic{

    /**
     * 提交订单数据
     * @param $orderId
     * @param $sms_code
     * @return array
     */
    public function submit($orderId,$sms_code){
        $param = [
            'method'=>'submit',
            'driver'=>'ReaWithholding',
            'order_id'=>$orderId,
            'sms_code'=>$sms_code,
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);

        return $result;
        
    }

    /**
     * 异步通知页面方法
     * @param $reques
     * @return string
     */
    public function toNotice($request){
        $param = [
            'method'=>'decrypt',
            'driver'=>'ReaWithholding',
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
            'driver'=>'ReaWithholding',
            'order_id'=>$orderId,
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }



}