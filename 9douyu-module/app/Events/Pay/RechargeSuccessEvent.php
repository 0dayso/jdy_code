<?php

namespace App\Events\Pay;

use App\Events\Event;


/**
 * Class CurrentBeforeEvent
 * @package App\Events\Invest
 * 零钱计划转入前事件
 */
class RechargeSuccessEvent extends Event
{

    public $data = [];

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

}
