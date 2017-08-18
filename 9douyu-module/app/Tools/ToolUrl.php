<?php
namespace App\Tools;
/**
 +------------------------------------------------------------------------------
 * 兼容ThinkPHP U
 +------------------------------------------------------------------------------
 */
use App\Http\Logics\Oss\OssLogic;
use App\Http\Logics\RequestSourceLogic;

class ToolUrl{

    /**
     * 获取链接+参数拼接结果
     * @param $path
     * @param $vars
     *
     * @return string
     */
    public static function getUrl($path, $vars = []) {
        $paramStr = http_build_query($vars);

        return self::renderUrl($path, $paramStr);
    }

    /**
     * 拼接链接，如果有?则使用&拼接，如果没有，则使用?拼接
     * @param        $url
     * @param string $paramStr
     *
     * @return string
     */
    public static function renderUrl($url, $paramStr = '') {
        if(empty($paramStr)) return $url;
        if(stripos($url, '?') === false) {
            $connect = '?';
        } else {
            $connect = '&';
        }

        return $url . $connect . $paramStr;
    }

    /**
     * 获取App 访问 【安卓|ios】基础url
     *
     * demo: http(s)://android.devmodule.9douyu.com
     * @param bool $https
     * @return bool|mixed
     */
    public static function getAppBaseUrl($https = false){

//        return env('MODULE_URL');

        if($https){
            $keyAndroid = 'APP_URL_ANDROID_HTTPS';
            $keyIos     = 'APP_URL_IOS_HTTPS';
        }else{
            $keyAndroid = 'APP_URL_ANDROID';
            $keyIos     = 'APP_URL_IOS';
        }
        $requestFrom = RequestSourceLogic::getSource();
        if ($requestFrom == RequestSourceLogic::SOURCE_ANDROID) {
            return env($keyAndroid);
        } elseif ($requestFrom == RequestSourceLogic::SOURCE_IOS) {
            return  env($keyIos);
        }
        return false;
    }

    /**
     * 判断是否SSL协议
     * @return boolean
     */
    public static function is_ssl() {
        if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))){
            return true;
        }elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] )) {
            return true;
        }
        return false;
    }

    /**
     * @param $dir
     * @return bool
     * 创建多级目录
     */
    public static function mkDirs($dir){
        if(!is_dir($dir)){
            if(!self::mkDirs(dirname($dir))){
                return false;
            }
            if(!mkdir($dir,0777)){
                return false;
            }
        }
        return true;
    }

    /**
     * @param $url
     * @param string $save_dir
     * @param string $filename
     * @return bool
     * 通过下载地址将下载文件保存至OSS读写私有bucket
     */
    public static function getFileAndSaveByUrl($url,$save_dir='',$filename='')
    {

        $opts = array('http' => array(
                                    'header' => "User-Agent:MyAgent/1.0\r\n"),
                                    "ssl"    => array(
                                             "verify_peer"=>false,
                                             "verify_peer_name"=>false,
                                    )
        );
        $context = stream_context_create($opts);
        $s = file_get_contents("$url", false, $context);
        $path = $save_dir.'/'.$filename;  //文件路径和文件名
        $ossLogic = new OssLogic('oss_2');
        $ossLogic->writeFile($s,$path);

        return true;

    }
}
