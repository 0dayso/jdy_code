<?php
/**
 * @desc    统计失败订单-发邮件
 * @author  @linglu
 */

namespace App\Console\Commands\Hour\Data;

use App\Http\Logics\Order\OrderLogic;
use Illuminate\Console\Command;


class OrderStatHourFail extends Command{

    //计划任务唯一标识
    protected $signature    = 'OrderStatHourFail';

    //计划任务描述
    protected $description  = 'APP端-每两个小时统计失败订单-发邮件';

    public function handle()
    {

        $logic = new OrderLogic();
        $clientArr  = ['ios','android'];
        $logic->statOrderHour($clientArr);

    }

}