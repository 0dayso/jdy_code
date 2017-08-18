<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/15
 * Time: 下午4:48
 */

namespace App\Tools;

use App\Http\Logics\User\SessionLogic;


/**
 * 视图间公用变量
 *
 * Class ViewShare
 * @package App\Tools
 */
class ViewShare
{
    const
        PRE = "view_",

        END = true;

    /**
     * 设置变量
     */
    public static function set(){
        // 视图公用的变量
        view()->share(
            self::PRE . 'ssl', ToolUrl::is_ssl()
        );

        // 用户信息
        view()->share(
            self::PRE . 'user', SessionLogic::getTokenSession()
        );
    }
}