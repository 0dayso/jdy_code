<?php
/**
 * Created by PhpStorm.
 * @desc    定时任务-账户资金统计
 * @date    2016年11月25日
 * @author  @llper
 *
 */
namespace App\Console\Commands\Day\Data;

use App\Http\Logics\Statistics\StatLogic;
use Illuminate\Console\Command;

class FundStatistics extends Command{

    //计划任务唯一标识
    protected   $signature = 'FundStatistics';

    //计划任务描述
    protected   $description = '每天00:00 账户资金数据统计.';


    public function handle(){

        $logic = new StatLogic();

        $logic->fundStatisticsWithDay();

    }
}