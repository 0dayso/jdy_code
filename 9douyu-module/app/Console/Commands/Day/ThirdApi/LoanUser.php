<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/27
 * Time: 下午2:46
 * Desc: 还款公告
 */

namespace App\Console\Commands\Day\ThirdApi;

use App\Http\Logics\Project\RefundRecordLogic;
use App\Tools\ToolTime;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class LoanUser extends Command
{

    //计划任务唯一标识
    protected $signature = 'jdy:RefundProjectLoanUser {times?}';

    //计划任务描述
    protected $description = '每天 00:30 执行回款通知借款人';

    /**
     *
     * Handle the event.
     * @param  $event
     * @throws \Exception
     */
    public function handle(){

        $refundLogic = new RefundRecordLogic();

        $times = $this->argument('times') ? $this->argument('times') : ToolTime::dbDate();

        $refundLogic->doNoticeLoanUserRefund($times);

    }

}