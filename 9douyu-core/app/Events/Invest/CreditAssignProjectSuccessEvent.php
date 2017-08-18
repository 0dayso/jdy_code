<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/26
 * Time: 15:17
 * Desc: 投资债转成功事件
 */

namespace App\Events\Invest;

use App\Events\Event;


class CreditAssignProjectSuccessEvent extends Event
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {

    }
}