<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/20
 * Time: 上午11:11
 */

namespace App\Http\Dbs\User;

use App\Http\Dbs\JdyDb;
/**
 * oauth refresh token 表
 * Class OAuthRefreshTokenDb
 * @package App\Http\Dbs
 */
class OAuthRefreshTokenDb extends JdyDb
{

    protected $table = 'oauth_refresh_tokens';

    /**
     * 删除过期数据
     */
    public static function deleteExpireRecord(){
        $nowTime    = date('Y-m-d H:i:s');
        return self::where('expires', '<', $nowTime)->delete();
    }
}