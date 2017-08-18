<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/1/10
 * Time: 下午1:34
 * Desc: 通过身份证号获取性别/尊称
 */

namespace App\Tools;

class ToolIdCard{

    /**
     * @param string $identityCard
     * @return string
     * @desc 通过身份证获取性别
     */
    public static function getSexByIdCard( $identityCard='' ){

        return (substr($identityCard, (strlen($identityCard) == 15 ? -1 : -2), 1) % 2) ? '男' : '女';

    }

    /**
     * @param string $identityCard
     * @return string
     * @desc 通过身份证获取性别尊称
     */
    public static function getSexNameByIdCard( $identityCard='' ){

        return (substr($identityCard, (strlen($identityCard) == 15 ? -1 : -2), 1) % 2) ? '先生' : '女士';

    }

}

