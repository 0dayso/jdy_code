<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/1
 * Time: 下午4:30
 * Desc: 09:00执行检测是否未回款
 */

namespace App\Console\Commands\Day\Refund;

use App\Http\Logics\Refund\ProjectLogic;
use Illuminate\Console\Command;

class CheckProjectRefund extends Command
{

    //计划任务唯一标识
    protected $signature = 'CheckProjectRefund';

    //计划任务描述
    protected $description = '每天09:00执行检测是否未回款';


    public function handle()
    {

        $refundLogic = new ProjectLogic();
        
        $refundLogic->CheckProjectRefund();

    }

}