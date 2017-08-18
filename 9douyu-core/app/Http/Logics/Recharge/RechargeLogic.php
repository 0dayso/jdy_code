<?php
/**
 * User: zhangshuang
 * Date: 16/4/25
 * Time: 16:55
 * Desc: 用户支付(更新用户成功充值金额等)相关逻辑层
 */

namespace App\Http\Logics\Recharge;

use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Recharge\RechargeModel;
use Log;

class RechargeLogic extends Logic{


    /**
     * @param $orderId
     * @param $userId
     * @param $cash
     * 更新用户成功充值记录
     */
    public function updateUserRechargeRecord($orderId,$userId,$cash){

        try{

            //数据校验
            ValidateModel::isUserId($userId);
            ValidateModel::isOrderId($orderId);
            ValidateModel::isCash($cash);

            //更新用户成功充值记录
            $model = new RechargeModel();
            $model->updateRecord($userId,$orderId,$cash);

        }catch(\Exception $e){

            $data = [
                'order_id' => $orderId,
                'user_id'  => $userId,
                'cash'     => $cash,
                'msg'      => $e->getMessage(),
                'code'     => $e->getCode()
            ];

            Log::error(__METHOD__.'Error',$data);

            return self::callError($e->getMessage());
        }

        return self::callSuccess(['order_id' => $orderId,'user_id' => $userId]);
    }
}