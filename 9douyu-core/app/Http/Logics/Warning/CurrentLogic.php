<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/15
 * Time: 下午3:16
 * Desc: 零钱计划相关
 */

namespace App\Http\Logics\Warning;

class CurrentLogic extends WarningLogic
{
    
    /**
     * @param $data
     * @desc 清除未计息用户的昨日收益
     */
    public static function doClearYesterdayInterestWarning($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】清除未计息用户的昨日收益';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * 回款自动进活动成功金额
     */
    public static function doRefundToCurrentSuccessNotice($data){


        $arr['subject'] = $data;

        $arr['title'] = '【通知】'.date('Y-m-d').'自动回款进零钱计划成功';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

        
    }

    /**
     * @param $data
     * @desc 添加零钱计划加息券利息数据失败
     */
    public static function addBonusInterest($data){

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】'.date('Y-m-d').'添加零钱计划加息券利息数据失败';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

}