<?php
/**
 * Created  by PhpStorm.
 * @desc    定时任务-每天各支付渠道-充值订单信息
 * @date    2017年02月23日
 * @author  @linglu
 *
 */
namespace App\Console\Commands\Day\Data;
use App\Http\Logics\Order\OrderLogic;
use Illuminate\Console\Command;

class OrderStatPayType extends Command{

    //计划任务唯一标识
    protected   $signature = 'OrderStatPayType';

    //计划任务描述
    protected   $description = 'APP端-每天00:10 各支付渠道-充值订单统计-发邮件';


    public function handle(){

        $logic = new OrderLogic();
        $clientArr  = ['ios','android'];
        $logic->statOrderPayTypeDay($clientArr);

    }
}