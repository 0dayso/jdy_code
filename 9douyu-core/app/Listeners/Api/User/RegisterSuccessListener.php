<?php

namespace App\Listeners\Api\User;

use App\Events\ExampleEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterSuccessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  $data
     * @return void
     */
    public function handle($data)
    {
        //
//        var_dump("User", $data, "pid:".getmypid());
        //return 'abc';
        #throw new \Exception('');
    }
}
