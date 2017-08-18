<?php

namespace App\Events\Api\User;

use App\Events\Api\ApiEvent;

/**
 * 激活成功
 * Class DoActivateSuccessEvent
 * @package App\Events\Api\User
 */
class DoActivateSuccessEvent extends ApiEvent
{

    /**
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }
}
