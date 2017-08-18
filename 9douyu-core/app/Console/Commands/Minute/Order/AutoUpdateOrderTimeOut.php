<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/15
 * Time: 下午2:51
 * Desc: 将十分钟之前的支付订单标记为超时
 */

namespace App\Console\Commands\Minute\Order;

use Illuminate\Console\Command;
use App\Http\Logics\Order\RechargeLogic;

class AutoUpdateOrderTimeOut extends Command{

    //计划任务唯一标识
    protected $signature = 'AutoUpdateOrderTimeOut';

    //计划任务描述
    protected $description = '每10分钟标记充值订单为超时状态';

    public function handle()
    {

        $logic = new RechargeLogic();

        $logic->updateDealingOrderTimeOut();

    }

}