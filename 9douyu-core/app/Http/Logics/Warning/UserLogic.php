<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/15
 * Time: 下午3:16
 * Desc: 零钱计划相关
 */

namespace App\Http\Logics\Warning;

class UserLogic extends WarningLogic
{
    
    /**
     * @param $data
     * @desc 清除未计息用户的昨日收益
     */
    public static function doChangeBalanceWarning($data)
    {

        $arr['subject'] = $data;

        $arr['title'] = '【Warning】用户账户余额操作';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

}