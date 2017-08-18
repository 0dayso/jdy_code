<?php
/**
 * User: zhangshuang
 * Date: 16/4/21
 * Time: 18:37
 * Desc: 充值卡相关model层
 */

namespace App\Http\Models\BankCard;
use App\Http\Dbs\BankCardChangeLogDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Dbs\AuthCardDb;
use App\Http\Dbs\PayLimitDb;
use App\Http\Dbs\BankCardDb;
use App\Lang\LangModel;
use App\Http\Dbs\OrderExtendDb;

class RechargeModel extends CardModel{


    public static $codeArr = [
        'bindCardByOrderGetOrderInfo'    => 1,
        'changeCard'                     => 2,
        'changeCardNotMatch'             => 3,
        'changeCardIsSAME'               => 4,
        'changeCardUpdateAuthCard'       => 5,
        'changeCardUpdateWithdrawCard'   => 6,
        'changeCardAddLog'               => 7

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_RECHARGE_CARD;


    /**
     * @param $orderId
     * 绑卡 充值成功，通过订单号获取相应的卡进行绑定
     */
    public static function bindCardByOrder($orderId,$userId){

        //绑定前的业务判断
        $authCard = self::beforBind($userId);

        if($authCard){
            return true;
        }

        //通过订单的扩展信息获取对应的银行卡信息
        $extendDb = new OrderExtendDb();
        $orderInfo = $extendDb->getOrderInfo($orderId);

        //订单扩展信息不存在
        if(empty($orderInfo)){
            throw new \Exception(LangModel::getLang('ERROR_ORDER_EXTEND_NOT_EXIST'),self::getFinalCode('bindCardByOrderGetOrderInfo'));
        }

        $payType = $orderInfo['type'];
        //网银 1000 - 1100 不需要绑卡
        if($payType >= OrderExtendDb::RECHARGE_CBPAY_TYPE && $payType < OrderExtendDb::RECHARGE_LLPAY_AUTH_TYPE){

            return true;
        }

        $bankId = $orderInfo['bank_id']; //所属银行
        $cardNo = $orderInfo['card_number']; //银行卡号

        self::bindCard($userId,$bankId,$cardNo);

    }

    /**
     * @param $userId
     * @param $bankId
     * @param $cardNo
     * 操作数据库添加充值卡及提现卡
     */
    private static function bindCard($userId,$bankId,$cardNo, $isAuthCard = false){

        if(!$isAuthCard) {
            $authDb = new AuthCardDb();
            //添加绑定卡
            $authDb->add($userId, $bankId, $cardNo);
        }
        //添加提现银行卡
        $bankDb = new BankCardDb();

        $bank = $bankDb->getUserCardByCardNumber($userId,$cardNo);
        if($bank){
            return true;
        }else{
            $bankDb->add($userId, $bankId, $cardNo);
        }
    }


    /**
     * @param $userId
     * @param $bankId
     * @param $cardNo
     * @return bool
     * @throws \Exception
     * 用户注册成功后三要素验证通过，然后实名+绑卡
     */
    public static function bindCardByCard($userId,$bankId,$cardNo, $isThrow = true){

        //绑定前的业务判断
        $authCard   = self::beforBind($userId);
        $isAuthCard = false;
        if($authCard){
            if($isThrow) {
                throw new \Exception(LangModel::getLang('ERROR_USER_BIND_CARD_IS_EXIST'), self::getFinalCode('bindCardByCard'));
            }else{
                $isAuthCard = true;
            }

        }

        self::bindCard($userId,$bankId,$cardNo, $isAuthCard);

    }

    /**
     * @param $cardNo
     * @throws \Exception
     * 检查银行卡是否已被绑定
     */
    public static function checkCardCanBind($cardNo, $isThrow = true){

        $authDb  = new AuthCardDb();
        $result = $authDb->getAuthCardByCardNo($cardNo);

        if($result){
            if($isThrow)
                throw new \Exception(LangModel::getLang('ERROR_BANK_CARD_IS_BINDED'),self::getFinalCode('checkCardIsUnique'));
            else{
                return true;
            }

        }
        if(!$isThrow)
            return false;
    }

    /**
     * @param $userId
     * @param $bankId
     * @param $cardNo
     * 用户更换绑定银行卡操作
     */
    public static function changeCard($userId,$bankId,$oldCardNo,$newCardNo){

        //绑定前的业务判断
        $authCard = self::beforBind($userId);

        if(!$authCard){

            throw new \Exception(LangModel::getLang('ERROR_USER_UNBIND_CARD'),self::getFinalCode('changeCard'));
        }

        //绑定卡号
        $cardNo = $authCard['card_number'];

        //判断绑定卡与传递的卡号是否一致
        if($cardNo !== $oldCardNo){

            throw new \Exception(LangModel::getLang('ERROR_BIND_CARD_IS_NOT_MATCH'),self::getFinalCode('changeCardNotMatch'));

        }

        //更换的银是否与原绑定卡相同
        if($cardNo === $newCardNo){

            throw new \Exception(LangModel::getLang('ERROR_CHANGE_CARD_IS_SAME_AS_AUTH_CARD'),self::getFinalCode('changeCardIsSAME'));

        }

        //更新绑定银行卡
        $authDb = new AuthCardDb();
        $result = $authDb->updateCard($userId,$oldCardNo,$newCardNo,$bankId);
        if(!$result){
            
            throw new \Exception(LangModel::getLang('ERROR_BIND_CARD_UPDATE_FAILED'),self::getFinalCode('changeCardUpdateAuthCard'));

        }

        //更新提现银行卡
        $bankDb = new BankCardDb();
        $result = $bankDb->updateCard($userId,$oldCardNo,$newCardNo,$bankId);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_WITH_DRAW_CARD_UPDATE_FAILED'),self::getFinalCode('changeCardUpdateWithdrawCard'));

        }

        /*
        //添加更新操作日志
        $changeLogDb = new BankCardChangeLogDb();
        $result = $changeLogDb->add($userId,$oldCardNo,$newCardNo);
        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_CHANGE_BIND_CARD_ADD_FAILED'),self::getFinalCode('changeCardAddLog'));

        }

        */
    }
}