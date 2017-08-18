<?php

namespace App\Events\Invest;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class CurrentBeforeEvent
 * @package App\Events\Invest
 * 零钱计划转入前事件
 */
class CurrentBeforeEvent extends Event
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
