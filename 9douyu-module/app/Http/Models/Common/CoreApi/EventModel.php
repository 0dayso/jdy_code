<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 11:40
 * Desc: 核心事件调用Model
 */

namespace App\Http\Models\Common\CoreApi;

use App\Http\Models\Common\CoreApiModel;
use Config;
use App\Http\Models\Common\HttpQuery;

class EventModel extends CoreApiModel{
    
    /**
     * @param $eventName        事件名称        必填
     * @param $notifyUrl        回调地址        必填
     * @return array
     * 核心事件注册
     */
    public static function doEventRegister($eventName,$notifyUrl){

        $api  = Config::get('coreApi.moduleEvent.doEventRegister');

        $params = [
            'event_name' => $eventName,
            'notifyUrl'  => $notifyUrl
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }

    /**
     * @return array
     * 获取核心定义的事件列表
     */
    public static function getEventList(){

        $api  = Config::get('coreApi.moduleEvent.getEventList');

        $params = [];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }
}