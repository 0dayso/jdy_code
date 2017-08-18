<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/7
 * Time: 下午2:11
 */

namespace Tests\AppApi\Logic\Sms;

use App\Http\Logics\User\SmsLogic;
use App\Http\Logics\User\UserLogic;

class SmsLogicTest extends \TestCase
{

    public function sendData(){
        return [
            //电话号存在,找回登录密码
            [
                'status'=> true,
                'phone' => '18234475430',
                'type'  => 'find_password',

            ],
            //电话号存在,找回交易密码
            [
                'status'=> true,
                'phone' => '18234475430',
                'type'  => 'find_tradingPassword',

            ],
            //电话号不存在,找回登录密码
            [
                'status'=> false,
                'phone' => '',
                'type'  => 'find_password',

            ],
            //电话号不存在,找回交易密码
            [
                'status'=> true,
                'phone' => '',
                'type'  => 'find_tradingPassword',

            ],

        ];
    }

    public function checkData(){
        return [
            //实名电话号
            [
                'status'  => true,
                'phone'   => '18234475430',
                'is_real' => 'on'
            ],
            //空号
            [
                'status'  => true,
                'phone'   => '',
                'is_real' => 'off'
            ],
            //未实名电话号
            [
                'status'  => true,
                'phone'   => '18601956440',
                'is_real' => 'off'
            ],
        ];
    }

    /**
     * @param $phone
     * @param $type
     * @param $status
     * @dataProvider sendData
     */
    public function testSendSms($phone, $type, $status){
        $logic = new SmsLogic();
        $result = $logic->sendSms($phone,$type);
        $this->assertEquals($result['status'], $status);
    }

    /**
     * @param $phone
     * @param $status
     * @param $is_real
     * @dataProvider checkData
     */
    public function testCheckIsRealName($phone, $status, $is_real){
        $userLogic = new UserLogic();
        $result = $userLogic->checkIsRealName($phone);

        $this->assertEquals($result['status'], $status);
        $this->assertEquals($result['data']['is_real'], $is_real);
        $this->assertEquals($result['data']['phone'], $phone);
    }
}