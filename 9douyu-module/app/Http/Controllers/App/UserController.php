<?php
/**
 * UserAction
 * @author husong
 *
 */
namespace App\Http\Controllers\App;

use App\Http\Logics\User\SessionLogic;

class UserController extends AppController {

    protected $userId = "";

    /**
     * 初始化方法 可用来验证用户是否登录
     */
    public function __construct(){

        $request    = app('request');
        parent::__construct($request);
        
        $this->setClient($this->client);
        $this->setToken($this->token);
        $this->setVersion($this->version);
        $this->checkAuth();

        /*
        //利用缓存标记今天已经登录
        $cache = getCache();
        $userId = $this->getUserId();
        $key = 'APP_LOGIN_' . $userId;
        if($userId && (date('Y-m-d') != $cache->get($key))) {
            UserSFLogic::updateLastLoginTime($userId, I('version', ''));
            $cache->set($key, date('Y-m-d'));
        }
        */
    }

    protected  function checkAuth(){

        if(!$this->checkToken()){

            $data = self::callError('登录超时');
            return self::appReturnJson($data,self::CODE_LOGIN_EXPIRE);
        }
        return true;
    }
    
    protected  function checkToken(){

        $token  = $this->getToken();
        $userId = "";

        if(!empty($token)){
           $userId = $this->getUserId();
        }

        if(empty($userId) && !empty($token)){
            return false;
        }else{
            $this->userId = $userId;
            return true;
        }
    }

    public function getUserId(){

        $session = SessionLogic::getTokenSession();
        
        if(isset($session['id'])){
            return $session['id'];
        }

        if(empty($userId)){
            $data = self::callError('登录超时');
            return self::appReturnJson($data,self::CODE_LOGIN_EXPIRE);
        }
    }


}
