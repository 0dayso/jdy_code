<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/31
 * Time: 下午7:10
 */

namespace App\Console\Commands\Day\CurrentNew;


use App\Http\Logics\CurrentNew\ProjectLogic;
use Illuminate\Console\Command;

class InvestOut extends Command
{

    //计划任务唯一标识
    protected $signature = 'CurrentNewInvestOut';

    //计划任务描述
    protected $description = '每天 00:10 执行新活期转出至账户余额';


    public function handle(){

        $rateLogic = new ProjectLogic();

        $rateLogic->doInvestOutJob();

    }

}