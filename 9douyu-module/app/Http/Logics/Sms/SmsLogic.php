<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午4:45
 * Desc: 广告管理
 */

namespace App\Http\Logics\Sms;
use App\Http\Logics\Logic;
use App\Http\Models\Common\ServiceApi\SmsModel;

class SmsLogic extends Logic
{

    /**
     * @desc 检测短信内容是否有敏感词
     * @param $smsContent
     * @return mixed
     */
    public function checkSmsContent( $smsContent )
    {

        if( empty( $smsContent ) )
        {
            return self::callError( "验证短信内容不能为空");
        }

        $blackWord = $this->getSmsInBlackList( $smsContent );

        if( !empty( $blackWord ) )
        {
            $blackWord = "短信检测的敏感词是: ". $blackWord ;
            return self::callError( $blackWord );
        }

        return self::callSuccess( );
    }

    /**
     * @desc 获取短信内容中的黑名单
     * @param $smsContent string
     * @return string
     */
    public function getSmsInBlackList( $smsContent )
    {
        $blackList = SmsModel::getBlackList();

        $blackword = '';

        if(empty( $blackList ) )
        {
            return self::callError( "黑名单数据为空");
        }

        foreach( $blackList as $word )
        {

            if(stripos($smsContent, $word) !== false)
            {
                $blackword .= $word.',';
            }

        }

        return $blackword;
    }


}
