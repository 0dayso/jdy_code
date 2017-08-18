<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date: 16/09/27
 * Time: 20:41PM
 * Desc: 每天定时给生日用户发送红包
 */

namespace App\Console\Commands\Day\User;


use App\Http\Logics\Batch\BatchListLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Tools\ToolArray;
use App\Tools\ToolStr;
use Illuminate\Console\Command;

class SendBirthdayBonus extends Command{
    //计划任务唯一标识
    protected $signature = 'SendBirthdayBonus';

    //计划任务描述
    protected $description = '每天09:00给当天生日的用户发送红包.';

    public function handle()
    {
        $userBonusLogic = new UserBonusLogic();

        $userBonusLogic->sendBirthdayBonus();
    }

}
