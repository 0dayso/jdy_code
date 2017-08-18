<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/14
 * Time: 下午2:54
 */

namespace App\Http\Logics\Weixin;

use Config;

use App\Http\Logics\Weixin\Module\StaffLogic;

use Log;

use App\Http\Models\Weixin\WechatModel;

use App\Http\Dbs\Weixin\UserLinkWechatDb;

use App\Http\Logics\Logic;

/**
 * 绑定逻辑类
 *
 * Class BindLogic
 * @package App\Http\Logics\Weixin
 */
class BindLogic extends Logic
{

    /**
     * 绑定微信用户
     *
     * @param int $phone
     * @param int $openId
     * @param int $userId  用户ID
     * @return array
     */
    public static function bind($phone= 0, $openId = 0, $userId =0){
        try {
            if ($phone && $openId) {
                $wechatRecord = WechatModel::getRecordByOpenid($openId);

                Log::info($wechatRecord);


                $data = [
                    'openid'   => $openId,
                    'user_id'   => $userId,
                    'wechat_id' => empty($wechatRecord['id']) ? 0 : $wechatRecord['id'],
                    'is_binding' => UserLinkWechatDb::IS_BINDING_TRUE,
                ];

                // 绑定
                $logicReturn = UserLinkWechatLogic::updateOrCreate($data);
                if(!$logicReturn['status']){
                    throw new \Exception($logicReturn['msg'], $logicReturn['code']);
                }

                // 给指定微信用户发送消息
                $return      = StaffLogic::sendTextMessage($openId, self::_getBindTpl($phone, $openId));

                Log::info($return);

                return self::callSuccess([$return]);
            }
        }catch (\Exception $e){
            $attributes['data']           = [$phone, $openId, $userId];
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

    }

    /**
     * 解绑微信用户
     *
     * @param int $openid
     * @return array
     */
    public static function unBind($openid = 0){
        if($openid){
            $return  = UserLinkWechatLogic::unBind($openid);

            if($return['status']) {
                // 给指定微信用户发送消息
                $return = StaffLogic::sendTextMessage($openid, self::_getUnBindTpl());

                Log::info($return);

                return true;
            }
            return false;
        }
    }

    /**
     * 绑定成功发送客服消息
     */
    protected static function _getBindTpl($phone = 0, $bindOpenid = 0) {
        $url              = Config::get('wechat.jdyWeixin.url');

        $unBindUrl    =  "{$url}/wechat/unBind/{$bindOpenid}";
        $str          =  "你的微信已绑定(账号:{$phone})，如需更换绑定请用其他微信再次绑定即可，你也可以<a href='{$unBindUrl}'>【立即解绑】</a>当前账号";
        return $str;
    }


    /**
     * 解除绑定发送客服消息
     */
    protected static function _getUnBindTpl() {
        $str = "你的九斗鱼账号已解除绑定";
        return $str;
    }

}