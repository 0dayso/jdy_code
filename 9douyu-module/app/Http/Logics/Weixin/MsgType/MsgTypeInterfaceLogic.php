<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/13
 * Time: 下午4:50
 */

namespace App\Http\Logics\Weixin\MsgType;

/**
 * 消息类型接口
 * Interface MsgTypeInterfaceLogic
 * @package App\Http\Logics\Weixin\MsgType
 */
interface MsgTypeInterfaceLogic
{
    public function handle($message);
}