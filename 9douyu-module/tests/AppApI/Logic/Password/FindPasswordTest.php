<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/7
 * Time: 下午3:38
 */
namespace Tests\AppApi\Logic\Password;

use App\Http\Logics\User\PasswordLogic;

class FindPasswordTest extends \TestCase
{
    public function sendData(){
        return [
            //密码初始化
            [
                'status'  => true,
                'id'      => '33',
                'phone'   => '18234475430',
                'pwd'     => '123qwe',
                'new_pwd' => '123qwe'
            ],
            //电话号为空
            [
                'status'  => false,
                'phone'   => '',
                'id'      => '',
                'pwd'     => '123qwe',
                'new_pwd' => '123qwe'
            ],
            //电话号错误
            [
                'status'  => false,
                'id'      => 'q33',
                'phone'   => '1234567891',
                'pwd'     => '123qwe',
                'new_pwd' => '123qwe'
            ],
            //密码为空
            [
                'status'  => false,
                'id'      => '33',
                'phone'   => '18234475430',
                'pwd'     => '',
                'new_pwd' => ''
            ],
            //电话号正确,密码纯数字
            [
                'status'  => false,
                'id'      => '33',
                'phone'   => '18234475430',
                'pwd'     => '123123123',
                'new_pwd' => '123123123'
            ],
            //电话号正确,密码不够6位
            [
                'status'  => false,
                'id'      => '33',
                'phone'   => '18234475430',
                'pwd'     => '1231',
                'new_pwd' => '1231'
            ],
            //电话号正确,密码大于16位
            [
                'status'  => false,
                'id'      => '33',
                'phone'   => '18234475430',
                'pwd'     => 'qwe123123123123123',
                'new_pwd' => 'qwe123123123123123'
            ],
            //电话号正确,密码不够6位
            [
                'status'  => false,
                'id'      => '33',
                'phone'   => '18234475430',
                'pwd'     => '1231',
                'new_pwd' => '1231'
            ],
            //电话号正确,两次密码不一致
            [
                'status'  => false,
                'id'      => '33',
                'phone'   => '18234475430',
                'pwd'     => '123qwe',
                'new_pwd' => '123123qwe'
            ],
            //电话号正确,两次密码不一致
            [
                'status'  => false,
                'id'      => '33',
                'phone'   => '18234475430',
                'pwd'     => '123qwe',
                'new_pwd' => ''
            ],
            //密码重置成功
            [
                'status'  => true,
                'id'      => '33',
                'phone'   => '18234475430',
                'pwd'     => 'jdy123',
                'new_pwd' => 'jdy123'
            ],
        ];
    }

    /**
     * @param $phone
     * @param $pwd
     * @param $new_pwd
     * @param $status
     * @dataProvider sendData
     */
    public function testResetPassword($status,$id,$phone,$pwd,$new_pwd){

        $logic      = new PasswordLogic();
        $result     = $logic->resetPassword($phone,$pwd,$new_pwd);
        $this->assertEquals($result['status'],$status);
    }

    /**
     * @param $id
     * @param $pwd
     * @param $new_pwd
     * @param $status
     * @dataProvider sendData
     */
    public function testFindTradingPwd($status,$id,$phone,$pwd,$new_pwd){

        $logic      = new PasswordLogic();
        $result     = $logic->modifyTradingPassword($pwd,$id,$new_pwd);
        $this->assertEquals($result['status'],$status);
    }
}