<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/14
 * Time: 下午4:40
 * Desc: 定期项目相关
 */

namespace App\Http\Logics\Warning;


class ProjectLogic extends WarningLogic
{

    /**
     * @param $data
     * @desc 更新项目状态为投资中
     */
    public static function updateStatusInvestingWarning($data)
    {

        $arr['subject'] = $data;

        $arr['title'] = '【Warning】更新项目状态为投资中';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 更新项目状态为还款中
     */
    public static function updateStatusRefundingWarning($data)
    {

        $arr['subject'] = $data;

        $arr['title'] = '【Warning】更新项目状态为还款中';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 更新项目状态为已完结
     */
    public static function updateStatusFinishedWaring($data){

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】更新项目状态为已完结';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }




}