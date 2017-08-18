<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每天03:00零钱计划加息券计息
 */
namespace App\Console\Commands\Day\Current;
use App\Http\Logics\Current\BonusLogic;
use Illuminate\Console\Command;

class BonusInterestAccrual extends Command{

    //计划任务唯一标识
    protected $signature = 'BonusInterestAccrual';

    //计划任务描述
    protected $description = 'Everyday 03:00 current user bonus interest accrual.';


    public function handle(){
        
        BonusLogic::bonusInterestAccrual();        

    }
}