<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/11/10
 * Time: 10:30
 * Desc: 生成活期待收数据
 */

namespace App\Console\Commands\Day\Current;

use App\Http\Logics\Invest\CurrentLogic;
use Illuminate\Console\Command;

class CreatePrincipal extends Command{

    //计划任务唯一标识
    protected $signature = 'CreateCurrentPrincipal';

    //计划任务描述
    protected $description = '每天0:05,生成所有活期投资人0点时分的账户本金';

    public function handle()
    {

        $logic = new CurrentLogic();

        $logic->createPrincipal();

    }
}