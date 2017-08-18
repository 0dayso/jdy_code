<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/20
 * Time: 下午4:04
 */

namespace App\Exceptions\User\Login;

use Exception;


class LoginException extends Exception
{
    const

        LOGIN_OAUTH_ERROR              = 201,                                     // auth2 error code for    interface
        LOGIN_OAUTH_ERROR_MESSAGE      = '登陆失败',                               // auth2 error message for interface

        LAST_CONST                     = NULL;


    public function __toString() {
        return __CLASS__.':['.$this->code.']:'.$this->message.'\n';
    }
}