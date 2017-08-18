<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/20
 * Time: 下午4:04
 */

namespace App\Exceptions\Activity;

use Exception;


class YiMafuException extends Exception
{
    const

        SAVE_ERROR              = 201,
        SAVE_ERROR_MESSAGE      = '重复提交',

        LAST_CONST              = NULL;


    public function __toString() {
        return __CLASS__.':['.$this->code.']:'.$this->message.'\n';
    }
}