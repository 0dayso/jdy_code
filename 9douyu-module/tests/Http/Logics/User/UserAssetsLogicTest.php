<?php
/*
 * User: linguanghui
 * Date: 17/3/04
 * Time: 10:34 Am
 * Desc: App4.0用户资产测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;

class UserAssetsLogicTest extends TestCase
{
   /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * @desc 用户数据供给
     * @return
     */
    public function userData(){

        $userData = [
            [
            'user_id1'=> 258082,
            'user_id2'=> 0,
            ]
            ];
        return $userData;
    }

    /**
     * @desc 测试获取用户信息
     * @dataProvider userData
     */
    public function testGetUserData($user_id1, $user_id2){

        $userLogic = new UserLogic();

        //测试user_id存在不为空
        $coreUserInfo = CoreApiUserModel::getCoreApiUserInfo($user_id1);

        $this->assertArrayHasKey('id', $coreUserInfo);

        $this->assertEquals($user_id1, $coreUserInfo['id']);

        $userInfo1 = $userLogic->getAppUserInfo($user_id1);

        $this->assertArrayHasKey('items', $userInfo1['data']);


        //测试user_id＝0
        $coreUserInfo1 = CoreApiUserModel::getCoreApiUserInfo($user_id2);

        $this->assertEquals([], $coreUserInfo1);
    }

    /**
     * @desc 测试AppV4格式化用户资产数据
     * @dataProvider userData
     * @return mixed
     */
    public function testAppV4FormatUserInfo($user_id1, $user_id2){

        $userLogic = new UserLogic();

        #测试格式化用户数据不为空
        $userInfo1 = $userLogic->getAppUserInfo($user_id1);

        $formatUserInfo1 = $userLogic->formatAppV4UserInfo($userInfo1['data'], $user_id1);

        $this->assertArrayHasKey('button_list', $formatUserInfo1);

        #测试传入user_info为空，user_id不为空
        $formatUserInfo2 = $userLogic->formatAppV4UserInfo([],$user_id1);

        $this->assertEquals([],$formatUserInfo2);


        //测试传入user_info不为空，user_id＝0
        $formatUserInfo3 = $userLogic->formatAppV4UserInfo($userInfo1['data'],$user_id2);
        $this->assertEquals([],$formatUserInfo3);

    }

}
