<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/20
 * Time: 16:54
 */

namespace App\Http\Logics\Monitor;

use App\Http\Logics\Logic;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Config;
use Log;

class AccessTokenLogic extends Logic{

    private $logic = null;
    public function __construct(){

        $this->logic = new SystemConfigModel();
    }

    /**
     * access token监控
     */
    public function accessTokenMonitor(){

        $coreToken = $this->getCoreAccessToken();

        $serverToken = $this->getServerAccessToken();

        $msg = '';

        $currentTime = time();
        $coreUpdateTime = strtotime($coreToken['updated_at']);

        $serverUpdateTime = strtotime($serverToken['updated_at']);

        $diffTime = 55 * 60 ;//35分钟

        if(($currentTime - $coreUpdateTime) >= $diffTime){

            $msg = "核心AccessToken未更新,请及时处理!\n";
        }

        if(($currentTime - $serverUpdateTime) >= $diffTime){
            $msg.="服务AccessToken未更新,请及时处理!";
        }

        if($msg){

            $receiveEmails = Config::get('email.monitor.accessToken');
            $model = new EmailModel();
            try{

                $title = '【Warning】Module AccessToken 监控';

                $model->sendHtmlEmail($receiveEmails,$title,$msg);

            }catch (\Exception $e){

                Log::Error(__METHOD__.'Error',['msg' => $e->getMessage()]);

            }

        }

    }


    /**
     * @return array|mixed
     * 获取核心token
     */
    private function getCoreAccessToken(){

        $key = 'ACCESS_TOKEN_CORE';

        $config = $this->logic->getByKey($key);

        return $config;
    }


    /**
     * @return array|mixed
     * 获取服务token
     */
    private function getServerAccessToken(){

        $key = 'ACCESS_TOKEN_SERVER';

        $config = $this->logic->getByKey($key);

        return $config;

    }
}