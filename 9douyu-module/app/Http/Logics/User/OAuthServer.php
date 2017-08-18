<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/8/2
 * Time: 上午11:42
 */

namespace App\Http\Logics\User;

use OAuth2\Server;

/**
 * 扩展oauth server 类 用户创建普付宝token
 *
 * Class OAuthServer
 * @package App\Http\Logics\User
 */
class OAuthServer extends Server
{
    /**
     *
     * 创建access token
     */
    public function createAccessToken($client, $userId){
        $obj = $this->createDefaultAccessTokenResponseType();
        return $obj->createAccessToken($client, $userId);
    }
}