<?php

/**
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 10:54
 * Desc: 支付成功处理失败，出现掉单，发送短信通知
 */

namespace App\Events\Order;

use App\Events\Event;

class RechargeSuccessHandleFailedEvent extends Event
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

        $orderId = $data['order_id'];
        $userId  = $data['user_id'];
        //

        //var_dump($data);
        //return 'abc';
        #throw new \Exception('');

        //支付回调处理失败发送短信提醒相关负责人掉单
    }
}
