<?php

namespace App\Console;

use App\Console\Commands\Day\Current\ClearUserYesterdayInterest;
use App\Console\Commands\Day\Current\CreatePrincipal;
use App\Console\Commands\Day\Refund\CheckProjectRefund;
use App\Console\Commands\Day\Refund\CreateTermPrincipal;
use App\Console\Commands\Day\Refund\RefundRecordSplit;
use App\Console\Commands\Day\SMS\RefundNotice;
use App\Console\Commands\Day\Data\OrderStatPayType;
use App\Console\Commands\Hour\Data\OrderStatHourFail;
use App\Console\Commands\Month\Refund\CreateRefundRecord;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use App\Console\Commands\Day\Withdraw\SendWithdrawEmail;
use App\Console\Commands\Day\Current\AutoRefundToCurrent;
use App\Console\Commands\Minute\Order\AutoUpdateOrderTimeOut;
use Illuminate\Support\Facades\Artisan;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SendWithdrawEmail::class,//发送提现邮件
        //AutoRefundToCurrent::class,//回款进零钱计划
        RefundRecordSplit::class,   //自动回款
        ClearUserYesterdayInterest::class,  //清除零钱计划未计息用户的昨日收益
        RefundNotice::class,    //每日早上10点发送明日回款短信提醒(运营部韩冰需求)
        AutoUpdateOrderTimeOut::class,
        //CreateRefundRecord::class,
        CreatePrincipal::class,
        CreateTermPrincipal::class,
        OrderStatPayType::class,             //每日统计各支付渠道订单信息-发邮件
        CheckProjectRefund::class,             //09:00执行检测是否未回款
        OrderStatHourFail::class             //每小时失败订单统计-发邮件
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->minuteSchedule($schedule);

        $this->hourlySchedule($schedule);
        
        $this->dailySchedule($schedule);
        
        $this->weeklySchedule($schedule);
        
        $this->monthlySchedule($schedule);
    }

    /**
     * @param $schedule
     * 以分为单位的计划任务
     */
    private function minuteSchedule($schedule){

        //每十分钟更新未处理的提现订单
        $schedule->call(function () {
            Artisan::call('AutoUpdateOrderTimeOut');
        })->everyTenMinutes();

       
    }

    /**
     * @param $schedule
     * 以小时为单位的计划任务
     */
    private function hourlySchedule($schedule){

        //每小时执行一次 失败订单统计-发邮件
        $schedule->call(function () {
            Artisan::call('OrderStatHourFail');
        })->when(function(){
            $minute = date('i');
            return ($minute == '00');
        });

    }

    /**
     * @param $schedule
     * 以天为单位的计划任务
     */
    private function dailySchedule($schedule){


        //每天01:00自动还款进零钱计划
        /*$schedule->call(function () {
            Artisan::call('AutoRefundToCurrent');
        })->dailyAt('01:00');*/

        //每天00:05自动拆分回款,进入Job队列
        $schedule->call(function () {
            Artisan::call('RefundRecordSplit');
            //生成合伙人活期计息本金
            Artisan::call('CreateCurrentPrincipal');
            //生成合伙人定期计算本金
            Artisan::call('CreateTermPrincipal');

        })->dailyAt('00:05');

        //每天00:10 统计昨日各支付渠道订单信息-发邮件
        $schedule->call(function () {
            Artisan::call('OrderStatPayType');
        })->dailyAt('00:10');

        //每天03:30清除昨日未计息的用户
        $schedule->call(function () {
            Artisan::call('ClearUserYesterdayInterest');
        })->dailyAt('03:30');

        //每日早上10:20发送回款短信提醒
        $schedule->call(function () {
            Artisan::call('RefundNotice');
        })->dailyAt('10:20');

        //每日9点检测定期回款情况
        $schedule->call(function () {
            Artisan::call('CheckProjectRefund');
        })->dailyAt('09:00');

        //每天08:10 / 15:10以邮件发送未处理的提现列表

        /*
        $schedule->call(function () {
            Artisan::call('SendWithdrawEmail');
        })->dailyAt('08:10');

        $schedule->call(function () {
            Artisan::call('SendWithdrawEmail');
        })->dailyAt('15:10');
        */
    }


    /**
     * @param $schedule
     * 以周为单位的计划任务
     */
    private function weeklySchedule($schedule){


    }



    /**
     * @param $schedule
     * 以月为单位的计划任务
     */
    private function monthlySchedule($schedule){


    }


}
