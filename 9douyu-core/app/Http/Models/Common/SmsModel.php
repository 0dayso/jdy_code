<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/3
 * Time: 10:53
 * Desc: 发送短信基础model
 */
namespace App\Http\Models\Common;

use App\Http\Logics\Module\SystemConfig\SystemConfigLogic;
use Illuminate\Support\Facades\App;


class SmsModel{


    private $params = [];

    /**
     * @param $phone
     * @param $msg
     * 数据组装
     */
    private function formatData($phone,$msg){

        $data = [
            'phone' => $phone,
            'msg'   => $msg
        ];

        $this->params = ['form_params' => $data];

        return '';

        //测试环境,如果不在白名单内,去掉
        $environment = App::environment();

        if( $environment != 'prod' ) {

            $whitePhoneList = SystemConfigLogic::getConfig('WHITE_PHONE_LIST');

            $whitePhoneList = explode(',', $whitePhoneList);

            if( !in_array($phone, $whitePhoneList) ){

                return false;

            }

        }

        $data = [
            'phone' => $phone,
            'msg'   => $msg
        ];

        $this->params = ['form_params' => $data];
    }


    /**
     * 发送通知短信
     */
    public function sendNotice($phone='',$msg=''){

        $this->formatData($phone,$msg);

        $result = $this->sendSms('notice');

        return $result;
    }

    /**
     * 发送验证码短信
     */
    public function sendVerify($phone = '',$msg = ''){

        $this->formatData($phone,$msg);

        $result = $this->sendSms('verify');

        return $result;
    }


    /**
     * 发送语音短信
     */
    public function sendVoice($phone = '',$msg = ''){

        $this->formatData($phone,$msg);

        $result = $this->sendSms('voice');

        return $result;
    }

    /**
     * 发送营销短信
     */
    public function sendMarket($phone = '',$msg = ''){

        $this->formatData($phone,$msg);

        $result = $this->sendSms('market');

        return $result;
    }

    /**
     * @param $type  发送短信类型 notice-通知类 verify-验证码 voice-语音 market-营销
     * 发送短信出口
     */
    private function sendSms($type){

        $return = HttpQuery::serverPost('/'.$type, $this->params);

        return $return;

    }
}