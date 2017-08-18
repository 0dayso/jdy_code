<?php
namespace App\Events\Activity;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class PartnerCommissionTransferEvent
 * @package App\Events\Award
 * 加息奖励结算事件
 */
class IncreaseTransferEvent extends Event
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