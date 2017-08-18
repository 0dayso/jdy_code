<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/6
 * Time: 下午8:57
 */

namespace App\Http\Models\Weixin;

use App\Http\Models\Model;

use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Http\Dbs\Weixin\UserLinkWechatDb;

use Log;

/**
 * 微信 model
 * Class WechatModel
 * @package App\Http\Models\Weixin
 */
class UserLinkWechatModel extends Model
{

    public static $codeArr            = [
        'updateOrCreate' => 1,
        'unBind'         => 2,
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_USER_LINK_WECHAT;


    /**
     * 创建/编辑微信数据【根据 openid】
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function updateOrCreate($data){

        $wechatObj = UserLinkWechatDb::getExistByOpenid($data['openid']);

        if($wechatObj){
            $return = UserLinkWechatDb::where('openid',$data['openid'])->update($data);
        }else{
            $return = UserLinkWechatDb::addRecord($data);
        }
        if(!$return)
            throw new \Exception(LangModel::getLang('ERROR_WECHAT_OPERATION'), self::getFinalCode('updateOrCreate'));

        return $return;
    }

    /**
     * 解绑
     * @param int $openid
     * @return static
     * @throws \Exception
     */
    public static function unBind($openid = 0){
        $return = UserLinkWechatDb::where('openid', $openid)->update(['is_binding' => UserLinkWechatDb::IS_BINDING_FALSE]);
        if(!$return)
            throw new \Exception(LangModel::getLang('ERROR_WECHAT_OPERATION_UNBIND'), self::getFinalCode('unBind'));

        return $return;
    }


}