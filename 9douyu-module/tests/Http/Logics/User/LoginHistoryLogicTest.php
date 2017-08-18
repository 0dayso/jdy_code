<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/10/18
 * Time: 19:56
 */

namespace Tests\Http\Logics\User;

use App\Http\Dbs\User\LoginHistoryDb;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\LoginLogic;
use App\Http\Models\User\UserLoginModel;
use App\Http\Models\User\UserModel;
use Illuminate\Support\Facades\Request;

class LoginHistoryLogicTest extends \TestCase{

    /**
     * @desc 获取用户封装结果
     * @throws \Exception
     */
    public function testGetCoreUserInfo(){

        $userInfo = UserModel::getCoreApiBaseUserInfo('15501191752', false);

        $return['userInfo']    = $userInfo;
        $return['request_source'] = RequestSourceLogic::getSource();

        dump($return);
    }
    /**
     * @desc 测试添加登录记录数据
     * @return mixed
     */
    public function testAddLoginHistoryDb(){


        $userLoginModel = new UserLoginModel();
        $data = [
            'user_id' => '69',
            'login_ip' => isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : Request::getClientIp(),
            'app_request' => '1'
        ];

        $result = $userLoginModel->createUserLoginHistory($data);
        echo $result;
    }

    /**
     * 测试登录成功事件处理
     */
    public function testLoginSuccessEvent(){
        $data = $this->testGetCoreUserInfo();
        $data['request_source'] = 'wap';
        \Event::fire(new \App\Events\User\LoginSuccessEvent(
                ['data' => $data]
            )
        );
    }
    /**
     * 获取某用户的登录次数
     */
    public function testGetLoginNum(){

        $userLoginModel = new UserLoginModel();

        $userId = 69;

        echo $userLoginModel->getUserLoginNum($userId);

    }

    /**
     * 获取多个用户的登录次数
     */
    public function testGetLoginNumByUserIds(){
        $loginHistoryModel = new UserLoginModel();

        $userIds = ['69','14'];

        $loginNums = $loginHistoryModel->getLoginNumByUserIds($userIds);
        foreach($loginNums as $key=>$val){
            dump($val);
        }
    }

    /**
     * 测试登录
     */
    public function testLogin(){

        $loginLogic = new LoginLogic();

        $data   =[
            'factor'     => '',  // 非browser 的 客户端 传入的加密 token的因子
            'username'   => '15501191752',
            'password'   => '123qwe',
        ];

        return $loginLogic->in($data);

    }
}