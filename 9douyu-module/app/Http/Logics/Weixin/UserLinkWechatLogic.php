<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\Weixin;

use App\Http\Logics\Logic;

use App\Http\Models\Weixin\UserLinkWechatModel;

use App\Http\Dbs\Weixin\UserLinkWechatDb;

use Log;

/**
 * 微信信息
 * Class WechatLogic
 * @package App\Http\Logics\Weixin
 */
class UserLinkWechatLogic extends Logic
{

    /**
     * 添加/编辑 微信信息
     * @param array $data
     * @return array
     */
    public static function updateOrCreate($data = []){
        try {
            $attributes = self::filterAttributes($data);

            Log::info('UserLinkWechatLogic-attributes: ', $attributes);

            $return     = UserLinkWechatModel::updateOrCreate($attributes);

        }catch (\Exception $e){
            $attributes['data']           = $data;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$return]);
    }

    /**
     * 添加/编辑 过滤白名单
     * @param array $data
     * @return array
     */
    public static function filterAttributes($data = []){
        $attributes = [
            'openid'       => $data['openid'],
            'user_id'        => $data['user_id'],
            'wechat_id'    => $data['wechat_id'],
            'is_binding'   => isset($data['is_binding']) ? $data['is_binding'] : UserLinkWechatDb::IS_BINDING_FALSE,
        ];

        return $attributes;
    }


    /**
     * 解绑
     *
     * @param int $openid
     * @return array
     */
    public static function unBind($openid = 0){
        try {
            $return     = UserLinkWechatModel::unBind($openid);

        }catch (\Exception $e){
            $attributes['openid']         = $openid;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$return]);
    }


    /**
     * 关注与取消关注
     *
     * @param string $openId
     * @param bool|true $is 为真 关注 否则取消关注
     * @return array|bool
     */
    public static function subscribe($openId = '', $is = true){
        $is   = (int)$is;

        $data = ['openid' => $openId, 'is_subscribe' => $is];
        try {
            if (empty($openId))
                return self::callError('openId不能为空');

            UserLinkWechatModel::updateOrCreate($data);

        }catch (\Exception $e){
            $attributes['data']           = $data;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }
        return self::callSuccess();
    }



}