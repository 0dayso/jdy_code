<?php
/**
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 11:36
 * Desc: 支付路由相关model层
 */

namespace App\Http\Models\Recharge;
use App\Http\Dbs\AuthCardDb;
use App\Http\Dbs\OrderDb;
use App\Http\Dbs\OrderExtendDb;
use App\Http\Models\Common\PayLimitModel;
use App\Http\Models\Model;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;

class RouteModel extends Model{

    public static $codeArr = [
        'getValidChannel'                   => 1,
        'getValidChannelBankIdMismatch'     => 2
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_ROUTE;

    
    /**
     * @param $userId
     * @param $cash
     * @param $bankId
     * 获取用户可用的支付通道
     */
    public static function getValidChannel($userId,$cash,$bankId){

        $authDb = new AuthCardDb();
        //判断用户是否绑卡
        $authCard = $authDb->getAuthCardByUserId($userId);
        
        $isBind = false;    //默认未绑卡
        //判断用户是否绑卡
        if(!empty($authCard)){
            $isBind = true;
            //传递的银行ID与真实绑定的银行ID不一致
            if((int)$bankId !== (int)$authCard['bank_id']){

                throw new \Exception(LangModel::getLang('ERROR_BANK_ID_MISMATCH'),self::getFinalCode('getValidChannelBankIdMismatch'));
            }
        }

        //根据银行获取可用支付通道的限额列表
        $limitList = PayLimitModel::getLimitListByBankId($bankId,$isBind);

        $usableList = [];

        if(!empty($limitList)){

            //用户已绑卡
            if($authCard){
                //绑卡用户最终的可用通道限额列表
                $usableList = self::getBindCardUserPayLimitList($userId,$bankId,$cash,$limitList);
            }else{
                //计算最大可充值金额
                foreach($limitList as $payType => $val){
                    $realCash = min((int)$val['limit'],(int)$val['day_limit']);
                    if($realCash >= $cash){
                        $usableList[$payType] = $realCash;
                    }
                }
            }
        }

        //没有可用的支付通道
        if(empty($usableList)){
            
            throw new \Exception(LangModel::getLang('ERROR_LIMIT_EXCEED'),self::getFinalCode('getValidChannel'));

        }
        //最优的支付通道
        $payType = self::chociePayType($usableList);

        return ['pay_type' => $payType];

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
                $usableList[$payType] =$tmpMaxCash;
            }
        }

        return $usableList;
    }

    /**
     * @param $usableList
     * 匹配最优的支付通道,实现通道自动切换
     */
    private static function chociePayType($list){
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

            //获取今天该通道的充值订单列表
            $extendDb = new OrderExtendDb();
            $orderList = $extendDb->getTodayOrderByType($payType);
            //没有充值记录,直接返回
            if(empty($orderList)){
                return $payType;
            }

            //该支付通道存在充值订单
            foreach ($orderList as $val){
                $orderIds[] = $val['order_id'];
            }

            //判断不成功的订单数是否大于2笔,如果大于切换通道
            $orderDb = new OrderDb();
            $num = $orderDb->getInvalidNumByOrderIds($orderIds);
            //不成功的订单次数大于该限制,直接切换支付通道
            if($num > OrderDb::INVALIED_ORDER_LIMIT){
                $payType = $typeList[1];
            }

        }

        return $payType;
    }

}