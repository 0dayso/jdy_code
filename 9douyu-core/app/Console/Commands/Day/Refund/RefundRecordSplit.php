<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/1
 * Time: 下午4:30
 * Desc: 回款拆分,进入Job自动执行
 */

namespace App\Console\Commands\Day\Refund;

use App\Http\Logics\Refund\ProjectLogic;
use Illuminate\Console\Command;

class RefundRecordSplit extends Command
{

    //计划任务唯一标识
    protected $signature = 'RefundRecordSplit';

    //计划任务描述
    protected $description = '每天00:05执行回款拆分到任务队列';


    public function handle()
    {

        $refundLogic = new ProjectLogic();
        
        $refundLogic->splitRefund();

    }

}