<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/27
 * Time: 下午1:35
 */

namespace App\Tools;

use App\Lang\AppLang;

class ToolOrder{

    private static $orderPrefix = 'JDY_';
    /**
     * APP银行限额提示
     * @param  int $limit
     * @return string
     */
    public static function rechargeBankLimit($limit){

        $note = AppLang::APP_RECHARGE_BANK_LIMIT;

        if($limit >= 10000){

            $note = sprintf($note,($limit/10000).'万');

        }else if($limit >= 1000){

            $note = sprintf($note,($limit/1000).'千');

        }else{

            $note = sprintf($note,$limit);

        }
        return $note;
    }


    /**
     * 最小充值金额提示
     * @param  int $min_money
     * @return string
     */
    public static function minRechargeCash($min_money){

        $note = AppLang::APP_MIN_RECHARGE_CASH;

        return sprintf($note,$min_money);
    }

    /**
     * 银行卡显示格式
     * @param  string $cardNumber 
     * @return string             
     */
    public static function hideCardNumber($cardNumber) {

        if(empty($cardNumber)) return '';

        $string = substr($cardNumber ,0 ,4).'****'.substr($cardNumber, -4);

        return $string;
    }

    public static function getBankImage($bankId) {


        return assetUrlByCdn('/static/app/images/bank/'.$bankId.'.png'); //todo 换成图片实存路径
    }


    /**
     * @return string
     * 生成订单号
     */
    public static function generateOrder(){

        return self::$orderPrefix.date('YmdHis').rand(1000,9999);
    }

}