<?php
/**
 * @desc    丰付网银支付
 * @date    2017-04-10
 * @author  @linglu
 *
 */
namespace App\Http\Logics\Pay;

use App\Http\Dbs\OrderDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\NationalModel;
use App\Http\Models\Pay\RechargeModel;
use Session;

class SumaOnlineLogic extends Logic{

    /**
     * @desc    提交网银支付订单数据
     * @param   $cash
     * @param   $orderId
     * @param   $bankCode
     * @param   $from
     * @return  array
     */
    public function submit($cash,$orderId,$bankCode,$from){
        $param = [
            'method'    => 'encrypt',
            'driver'    => 'SumaOnline',
            'order_id'  => $orderId,
            'bankCode'  => $bankCode,
            'totalPrice'=> $cash,
            'return_url'=> NationalModel::createReturnUrl('SumaOnline',$from),
            'back_url'  => NationalModel::createReturnUrl('SumaOnline',$from),
            'notify_url'=> NationalModel::createNoticeUrl('SumaOnline'),
        ];
        $rechargeModel  = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }

    /**
     * @desc    支付同步页面跳转处理方法
     * @return  bool
     */
    public function toReturn($request,$from = ''){
        $param = [
            'method'=> 'decrypt',
            'driver'=> 'SumaOnline',
        ];
        //Session::put('payCash',$request['v_amount']);
        $param = array_merge($param,$request);
        
        return \App\Http\Logics\Pay\RechargeLogic::returnDecrypt($param);
    }

    /**
     * 京东支付异步通知页面方法
     */
    public function toNotice($request){
        $param  = [
            'method'=>'decrypt',
            'driver'=>'SumaOnline',
        ];
        $param  = array_merge($param,$request);
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
     * @desc    查单
     * @param   $orderId
     * @return  array
     */
    public function search($orderId){
        $param  = [
            'method'    => 'search',
            'driver'    => 'SumaOnline',
            'no_order'  => $orderId,
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }


}