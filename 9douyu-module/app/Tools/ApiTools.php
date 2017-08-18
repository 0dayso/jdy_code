<?php
namespace App\Tools;
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 17/1/16
 * Time: 下午6:14
 */

class ApiTools
{


    /**
     * 弥补dingo api 不能实现多 api 域名
     */
    public static function getSupportDomain(){
        $host       = app('request')->getHost();
        $domain     = env('MAIN_DOMAIN');
        $ios        = env('IOS_SUB_DOMAIN');
        $android    = env('ANDROID_SUB_DOMAIN');
        $ios_pre    = env('IOS_PRE_SUB_DOMAIN');
        $android_pre= env('ANDROID_PRE_SUB_DOMAIN');
        $domains = [
            $ios . $domain,
            $android . $domain,
            $android_pre . $domain,
            $ios_pre . $domain,
        ];

        if(in_array($host, $domains) && app('request')->path() != 'app/gateway'){
            return $host;
        }
        return 'domain';
    }
}
