<?php
/**
 * @desc    丰付支付
 * @date    2017-01-11
 * @author  @llper
 *
 */
namespace App\Http\Logics\Pay;

use App\Http\Dbs\OrderDb;
use App\Http\Logics\Logic;
use App\Http\Models\Pay\RechargeModel;
use App\Http\Models\Common\NationalModel;

class SumaAuthLogic extends Logic{

    const   BANK_CARD_DEBIT = 0,    // 储蓄卡，借记卡
            BANK_CARD_CREDIT= 1;    // 信用卡
    /**
     * @desc    创建丰付订单
     **/
    public function createOrder($orderId,$totalPrice,$userId,$from='pc'){
        $param = [
            'method'    => 'encrypt',
            'driver'    => 'SumaAuth',
            'order_id'  => $orderId,
            'totalPrice'=> $totalPrice,
            'user_id'   => $userId,
            'platform'  => $from,
            'notify_url'=> NationalModel::createNoticeUrl('SumaAuth'),
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;

    }

    /**
     * @desc    发送短信验证
     *
     **/
    public function sendCode($params){
        #根据银行ID，获取-丰付银行代码
        $bankCode   = $this->getBankCodeByBankId($params['bank_id']);
        #卡类型-目前只支持 储蓄卡 0 ， 信用卡 1 【暂不支持】
        $bankCardType   = self::BANK_CARD_DEBIT;
        $param = [
            'method'        => 'sendCode',
            'driver'        => 'SumaAuth',
            'platform'      => $params['platform'],
            'order_id'      => $params['order_id'],
            'mobilePhone'   => $params['mobilePhone'],
            'bank_id'       => $params['bank_id'],
            'bankCardType'  => $bankCardType,
            'bankCode'      => $bankCode,
            'bankAccount'   => $params['bankAccount'],
            'userId'        => $params['userId'],
            'name'          => $params['name'],
            'idCard'        => $params['idCard'],
            'isFirst'       => $params['isFirst'],
        ];

        $rechargeModel = new RechargeModel();

        $result = $rechargeModel->payService($param);

        return $result;

    }

    /**
     * 提交订单数据
     * @param $orderId
     * @param $sms_code
     * @return array
     */
    public function submit($params){
        #根据银行ID，获取-丰付银行代码
        $bankCode   = $this->getBankCodeByBankId($params['bank_id']);
        #卡类型-目前只支持 储蓄卡 0 ， 信用卡 1 【暂不支持】
        $bankCardType   = self::BANK_CARD_DEBIT;
        $param = [
            'method'        => 'submit',
            'driver'        => 'SumaAuth',
            'platform'      => $params['platform'],
            'order_id'      => $params['order_id'],
            'mobilePhone'   => $params['mobilePhone'],
            'bank_id'       => $params['bank_id'],
            'bankCardType'  => $bankCardType,
            'bankCode'      => $bankCode,
            'bankAccount'   => $params['bankAccount'],
            'userId'        => $params['userId'],
            'name'          => $params['name'],
            'idCard'        => $params['idCard'],

            'randomCode'        => $params['randomCode'],
            'randomValidateId'  => $params['randomValidateId'],
            'tradeId'           => $params['tradeId'],
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
            'method'    => 'decrypt',
            'driver'    => 'SumaAuth',
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
            'method'    => 'search',
            'driver'    => 'SumaAuth',
            'order_id'  => $orderId,
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }


    /**
     * @desc    丰付支付-银行对应关系
     **/
    public function getBankCodeByBankId($bankId){
        $bankList   = [
            '1'  => 'icbc',
            '2'  => 'abc',
            '3'  => 'boc',
            '4'  => 'ccb',
            '5'  => 'comm',
            '6'  => 'cmb',
            '7'  => 'spdb',
            '8'  => 'cmsb',
            '9'  => 'cib',
            '10' => 'ceb',
            '11' => 'bjb',
            '12' => 'cjb',
            '13' => 'cncb',
            '14' => 'psbc',
            '15' => 'hxb',
            '16' => 'shb',
            '17' => 'pab',
            '18' => '',
            '19' => '',
            '20' => 'hzb',
            '21' => 'nbcb',
        ];

        return $bankList[$bankId]?$bankList[$bankId]:'';
    }





}