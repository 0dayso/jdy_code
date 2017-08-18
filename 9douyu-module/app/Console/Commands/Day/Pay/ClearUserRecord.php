<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每天00:05清空用户昨日充值记录
 */
namespace App\Console\Commands\Day\Pay;
use App\Http\Logics\Pay\RechargeLogic;
use Illuminate\Console\Command;

class ClearUserRecord extends Command{

    //计划任务唯一标识
    protected $signature = 'ClearUserDayRecord';

    //计划任务描述
    protected $description = 'Everyday 00:05 clear user day recharge record.';


    public function handle(){

        $logic = new RechargeLogic();
        //清空昨日充值记录
        $logic->clearUserDayRecord();
    }
}