<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每天23:50自动生成一条零钱计划利率
 */
namespace App\Console\Commands\Day\CurrentNew;
use App\Http\Logics\CurrentNew\RateLogic;
use Illuminate\Console\Command;
class AutoCreateCurrentNewRate extends Command{

    //计划任务唯一标识
    protected $signature = 'AutoCreateCurrentNewRate';

    //计划任务描述
    protected $description = '每天 22:30 自动创建新活期利率';


    public function handle(){
        
        $logic = new RateLogic();
        $logic->autoCreateRate();

        
    }
}