<?php
/**
 * User: zhangshuang
 * Date: 16/4/15
 * Time: 13:13
 * Desc: 充值银行卡相关逻辑层
 */

namespace App\Http\Logics\BankCard;

use App\Http\Logics\Logic;
use App\Http\Dbs\AuthCardDb;
use App\Http\Logics\Warning\OrderLogic;
use App\Http\Models\BankCard\RechargeModel;
use App\Http\Models\BankCard\CardModel;
use App\Http\Models\Common\ValidateModel;
use Log;


class RechargeLogic extends Logic{

    /**
     * @param int $userId
     * 根据用户ID获取对应的绑定银行卡列表
     */
    public function getUserAuthCardByUserId($userId){

        try{

            $data = [];
            //验证用户ID是否正确
            //RechargeModel::isUserId($userId);
            ValidateModel::isUserId($userId);

            $authCard = RechargeModel::getUserAuthCard($userId);


            if($authCard){
                $data =[
                    'bank_id' => $authCard['bank_id'],
                    'card_no' => $authCard['card_number'],
                ];
            }

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess($data);
    }

    /**
     * @param $orderId
     * @param $userId
     * 充值成功绑定银行卡
     */
    public function bindCard($orderId,$userId){

        //绑卡
        try{
            self::beginTransaction();

            //数据验证
            ValidateModel::isUserId($userId);
            ValidateModel::isOrderId($orderId);

            //绑定银行卡
            RechargeModel::bindCardByOrder($orderId,$userId);

            self::commit();

        }catch(\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg'=>$e->getMessage(),'code' => $e->getCode()]);

            //绑卡失败添加处理事件(发送邮件通知)
            $params = [
                'event_name' => 'App\Events\BankCard\BindCardFailedEvent',
                'order_id' => $orderId,
                'user_id' => $userId,
                'msg' => $e->getMessage()
            ];

            OrderLogic::bindCardWarning($params);

            \Event::fire('App\Events\BankCard\BindCardFailedEvent',[$params]);

        }

        $data = [
            'user_id'   => $userId,
            'order_id'  => $orderId,
        ];
        return self::callSuccess($data);

    }

    /**
     * @param $userId
     * @param $bankId
     * @param $oldCardNo
     * @param $newCardNo
     * 更新绑定银行卡
     */
    public function changeCard($userId,$bankId,$oldCardNo,$newCardNo){

        try{

            self::beginTransaction();

            ValidateModel::isUserId($userId);
            ValidateModel::isBankId($bankId);
            ValidateModel::isBankCard($oldCardNo);
            ValidateModel::isBankCard($newCardNo);

            RechargeModel::changeCard($userId,$bankId,$oldCardNo,$newCardNo);

            self::commit();

            return self::callSuccess();
        }catch (\Exception $e){

            self::rollback();
            return self::callError($e->getMessage());
        }
    }
}