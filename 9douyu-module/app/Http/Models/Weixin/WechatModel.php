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

use App\Http\Dbs\Weixin\WechatDb;

use Log;

/**
 * 微信 model
 * Class WechatModel
 * @package App\Http\Models\Weixin
 */
class WechatModel extends Model
{

    public static $codeArr            = [
        'updateOrCreate' => 1,
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_WECHAT;


    /**
     * 创建/编辑微信数据【根据 openid】
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function updateOrCreate($data){

        $wechatObj = WechatDb::getExistByOpenid($data['openid']);

        if($wechatObj){
            $return = WechatDb::where('openid',$data['openid'])->update($data);
        }else{
            $return = WechatDb::addRecord($data);
        }
        if(!$return)
            throw new \Exception(LangModel::getLang('ERROR_WECHAT_OPERATION'), self::getFinalCode('updateOrCreate'));

        return $return;
    }

    /**
     * 根据openid 获取数据
     * @param int $openid
     */
    public static function getRecordByOpenid($openid = 0){
        return WechatDb::where('openid', $openid)->first();
    }




}