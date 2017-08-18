<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date: 16/09/28
 * Time: 19:13 PM
 * Desc: 每天定时给今天回款的用户奖励加息券的短信提醒
 */
namespace App\Console\Commands\Day\User;



use App\Http\Logics\Batch\BatchListLogic;
use Illuminate\Console\Command;

class SendRefundUserSms extends Command{

    //计划任务唯一标识
    protected $signature = 'SendRefundUserSms';

    //计划任务描述
    protected $description = '每天上午10:20给当日回款的发加息券的用户发送短信提醒.';

    public function handle()
    {
        $batchListLogic = new BatchListLogic();

        $batchListLogic->sendRefundBonusSms();
    }
}