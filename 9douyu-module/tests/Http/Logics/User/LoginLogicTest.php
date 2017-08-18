<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 17/3/2
 * Time: 上午10:09
 */

namespace Tests\Http\Logics\User;

use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\LoginLogic;
use App\Http\Logics\User\TokenLogic;


/**
 * 登陆 logic
 *
 * Class LoginLogicTest
 * @package Tests\Http\Logics\User
 */
class LoginLogicTest extends \TestCase
{

    protected function setUp()
    {
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_SERVER['REMOTE_ADDR']    = '127.0.0.1';

        parent::setUp();
    }

    /**
     * 登陆数据提供器
     *
     * @return array
     */
    public function InProvider(){
        return [
            [
                'status' => true,
                'client' => 'ios',
                'data' => [
                    'factor'     => '',
                    'username'   => '15201594661',
                    'password'   => '123qwe',
                ]
            ],
            [
                'status' => false,
                'client' => 'ios',
                'data' => [
                    'factor'     => '',
                    'username'   => '15201591111',
                    'password'   => '123qwe',
                ]
            ],
            [
                'status' => false,
                'client' => 'ios',
                'data' => [
                    'factor'     => '',
                    'username'   => '1511',
                    'password'   => '123qwe',
                ]
            ],

            [
                'status' => false,
                'client' => 'android',
                'data' => [
                    'factor'     => '',
                    'username'   => '15201594661',
                    'password'   => '12we',
                ]
            ],
            [
                'status' => true,
                'client' => 'android',
                'data' => [
                    'factor'     => 'af2312ee3rf3rf4g5tg5g212121',
                    'username'   => '15201594661',
                    'password'   => '123qwe',
                ]
            ],
        ];
    }

    /**
     * @param $status
     * @param $client
     * @param $data
     *
     * @dataProvider InProvider
     */
    public function testIn($status, $client, $data){

        RequestSourceLogic::setSource($client);
        $this->assertEquals(RequestSourceLogic::getSource(), $client);

        $loginLogic                = new LoginLogic();

        $logicReturn               = $loginLogic->in($data);
        if($status === true) {
            $this->assertTrue($logicReturn['status']);
        }else{
            $this->assertNotTrue($logicReturn['status']);
        }
    }

    /**
     *
     * @return mixed
     */
    public function testInDepends(){

        $data                      = [
            'factor'     => 'af2312ee3rf3rf4g5tg5g212121',
            'username'   => '15201594661',
            'password'   => '123qwe',
        ];

        $client                    = 'android';

        RequestSourceLogic::setSource($client);
        $this->assertEquals(RequestSourceLogic::getSource(), $client);

        $loginLogic                = new LoginLogic();

        $logicReturn               = $loginLogic->in($data);

        $this->assertTrue($logicReturn['status']);

        return [
            'client'    => $client,
            'token'     => $logicReturn['data']['access_token'],
            'factor'    => $data['factor'],
            'tokenKey'  => $logicReturn['data']['access_token_key']
        ];
    }


    /**
     * @depends testInDepends
     *
     * @param $data
     */
    public function testDestroy($data){

        $client     = $data['client'];
        $token      = $data['token'];
        $factor     = $data['factor'];
        $tokenKey   = $data['tokenKey'];

        $tokenLogic = new TokenLogic;
        $tokenLogic->setSession($token, $tokenKey, $factor);

        RequestSourceLogic::setSource($client);
        $this->assertEquals(RequestSourceLogic::getSource(), $client);

        $logicReturn = LoginLogic::destroy();

        $this->assertTrue($logicReturn['status']);
    }

    /**
     * 检测手机号数据提供器
     *
     * @return array
     */
    public function checkPhoneProvider(){
        return [
            [
                'status' => true,
                'phone' => '15201594661',
            ],
            [
                'status' => false,
                'phone' => '1520159466',
            ],
            [
                'status' => false,
                'phone' => '1520159466111',
            ],
        ];
    }


    /**
     * 检测手机号
     *
     * @dataProvider checkPhoneProvider
     *
     * @param $status
     * @param $phone
     */
    public function testCheckPhone($status, $phone){
        $logicReturn = LoginLogic::checkPhone($phone);

        if($status === true) {
            $this->assertTrue($logicReturn['status']);
        }else{
            $this->assertNotTrue($logicReturn['status']);
        }
    }

}