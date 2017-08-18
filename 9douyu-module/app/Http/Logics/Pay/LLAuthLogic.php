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
use App\Http\Logics\RequestSourceLogic;
use App\Http\Models\Common\NationalModel;
use App\Http\Models\Pay\RechargeModel;

class LLAuthLogic extends Logic{

    /**
     * 提交连连支付订单数据
     * @param $userId
     * @param $cardNo
     * @param $cash
     * @param $orderId
     * @param $from
     * @return array
     */
    public function submit($userId,$cardNo,$cash,$orderId,$from){
        $user = $this->getUser($userId);
        $platform = strtoupper($from);
        $param = [
            'method'=>'encrypt',
            'driver'=>'LLAuth',
            'order_id'=>$orderId,
            'cash'=>$cash,
            'card_no'=>$cardNo,
            'name'=>$user['real_name'],
            'id_card'=>$user['identity_card'],
            'user_id'=>$user['id'],
            'notify_url'=>NationalModel::createNoticeUrl('LLAuth'),
            'return_url'=>NationalModel::createReturnUrl('LLAuth',$from),
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
        $param = [
            'method'=>'decrypt',
            'driver'=>'LLAuth',
            'decrypt_type'=>'return',
            'platform' => $from,
        ];

        if($from == RequestSourceLogic::SOURCE_WAP){

            $request = isset($request['res_data']) ? json_decode($request['res_data'],true) : [];
        }

        if(!$request){

            $param= [];
        }else{

            $param = array_merge($param,$request);

        }


        return \App\Http\Logics\Pay\RechargeLogic::returnDecrypt($param);


    }

    /**
     * 连连认证支付异步通知页面方法
     */
    public function toNotice($request){

        $ok     = "{'ret_code':'0000','ret_msg':'交易成功'}";
        $error  = "{'ret_code':'9999','ret_msg':'验签失败'}";

        $param = [
            'method'=>'decrypt',
            'driver'=>'LLAuth',
            'decrypt_type'=>'notice',
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
            'driver'=>'LLAuth',
            'order_id'=>$orderId,
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }




//
//    /**
//     * @param $res
//     * @return mixed
//     * 格式化查单结果，主要给后台查单使用
//     */
//    public function format($returnData){
//
//
//        if($returnData["ret_code"] == '0000'){
//            switch($returnData['result_pay']){
//                case 'SUCCESS':{
//                    $status = '成功';
//                    break;
//                }
//                case 'WAITING':{
//                    $status = '等待支付';
//                    break;
//                }
//                case 'FAILURE':{
//                    $status = '失败';
//                    break;
//                }
//                default:{
//                    $status = '未知';
//                    break;
//                }
//            }
//        }else{
//            $status = $returnData['ret_msg'];
//        }
//    }

//    /**
//     * @param $cardNo
//     * @return mixed
//     * 连连卡bin接口
//     */
//
//    public function checkAuthCard($cardNo){
//
//        $request = array(
//            'card_no' => $cardNo,
//            'sign' => md5($cardNo.self::AUTH_KEY),
//        );
//        $return  = curlOpen(self::API_URL,$request,true);
//        return $return;
//    }
//
//
//    /**
//     * @param $cardNo
//     * @return array
//     * 远程获取银行卡信息
//     */
//    public function fetchBankInfoFromRemote($cardNo){
//
//        //$config         = C("LLPAY_CONFIG");
//        $strParams      = array(
//            "oid_partner" => $this->config["OID_PARTNER"],
//            "card_no"     => $cardNo,
//            "sign_type"   => $this->config["SIGN_TYPE"],
//        );
//
//        $strParams = $this->service->llpayMakeSignMsg($strParams);
//
//        $strParams = json_encode($strParams);
//
//        $result    = (array)json_decode(curlOpen($this->config["BANK_CARD_QUERY"],$strParams));
//        //Log::write("======card_no:{$cardNo},check info:". print_r($result, true));
//        //添加日志记录
//        \CustomLog::addLog('checkAuthCardResult',$strParams."\n".print_r($result,true));
//        return $result;
//    }






}