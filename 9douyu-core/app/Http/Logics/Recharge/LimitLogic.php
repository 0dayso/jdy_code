<?php
/**
 * User: zhangshuang
 * Date: 16/4/15
 * Time: 14:07
 * Desc: 支付限额相关逻辑层
 */

namespace App\Http\Logics\Recharge;
use App\Http\Logics\Logic;
use App\Http\Dbs\AuthCardDb;
use App\Http\Dbs\PayLimitDb;
use App\Http\Models\Common\BankCardModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Recharge\LimitModel;

class LimitLogic extends Logic{

    /**
     * @param $userId
     * @return array
     * 获取用户银行卡限额信息
     */
    public function getBindCardLimitByUserId($userId){


        try{

            //检查用户ID是否合法
            //LimitModel::isUserId($userId);
            ValidateModel::isUserId($userId);

            /*
            //获取用户绑定卡信息，同卡进出
            $authCard = BankCardModel::getUserAuthCard($userId);

            if(empty($authCard)){
                return self::callError('未绑定银行卡');
            }


            //有绑卡信息，直接查询当前银行的限额信息
            $bankId = $authCard['bank_id'];
            //根据银行获取相应可用通道的限额列表
            //$limitArr = $this->getLimitListByBankId($bankId);

            $limitArr = LimitModel::getLimitListByBankId($bankId);
            if(!empty($limitArr)){
                //获取用户成功充值记录列表
                //$userList = $this->getUserRechargedList($userId);
                $userList = LimitModel::getUserRechargedList($userId,$bankId);
                //计算最大可充值金额
                foreach($limitArr as $payType => $val){

                    $limit = (int)$val['limit'];//单笔限额
                    $dayLimit = (int)$val['day_limit'];//单日限额

                    if(isset($userList[$payType])){

                        $dayUsableCash = $dayLimit - $userList[$payType];//今日剩余可充值金额
                        $todayFreeCash = max((int)$dayUsableCash,0) ;

                    }else{
                        $todayFreeCash = $dayLimit;
                    }
                    $tmpMaxCash = min($limit,$todayFreeCash); //单笔、今日剩余可充值金额取较小值
                    if($tmpMaxCash > $maxCash){
                        $maxCash = $tmpMaxCash;
                    }
                }
            }
            */
            $maxCash = LimitModel::getBindUserLimit($userId);

        }catch(\Exception $e){

            return self::callError($e->getMessage());

        }
        return self::callSuccess(['cash'=>$maxCash]);


    }


    /**
     * @return array
     * 未绑卡情况下每个银行的限额列表
     */
    public function getRechargeCardLimit($userId){

        /*
        //获取所有可用的支付通道限额列表
        $limitDb = new PayLimitDb();
        $limitList = $limitDb->getAllBankLimit();
        */
        try {



            //检查用户ID是否合法
            //LimitModel::isUserId($userId);
            ValidateModel::isUserId($userId);


            /*
            $list = [];
            foreach ($limitList as $value) {

                $val        = (array)$value;

                $limit      = (int)$val['limit']; //单笔限额
                $dayLimit   = (int)$val['day_limit']; //单日限额

                $limit      = ($limit    === 0) ? PayLimitDb::PAY_LIMIT : $limit; //单笔限额
                $dayLimit   = ($dayLimit === 0) ? PayLimitDb::PAY_LIMIT : $dayLimit;//单日限额

                $usableLimit = min($limit, $dayLimit); //单笔、单日限额取较小值 并转换成元为单位
                $bankId = $val['bank_id']; //银行ID

                if (isset($list[$bankId])) {
                    if ($list[$bankId]['cash'] < $usableLimit) {
                        $list[$bankId] = [
                            'bank_id' => $bankId,
                            'cash' => ToolMoney::formatDbCashDelete($usableLimit), //金额转化成元
                        ];
                    }
                } else {
                    $list[$bankId] = [
                        'bank_id' => $bankId,
                        'cash' => ToolMoney::formatDbCashDelete($usableLimit)  //金额转化成元
                    ];
                }

            }
            */

            $list   = LimitModel::getBankLimit($userId);
            
        }catch(\Exception $e){

            return self::callError($e->getMessage());

        }

        return self::callSuccess(array_values($list));
    }
}