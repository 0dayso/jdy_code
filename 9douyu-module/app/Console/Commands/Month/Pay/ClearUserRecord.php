<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每月1号00:05分清空上月充值记录
 */
namespace App\Console\Commands\Month\Pay;
use App\Http\Logics\Pay\RechargeLogic;
use Illuminate\Console\Command;

class ClearUserRecord extends Command{

    //计划任务唯一标识
    protected $signature = 'ClearUserMonthRecord';

    //计划任务描述
    protected $description = 'Every month 1st 00:05 clear month recharge record.';


    public function handle(){

        $logic = new RechargeLogic();
        //清空上个月充值记录
        $logic->clearUserMonthRecord();
    }
}