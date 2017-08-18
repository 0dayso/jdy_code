<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每天00:10零钱计划计息,向核心发起计息请求
 */
namespace App\Console\Commands\Day\CurrentNew;
use App\Http\Logics\CurrentNew\RateLogic;
use Illuminate\Console\Command;

class InterestAccrual extends Command{

    //计划任务唯一标识
    protected $signature = 'CurrentNewInterestAccrual';

    //计划任务描述
    protected $description = '每天00:10计算新版零钱计划';


    public function handle(){

        $rateLogic = new RateLogic();
        $rateLogic->interestAccrual();

    }
}