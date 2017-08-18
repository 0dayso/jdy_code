<?php

/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/3
 * Time: 下午12:14
 */

namespace App\Http\Models\Common;

use App\Http\Dbs\SystemConfigDb;
use App\Tools\ToolCurl;
use Log;
/**
 * Curl 第三方服务和核心api
 * Class HttpQuery
 * @package App\Http\Models\Common
 */
class HttpQuery
{

    /**
     * curl 第三方服务
     * @param null $url
     * @param array $PostData
     * @return null|void
     */
    public static function serverPost($url = null, $postData=[]) {

        if(empty($url)){

            return [];

        };

        $url = env('SERVICE_URL').$url;

        Log::info(json_encode(['url'=>$url, 'data'=> $postData]));
        
        $db = new SystemConfigDb();

        $config = $db->getInfoByKey('ACCESS_TOKEN_SERVER');

        if(empty($config)){

            return [];
        }

        $authorizationStr = unserialize($config['value']);

        $headers['Authorization'] = [$authorizationStr];

        $return = ToolCurl::curlPost($url, $postData, $headers);

        return json_decode($return, true);

    }



}
