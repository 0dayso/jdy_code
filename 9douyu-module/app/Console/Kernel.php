<?php

namespace App\Console;

use App\Console\Commands\Day\Activity\AutoSettledAward;
use App\Console\Commands\Day\Article\RefundNotice;
use App\Console\Commands\Day\Credit\SendEndProjectCredit;
use App\Console\Commands\Day\Current\BatchAutoInvest;
use App\Console\Commands\Day\Data\InvestRefundStat;
use App\Console\Commands\Day\Data\StatisticsFundHistory;
use App\Console\Commands\Day\Partner\BackInterest;
use App\Console\Commands\Day\Partner\CreateFundStatistics;
use App\Console\Commands\Day\Partner\ResetInterest;
use App\Console\Commands\Day\Partner\SplitInviteUser;
use App\Console\Commands\Day\ThirdApi\LoanUser;
use App\Console\Commands\ImportOldData\CurrentCreditImport;
use App\Console\Commands\Minute\Monitor\AccessToken;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

use App\Console\Commands\Day\Current\CreditRecovery;
use App\Console\Commands\Day\Current\BonusInterestAccrual;
use App\Console\Commands\Day\Current\AutoCreateCurrentRate;
use App\Console\Commands\Day\Current\InterestAccrual;

use App\Console\Commands\Month\Pay\ClearUserRecord as ClearUserMonthRecord;
use App\Console\Commands\Day\Pay\ClearUserRecord as ClearUserDayRecord;
use App\Console\Commands\Day\User\DeleteExpireRecord;
use App\Console\Commands\ImportOldData\CreditImport;
use App\Console\Commands\ImportOldData\ProjectLinkCreditImport;
use App\Console\Commands\Hour\AccessToken\Core as AccessTokenUpdateCore;
use App\Console\Commands\Hour\AccessToken\Server as AccessTokenUpdateServer;
use App\Console\Commands\Day\Current\UpdateFundStatistics;
use App\Console\Commands\Day\Current\UpdateInterest;
use App\Console\Commands\Day\Data\SendWebData;
use App\Console\Commands\Day\User\SendBirthdayBonus;
use App\Console\Commands\Day\User\SendRefundUserBonus;
use App\Console\Commands\Day\User\SendRefundUserSms;
use App\Console\Commands\Day\Data\SendBirthdayEmail;
use App\Console\Commands\Hour\User\SendAuthMail;
use App\Console\Commands\Hour\Withdraw\SendWithdrawFiveEmail;
use App\Console\Commands\Hour\Recharge\SendFailRechargeEmail;
//use App\Console\Commands\Day\Credit\DoDisperseCredit;
//当日提现充值金额数据统计
use App\Console\Commands\Day\Data\RechargeAndWithdrawData;

use App\Console\Commands\Minute\Order\WithdrawBill;
#@llper 定时任务-账户资金统计
use App\Console\Commands\Day\Data\FundStatistics;
use App\Console\Commands\Day\Data\TimeCashLoan;

//第三方数据接口
use App\Console\Commands\Day\ThirdApi\ZgcUploadCredit;
//Oss文件目录上传
use App\Console\Commands\OssFile\OssFileImport;
//修改文章表content字段中的picture/id为Oss相关路径
use App\Console\Commands\OssFile\UpdateArticleHref;
use App\Console\Commands\OssFile\UpdateArticleSrc;


/*use App\Console\Commands\Day\CurrentNew\InterestAccrual as InterestNewAccrual;
use App\Console\Commands\Day\CurrentNew\InvestOut;
use App\Console\Commands\Day\CurrentNew\CleanUserYesterdayInterest;
use App\Console\Commands\Day\CurrentNew\AutoCreateCurrentNewRate;*/

class Kernel extends ConsoleKernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @var array
     */
    protected $bootstrappers = [
        'Illuminate\Foundation\Bootstrap\DetectEnvironment',
        'Illuminate\Foundation\Bootstrap\LoadConfiguration',
//        'Illuminate\Foundation\Bootstrap\ConfigureLogging',
        'App\Tools\JdyConfigureLogging',
        'Illuminate\Foundation\Bootstrap\HandleExceptions',
        'Illuminate\Foundation\Bootstrap\RegisterFacades',
        'Illuminate\Foundation\Bootstrap\SetRequestForConsole',
        'Illuminate\Foundation\Bootstrap\RegisterProviders',
        'Illuminate\Foundation\Bootstrap\BootProviders',
    ];


    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        WithdrawBill::class,
        // Commands\Inspire::class,
        CreditRecovery::class,
        BonusInterestAccrual::class,
        AutoCreateCurrentRate::class,
        InterestAccrual::class,

        ClearUserMonthRecord::class,
        ClearUserDayRecord::class,
        DeleteExpireRecord::class,
        AccessTokenUpdateCore::class,
        AccessTokenUpdateServer::class,
