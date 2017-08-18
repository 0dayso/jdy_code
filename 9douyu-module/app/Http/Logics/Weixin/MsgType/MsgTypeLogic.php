<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/13
 * Time: 下午4:50
 */

namespace App\Http\Logics\Weixin\MsgType;

use App\Http\Logics\Logic;

/**
 *
 * Class MsgTypeLogic
 * @package App\Http\Logics\Weixin\MsgType
 */
abstract class MsgTypeLogic extends Logic{

    /**
     * 处理事件类型消息
     */
    public function handle($message){
        $event = strtolower($message->Event);
        if(method_exists($this, $event)){
            return $this->$event($message);
        }else{
            // todo log
            $obj = new AnyInvalidLogic;
            return $obj->handle($message);
        }
    }

}