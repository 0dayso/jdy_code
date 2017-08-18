<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/13
 * Time: 下午4:50
 */

namespace App\Http\Logics\Weixin\MsgType;

use App\Http\Logics\Weixin\Module\MessageLogic;

use App\Http\Logics\Weixin\UserLinkWechatLogic;

use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Wechat;

use Log;

use Config;

use App\Http\Dbs\Weixin\UserLinkWechatDb;

/**
 * 消息类型【事件】
 *
 * Class EventLogic
 * @package App\Http\Logics\Weixin\MsgType
 */
class EventLogic extends MsgTypeLogic implements MsgTypeInterfaceLogic{

    const
        REGISTER        = 'BIND_REGISTER_USER', // 注册 KEY

        CONTACT_US      = 'CONTACT_US',         // 联系我们

        END             = true;

    /**
     * 订阅事件
     * @param $message
     * @return mixed
     */
    public function subscribe($message){
        $openId      = $message->FromUserName;

        $logicReturn = UserLinkWechatLogic::subscribe($openId);

        if(!$logicReturn['status']){
            Log::info(__METHOD__,  $logicReturn);
        }

        $text = [
            'content'       => self::_subscribe()
        ];

        return MessageLogic::text($text);

        /**
         * 关注后推送图文消息
         */
//        $imageText =       [
//            'title' => '老板，这是我的个人简历！',
//            'description' => '心安财有余！',
//            'image' => Config::get('wechat.jdyWeixin.url') . '/static/images/jlfm.jpg',
//            //'Url' => 'http://mp.weixin.qq.com/s?__biz=MzAxODA5MTQxMg==&mid=504560689&idx=1&sn=3b38b9c1ff405e16e9c2b00e814153e6#rd',
//            'url'   => 'http://mp.weixin.qq.com/s?__biz=MzA3NDI3OTMxOQ==&mid=2697900583&idx=1&sn=c6375f1c49989deaa333717598a09893&chksm=ba3822df8d4fabc9ca3240b4779957dff379aa4c2884a6e032463e9d14d6b51d05aeef138488#rd',
//        ];

//        return MessageLogic::imageText($imageText);
    }

    private static function _subscribe(){

//        $text = "鱼客您好\n";
//        $text .= "路过16，迎来17\n\n";
//
//        $text .= "相信九斗鱼不会错过和你的每一个瞬间\n\n";
//
//        $text .= "财富路上有“鱼”相伴才安心\n\n";
//
//        $text .= "快加入九斗鱼平台吧！\n\n";
//
//        $text .= "新手<a href='https://wx.9douyu.com/Novice/introduce?from=wap'>［注册领取888元现金券］</a>\n\n";
//
//        $text .= "加入合伙人<a href='http://wx.9douyu.com/activity/y2015partner?from=winxin1'>［赚取最高3%佣金］</a>\n\n";
//
//        $text .= "还有更多优质<a href='http://wx.9douyu.com/project/lists'>［优选项目］</a>\n\n";
//
//        $text .= "和小鱼儿 “17” 赚起来吧";


        $text = SystemConfigModel::getConfig('wechat.subscribe');

        $text = stripcslashes($text);

        \Log::info(__METHOD__, [$text]);

        if(empty($text)){
            $text = "鱼客您好";
        }

        return $text;
    }

    /**
     * 取消订阅
     * @param $message
     * @return mixed
     */
    public function unsubscribe($message){
        $openId      = $message->FromUserName;

        $logicReturn = UserLinkWechatLogic::subscribe($openId, false);

        if(!$logicReturn['status']){
            Log::info(__METHOD__,  $logicReturn);
        }
    }

    /**
     * 点击事件【自定义菜单】
     * @param $message
     * @return mixed
     */
    public function click($message){

        $message->FromUserName;

        Log::info('EventLogic - click');
        Log::info($message);


        /**
         * 菜单
         */
        switch($message->EventKey){
            case self::REGISTER: //捕获 菜单：【绑定/解绑】事件
                return $this->bind($message->FromUserName);
                break;
            case self::CONTACT_US://捕获 菜单：【联系我们】事件
                return $this->contact($message->FromUserName);
                break;
        }
    }


    /**
     * 注册绑定/登陆绑定
     * @return string
     */
    private function bind($openid = 0){
        $url              = Config::get('wechat.jdyWeixin.url');
        $session          = UserLinkWechatDb::getValidPhoneByOpenid($openid);
        if($session){
            $uid = $session['user_id'];
            $session = UserModel::getCoreApiUserInfo($uid);
            $phone   = !empty($session['phone']) ? $session['phone'] : null;
            $unBindUrl    = "{$url}/wechat/unBind/{$openid}";
            $result       = "您的微信已绑定(账号:{$phone})，如需更换绑定请用其他微信再次绑定即可，您也可以<a href='{$unBindUrl}'>【立即解绑】</a>当前账号";
        }else{
            $bindUrl      = "{$url}/wechat/login";
            //$registeUrl   = "{$url}/wechat/register";
            $registeUrl   = "{$url}/register?userId=56941";
            $str          = "抱歉，您还未绑定微信账号。已有九斗鱼账户，请点击这里<a href='%s'>【立即绑定】</a>。新用户请点击这里<a href='%s'>【注册】</a>并绑定";
            $result       = sprintf($str, $bindUrl, $registeUrl);
        }
        return $result;
    }

    /**
     * 联系我们
     *
     * @param string $openid
     * @return \EasyWeChat\Message\Text
     */
    private function contact($openid = ''){
        /**
         * 点击联系我们推送的文本消息
         */


        $text = SystemConfigModel::getConfig('wechat.contact_us');

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