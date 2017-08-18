<?php

namespace App\Http\Controllers;

use App\Http\Logics\User\TokenLogic;

use App\Http\Logics\RequestSourceLogic;

use App\Http\Logics\User\UserLogic;
use App\Http\Requests\Request;
use App\Tools\ToolJump;
use App\Tools\ViewShare;

abstract class Controller extends LaravelController
{
    public function __construct(){

        $this->beforeConstruct();

        // 设置 session 获取session 使用 SessionLogic::getTokenSession()
        $tokenLogic = new TokenLogic;

        $request    = app('request');
        $token      = $request->input('token');
        $tokenKey   = $request->input('tokenKey');
        $factor     = $request->input('factor');

        $tokenLogic->setSession($token, $tokenKey, $factor);
        // 视图间公用变量
        ViewShare::set();

        $this->appendConstruct();
    }

    /**
     * 构造方法前扩展
     */
    public function beforeConstruct(){
        // 设置来源 获取来源 用 RequestSourceLogic::getSource()
        RequestSourceLogic::setSource();
    }

    /**
     * 构造方法追加扩展
     */
    public function appendConstruct(){}


    /**
     * @param bool $forceLogin
     * @return bool
     * 判断用户是否已登录
     */
    protected function checkLogin($forceLogin = false) {

        if(!$this->getUserId()){
            if($forceLogin) {
                $request         = app('request');
                if($request->ajax()) {
                    exit(json_encode(['code'=> 302, 'redirectUrl'=>'/login']));
                }else{
                    $routUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/login';

                    ToolJump::setLoginUrl($routUrl);

                    Header("Location: /login");
                    exit();
                }

//                Header("Location: /login");
//                exit();
            }
            return false;
        }
        return true;
    }

    /**
     * 判断用户是否实名过
     */
    protected function getVerifyStatus(){

        $userInfo = $this->getUser();
        $status = UserLogic::getUserAuthStatus($userInfo);
        if( $status['name_checked'] == 'off'){
            return false;
        }else{
            return true;
        }
    }


    /**
     * json return
     *
     * @param array $data
     * @return string
     */
    public static function returnJson($data = []){
        if (!headers_sent()) {
            header(sprintf('%s: %s', 'Content-Type', 'application/json'));
        }
        exit(json_encode($data));
    }


}
