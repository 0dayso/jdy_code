<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 17/3/2
 * Time: 下午2:32
 */

namespace Tests\Http\Logics\User;

use App\Http\Logics\Article\ArticleLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\RegisterLogic;

use App\Http\Logics\User\SmsLogic;
use App\Http\Logics\User\UserLogic;


/**
 * 注册相关测试
 *
 * Class RegisterLogicTest
 * @package Tests\Http\Logics\User
 */
class RegisterLogicTest extends \TestCase
{

    protected function setUp()
    {
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_SERVER['REMOTE_ADDR']    = '127.0.0.1';

        putenv('CACHE_DRIVER=memcached');

        return parent::setUp();
    }

    /**
     * 发送手机验证码数据提供器
     *
     * @return array
     */
    public function sendSmsProvider(){
        $phone = '15200000000';
        return [
            [
                'status' => true,
                'phone' => $phone,
                'client' => 'android',
            ],
            [
                'status' => false,
                'phone' => '15201594661',
                'client' => 'android',
            ],
            [
                'status' => false,
                'phone' => '1520159466',
                'client' => 'ios',
            ],
            [
                'status' => false,
                'phone' => '1520159466111',
                'client' => 'android',
            ],
        ];
    }

    /**
     * 发送注册验证码
     *
     * @dataProvider sendSmsProvider
     *
     * @param $status
     * @param $phone
     * @param $client
     */
    public function testSendSms($status, $phone, $client){
        $registerLogic    = new RegisterLogic();

        $logicReturn      = $registerLogic->sendRegisterSms($phone);

        if($status) {
            $this->assertTrue($logicReturn['status']);
        }else{
            $this->assertNotTrue($logicReturn['status']);
        }
    }

    /**
     * 验证注册短信验证码
     * @dataProvider sendSmsProvider
     *
     * @param $status
     * @param $phone
     * @param $client
     */
    public function testCheckRegisterCode($status, $phone, $client){
        if($status) {
            $logic         = new SmsLogic();
            $sessionCode   = \Cache::get('PHONE_VERIFY_CODE' . $phone);
            $logicReturn   = $logic->checkRegisterCode($phone, $sessionCode);

            $this->assertTrue($logicReturn['status']);
        }
    }


    /**
     * 注册执行
     * @dataProvider sendSmsProvider
     *
     * @param $status
     * @param $phone
     * @param $client
     */
    public function testDoRegister($status, $phone, $client){

        RequestSourceLogic::setSource($client);
        $this->assertEquals(RequestSourceLogic::getSource(), $client);

        $sessionCode = \Cache::get('PHONE_VERIFY_CODE' . $phone);

        $data = [
            'phone'         => $phone,
            'phone_code'    => $sessionCode,
            'password'      => '123qwe',
            'aggreement'    => 1,//同意协议
            'request_source'=> RequestSourceLogic::getSource(),
            'invite_phone'  => '',
            'channel'       => '',
            'channel_id'    => '',
        ];

        //数据处理
        $registerLogic = new RegisterLogic;
        $logicRegisterReturn = $registerLogic->doRegister($data);
        if($status) {
            $this->assertTrue($logicRegisterReturn['status']);
            \Log::info(__METHOD__, [$logicRegisterReturn]);
            $userId         = $logicRegisterReturn['data']['coreApiData']['id'];

            $logic = new UserLogic();

            $logic->doUserFrozen($userId);

        } else{
            $this->assertNotTrue($logicRegisterReturn['status']);
        }
    }


    /**
     * 注册协议
     *
     */
    public function tsstGetAgreement(){
        $articleLogic    = new ArticleLogic();

        $logicReturn     = $articleLogic->getRegisterAgreementHtml();

        $this->assertTrue($logicReturn['status']);
        $this->assertArrayHasKey('info', $logicReturn['data']);
    }
}