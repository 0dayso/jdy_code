<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/13
 * Time: 下午5:43
 * Desc: 逻辑层捕获到的model错误信息，触发报警通知，根据级别来确定通知方式
 *          1：发送邮件；
 *          2：发送短信；
 */

namespace App\Http\Logics\Warning;

use App\Http\Logics\Logic;
use App\Http\Logics\Module\SystemConfig\SystemConfigLogic;
use App\Http\Models\Common\EmailModel;
use Log;

class WarningLogic extends Logic
{

    const   TYPE_EMAIL = 1, //邮件
            TYPE_PHONE = 2; //短信


    /**
     * @param $key
     * @return array
     * @desc 通过key获取配置信息
     */
    public static function getConfigDataByKey($key)
    {

        return SystemConfigLogic::getConfig($key);

    }

    /**
     * @param $configData
     * @param $data
     * @desc 执行发送
     */
    public static function doSend($configData, $data,$attachment = [])
    {

        if(isset($configData['value']['RECEIVE']) && !empty($configData['value']['RECEIVE'])){

            $receiveList = $configData['value']['RECEIVE'];

            $receiveList = explode('|', $receiveList);

            foreach ($receiveList as $value){

                $receiveList = explode(',', $value);

                $email[$receiveList[0]] = $receiveList[1];

            };
            if( $configData['value']['TYPE'] == self::TYPE_EMAIL ){

                $emailModel = new EmailModel();
                $result = $emailModel->sendHtmlEmail($email, $data['title'], $data['subject'],$attachment);
                
                if( !$result['status'] ){

                    Log::Error(__METHOD__.'doSendError', [json_encode($data)]);

                }

            }

            return $result;

        }


    }


}