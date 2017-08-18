<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每天00:10零钱计划计息,向核心发起计息请求
 */
namespace App\Console\Commands\Day\Current;
use App\Http\Logics\Current\RateLogic;
use Illuminate\Console\Command;

class InterestAccrual extends Command{

    //计划任务唯一标识
    protected $signature = 'InterestAccrual';

    //计划任务描述
    protected $description = 'Everyday 00:10 current user interest accrual.';


    public function handle(){

        $rateLogic = new RateLogic();
        $rateLogic->interestAccrual();

    }
}