<?php
/**
 * Created by PhpStorm.
 * User: 林光辉
 * Date: 16/10/08
 * Time: 11:54
 * Desc: 09:00 定时发送每日生日用户邮件
 */

namespace App\Console\Commands\Day\Data;

use App\Http\Logics\Data\BirthdayDataLogic;
use Illuminate\Console\Command;

class SendBirthdayEmail extends Command{
    //计划任务唯一标识
    protected $signature = 'SendBirthdayEmail';

    //计划任务描述
    protected $description = '每天09:00把当日生日的用户信息发邮件给客服.';


    public function handle(){

        $birthdayDataLogic = new BirthdayDataLogic();

        $birthdayDataLogic->sendBirthdayData();
    }
}