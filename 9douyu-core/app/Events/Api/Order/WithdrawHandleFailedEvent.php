<?php

namespace App\Events\Api\Order;

use App\Events\Api\ApiEvent;

/**
 * 提现批量失败
 * Class WithdrawHandleFailedEvent
 * @package App\Events\Api\Order
 */
class WithdrawHandleFailedEvent extends ApiEvent
{

    /**
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }
}
