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
use App\Http\Models\Common\NationalModel;

class UmpWithHoldLogic extends Logic{

    /**
     * 提交订单数据
     * @param $orderId
     * @param $userId
     * @param $cardNo
     * @param $cash
     * @return array
     */
    public function submit($userId,$orderId,$cardNo,$cash){
        $user = $this->getUser($userId);
        $param = [
            'method'=>'submit',
            'driver'=>'UmpWithholding',
            'order_id'=>$orderId,
            'id_card'=>$user['identity_card'],
            'name'=>$user['real_name'],
            'card_no'=>$cardNo,
            'cash'=>$cash,
            'notify_url'=>NationalModel::createNoticeUrl('UmpWithHold'),
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        if($result['status']==OrderDb::TRADE_SUCCESS){
            $rechargeModel->paySuccess($result['order_id'],$result['trade_no']);
        }else{
            $rechargeModel->payFail($result['order_id'],$result['msg']);
        }
        return $result;
    }



    /**
     * 查单
     * @param $orderId
     * @return array
     */
    public function search($orderId){
        $param = [
            'method'=>'search',
            'driver'=>'UmpWithholding',
            'order_id'=>$orderId,
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
        return 'succ';
    }



}