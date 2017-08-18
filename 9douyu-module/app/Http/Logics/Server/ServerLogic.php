<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/28
 * Time: 下午8:01
 */

namespace App\Http\Logics\Server;


use App\Http\Logics\Logic;
use App\Http\Models\SystemConfig\SystemConfigModel;

class ServerLogic extends Logic
{

    const
        CLIENT_IOS          = 'ios',
        CLIENT_ANDROID      = 'android',
        CLIENT_APP_STORE    = 'app_store';

    /**
     * @param $client
     * @return array
     * @desc 获取app配置信息
     */
    public function getServerLogic($client){

        $server = SystemConfigModel::getConfig('APP_SERVER_LIST');

        $data = [];

        $list['list'] = [];

        foreach($server as $key => $item){
            $clientArr = explode('-', strtolower($key));
            if(in_array($client, $clientArr)){
                $list['list'][]  = $item.'/app/gateway';
            }

            if($client == self::CLIENT_IOS && in_array(self::CLIENT_APP_STORE,$clientArr)){
                $list['app_store'] = $item.'/app/gateway';
            }

            if('expired' == strtolower($key)){
                $list['expired'] = $item;
            }else{
                $list['expired'] = 60*5;
            }

        }

        $data[$client] = $list;

        return self::callSuccess($data);

    }

}