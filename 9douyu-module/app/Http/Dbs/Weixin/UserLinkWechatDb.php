<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/6
 * Time: 下午8:35
 */


namespace App\Http\Dbs\Weixin;

use App\Http\Dbs\JdyDb;

/**
 * 微信 用户 绑定关系表类
 *
 * Class UserLinkWechatDb
 * @package App\Http\Dbs\Weixin
 */
class UserLinkWechatDb extends JdyDb
{

    const
        IS_BINDING_TRUE   = 1, // 绑定
        IS_BINDING_FALSE  = 0, // 未绑定

        IS_SUBSCRIBE_TRUE = 1, // 关注
        IS_SUBSCRIBE_FALSE= 0, // 非专注

    END = true;

    /**
     * 创建记录
     * @param array $attributes
     * @return static
     */
    public static function addRecord($attributes = []){
        $model = new static($attributes, array_keys($attributes));
        return $model->save();
    }


    /**
     * 返回指定openid 数据行是否存在
     * @param int $openid
     */
    public static function getExistByOpenid($openid = 0){
        return static::where('openid', '=', $openid)->exists();
    }

    /**
     * 获取指定微信号绑定的手机号
     * @param int $openid
     */
    public static function getValidPhoneByOpenid($openid = 0){
        $recordObj = static::where('openid', '=', $openid)
            ->where('is_binding', '=' , self::IS_BINDING_TRUE)
            ->first();
        return is_object($recordObj) ? $recordObj->getAttributes() : $recordObj;
    }


    /**
     * 通过openId 获取用户信息
     *
     * @param int $openid
     * @return mixed
     */
    public static function getUserInfo($openid = 0){
        $recordObj = static::where('openid', '=', $openid)->first();
        return is_object($recordObj) ? $recordObj->getAttributes() : $recordObj;
    }

    /**
     * 通过userId 获取用户信息
     *
     * @param int $userId
     * @return mixed
     */
    public static function getUserInfoByUserId($userId = 0){
        $recordObj = static::where('user_id', '=', $userId)->first();
        return is_object($recordObj) ? $recordObj->getAttributes() : $recordObj;
    }



}