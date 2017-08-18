<?php

namespace App\Events\Api\User;

use App\Events\Api\ApiEvent;

/**
 * 注册成功
 * Class RegisterSuccessEvent
 * @package App\Events\Api\User
 */
class RegisterSuccessEvent extends ApiEvent
{

    /**
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }
}
