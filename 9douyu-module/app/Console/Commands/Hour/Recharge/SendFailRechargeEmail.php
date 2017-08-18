<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/10/10
 * Time: 14:55 Pm
 * Desc: 每小时发送充值失败的用户信息
 */

namespace App\Console\Commands\Hour\Recharge;

use App\Http\Logics\Data\FailRechargeOrderLogic;
use Illuminate\Console\Command;

class SendFailRechargeEmail extends Command{

    //计划任务唯一标识
    protected $signature = 'SendFailRechargeEmail';

    //计划任务描述
    protected $description = '每个小时发送充值失败的用户信息';

    public function handle(){
        $logic = new FailRechargeOrderLogic();

        $logic->sendFailRechargeData();
    }

}