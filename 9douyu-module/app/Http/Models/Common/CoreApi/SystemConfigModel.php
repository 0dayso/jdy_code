<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 11:42
 * Desc: 核心系统配置操作调用model
 */

namespace App\Http\Models\Common\CoreApi;

use App\Http\Models\Common\CoreApiModel;
use App\Http\Models\Common\HttpQuery;
use Config;


class SystemConfigModel extends CoreApiModel{

    /**
     * @return array
     * 获取核心配置信息
     */
    public static function getConfigList(){

        $api = Config::get('coreApi.moduleConfig.getConfigList');

        $params = [];

        $return = HttpQuery::corePost($api, $params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }

    /**
     * @param $key
     * @param $value
     * @param $userId
     * @param $status
     * @param $second_des
     * 添加系统配置信息
     */
    public static function doCreateConfig($key,$value,$userId,$status,$second_des){

        $api = Config::get('coreApi.moduleConfig.addConfig');

        $params = [
            'key'           => $key,
            'value'         => $value,
            'user_id'       => $userId,
            'status'        => $status,
            'second_des'    => $second_des
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;
    }


    /**
     * @param $id
     * @param $key
     * @param $value
     * @param $userId
     * @param $status
     * @param $second_des
     * 编辑系统配置信息
     */
    public static function doEditConfig($id,$key,$value,$userId,$status,$second_des){

        $api = Config::get('coreApi.moduleConfig.editConfig');

        $params = [
            'id'            => $id,
            'key'           => $key,
            'value'         => $value,
            'user_id'       => $userId,
            'status'        => $status,
            'second_des'    => $second_des
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;
    }


    public static function doEditConfigByKey($key,$name,$value,$userId,$status,$second_des){

        $api = Config::get('coreApi.moduleConfig.editConfigByKey');

        $params = [
            'key'           => $key,
            'value'         => $value,
            'name'          => $name,
            'user_id'       => $userId,
            'status'        => $status,
            'second_des'    => $second_des
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;
    }


    /**
     * @param $id
     * 获取指定的配置信息
     */
    public static function getConfig($id){

        $api = Config::get('coreApi.moduleConfig.getConfig');

        $params = [
            'id'            => $id,
        ];

        $return = HttpQuery::corePost($api, $params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }



}