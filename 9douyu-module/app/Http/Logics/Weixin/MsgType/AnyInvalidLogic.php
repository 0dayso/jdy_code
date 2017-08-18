<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/13
 * Time: 下午4:50
 */

namespace App\Http\Logics\Weixin\MsgType;

use App\Http\Logics\Logic;

use App\Http\Logics\Weixin\Module\MessageLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;

/**
 * 消息类型 【任何未识别的消息类型】
 *
 * Class AnyInvalidLogic
 * @package App\Http\Logics\Weixin\MsgType
 */
class AnyInvalidLogic extends Logic implements MsgTypeInterfaceLogic{

    /**
     * 处理事件类型消息 默认消息
     */
    public function handle($message){
        //$defaultMessage  =  "亲爱的，请问有什么可以帮助您的；\n<a href='http://www.sobot.com/chat/h5/index.html?sysNum=54037ae382a141c8b7fa69f402a99b7c&source=1'>快来点我，跟小鱼儿沟通吧～</a>";

        $text = SystemConfigModel::getConfig('wechat.any');

        $text = stripcslashes($text);

        \Log::info(__METHOD__, [$text]);

        if(empty($text)){
            $text = "鱼客您好";
        }

        $text = [
            'content'       => $text
        ];

        return MessageLogic::text($text);
    }
}