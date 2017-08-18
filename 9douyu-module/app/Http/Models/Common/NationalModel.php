<?php

/**
 * Created by PhpStorm.
 * User: caeln
 * Date: 16/5/23
 * Time: 下午12:14
 */

namespace App\Http\Models\Common;
use App\Http\Dbs\SystemConfig\SystemConfigDb;
use Cache,Config;


/**
 * 公用数据
 * Class NationalModel
 * @package App\Http\Models\Common
 */
class NationalModel
{
    /**
     * 获取配置缓存value
     * @param $key $key|$key.subkey
     * @return string
     */
    public static function getConfig($key){
        if(empty($key)) return null;
        $keyArr = explode('.',$key);
        $keyVal = Cache::get($keyArr[0]); //大key－val
        if(empty($keyVal)){
            $db = new SystemConfigDb();
            $res = $db->getConfig($keyArr[0]);
            if(empty($res)) return null;
            $keyVal = unserialize($res['value']);
            Cache::put($key,$keyVal,90*24*3600);//3个月
        }
        if(isset($keyArr[1])){ //二级key
            return $keyVal[$keyArr[1]];
        }
        return $keyVal;
    }


    /**
     * @param $platForm
     * @return string
     * 生成三端支付回调地址
     */
    public static function createNoticeUrl($platForm){
        //return Config::get('pay.PAY_NOTICE_URL').$platForm;
        return self::getUrl().'/pay/notice/'.$platForm;
    }

    /**
     * 生成三端支付Return地址
     */
    public static function createReturnUrl($platForm,$from){
        //return Config::get('pay.PAY_RETURN_URL').$platForm.'/'.$from;
        return self::getUrl().'/pay/return/'.$platForm.'/'.$from;
    }

    public static function getUrl(){

        if(HttpQuery::isPre()){
            return env('APP_PRE_URL_PC_HTTPS');
        }else{
            return env('APP_URL_PC_HTTPS');
        }
    }

}
