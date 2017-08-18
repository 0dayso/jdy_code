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

class HnaOnlineLogic extends Logic{

    /**
     * 提交新生网银支付订单数据
     * @param $cash
     * @param $orderId
     * @param $bankCode
     * @param $from
     * @return array
     */
    public function submit($cash,$orderId,$bankCode,$from){
        $param = [
            'method'=>'encrypt',
            'driver'=>'HnaOnline',
            'order_id'=>$orderId,
            'cash'=>$cash,
            'bank_code'=>$bankCode,
            'return_url'=>NationalModel::createReturnUrl('HnaOnline',$from),
            'notify_url'=>NationalModel::createNoticeUrl('HnaOnline'),//'http://www.wlask.com/notify.php'
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }

    /**
     * 新生支付同步页面跳转处理方法
     * @return bool
     */
    public function toReturn($request,$from = ''){
        $param = [
            'method'=>'decrypt',
            'driver'=>'HnaOnline',
        ];
        //Session::put('payCash',$request['v_amount']);
        $param = array_merge($param,$request);

        /*
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        if($result['trade_status']==OrderDb::TRADE_SUCCESS){
            return true;
        }
        return false;
        */

        return \App\Http\Logics\Pay\RechargeLogic::returnDecrypt($param);

    }

    /**
     * 新生支付异步通知页面方法
     */
    public function toNotice($request){
        $param = [
            'method'=>'decrypt',
            'driver'=>'HnaOnline',
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
            return '200';
        else
            return '100';
    }


    /**
     * 查单
     * @param $orderId
     * @return array
     */
    public function search($orderId){
        $param = [
            'method'=>'search',
            'driver'=>'HnaOnline',
            'no_order'=>$orderId,
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }


}