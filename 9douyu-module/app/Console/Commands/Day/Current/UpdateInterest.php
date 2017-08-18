<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/15
 * Time: 14:16
 * Desc: 每天06:00更新零钱计划昨日收益及成本相关信息
 */
namespace App\Console\Commands\Day\Current;

use App\Http\Logics\Current\FundLogic;
use Illuminate\Console\Command;

class UpdateInterest extends Command{

    //计划任务唯一标识
    protected $signature = 'UpdateCurrentInterest';

    //计划任务描述
    protected $description = 'Everyday 06:00 update current interest statistics.';


    public function handle(){

        $logic = new FundLogic();

        $logic->updateInterest();
    }
}