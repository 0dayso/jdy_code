<?php
/**
 * User: zhangshuang
 * Date: 16/4/25
 * Time: 16:59
 * Desc: 支付相关(更新用户成功充值记录等)model层
 */

namespace App\Http\Models\Recharge;

use App\Http\Dbs\OrderExtendDb;
use App\Http\Dbs\UserPayListDb;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Dbs\PayLimitDb;

class RechargeModel extends Model{


    public static $codeArr = [
        'updateRecordGetOrderInfo'           => 1,
        'updateRecordUpdateRecord'           => 2,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_RECHARGE;

    /**
     * @param $userId
     * @param $orderId
     * 更新用户今日的成功充值记录
     */
    public function updateRecord($userId,$orderId,$cash){

        //通过订单的扩展信息获取对应的银行卡信息
        $extendDb = new OrderExtendDb();
        $orderInfo = $extendDb->getOrderInfo($orderId);

        //订单扩展信息不存在
        if(empty($orderInfo)){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_EXTEND_NOT_EXIST'),self::getFinalCode('updateRecordGetOrderInfo'));
        }

        $payType = $orderInfo['type'];
        //网银 1000 - 1100 不需要记录
        if($payType >= PayLimitDb::RECHARGE_CBPAY_TYPE && $payType < PayLimitDb::RECHARGE_LLPAY_AUTH_TYPE){

            return true;
        }

        //银行ID
        $bankId = $orderInfo['bank_id'];


        $db = new UserPayListDb();
        $result = $db->getUserPayList($userId,$bankId,$payType);

        if($result){
            $result = $db->updateRecord($userId,$bankId,$payType,$cash);
            if(!$result){
                throw new \Exception(LangModel::getLang('ERROR_RECHARGE_UPDATE_RECORD_FAILED'),self::getFinalCode('updateRecordUpdateRecord'));
            }
        }else{
            $db->addRecord($userId,$bankId,$payType,$cash);
        }



    }
}