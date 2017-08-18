<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/15
 * Time: 11:00
 * Desc: 每天00:05生成零钱计划汇总数
 */
namespace App\Console\Commands\Day\Current;
use App\Http\Logics\Current\FundLogic;
use Illuminate\Console\Command;

class UpdateFundStatistics extends Command{

    //计划任务唯一标识
    protected $signature = 'UpdateFundStatistics';

    //计划任务描述
    protected $description = 'Everyday 00:05 update current fund statistics.';


    public function handle(){

        $logic = new FundLogic();

        $logic->updateFundStatistics();
    }
}