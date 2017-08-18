<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 12:08
 */

namespace App\Http\Models\Common;
use App\Http\Dbs\Order\PayLimitDb;
use App\Http\Dbs\Order\UserPayListDb;
use App\Tools\ToolVersion;

class PayLimitModel{

    /**
     * @param $bankId
     * 根据银行卡获取可用通道的限额列表
     * @return Array(
     *     1201 => array(
     *          limit => 10000,     //单笔
     *          day_limit => 50000 //单日
     *      )
     * )
     */
    public static function getLimitListByBankId($bankId,$isBind = true,$version='',$isApp=false){

        $limitArr = [];
        $limitDb = new PayLimitDb();

        if($isBind){
            //根据绑定卡的银行获取所有的限额列表信息
            $limitList = $limitDb->getLimitByBank($bankId);
        }else{
            //未绑卡情况要过滤掉翼支付
            $limitList = $limitDb->getUnbindLimitByBank($bankId);
        }

        //没有可用的支付通道
        if(empty($limitList)){
            return $limitArr;
        }
        foreach($limitList as $value){
            $val = (array)$value;

            //按版本号过滤渠道
            if(self::isFilter($version,$val['version'],$isApp)){

                continue;
            }

            $limit = (int)$val['limit'];//单笔限额
            $dayLimit = (int)$val['day_limit'];//单日限额

            $limitArr[$val['pay_type']] = [
                'limit' => ($limit === 0) ? PayLimitDb::PAY_LIMIT : $limit,
                'day_limit' => ($dayLimit === 0) ? PayLimitDb::PAY_LIMIT : $dayLimit,
            ];
        }

        return $limitArr;
    }


    /**
     * @param $version
     * @param $dbVersion
     * @param $isApp
     * @return bool
     * 根据版本号判断该渠道是否应该过滤
     */
    public static function isFilter($version,$dbVersion,$isApp){

        if($isApp && ToolVersion::compare_version($version,$dbVersion) <= 0){

            return true;
        }

        return false;
    }

    /**
     * @param $userId
     * 获取用户已成功充值的记录列表
     * @return Array(
     *    1201 => 10000
     *    1202 => 50000
     * )
     */
    public static function getUserRechargedList($userId,$bankId){

        $userList = [];
        //用户充值成功记录列表
        $userPayDb  = new UserPayListDb();
        $list = $userPayDb->getUserPayListByBankId($userId,$bankId);

        foreach($list as $val){
            $userList[$val['pay_type']] = $val['day_cash'];
        }

        return $userList;
    }
}