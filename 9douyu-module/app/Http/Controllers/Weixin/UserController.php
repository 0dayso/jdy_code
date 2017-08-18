<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/19
 * Time: 上午10:38
 */

namespace App\Http\Controllers\Weixin;

use App\Http\Logics\User\UserLogic;
use Illuminate\Http\Request;
use App\Http\Logics\User\PasswordLogic;


class UserController extends WeixinController
{

    public function __construct()
    {
        parent::__construct();
        $this->checkLogin(true);
    }

    /**
     * @param Request $request
     * @return mixed
     * @desc 验证交易密码是否证确认
     */
    public function checkTradePassword(Request $request){

        $password   = $request->input('trading_password');

        $userId     = $this->getUserId();
        
        $user       = $this->getUser();
        
        $authData   = UserLogic::getUserAuthStatus($user);
        
        if($authData['password_checked'] == 'off'){
            
            $logic = new PasswordLogic();
            
            $result = $logic->setTradingPassword($password,$userId);
            
        }else{
            $logic      = new PasswordLogic();

            $result     = $logic->checkTradingPassword($password,$userId);
        }

        

        return json_encode($result);
    }


    /**
     * 判断用户是否实名过
     */
    protected function checkIdentity(){

        $verifyStatus = $this->getVerifyStatus();

        if($verifyStatus === false){

            Header("Location: /user/verify");

            exit();
        }
    }

}