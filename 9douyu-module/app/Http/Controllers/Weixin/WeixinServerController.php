<?php

namespace App\Http\Controllers\Weixin;

use Log;

use Wechat;

use Config;

/**
 * 微信模块服务类
 * Class WeixinServerController
 * @package App\Http\Controllers\Weixin
 */
class WeixinServerController extends WeixinController
{

    /**
     * 构造方法追加扩展
     */
    public function appendConstruct(){
        //关闭debug
        \Debugbar::disable();
    }


    /**
     * 获取 可用的消息类型
     */
    protected static function getWechatMsgType(){
        return [
            'event',// 事件类型
        ];
    }


    /**
     * 微信服务
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.');

        $wechat = app('wechat');

        $wechat->server->setMessageHandler(

            function($message){
                // 实现的类型
                $canUseMsgType   = self::getWechatMsgType();
                // 交互的类型
                $msgType         = $message->MsgType;
                $msgType         = strtolower($msgType);
                // 可以处理的类型
                $class           = '\App\Http\Logics\Weixin\MsgType\\'.ucfirst($msgType).'Logic';
                Log::info($class);
                if(!in_array(strtolower($msgType), $canUseMsgType) || !class_exists($class)){
                    $class   = '\App\Http\Logics\Weixin\MsgType\AnyInvalidLogic';
                }

                Log::info($class);
                $obj = new $class;
                return $obj->handle($message);
            }

        );

        Log::info('return response.');

        return $wechat->server->serve();
    }

}
