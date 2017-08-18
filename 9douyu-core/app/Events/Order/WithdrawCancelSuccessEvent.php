<?php

/**
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 10:54
 * Desc: 取消提现成功发送短信通知用户
 */

namespace App\Events\Order;

use App\Events\Event;

class WithdrawCancelSuccessEvent extends Event
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
        //var_dump($data);
        #throw new \Exception('');

        //提现取消完成发送短信通知用户
    }
}
