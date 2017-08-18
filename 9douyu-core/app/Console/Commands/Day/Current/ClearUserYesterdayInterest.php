<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/15
 * Time: 下午2:51
 * Desc: 清除昨日未计息用户的昨日收益字段
 */

namespace App\Console\Commands\Day\Current;

use App\Http\Logics\Refund\CurrentLogic;
use Illuminate\Console\Command;

class ClearUserYesterdayInterest extends Command{

    //计划任务唯一标识
    protected $signature = 'ClearUserYesterdayInterest';

    //计划任务描述
    protected $description = '每天3:30,清除未计息用户的昨日收益为0';

    public function handle()
    {

        $logic = new CurrentLogic();

        $logic->clearUserYesterdayInterest();

    }

}