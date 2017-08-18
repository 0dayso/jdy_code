<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/9/27
 * Time: 下午5:32
 */

namespace App\Tools;


use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\SessionLogic;

class ToolAppLoginWap
{
    /**
     * 获取 登陆后 浏览器端 真实访问端
     *
     * @return mixed
     */
    public static function getBrowserRealClient(){
        $session = SessionLogic::getTokenSession();
        if(!empty($session)) {
            $isApp = RequestSourceLogic::isAppRequest();
            if (!($isApp)) {
                $cookie = isset($_COOKIE[env('COOKIE_NAME', 'JDY_COOKIES')]) ? $_COOKIE[env('COOKIE_NAME', 'JDY_COOKIES')] : null;
                if ($cookie) {
                    $tokenData = SessionLogic::decryptCookie($cookie);
                    if (isset($tokenData['client'])) {
                        $client = $tokenData['client'];
                        return $client;
                    }
                }
            }
        }
        return false;
    }
}