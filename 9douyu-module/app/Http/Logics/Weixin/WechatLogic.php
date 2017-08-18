<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\Weixin;

use App\Http\Logics\Logic;

use App\Http\Models\Weixin\WechatModel;

use App\Http\Dbs\Weixin\WechatDb;

use Log;

/**
 * 微信信息
 * Class WechatLogic
 * @package App\Http\Logics\Weixin
 */
class WechatLogic extends Logic
{

    /**
     * 添加/编辑 微信信息
     * @param array $data
     * @return array
     */
    public static function updateOrCreate($data = []){
        try {
            $attributes = self::filterAttributes($data);

            Log::info('attributes: ', $attributes);

            $return     = WechatModel::updateOrCreate($attributes);

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
            'openid'            => $data['openid'],
            'nickname'          => $data['nickname'],
            'headimgurl'        => $data['headimgurl'],
            'type'              => !empty($data['type']) ? $data['type'] : WechatDb::TYPE_DEFAULT,
        ];

        return $attributes;
    }

}