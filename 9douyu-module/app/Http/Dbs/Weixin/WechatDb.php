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
 * 微信类
 * Class WechatDb
 * @package App\Http\Dbs\Weixin
 */
class WechatDb extends JdyDb
{

    const
        TYPE_SUBSCRIBE = 1, // 关注服务号
        TYPE_DEFAULT  = 0,  // 默认


    END = true;

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = [
        'openid',
        'nickname',
        'headimgurl',
        'type',
    ];

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
     * 返回指定openid 数据行
     * @param int $openid
     */
    public static function getExistByOpenid($openid = 0){
        return static::where('openid', '=', $openid)->exists();
    }




}