<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/8
 * Time: 下午1:54
 */

namespace Tests\AppApi\Logic\Password;

use App\Http\Logics\User\PasswordLogic;

class UpdatePasswordTest extends \TestCase
{
    public function updateData(){
        return [
            //修改成功
            [
                'user_id'    => '14',
                'old_pwd'    => '123qwe',
                'new_pwd'    => 'testpassword123',
                'repeat_pwd' => 'testpassword123',
                'status'     => true,
            ],
            //与原密码相同
            [
                'user_id'    => '14',
                'old_pwd'    => 'testpassword123',
                'new_pwd'    => 'testpassword123',
                'repeat_pwd' => 'testpassword123',
                'status'     => false,
            ],
            //与交易密码相同
            [
                'user_id'    => '14',
                'old_pwd'    => 'testpassword123',
                'new_pwd'    => 'qwe123',
                'repeat_pwd' => 'qwe123',
                'status'     => false,
            ],
            //两次密码不相同
            [
                'user_id'    => '14',
                'old_pwd'    => 'testpassword123',
                'new_pwd'    => 'qwe123',
                'repeat_pwd' => 'qwe123123',
                'status'     => false,
            ],
            //原密码错误
            [
                'user_id'    => '14',
                'old_pwd'    => '123qweqwe',
                'new_pwd'    => 'test123',
                'repeat_pwd' => 'test123',
                'status'     => false,
            ],
            //初始化密码
            [
                'user_id'    => '14',
                'old_pwd'    => 'testpassword123',
                'new_pwd'    => '123qwe',
                'repeat_pwd' => '123qwe',
                'status'     => true,
            ],
        ];
    }

    public function updateTradingData(){
        return [
            //修改成功
            [
                'user_id'    => '14',
                'old_pwd'    => 'qwe123',
                'new_pwd'    => '123testpwd',
                'repeat_pwd' => '123testpwd',
                'status'     => true,
            ],
            //与原密码相同
            [
                'user_id'    => '14',
                'old_pwd'    => '123testpwd',
                'new_pwd'    => '123testpwd',
                'repeat_pwd' => '123testpwd',
                'status'     => false,
            ],
            //与交易密码相同
            [
                'user_id'    => '14',
                'old_pwd'    => '123testpwd',
                'new_pwd'    => '123qwe',
                'repeat_pwd' => '123qwe',
                'status'     => false,
            ],
            //两次密码不相同
            [
                'user_id'    => '14',
                'old_pwd'    => '123testpwd',
                'new_pwd'    => 'qqqqq123',
                'repeat_pwd' => 'qq123123',
                'status'     => false,
            ],
            //原密码错误
            [
                'user_id'    => '14',
                'old_pwd'    => '123qweqwe',
                'new_pwd'    => 'test123',
                'repeat_pwd' => 'test123',
                'status'     => false,
            ],
            //初始化密码
            [
                'user_id'    => '14',
                'old_pwd'    => '123testpwd',
                'new_pwd'    => 'qwe123',
                'repeat_pwd' => 'qwe123',
                'status'     => true,
            ],
        ];
    }
    /**
     * @param $userId
     * @param $oldPassword
     * @param $newPassword
     * @param $repeatPwd
     * @param $status
     * @dataProvider updateData
     */
    public function testUpdatePassword($userId,$oldPassword,$newPassword,$repeatPwd,$status){
        $logic = new PasswordLogic();
        $result = $logic->updatePasswordV4($userId,$oldPassword,$newPassword,$repeatPwd);

        $this->assertEquals($status,$result['status']);
    }

    /**
     * @param $userId
     * @param $oldPassword
     * @param $newPassword
     * @param $repeatPwd
     * @param $status
     * @dataProvider updateTradingData
     */
    public function testUpdateTradingPassword($userId,$oldPassword,$newPassword,$repeatPwd,$status){
        $logic = new PasswordLogic();
        $result = $logic->updateTradingPasswordV4($userId,$oldPassword,$newPassword,$repeatPwd);

        $this->assertEquals($status,$result['status']);
    }
}