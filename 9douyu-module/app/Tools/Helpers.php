<?php
date_default_timezone_set('UTC');
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/11/22
 * Time: 下午2:48
 */

/**
 * 扩展 laravel assetFrom + 版本号
 *
 * @param $path 文件自路径
 * @param null $version 版本号 默认主版本
 * @param null $root
 * @param bool|true $secure
 * @return mixed
 */
use App\Tools\ToolEnv;
function assetFromCdn($path, $version = null, $root = '', $secure = true)
{
    $version = (is_null($version) ? config('cdn.version') : (config('cdn.version') + $version));
    $path .= '?v=' . $version;
    return app('url')->assetFrom($root, $path, $secure);
}



/**
 * @param $path
 * @return string
 */
function assetUrlByCdn($path){
    if(empty($path)){
        return '';
    }
    $path = trim($path);
    $currentEnv = ToolEnv::getAppEnv();
    $currentSec = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https' : 'http';
    if($currentEnv=='production' && $currentSec=='https'){
        $cdnHost = env('STATIC_URL_HTTPS');
    }else{
        $cdnHost = env('STATIC_URL');
    }
    if(empty($cdnHost)){
        $cdnHost = str_replace('www','static',env('APP_URL_PC'));
    }
    $version  = (empty(env('STATIC_RESOURCE_VERSION'))) ? date('Ymd'): env('STATIC_RESOURCE_VERSION');
    $path    .= '?v=' . $version;
    $new_path =  $cdnHost.'/'.trim($path, '/');
    if(preg_match('/img1.*\/jquery-1\.9\.1\.min\.js/',$new_path)){
        $new_path = str_replace('img1.','static.',$new_path);
    }
    return $new_path;
}

function formalEnvironment(){

    $hostDomain =   env('APP_DOMAIN');

    $currentEnv = ToolEnv::getAppEnv();

    if( $currentEnv == 'production' && $hostDomain =='www.9douyu.com'){

        return true;
    }

    return false;
}