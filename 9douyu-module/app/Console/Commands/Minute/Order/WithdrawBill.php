<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/4
 * Time: 11:54
 * Desc: 每5分钟执行
 */
namespace App\Console\Commands\Minute\Order;
use Illuminate\Console\Command;

use App\Http\Logics\Order\WithdrawBillLogic;

class WithdrawBill extends Command{

    //计划任务唯一标识
    protected $signature = 'WithdrawBill';

    //计划任务描述
    protected $description = 'Every 5min auto check Bill';


    public function handle(){
        $logic = new WithdrawBillLogic();
        $logic->checkBillOrder();
    }
}