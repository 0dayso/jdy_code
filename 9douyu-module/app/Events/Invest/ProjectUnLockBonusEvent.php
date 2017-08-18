<?php

namespace App\Events\Invest;

use App\Events\Event;

class ProjectUnLockBonusEvent extends Event
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
