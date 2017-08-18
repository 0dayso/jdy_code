<?php
/**
 * @desc    定时任务-快金-我要借款
 * @date    2017-03-06
 * @author  @llper
 *
 */
namespace App\Console\Commands\Day\Data;

use App\Http\Logics\TimeCash\TimeCashLogic;
use Illuminate\Console\Command;

class TimeCashLoan extends Command{

    //计划任务唯一标识
    protected   $signature = 'TimeCashLoan';

    //计划任务描述
    protected   $description = '每天10:00 给快金发送-我要借款-借款信息邮件.';


    public function handle(){

        TimeCashLogic::getLoanRecord();

    }
}