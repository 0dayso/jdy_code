<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/10
 * Time: 下午1:36
 */

namespace App\Events\Pay;


use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class RechargeBatchEvent
 * @package App\Events\Pay
 * @对账文件入队列
 */

class RechargeBatchEvent extends Event
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