<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date: 16/09/28
 * Time: 19:13 PM
 * Desc: 每天定时给今天回款的用户发加息券
 */
namespace App\Console\Commands\Day\User;



use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Project\RefundRecordLogic;
use Illuminate\Console\Command;

class SendRefundUserBonus extends Command{

    //计划任务唯一标识
    protected $signature = 'SendRefundUserBonus';

    //计划任务描述
    protected $description = '每天上午7:00给【明天】回款的用户发送加息券.';

    public function handle()
    {
        $userBonusLogic = new UserBonusLogic();

        $userBonusLogic->sendTodayRefundUserBonus();
    }
}