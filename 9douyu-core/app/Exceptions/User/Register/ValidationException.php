<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/13
 * Time: 下午2:05
 */

namespace App\Exceptions\User\Register;

use Exception;

/**
 * 注册验证异常
 * Class ValidationException
 * @package App\Exceptions\Registers
 */
class ValidationException extends Exception
{

    const 
        ERROR_NOT_PHONE             = 201,      // 不是有效手机号
        ERROR_NOT_PHONE_LENGTH      = 202,      // 不是有效手机号长度
        ERROR_NOT_PASSWORD_LENGTH   = 203,      // 不是有效的密码长度
        ERROR_ACTIVE                = 204,      // 注册已激活的用户
        ERROR_INACTIVE              = 205,      // 注册未激活
        ERROR_PARAM                 = 206,      // 参数错误
        LAST_ITEM                   = NULL;

    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
    }

    public function __toString() {
        return __CLASS__.':['.$this->code.']:'.$this->message.'\n';
    }
}
