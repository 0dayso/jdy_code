<?php

namespace App\Events\Project;

use App\Events\Event;



class CreateProjectSuccessEvent extends Event
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
