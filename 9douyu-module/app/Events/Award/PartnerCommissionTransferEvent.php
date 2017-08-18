<?php
namespace App\Events\Award;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
* Class PartnerCommissionTransferEvent
* @package App\Events\Award
* 合伙人佣金转出事件
*/
class PartnerCommissionTransferEvent extends Event
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