<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/21
 * Time: 14:26
 */

namespace App\Tools;

use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Logics\Logic;

class IPLimit{


    /**
     * @param $ip
     * @return array
     * 核心回调模块IP限制判断
     */
    public static function coreRequestIpCheck($ip){

        $list = SystemConfigModel::getConfig('CORE_CALLBACK_IP_LIST');

        if($list){

            $ipArr = explode(',',$list);

            if(!in_array($ip,$ipArr)){

                return Logic::callError('商户IP错误');
            }else{

                return Logic::callSuccess();
            }
        }
    }
}