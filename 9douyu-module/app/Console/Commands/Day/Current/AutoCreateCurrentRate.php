<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每天23:50自动生成一条零钱计划利率
 */
namespace App\Console\Commands\Day\Current;
use App\Http\Logics\Current\RateLogic;
use Illuminate\Console\Command;
class AutoCreateCurrentRate extends Command{

    //计划任务唯一标识
    protected $signature = 'AutoCreateCurrentRate';

    //计划任务描述
    protected $description = '每天 22:30 自动创建活期利率';


    public function handle(){
        
        $logic = new RateLogic();
        $logic->autoCreateRate();

        
    }
}