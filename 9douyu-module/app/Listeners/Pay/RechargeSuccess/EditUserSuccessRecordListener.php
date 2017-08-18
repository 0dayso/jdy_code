<?php

namespace App\Listeners\Pay\RechargeSuccess;


use App\Events\Pay\RechargeSuccessEvent;
use App\Http\Logics\Pay\RechargeLogic;

class EditUserSuccessRecordListener
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
     * @param  ProjectBeforeEvent  $event
     * @return void
     */
    public function handle(RechargeSuccessEvent $event)
    {
        $data = $event->data;

        $orderId = $data['order_id'];//订单号

        $logic = new RechargeLogic();
        
        $logic->updateUserRechargeRecord($orderId);
    }
}
