<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Weixin\WeixinController;
use Log;
use Illuminate\Http\Request;
use App\Http\Logics\Media\ChannelLogic;
use Wechat;

/**
 * 给予微信基数服务类的活动contreller
 * Class ActivityBaseController
 * @package App\Http\Controllers\Weixin
 */
class ActivityBaseController extends WeixinController
{
    /**
     * return $array
     * @desc 根据channel 获取下载包的地址
     */
    protected function setChannelInfo()
    {
        $request    =   app('request');

        $channel    =   $request->input('channel','') ;

        $logic      =   new ChannelLogic() ;

        $package    =   $logic->getPackage($channel);//推广包名

        return ['channel' => $channel , 'package' => $package ];
    }
}
