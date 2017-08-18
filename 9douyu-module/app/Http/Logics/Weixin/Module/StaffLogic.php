<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/14
 * Time: 下午5:12
 */

namespace App\Http\Logics\Weixin\Module;

use EasyWeChat\Message\Text;

/**
 * 客服
 * Class StaffLogic
 * @package App\Http\Logics\Module
 */
class StaffLogic
{
    /**
     * 主动发送消息给用户接口[text]
     */
    public static function sendTextMessage($openId = 0, $message = ''){
        if($openId && $message) {
            $wechat  = app('wechat');
            $staff   = $wechat->staff;
            $message = new Text(['content' => $message]);
            return $staff->message($message)->to($openId)->send();
        }
        return false;

    }
}