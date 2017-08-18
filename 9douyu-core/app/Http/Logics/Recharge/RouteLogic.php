<?php
/**
 * User: zhangshuang
 * Date: 16/4/15
 * Time: 14:13
 * Desc: 支付路由选择相关逻辑层
 */

namespace App\Http\Logics\Recharge;

use App\Http\Logics\Logic;
use App\Http\Models\Common\BankCardModel;
use App\Http\Models\Recharge\LimitModel;
use App\Http\Dbs\AuthCardDb;
use App\Http\Models\Recharge\RouteModel;
use App\Http\Models\Common\ValidateModel;


class RouteLogic extends Logic{
    /**
     * @param $userId
     * @param $cash
     * @param $bankId
     * 路由匹配
     */
    public function choiceRouteChannel($userId,$cash,$bankId){


        try{

            /*
            //数据验证
            RouteModel::isUserId($userId);
            RouteModel::isCash($cash);
            RouteModel::isBankId($bankId);
            */
            //验证用户ID
            ValidateModel::isUserId($userId);
            //验证金额
            ValidateModel::isCash($cash);
            //订单格式验证
            ValidateModel::isBankId($bankId);

            /*

            //判断用户是否绑卡
            $authCard = BankCardModel::getUserAuthCard($userId);

            $cash = ToolMoney::formatDbCashAdd($cash); //金额转化成分
            //判断用户是否绑卡
            $isBind = $authCard ? true : false;

            //根据银行获取可用支付通道的限额列表
            $limitList = RouteModel::getLimitListByBankId($bankId,$isBind);

            if(!empty($limitList)){
                $usableList = [];

                //用户已绑卡
                if($authCard){
                    $userList = RouteModel::getUserRechargedList($userId,$bankId);

                    //计算最大可充值金额
                    foreach($limitList as $payType => $val){

                        $limit = (int)$val['limit'];//单笔限额
                        $dayLimit = (int)$val['day_limit'];//单日限额

                        if(isset($userList[$payType])){
                            $dayUsableCash = $dayLimit - $userList[$payType];//今日剩余可充值金额
                            $todayFreeCash = max((int)$dayUsableCash,0) ;
                        }else{
                            $todayFreeCash = $dayLimit;
                        }

                        $tmpMaxCash = min($limit,$todayFreeCash); //单笔与今日剩余可投金额取较小值

                        //与上一次的最大可充值金额进行比较，若大于直接替换
                        if($tmpMaxCash >= $cash){
                            $usableList[$payType] = ToolMoney::formatDbCashDelete($tmpMaxCash);
                        }
                    }

                }else{
                    //计算最大可充值金额
                    foreach($limitList as $payType => $val){
                        $realCash = min((int)$val['limit'],(int)$val['day_limit']);
                        if($realCash >= $cash){
                            $usableList[$payType] = ToolMoney::formatDbCashDelete($realCash);
                        }
                    }
                }
            }
            */

            $typeList = RouteModel::getValidChannel($userId,$cash,$bankId);

        }catch(\Exception $e){

            return self::callError($e->getMessage());

        }

        return self::callSuccess($typeList);

    }
}