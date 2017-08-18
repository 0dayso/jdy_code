<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/27
 * Time: 下午5:50
 */

namespace App\Events\Activity;


use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

class LotteryEvent extends Event
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
        
        Log::info('lotteryData：' . json_encode($this->data));
    }
}