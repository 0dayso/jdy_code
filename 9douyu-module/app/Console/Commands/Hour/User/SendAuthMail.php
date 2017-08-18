<?php
/**
 * Created by PhpStorm.
 * User: 林光辉
 * Date: 16/10/08
 * Time: 11:54
 * Desc: 定时每小时发送实名认证用户
 */

namespace App\Console\Commands\Hour\User;

use App\Http\Logics\Data\AuthUserLogic;
use Illuminate\Console\Command;

class SendAuthMail extends Command{

    //计划任务唯一标识
    protected $signature = 'SendAuthMail';

    //计划任务描述
    protected $description = '每个小时发送实名认证用户发邮件给客服';

    public function handle(){

        $authLogic = new AuthUserLogic();

        $authLogic->sendAuthData();

    }
}