//        CreditImport::class,//todo 债权导入后 移除
//        ProjectLinkCreditImt port::class,//todo 项目债权导入后 移除
        BackInterest::class,    //每日凌晨 5 点,合伙人计息拆分
        UpdateFundStatistics::class,
        UpdateInterest::class,
        SendWebData::class,
        AutoSettledAward::class,        //每天执行奖励结算
        CurrentCreditImport::class,
        AccessToken::class,
        SendBirthdayBonus::class, //每日发生日红包
        RefundNotice::class, //每天凌晨5点生成还款公告
        SendRefundUserBonus::class, //每天发送【明天】回款加息券
        //SendRefundUserSms::class, //当日回款的发加息券的用户发送短信提醒,降低短信成本,该短信与core的回款短信内容做了合并
        SendBirthdayEmail::class,//09:00发送每日生日用户给客服
        SendAuthMail::class,// 每小时发送实名认证邮件
        SendWithdrawFiveEmail::class, //每小时发送提现大于五万用户信息
        SendFailRechargeEmail::class,  //每小时发送充值失败的订单
        //DoDisperseCredit::class, //每天凌晨对用户和债权进行分散债权匹配
        RechargeAndWithdrawData::class,//每天09:30Pm 发送当日充值提现的统计数据

        TimeCashLoan::class,    //给快金发送-我要借款-借款人信息
        FundStatistics::class,  //账户资金统计
        InvestRefundStat::class,//中金云数据统计
        ZgcUploadCredit::class,//中关村数据上报数据接口
        SplitInviteUser::class,
        ResetInterest::class,
        CreateFundStatistics::class,
        StatisticsFundHistory::class,    //根据事件类型分组进行数据统计
        BatchAutoInvest::class,  //每天凌晨3点批量获取自动投资活期的记录

        OssFileImport::class,    //提交文件目录到Oss
        UpdateArticleHref::class,     //修改文章表content字段中的picture/id为Oss相关路径
        UpdateArticleSrc::class,     //修改文章表content字段中的picture/id为Oss相关路径
        LoanUser::class,            //借款人,
        SendEndProjectCredit::class //每天5:00发送今日到期项目的债权信息

        //InterestNewAccrual::class,
        //InvestOut::class,
        //CleanUserYesterdayInterest::class,
        //AutoCreateCurrentNewRate::class,
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

        $schedule->call(function(){

            Artisan::call('WithdrawBill');
            Artisan::call('monitor:AccessToken');

        })->everyFiveMinutes();

    }

    /**
     * @param $schedule
     * 以小时为单位的计划任务
     */
    private function hourlySchedule($schedule){
        $schedule->call(function () {
            Artisan::call('AccessTokenCore');
            Artisan::call('AccessTokenServer');
        })->when(function(){
            $minute = date('i');
            //每小时0分、30分更新内核access_token
            //本地随时可更新
            $environment = app()->environment();
            if($environment == 'local') {
                return true;
            }
            return ($minute == '00') || ($minute == '30');
        });

        $schedule->call(function(){
            //每小时发送实名认证邮件
            Artisan::call('SendAuthMail');
            //每小时发送提现大于5万的用户
            Artisan::call('SendWithdrawFiveEmail');
            //每小时发送充值失败的订单信息
            Artisan::call('SendFailRechargeEmail');
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


        $schedule->call(function () {
            //每天0点5分回收零钱计划债权
            Artisan::call('CurrentCreditRecovery');

            //清空昨日充值记录
            Artisan::call('ClearUserDayRecord');
            //生成零钱计划资金汇总信息
            Artisan::call('UpdateFundStatistics');


        })->dailyAt('00:05');

        $schedule->call(function () {
            //新活期计息
            //Artisan::call('CurrentNewInterestAccrual');

            //新活期转出
            //Artisan::call('CurrentNewInvestOut');

        })->dailyAt('00:10');

        $schedule->call( function() {

            //发送每日数据统计邮件
            Artisan::call('SendWebData');

        })->dailyAt('00:17');
        $schedule->call( function () {

            //Artisan::call( 'doDisperseCredit' );
            Artisan::call( 'jdy:RefundProjectLoanUser' );

        })->dailyAt('00:30');

        //每天0点10分向核心发起零钱计划计息指令
        $schedule->call(function () {
            Artisan::call('InterestAccrual');
        })->dailyAt('01:00');

        //每天3点零钱计划加息券计息
        $schedule->call(function () {
            Artisan::call('BonusInterestAccrual');
            Artisan::call('BatchAutoInvest');
        })->dailyAt('03:00');

        //每天23:50分生成一条零钱计划利率
        $schedule->call(function () {
            Artisan::call('AutoCreateCurrentRate');
            //Artisan::call('AutoCreateCurrentNewRate');
        })->dailyAt('22:30');


        //每天02:00 删除过期token
        $schedule->call(function () {
            Artisan::call('jdy:day-user-DeleteExpireRecord');
        })->dailyAt('02:00');

        //每天 03:00 执行合伙人用户拆分
        $schedule->call(function () {
            Artisan::call('jdy:day-partner-SplitInviteUser');
            //Artisan::call('ClearNewUserYesterdayInterest');
        })->dailyAt('03:00');

        //每天 04:00 重置未计息合伙人信息
        $schedule->call(function () {
            Artisan::call('jdy:day-partner-resetInterest');
        })->dailyAt('04:00');

        //每天 06:00 更新零钱计划收益汇总信息
        $schedule->call(function () {
            Artisan::call('UpdateCurrentInterest');
            Artisan::call('jdy:day-partner-CreateFundStatistics');

        })->dailyAt('06:00');

        //每天 08:00 结算活动加息奖励
        $schedule->call(function () {
            Artisan::call('jdy:AutoSettledActivityAward');
        })->dailyAt('08:00');

        //每天 05:00 生成还款公告
        $schedule->call(function () {
            Artisan::call('jdy:RefundArticleNotice');
            Artisan::call('SendEndProjectCredit');
        })->dailyAt('05:00');



        $schedule->call(function(){
            //每天09:00发送每日生日用户给客服
            Artisan::call('SendBirthdayEmail');
            //每天09:00发送每日生日用户的红包
            Artisan::call('SendBirthdayBonus');
        })->dailyAt('9:00');

        $schedule->call(function(){
            //每天07:00给明日回款用户发送加息券
            Artisan::call('SendRefundUserBonus');
        })->dailyAt('07:00');

        /*$schedule->call(function(){
            //每天10:20给当日回款的发加息券的用户发送短信提醒
            Artisan::call('SendRefundUserSms');
        })->dailyAt('10:20');*/

        $schedule->call( function () {
            //每天下午18:00上传债权数据到中关村协会共享数据平台
            Artisan::call('UploadCreditZgc');
        })->dailyAt('18:00');

        //每天00:00分生成一条账户资金统计
        $schedule->call(function () {
            Artisan::call('FundStatistics');
        })->dailyAt('00:00');

        //每天23:30分生成一条中金云统计
        $schedule->call(function () {
            Artisan::call('InvestRefundStat');
        })->dailyAt('23:30');


        $schedule->call(function () {
            Artisan::call('statisticsFundHistory');
        })->dailyAt('00:05');

        $schedule->call(function(){
            //每天10:00 给快金发送-我要贷款-贷款人信息
            Artisan::call('TimeCashLoan');
        })->dailyAt('10:00');

        $schedule->call(function(){
            //每天晚上09:30发送当日充值成功以及申请提现的总结数据邮件
            Artisan::call('sendRechargeWithdrawDayDataEmail');
        })->when(function(){

            $time = date( 'H:i' );

            return ( $time == '12:00' ) || ( $time == '15:00' ) || ( $time == ' 18:00' ) || ( $time == '21:00' );
        });
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

        $schedule->call(function () {
            //清空用户上月充值成功记录
            Artisan::call('ClearUserMonthRecord');
        })->monthly();

    }


}
