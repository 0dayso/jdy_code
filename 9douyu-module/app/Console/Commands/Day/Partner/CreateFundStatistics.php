<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 2016/12/1
 * Time: 16:10
 * Desc: 合伙人统计数据
 */
namespace App\Console\Commands\Day\Partner;
use App\Http\Logics\Partner\PartnerLogic;
use Illuminate\Console\Command;

class CreateFundStatistics extends Command{

    //计划任务唯一标识
    protected $signature = 'jdy:day-partner-CreateFundStatistics';

    //计划任务描述
    protected $description = '第天凌晨6点生成合伙人汇总数据.';


    public function handle(){

        $logic = new PartnerLogic();

        $logic->createFundStatistics();
    }
}