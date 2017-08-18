<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/7
 * Time: 上午10:13
 * Desc: 公共工具类
 */

namespace App\Tools;

class ToolMoney
{

    /**
     * @param $cash
     * @return mixed
     * @desc 格式化金额，金额 * 100
     */
    public static function formatDbCashAdd($cash)
    {

        return $cash;

        //return $cash * 100;

    }

    /**
     * @param $cash
     * @return float
     * @desc 格式化金额，金额 / 100
     */
    public static function formatDbCashDelete($cash)
    {

        return $cash;

        //return $cash / 100;

    }


    /**
     * @param $cash
     * @return mixed
     * @desc 格式化金额，金额 * 10000
     */
    public static function formatDbCashAddTenThousand($cash)
    {

        return $cash * 10000;

    }

    /**
     * @param $cash
     * @return float
     * @desc 格式化金额，金额 / 10000
     */
    public static function formatDbCashDeleteTenThousand($cash)
    {

        return $cash / 10000;

    }

    //站内所有价格统一格式化
    public static function moneyFormat($money, $decimals = 2, $decPoint = '.', $thousandsSep = ',')
    {
        $isSign = 0;
        if ($money < 0) {
            $money  = abs($money);
            $isSign = 1;
        }
        $decPattern = '';
        for ($i = 0; $i < $decimals; $i++) {
            $decPattern .= '(\d)?';
        }
        $pattern  = sprintf('#^(\d+)(\.)?%s(\d+)?$#', $decPattern);
        $moneyStr = '';
        if (preg_match($pattern, $money, $match)) {
            $moneyStr .= isset($match[1]) ? (int)$match[1] : 0;
            $moneyStr .= isset($match[2]) ? $match[2] : '.';
            for ($i = 1; $i <= $decimals; $i++) {     //取小数点位数个数值，不存在则用0替代
                $index = 2 + $i;
                $moneyStr .= isset($match[ $index ]) ? $match[ $index ] : 0;
            }
        }

        $value = number_format(floatval($moneyStr), $decimals, $decPoint, $thousandsSep);
        if ($isSign == 1) $value = "-" . $value;

        return $value;

    }
}
