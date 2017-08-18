<?php

namespace App\Http\Controllers;

use App\Http\Logics\Auth\SecurityAuthLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Common\AppSecurityModel;
use Illuminate\Support\Facades\App;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Log;

class Controller extends BaseController
{

    protected static $authId       = null;
    protected static $name         = null;
    protected static $sign         = null;
    protected static $secretKey    = null;
    protected static $_request     = null;

    /**
     * @param Request $request
     * @desc 公共检测
     */
    public function __construct(Request $request)
    {

        /*
        self::$_request = $request;
        
        $environment = App::environment();

        //开发环境url添加参数&getSign=1获取sign 
        if(('local' === $environment) && $request->input('getSign')) {
            return $this->getSign($request);
        }

        self::$name = $request->input('name','');

        //开发环境绕过sign但保留用户获取功能，未指定用户则使用默认
        if('local' === $environment) {
            if('' === self::$name){
                self::$name = 'cli_test_user';
            }
            $info = SecurityAuthLogic::getInfoByName(self::$name);
            self::$authId = $info['id'];
            self::$secretKey = $info['secret_key'];
        }

        if( $environment != 'local' && $environment !='testing'){

            $return = Logic::callError('验证签名错误');

            self::$name = $request->input('name');

            self::$sign = $request->input('sign');

            $data = $request->input();

            unset($data['sign']);

            $data = json_encode($data);

            $res = SecurityAuthLogic::checkSignByName(self::$name, self::$sign, $data);

            if( $res ){

                self::$authId       = $res['id'];

                self::$name         = $res['name'];

                self::$secretKey    = $res['secret_key'];

            }else{

                $log = $request->input();

                Log::Error('CheckSecurityError', $log);

                self::returnJson($return);

            }

        }
        */

    }

    /**
     * 返回json
     * @param array $data
     * @return array
     */
    protected static function returnJson($data = []){
        if (!headers_sent()) {
            header(sprintf('%s: %s', 'Content-Type', 'application/json'));
        }
        //$secretKey    = self::$secretKey;
        //$data['sign'] = md5(asort($data).$secretKey);

        exit(json_encode($data));
    }

    /**
     * @param Request $request
     * @return bool
     * @desc 获取签名
     */
    public function getSign(Request $request) {

        if('local' === App::environment()) {

            $info = SecurityAuthLogic::getInfoByName($request->input('name'));
            
            if(empty($info['name'])) {

                self::returnJson(['sign' => false]);

            }
            
            $data = $request->input();

            unset($data['sign']);

            $data = json_encode($data);
            
            $sign = SecurityAuthLogic::getMd5Sign($info['name'], $info['secret_key'], $data);
            
            self::returnJson(['post_sign' => $sign]);
        }
        
        return true;
    }

}
