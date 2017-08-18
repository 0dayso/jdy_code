<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/10/24
 * Time: 14:56
 */

use Cache;
class LoginTest extends \TestCase{


    public function testLogin(){

        $this->visit('http://wx.devmodule.9douyu.com/login')->see('登录');

    }

    /**
     * @desc 测试登录操作
     */
    public function testLoginIn(){
        $url = "http://wx.devmodule.9douyu.com/login/doLogin";
        $loginData = [
            'factor'     => '',
            'username' => 15501191752,
            'password' => 'lgh189491'
        ];

        $this->post($url, $loginData);
        echo Session::get('msg');
    }

    public function testRegister(){
        $this->visit('http://wx.devmodule.9douyu.com/register?channel=fy_news')->see('用户注册');
    }

    /**
     * 测试拆分后注册的行为
     */
    public function testRegisterIn(){
        $phone = "13300006957";
        $registerLogic = new \App\Http\Logics\User\RegisterLogic();
        $return = $registerLogic->sendRegisterSms((int)$phone);
        //dd(Cache::get('PHONE_VERIFY_CODE' .$phone));
        //dd($return);

        $url = "http://wx.devmodule.9douyu.com/register/doRegister";

        $data = [
            'phone' =>  $phone,
            'password' =>  '123qwe',
            'code' => \Cache::get('PHONE_VERIFY_CODE' .$phone),
            'aggreement' =>  '1',
            'invite_phone'=> '15501191752',
            'channel' =>  "fy_news",
            'invite_id' =>  '',
            'type' =>  '',
            'user_type' =>  '2',
        ];
        $this->post($url, $data);
        echo Session::get('errorMsg');
    }
}