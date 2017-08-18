<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/14
 * Time: 下午2:54
 */

namespace App\Http\Logics\Weixin;

use Config;

use Session;

/**
 * 微信用户信息
 *
 * Class UserLogic
 * @package App\Http\Logics\Weixin
 */
class UserLogic
{
    /**
     * 获取微信用户基本信息
     *
     * @param array $data
     * @return array|bool
     */
    public static function getUserInfo($data =[]){
        $return      = false;

        $wechat      = app('wechat');
        $user        = $wechat->oauth->user();

        if($user){

            $return = [
                'openid'     => $user->getId(),  // 对应微信的 OPENID
                'nickname'   => $user->getNickname(), // 对应微信的 nickname
                'headimgurl' => $user->getAvatar(), // 头像网址

                'type'       => isset($data['type']) ? $data['type'] : \App\Http\Dbs\Weixin\WechatDb::TYPE_DEFAULT,//类型
            ];

        }
        return $return;
    }

    /**
     * 设置微信信息
     * @param array $data
     * @return array
     */
    public static function setUserInfo($data = []){
        if($data) {
            $logicReturn = WechatLogic::updateOrCreate($data);
            return $logicReturn;
        }
        return false;
    }

    /**
     * 授权
     * @param string $scopes[snsapi_base/snsapi_userinfo]
     * @param null $redirect
     */
    public static function wechatAuthorize($scopes = 'snsapi_base', $redirect = null){
        $url              = Config::get('wechat.jdyWeixin.url');
        if($redirect){
            $redirect     = "{$url}/$redirect";
        }
        $wechat      = app('wechat');
        $oauth       = $wechat->oauth;
        \Log::info(__METHOD__ .'###'. $redirect);
        $response    = $oauth->scopes([$scopes])->redirect($redirect);

        return $response;
    }

    /**
     * 设置 session
     *
     * @param null $user
     */
    public static function setSession($user = null){
        session(['wechat.oauth_user' => $user]);
    }



}