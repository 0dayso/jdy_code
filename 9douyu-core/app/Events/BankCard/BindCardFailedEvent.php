<?php

/**
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 10:54
 * Desc: 绑卡失败事件处理
 */

namespace App\Events\BankCard;

use App\Events\Event;

class BindCardFailedEvent extends Event
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
        //return 'abc';
        #throw new \Exception('');

        //绑卡失败发送邮件
    }
}
