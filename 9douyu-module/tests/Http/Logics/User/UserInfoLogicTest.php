<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/3
 * Time: 下午4:20
 */

namespace Tests\Http\Logics\User;
use App\Http\Logics\User\UserInfoLogic;


/**
 * 用户扩展信息相关测试
 *
 * Class RegisterLogicTest
 * @package Tests\Http\Logics\User
 */
class UserInfoLogicTest extends \TestCase
{
    /**
     * 修改个人邮箱地址数据提供器
     *
     * @return array
     */
    public function updateEmailProvider(){
        return [
            //正常
            [
                'is' => true,
                'data' => [
                    "user_id" => 31,
                    "email"   => "630132223@qq.com",
                ]
            ],
            [
                'is' => true,
                'data' => [
                    "user_id" => 33,
                    "email"   => "",
                ]
            ],
            //无此user_id
            [
                'is' => false,
                'data' => [
                    "user_id" => 1500,
                    "email"   => "630132223@qq.com",
                ]
            ],
            [
                'is' => false,
                'data' => [
                    "user_id" => 0,
                    "email"   => "630132223@qq.com",
                ]
            ],
            //email格式错误
            [
                'is' => false,
                'data' => [
                    "user_id" => 31,
                    "email"   => "630132223qq.com",
                ]
            ],
        ];
    }

    /**
     * 修改个人邮箱地址数据提供器
     *
     * @return array
     */
    public function updateAddressProvider(){
        return [
            //正常
            [
                'is' => true,
                'data' => [
                    "user_id" => 31,
                    "address" => "北京市昌平区东小口镇 中滩村大街6号院 合立方小区 2号楼  2单元 1304",
                ]
            ],
            [
                'is' => true,
                'data' => [
                    "user_id" => 33,
                    "address" => "!@#$%……&*()——+1234567890",
                ]
            ],
            [
                'is' => true,
                'data' => [
                    "user_id" => 91,
                    "address" => "",
                ]
            ],
            //无此user_id
            [
                'is' => false,
                'data' => [
                    "user_id" => 1500,
                    "address" => "北京市昌平区东小口镇",
                ]
            ],
            [
                'is' => false,
                'data' => [
                    "user_id" => 0,
                    "address" => "北京市昌平区东小口镇",
                ]
            ],
        ];
    }
    /**
     * @param $is
     * @param $data
     * @dataProvider updateEmailProvider
     */
    public function testUpdateEmail($is, $data){
        $logic = new UserInfoLogic();
        $result = $logic -> setUserEmail($data['user_id'],$data['email']);

        if($is === true) {
            $this->assertTrue($result['status']);
        }else{
            $this->assertNotTrue($result['status']);
        }
    }

    /**
     * @param $is
     * @param $data
     * @dataProvider updateAddressProvider
     */
    public function testUpdateAddress($is, $data){
        $logic = new UserInfoLogic();
        $result = $logic -> setUserAddress($data['user_id'],$data['address']);

        if($is === true) {
            $this->assertTrue($result['status']);
        }else{
            $this->assertNotTrue($result['status']);
        }
    }
}