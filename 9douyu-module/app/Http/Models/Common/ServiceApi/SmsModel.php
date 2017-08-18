<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/18
 * Time: 13:46
 */

namespace App\Http\Models\Common\ServiceApi;

use App\Http\Models\Common\ServiceApiModel;
use App\Http\Models\Common\HttpQuery;
use Config;
use Cache;

class SmsModel extends ServiceApiModel{

    /**
     * @param $phone
     * @param $msg
     * @return array
     * 发送通知短信
     */
    public static function sendNotice($phone,$msg = ''){

        $api  = Config::get('serviceApi.moduleSms.notice');

        return self::send($api,$phone,$msg);
    }

    /**
     * @param $phone
     * @param string $msg
     * @return array
     * 发送验证码短信
     */
    public static function sendVerify($phone,$msg = ''){

        $api  = Config::get('serviceApi.moduleSms.verify');

        return self::send($api,$phone,$msg);

    }


    /**
     * @param $phone
     * @param string $msg
     * @return array
     * 发送营销短信
     */
    public static function sendMarket($phone,$msg = ''){

        $api  = Config::get('serviceApi.moduleSms.market');

        return self::send($api,$phone,$msg);

    }


    /**
     * @param $api
     * @param $phone
     * @param string $msg
     * @return array
     * 发送短信出口
     */
    private static function send($api,$phone,$msg = ''){


        $params = [
            'phone'     => $phone,
            'msg'       => $msg,
        ];

        $return = HttpQuery::serverPost($api,$params);

        return $return;
    }

    /**
     * @desc 获取短信内容内名单
     * @author linguanghui
     * @return array
     */
    public static function getBlackList()
    {
        $key = 'SMS_BLACK_WORDS';

        $expire = 30*24*60;

        $blackList = Cache::get( $key );

        if( !empty( $blackList ) ){

            return json_decode( $blackList, true );

        }else{

            $api  = Config::get('serviceApi.moduleSms.blackList');

            $return = HttpQuery::serverPost( $api, [] );

            if( $return['status'] && !empty( $return['data'] )){

                $blackList = $return['data'];

                Cache::put( $key, json_encode($blackList), $expire );

                return $blackList;

            }else{

                return [];
            }
        }
    }
}
