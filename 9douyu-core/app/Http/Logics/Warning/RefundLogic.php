<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/14
 * Time: 下午4:39
 * Desc: 回款计息相关
 */

namespace App\Http\Logics\Warning;


class RefundLogic extends WarningLogic
{

    /**
     * @param $data
     * @desc 定期加息券生成回款记录
     */
    public static function createBonusRateRecordWarning($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】定期加息券生成回款记录';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 零钱计划计息
     */
    public static function doRefundCurrentWarning($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】零钱计划计息';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 拆分零钱计划回款
     */
    public static function splitRefundCurrentWarning($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】拆分零钱计划回款';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * 凌晨开始计息
     */
    public static function doRefundCurrentJobWarning($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】凌晨开始零钱计划计息';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }


    /**
     * @param $data
     * @desc 分拆定期项目回款
     */
    public static function splitRefundProjectWarning($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】分拆定期项目回款';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 检测定期项目回款
     */
    public static function CheckProjectRefund($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Error】'.$data['msg'];

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 定期项目回款
     */
    public static function doRefundProjectWarning($data)
    {

        $arr['subject'] = $data;

        $arr['title'] = '【Warning】定期项目回款失败';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 发送回款成功通知
     */
    public static function doRefundSuccessNotice($data)
    {

        $arr['title'] = '【通知】'.$data['times'].'定期项目回款成功';

        $arr['subject'] = '今日回款总人数:'.$data['user_id_num']."\r\n \r\n".'今日回款总金额:'.$data['cash'];

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }
    
}