<?php

/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/3
 * Time: 下午12:14
 */

namespace App\Http\Models\Common;

use App\Http\Logics\Auth\SecurityAuthLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolCurl;
use Log;
use Config;
/**
 * Curl 第三方服务和核心api
 * Class HttpQuery
 * @package App\Http\Models\Common
 */
class HttpQuery
{
    private static $instance = null;

    private static $name       = 'cli_test_user';
    private static $secretKey  = '209c02k29';


    protected function __construct(){}


    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * @return bool|null
     * @desc 检测是否是预发布环境
     */
    public static function isPre()
    {
        static $is_pre = null;

        if ($is_pre === null)
        {

            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

            $is_pre = (strpos($host, 'pre') !== false);

        }

        return $is_pre;

    }

    /**
     * curl core核心接口
     * @param null $url
     * @param array $PostData
     * @param string $authorizationStr
     * @return null|void
     */
    public static function corePost($url = null, $postData=[], $authorizationStr = '') {

        if(empty($url)){
            return [];
        }

        $url = env('CORE_URL').$url;

        Log::info(json_encode(['url'=>$url, 'data'=> $postData]));
        $data         = $postData;

        $data['secret_sign'] = SecurityAuthLogic::getMd5Sign(self::$name, self::$secretKey, json_encode($postData));

        if(empty($authorizationStr)) {
            $authorizationStr = SystemConfigModel::getConfig('ACCESS_TOKEN_CORE');
        }
        $headers = [];

        if(!empty($authorizationStr)) {
            $headers['Authorization'] = [$authorizationStr];
        }
        $return = ToolCurl::curlPost($url, $data, $headers);

        if(is_string($return)){
            Log::info('coreParam : ' . json_encode($data));
            Log::info('corePost : ' . $return);
        }

        Log::info('corePostReturn : ' , [$url, $postData, $return]);

        return json_decode($return, true);

    }



    /**
     * curl 第三方服务
     * @param null $url
     * @param array $PostData
     * @return null|void
     */
    public static function serverPost($url = null, $postData=[], $authorizationStr = '') {

        if(empty($url)){

            return [];

        };

        $url = env('SERVICE_URL').$url;

        Log::info(json_encode(['url'=>$url, 'data'=> $postData]));

        $data         = $postData;

        $data['secret_sign'] = SecurityAuthLogic::getMd5Sign(self::$name, self::$secretKey, json_encode($postData));

        if(empty($authorizationStr)) {
            $authorizationStr = SystemConfigModel::getConfig('ACCESS_TOKEN_SERVER');
        }
        $headers = [];

        if(!empty($authorizationStr)) {
            $headers['Authorization'] = [$authorizationStr];
        }

        $return = ToolCurl::curlPost($url, $data, $headers);

        if(is_string($return)){
            Log::info('serviceParam : ' . json_encode($data));
            Log::info('servicePost : ' . $return);
        }

        return json_decode($return, true);

    }

    /**
     * curl 第三方服务
     * @param null $url
     * @param array $PostData
     * @return null|void
     */
    public static function serverPostkb($url = null, $postData=[], $authorizationStr = '') {

        if(empty($url)){

            return [];

        };

        $url    = "http://service-kb.9douyu.com".$url;

        Log::info(json_encode(['url'=>$url, 'data'=> $postData]));

        $data   = $postData;

        $data['secret_sign']    = SecurityAuthLogic::getMd5Sign(self::$name, self::$secretKey, json_encode($postData));

        if(empty($authorizationStr)) {
            $authorizationStr   = SystemConfigModel::getConfig('ACCESS_TOKEN_SERVER');
        }
        $headers = [];

        if(!empty($authorizationStr)) {
            $headers['Authorization'] = [$authorizationStr];
        }

        $return = ToolCurl::curlPost($url, $data, $headers);

        if(is_string($return)){
            Log::info('serviceParam : ' . json_encode($data));
            Log::info('servicePost : ' . $return);
        }

        return json_decode($return, true);

    }

    /**
     * curl Jy接口
     *
     * @param null $url
     * @param array $postData
     * @return mixed
     * @throws \Exception
     */
    public static function JyPost($url = null, $postData=[]) {

        if(empty($url)){
            return [];
        };

        Log::info(json_encode(['url'=>$url, 'data'=> $postData]));

        $data         = $postData;

        $data['secret_sign'] = SecurityAuthLogic::getMd5Sign(self::$name, self::$secretKey, json_encode($postData));

        $headers = [];

        $return = ToolCurl::curlPostNoVerify($url, $data, $headers);

        if(is_string($return)){
            Log::info(__METHOD__ . ' param: ' . json_encode($data));
            Log::info(__METHOD__ . ' return: ' . $return);
        }
        return json_decode($return, true);
    }

    /**
     * @desc  邮件发送接口
     * @param null   $url
     * @param array  $postData
     * @param string $authorizationStr
     * @return array|mixed
     */
    public static function SendEmailPost($url = null, $postData=[], $authorizationStr = ''){

        if(empty($url)){

            return [];

        };

        $url = env('SERVICE_URL').$url;

        Log::info(json_encode(['url'=>$url, 'data'=> $postData]));

        $data         = $postData;

        $data['secret_sign'] = SecurityAuthLogic::getMd5Sign(self::$name, self::$secretKey, json_encode($postData));

        if(empty($authorizationStr)) {
            $authorizationStr = SystemConfigModel::getConfig('ACCESS_TOKEN_SERVER');
        }
        $headers = [];

        if(!empty($authorizationStr)) {
            $headers['Authorization'] = [$authorizationStr];
        }

        $return = ToolCurl::curlEmailPost($url, $data, $headers);

        if(is_string($return)){
            Log::info('serviceParam : ' . json_encode($data));
            Log::info('servicePost : ' . $return);
        }

        return json_decode($return, true);

    }

    /**
     * @desc 借款人体系发送接口
     * @author linguanghui
     * @param $url  string
     * @param $postData array
     * @param $authorizationStr  string
     */
    public static function  loanUserPost( $url  = null, $postData = [], $authorizationStr = '' )
    {
        if(empty($url)){
            return [];
        };

        $url = env('LOAN_USER_APP_URL').$url;

        //设置向借款人体系传送债权数据的签名sign
        $apiAuthKey  = Config::get('loanUserApi.ApiSign.api_auth_key');

        ksort($postData);

        $dataStr =  (string)json_encode($postData);

        $sign = md5($dataStr.$apiAuthKey);

        $params = [

            'data' => $postData,
            'sign' => $sign,

        ];

        Log::info('loanUserPostRequest:', ['url'=>$url, 'data'=> $params]);

        $return = ToolCurl::curlPostNoVerify( $url, $params );

        if(is_string($return)){
            Log::info('loanUserParam : ', [json_encode($params)]);
            Log::info('loanUserPost : ',   [$return]);
        }

        return json_decode($return, true);
    }


}
