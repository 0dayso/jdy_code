<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/14
 * Time: 下午4:38
 * Desc: 投资相关
 */

namespace App\Http\Logics\Warning;


class InvestLogic extends WarningLogic
{

    /**
     * @param $data
     * @desc 投资零钱计划失败
     */
    public static function investCurrentWarning($data)
    {

        $arr['subject'] = $data;

        $arr['title'] = '【Warning】零钱计划投资失败';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');
        
        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 投资定期失败
     */
    public static function investProjectWarning($data)
    {

        $arr['subject'] = $data;

        $arr['title'] = '【Warning】投资定期失败';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }


}