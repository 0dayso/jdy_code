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

class QdbWithHoldLogic extends Logic{

    /**
     * 提交订单数据
     * @param $orderId
     * @param $sms_code
     * @return array
     */
    public function submit($orderId,$sms_code){
        $param = [
            'method'=>'submit',
            'driver'=>'QdbWithholding',
            'order_id'=>$orderId,
            'sms_code'=>$sms_code,
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
            'driver'=>'QdbWithholding',
        ];
        $param = array_merge($param,$request);
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        //接收回调数据后的订单处理
        if($result['trade_status']==OrderDb::TRADE_SUCCESS){
            $orderDone = $rechargeModel->paySuccess($result['order_id'],$result['trade_no']);
        }else{
            $orderDone = $rechargeModel->payFail($result['order_id'],$result['msg']);
        }
        //通知状态
        if($orderDone)
            return 'SUCCESS';
        else
            return 'FAIL';
    }


    /**
     * 查单
     * @param $orderId
     * @return array
     */
    public function search($orderId){
        $param = [
            'method'=>'search',
            'driver'=>'QdbWithholding',
            'order_id'=>$orderId,
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }



}