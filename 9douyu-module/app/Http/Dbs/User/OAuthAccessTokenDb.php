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
 * oauth token 表
 * Class OAuthAccessTokenDb
 * @package App\Http\Dbs
 */
class OAuthAccessTokenDb extends JdyDb
{

    protected $table = 'oauth_access_tokens';

    public $timestamps = false;

    /**
     * 获取OAuth2.0 user_id 用于获取 用户唯一标示手机号
     * @param null $token
     * @return mixed
     */
    public static function getUserIdByToken($token = null){
        $obj = static::select(['user_id', 'expires', 'client_id'])->where(['access_token'=>$token])->first();
        return is_null($obj) ? $obj : $obj->toArray();
    }

    /**
     * 使指定用户 access_token 过期 登出时候调用
     * @param null $phone
     * @return mixed
     */
    public static function expire($id = null){
        $nowTime    = date('Y-m-d H:i:s');
        $expireTime = date('Y-m-d H:i:s', strtotime('-1 minute'));
        return self::where(['user_id' => $id])->where('expires', '>=', $nowTime)->update(['expires'=> $expireTime]);
    }

    /**
     * 踢出其他端登陆
     * @param $token
     * @param $phone
     */
    public static function kickOtherLogin($token, $userId){
        $nowTime    = date('Y-m-d H:i:s');
        $expireTime = date('Y-m-d H:i:s', strtotime('-1 minute'));
        return self::where(['user_id' => $userId])->where('expires', '>=', $nowTime)->where('access_token', '<>', $token)->update(['expires'=> $expireTime]);
    }


    /**
     * 删除过期数据
     */
    public static function deleteExpireRecord(){
        $nowTime    = date('Y-m-d H:i:s');
        return self::where('expires', '<', $nowTime)->delete();
    }

    /**
     * 更新过期时间【用于用户频繁点击 更新过期时间】
     * @param $token
     */
    public static function updateExpires($token){
        $nowTime                = date('Y-m-d H:i:s');
        $token_lifetime         = env('OAUTH_TOKEN_LIFETIME') ? env('OAUTH_TOKEN_LIFETIME') : 3600;//一小时

        $record                 = self::getUserIdByToken($token);

        if(!empty($record['expires'])) {
            $expires = date('Y-m-d H:i:s', (strtotime($record['expires']) + $token_lifetime));
            return self::where(['access_token' => $token])->where('expires', '>=', $nowTime)
                ->update(
                    ['expires' => $expires]
                );
        }else{
            \Log::error(__METHOD__, ['要更新的token数据不存在']);
        }
    }

    /**
     * 更新app token 过期时间 +1 年
     *
     * @param $token
     * @return mixed
     */
    public static function prolongAppTokenExpires($token){
        $nowTime                = date('Y-m-d H:i:s');
        $token_App_lifetime     = 3600 * 24 * 365;
        $expires                = date('Y-m-d H:i:s', (strtotime($nowTime) + $token_App_lifetime));

        return self::where(['access_token' => $token])->where('expires', '>=', $nowTime)
            ->update(
                ['expires'=> $expires]
            );
    }

}
