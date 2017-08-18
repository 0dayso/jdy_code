<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/10/10
 * Time: 14:55 Pm
 * Desc: 每个小时发送提现大于5万的用户信息给用户
 */

namespace App\Console\Commands\Hour\Withdraw;

use App\Http\Logics\Data\WithdrawFiveUserLogic;
use Illuminate\Console\Command;

class SendWithdrawFiveEmail extends Command{

    //计划任务唯一标识
    protected $signature = 'SendWithdrawFiveEmail';

    //计划任务描述
    protected $description = '每个小时发送提现大于5万的用户信息预警邮件';

    public function handle(){
        $logic = new WithdrawFiveUserLogic();

        $logic->sendWithdrawFiveUserData();
    }
}