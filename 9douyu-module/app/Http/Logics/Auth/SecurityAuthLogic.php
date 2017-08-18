<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/22
 * Time: 下午2:52
 * Desc: 安全认证中心，针对应用的验证
 */

namespace App\Http\Logics\Auth;

use App\Http\Logics\Logic;

class SecurityAuthLogic extends Logic
{
    /**
     * @param $authId
     * @param $secretKey
     * @return string
     * @desc 获取加密
     */
    public static function getMd5Sign($authId, $secretKey,$data='')
    {

        return md5(md5($data).$secretKey.$authId);

    }


}