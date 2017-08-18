<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/25
 * Time: 下午6:14
 */

namespace App\Console\Commands\Day\CurrentNew;


use App\Http\Logics\CurrentNew\ProjectLogic;
use Illuminate\Console\Command;

class CleanUserYesterdayInterest extends Command
{

    //计划任务唯一标识
    protected $signature = 'ClearNewUserYesterdayInterest';

    //计划任务描述
    protected $description = '每天3:30,清除未计息用户的昨日收益为0';

    public function handle()
    {

        $logic = new ProjectLogic();

        $logic->clearUserYesterdayInterest();

    }

}