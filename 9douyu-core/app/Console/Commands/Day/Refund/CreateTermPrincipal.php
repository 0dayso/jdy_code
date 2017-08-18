<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/11/10
 * Time: 10:30
 * Desc: 生成所有定期待收金额
 */

namespace App\Console\Commands\Day\Refund;

use App\Http\Logics\Invest\ProjectLogic;
use Illuminate\Console\Command;

class CreateTermPrincipal extends Command{

    //计划任务唯一标识
    protected $signature = 'CreateTermPrincipal';

    //计划任务描述
    protected $description = '每天0:05,生成定期投资用户的待收本金';

    public function handle()
    {

        $logic = new ProjectLogic();

        $logic->createTermPrincipal();

    }
}