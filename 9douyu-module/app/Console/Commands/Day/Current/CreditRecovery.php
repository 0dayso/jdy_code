<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每天00:05还原零钱计划债权
 */
namespace App\Console\Commands\Day\Current;
use App\Http\Logics\Current\CreditLogic;
use Illuminate\Console\Command;

class CreditRecovery extends Command{

    //计划任务唯一标识
    protected $signature = 'CurrentCreditRecovery';

    //计划任务描述
    protected $description = 'Everyday 00:05 clear user current credit.';


    public function handle(){

        //还原零钱计划债权
        CreditLogic::recovery();
    }
}