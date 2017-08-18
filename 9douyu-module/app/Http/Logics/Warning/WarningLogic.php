<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2016/12/2
 * Time: 上午11:17
 * Desc: 系统报警
 */

namespace App\Http\Logics\Warning;
use App\Http\Dbs\SystemConfig\SystemConfigDb;
use App\Http\Logics\Data\DataStatisticsLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Common\ServiceApi\SmsModel;

class WarningLogic extends Logic{

    const   TYPE_EMAIL = 1, //邮件
            TYPE_PHONE = 2; //短信


    /**
     * @param $key
     * @return array
     * @desc 通过key获取配置信息
     */
    public static function getConfigDataByKey($key)
    {
        $db  = new SystemConfigDb();
        $res = $db->getConfig($key);
        if( !empty($res) ){

            $res['value']   = unserialize($res['value']);
            $res['second_des'] = unserialize($res['second_des']);

        }

        return $res;
    }


    /**
     * @param $phones
     * @param $title
     * @return bool
     * @desc 发送短信报警,如果失败,则持续报警,直至成功
     */
    public static function doSmsWarning($title){

        $logic = new DataStatisticsLogic();

        $phones = $logic->getMailTaskEmailConfig('systemWarning');

        if( empty($phones) ){

            return false;

        }

        $item = '';

        foreach ($phones as $v){

            $item[] = $v;

        }

        $result = SmsModel::sendNotice($item, $title);

        if( !$result['status'] ){

            \Log::Error(__METHOD__.'Error', [$title]);

            //self::doSmsWarning($title);

        }


    }

    /**
     * @param   $configData
     * @param   $data
     * @desc    执行发送
     */
    public static function doSendEmail($configData, $data,$attachment = [])
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