<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/3
 * Time: 上午9:53
 * Desc: 调用服务封装请求数据
 */

namespace App\Tools;

use Config;

class ToolService{

    /**
     * 获取调用服务数据的组装数据 + 合作ID、合作秘钥
     *
     * @param array $data
     * @return mixed
     */
    public static function getParam($data = []){
        $apiConfig  = Config::get('serviceApi.partner');
        if(empty($data))
            return false;

        return array_merge($data, $apiConfig);
    }

}