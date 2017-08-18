<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/12
 * Time: 14:56
 */

namespace App\Http\Logics\Current;

use App\Http\Logics\Logic;
use App\Http\Models\Current\BonusModel;

class BonusLogic extends Logic{

    /**
     * 发送零钱计划加息券计息请求
     */
    public static function bonusInterestAccrual(){

        try{
            //获取昨日使用加息券的用户总数
            $total = BonusModel::getYesterdayBonusUserNum();

            //分页获取加息用户并发送请求到核心进行计息
            BonusModel::getYesterdayBonusUserList($total);

        }catch(\Exception $e){

        }
    }
}