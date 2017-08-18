<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/22
 * Time: 下午2:52
 * Desc: 安全认证中心，针对应用的验证
 */

namespace App\Http\Logics\Auth;

use App\Http\Dbs\SecurityAuthDb;
use App\Http\Logics\Logic;

class SecurityAuthLogic extends Logic
{


    /**
     * @param $authId
     * @return mixed
     * @desc 通过auth id获取信息
     */
    public static function getInfoByName($name)
    {

        $db = new SecurityAuthDb();

        return $db->getInfoByName($name);

    }

    /**
     * @param $authId
     * @param $sign
     * @return bool
     * @desc 检测加密值
     */
    public static function checkSignByName($name, $sign, $data='')
    {

        $info = self::getInfoByName($name);

        if( $info['status'] === SecurityAuthDb::STATUS_LOCKED ){//状态异常

            return false;

        }

        $md5Sign = self::getMd5Sign($name,$info['secret_key'],$data);

        if( $md5Sign === $sign ){

            return $info;

        }

        return false;

    }

    /**
     * @param $authId
     * @param $secretKey
     * @return string
     * @desc 获取加密
     */
    public static function getMd5Sign($name, $secretKey,$data='')
    {

        return md5(md5($data).$secretKey.$name);

    }


}