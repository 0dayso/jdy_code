<?php

namespace App\Events\Api\Invest;
use App\Events\Api\ApiEvent;

class ProjectSuccessEvent extends ApiEvent
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        parent::__construct($data);
        //
    }
}
