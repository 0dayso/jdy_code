<?php
/**
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 11:36
 * Desc: 支付路由相关model层
 */

namespace App\Http\Models\Pay;
use App\Http\Dbs\Order\PayLimitDb;
use App\Http\Dbs\OrderDb;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Http\Models\Common\PayLimitModel;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Model;
use EasyWeChat\Payment\Order;

class RouteModel extends Model{


    /**
     * @param $userId
     * @param $cash
     * @param $bankId
     * 获取用户可用的支付通道
     */
    public static function getValidChannel($userId,$cash,$bankId,$isBind,$version,$isApp){

        $defaultPayType    = 0;
        //根据银行获取可用支付通道的限额列表
        $limitList = PayLimitModel::getLimitListByBankId($bankId,$isBind,$version,$isApp);

        $usableList = [];

        if(!empty($limitList)){

            //用户已绑卡
            if($isBind){
                //绑卡用户最终的可用通道限额列表
                $usableList = self::getBindCardUserPayLimitList($userId,$bankId,$cash,$limitList);
            }else{
                //计算最大可充值金额
                foreach($limitList as $payType => $val){
                    $realCash = min((int)$val['limit'],(int)$val['day_limit']);
                    //渠道剩余金额大于充值金额 && 不是联动优势,则进入备选支付通道
                    if($realCash >= $cash && $payType != OrderDb::RECHARGE_UMPPAY_WITHHOLD_TYPE){
                        $usableList[$payType] = $realCash;
                    }
                }
            }
        }


        //没有可用的支付通道
        if(empty($usableList)){
            return $defaultPayType;
        }

        //优先匹配已成功充值过的支付通道
        if($isBind){

            $succList = OrderModel::getSuccPayChannelList($userId);

            if(!empty($succList)){
                shuffle($succList);
                foreach($usableList as $payType => $cash){
                    if(in_array($payType,$succList)){
                        return $payType;
                    }
                }
            }

        }

        //最优的支付通道
        $payType = self::chociePayType($usableList,$userId);
        return $payType;

    }


    /**
     * @param $userId     用户Id
     * @param $bankId     银行ID
     * @param $cash       充值金额
     * @param $limitList  各个支付通道的限额列表
     * 获取绑卡用户可用的支付限额列表
     */
    private static function getBindCardUserPayLimitList($userId,$bankId,$cash,$limitList){
        $usableList = [];
        //获取用户当前银行已成功充值的列表
        $userList = PayLimitModel::getUserRechargedList($userId,$bankId);

        /*
        //获取用户非网银支付通道成功充值的次数
        $succData = OrderModel::getSuccOrderNum($userId);

        if(!empty($succData)){
            $num = (int)$succData['num'];
        }else{
            $num = 0;
        }
        */
        //计算最大可充值金额
        foreach($limitList as $payType => $val){

            /*
            //未成功支付过,不匹配联动优势支付通道
            if($num === 0 && $payType == OrderDb::RECHARGE_UMPPAY_WITHHOLD_TYPE){
                continue;
            }
            */
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
                $usableList[$payType] =$tmpMaxCash;
            }
        }

        return $usableList;
    }

    /**
     * @param $usableList
     * 匹配最优的支付通道,实现通道自动切换
     */
    private static function chociePayType($list,$userId){
        //按金额升序
        asort($list);
        //取出所有的支付通道类型
        $typeList = array_keys($list);
        //取可充值金额最小的通道
        $payType  = $typeList[0];

        /**
         * 若当前匹配的通道失败2次且有其他的可用支付通道则跳过此通道
         */
        if(count($typeList) > 1){

            $num = self::getTodayInvalidOrderNum($userId,$payType);

            //不成功的订单次数大于该限制,直接切换支付通道
            if($num > OrderDb::INVALIED_ORDER_LIMIT){
                $payType = $typeList[1];
            }

        }

        return $payType;
    }

    /**
     * @param $userId
     * @param $payType
     * @return int
     * 获取用户某个通道今日无效充值次数
     */
    private static function getTodayInvalidOrderNum($userId,$payType){

        $result = OrderModel::getTodayInvalidRechargeNum($userId,$payType);

        if($result){

            return $result['num'];
        }else{

            return 0;
        }
    }

}