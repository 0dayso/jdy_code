<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/26
 * Time: 上午11:29
 */

namespace App\Http\Models\Common;

use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Tools\ToolTime;
use App\Http\Dbs\Project\ProjectDb;
use Illuminate\Support\Facades\Log;

class IncomeModel extends Model
{

    const BASE_YEAR_DAY     = 365,
        BASE_YEAR_MONTH     = 12,
        BASE_PERCENTAGE     = 100,
        MIN_INTEREST        = 0.005,    //零钱计划的计息最低利息,暂时没有用处
        TEN_THOUSANDS       = 10000;    //分为单位




    /**
     * @param $cash
     * @param $rate
     * @return float
     * @desc 获取零钱计划收益
     */
    public static function getCurrentInterest($cash, $rate)
    {

        return round( ($cash * ($rate / self::BASE_PERCENTAGE) / self::BASE_YEAR_DAY), 2 );

    }

    /**
     * @param $rate
     * @return float
     * @desc 获取零钱计划计息的最小金额
     */
    public static function getCurrentInterestMinCash($rate)
    {

        return round( (self::MIN_INTEREST * self::BASE_YEAR_DAY / ($rate / self::BASE_PERCENTAGE)), 2 );

    }


    /**
     * @param $rate
     * @return float
     * 每万元收益方法
     */
    public static function getTenThousandInterest($rate){

        return self::getCurrentInterest(self::TEN_THOUSANDS,$rate);
    }

    /**
     * @param $profit
     * @param $investTime
     * @param $refundType
     * @return float
     * @desc 获取预计万元收益
     */
    public static function getInterestPlan($profit, $investTime, $refundType='')
    {

        //等额本息万元收益计算
        if($refundType == ProjectDb::REFUND_TYPE_EQUAL_INTEREST){
            return self::getEqualPrincipalInterest($profit, $investTime);
        }
        if( $investTime <= self::BASE_YEAR_MONTH || $refundType == ProjectDb::REFUND_TYPE_ONLY_INTEREST ){

            return round((self::TEN_THOUSANDS * $profit / self::BASE_PERCENTAGE / self::BASE_YEAR_MONTH * $investTime), 2);

        }else{

            return round((self::TEN_THOUSANDS * $profit / self::BASE_PERCENTAGE / self::BASE_YEAR_DAY * $investTime), 2);

        }

    }

    /**
     * @desc 等额本息万元收益
     * @param $cash
     * @param $profit
     * @param $investTime
     * @return float
     */
    public static function getEqualPrincipalInterest($profit, $investTime, $cash = self::TEN_THOUSANDS){
        $tenThousandInterest = 0;
        //月利率
        $monthPercentage  = $profit / self::BASE_PERCENTAGE / self::BASE_YEAR_MONTH;
        $principalTotal = 0;
        //每月应还金额
        $noFormatEqualCash = (($cash*$monthPercentage * pow((1+$monthPercentage),$investTime)) / (pow((1+$monthPercentage),$investTime)-1));
        for($i=1; $i<=$investTime; $i++){
            //等额本息每月利息
            $monthInterest = round((($cash * $monthPercentage - $noFormatEqualCash) * pow((1+$monthPercentage),$i-1) + $noFormatEqualCash),2);
            //等额本息万元收益之和
            $tenThousandInterest += $monthInterest;

            $principalTotal += (round($noFormatEqualCash,2)-$monthInterest);

        }

        $diffCash = $cash - round($principalTotal,2);

        $tenThousandInterest -= round($diffCash, 2);

        return round($tenThousandInterest,2);
    }

    /**
     * @param $profit
     * @param $investTime
     * @param $cash
     * @return float
     * @desc 确定条件,获取收益
     */
    public static function getInterestByParam($profit, $investTime, $cash){

        return round(($cash * $profit / self::BASE_PERCENTAGE / self::BASE_YEAR_DAY * $investTime), 2);

    }

}
