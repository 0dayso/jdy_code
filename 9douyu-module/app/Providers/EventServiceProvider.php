<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //注册成功事件
        'App\Events\User\RegisterSuccessEvent' => [
            \App\Listeners\User\RegisterSuccess\UserInfoListener::class,           // 记录用户详情
            \App\Listeners\User\RegisterSuccess\InviteRelationshipListener::class,  // todo 邀请关系
            \App\Listeners\User\RegisterSuccess\InviteMediaListener::class,         //记录用户注册来源推广渠道
            \App\Listeners\User\RegisterSuccess\ActivityAwardListener::class,           //新用户注册奖励
            // ...各种活动
        ],

        //新手奖励事件
        'App\Events\User\NoviceRewardsEvent' => [
            \App\Listeners\User\RegisterSuccess\NoviceAwardListener::class,           //新手奖励事件
            // ...各种活动
        ],
        //投资之前红包加息券检测事件
        'App\Events\Invest\ProjectBeforeEvent' => [
            \App\Listeners\Invest\ProjectBefore\CheckBonusListener::class,
        ],


        //投资之前红包加息券检测事件
        'App\Events\Invest\CurrentBeforeEvent' => [
            \App\Listeners\Invest\CurrentBefore\CheckBonusListener::class,
        ],


        //投资成功事件
        'App\Events\Invest\ProjectSuccessEvent' => [
            \App\Listeners\Invest\ProjectSuccess\UpdateBonusListener::class,           //更新红包加息券
            \App\Listeners\Invest\ProjectSuccess\AddRateRefundRecordListener::class,           //生成加息券回款记录
            \App\Listeners\Invest\ProjectSuccess\AddInvestListener::class,    //增加module投资数据
            \App\Listeners\Invest\ProjectSuccess\SendSMSListener::class,           //发送投资成功短信
            \App\Listeners\Invest\ProjectSuccess\JyActivityListener::class,     //通知指定openid 投资大于100【地推活动加积分】
            \App\Listeners\Notice\NoticeListener::class,    //站内信
            \App\Listeners\Invest\ProjectSuccess\LoanUserListener::class,           //通知借款人
            \App\Listeners\Award\Activity\ActivityStatisticsListener::class,           //处理活动记录
        ],

        //零钱计划转入成功事件
        'App\Events\Invest\CurrentSuccessEvent' => [
            \App\Listeners\Invest\CurrentSuccess\AutoPublishProjectListener::class,         //自动生成项目
            \App\Listeners\Invest\CurrentSuccess\DoUseCurrentBonusListener::class,         //使用红包
        ],

        //充值成功事件
        'App\Events\Pay\RechargeSuccessEvent' => [
            \App\Listeners\Pay\RechargeSuccess\EditUserSuccessRecordListener::class, //更新用户成功充值记录

        ],

        //创建定期项目成功事件
        'App\Events\Project\CreateProjectSuccessEvent' => [
            \App\Listeners\Project\ProjectExtendListener::class,           // 项目扩展信息记录
        ],

        'Illuminate\Database\Events\QueryExecuted' => [
            \App\Listeners\QueryListener::class,
        ],

        //定期项目的红包解锁
        'App\Events\Invest\ProjectUnLockBonusEvent' => [
            \App\Listeners\Invest\ProjectError\CheckBonusListener::class,
        ],

        //合伙人收益转出
        'App\Events\Award\PartnerCommissionTransferEvent' => [
            \App\Listeners\Award\Partner\CommissionTransferListener::class,
        ],

        //批量记录审核通过
        'App\Events\Batch\AuditSuccessEvent' => [
            \App\Listeners\Batch\BatchListListener::class
        ],

        //加息结算
        'App\Events\Activity\IncreaseTransferEvent' => [
            \App\Listeners\Award\Activity\CommissionTransferListener::class,
        ],

        //活动连续签到奖励
        'App\Events\Activity\SignEvent'  => [
            \App\Listeners\Award\Activity\NationalSignListener::class,
        ],

        //抽奖成功后的事件
        'App\Events\Activity\LotteryEvent'=>[
            \App\Listeners\Award\Activity\LotteryTransferListener::class,
        ],

        //猜灯谜奖励
        'App\Events\Activity\GuessRiddlesEvent'  => [
            \App\Listeners\Award\Activity\GuessRiddlesLister::class,
        ],

        //读取对账文件事件
        'App\Events\Pay\RechargeBatchEvent'=>[
            \App\Listeners\Pay\RechargeCheck\CheckBatchTransferListener::class,
        ],

        //执行充值对账事件
        'App\Events\Pay\RechargeCheckEvent'=>[
            \App\Listeners\Pay\RechargeCheck\ReconcileTransferListener::class,
        ],
        //登录成功的时间
        'App\Events\User\LoginSuccessEvent' => [
            \App\Listeners\User\LoginSuccess\AddLoginHistoryListener::class,//添加登录的历史记录
        ],

        //后台添加第三方债权一月期成功的事件
        'App\Events\Admin\Credit\CreditThirdDetailEvent' =>[
            \App\Listeners\Admin\Credit\AddCreditThirdSuccessListener::class,//添加第三方债权人信息
        ],
        //每日债权匹配完成事件
        'App\Events\Admin\Credit\MatchSuccessEvent' =>[
            \App\Listeners\Admin\Credit\ResetUnMatchCreditListener::class,//重置剩余未匹配债权监听
        ],
        //债权数据初始化存入redis成功后的债权匹配
        'App\Events\Credit\AccountCreditMatchEvent'  =>[
           \App\Listeners\Credit\AccountCrediMatchSuccessListener::class,  //分散债权匹配
        ],

        //发放新手红包成功事件
        'App\Events\User\ActivityAwardSuccessEvent' => [
            \App\Listeners\Notice\NoticeListener::class,    //站内信
        ],

        //创建提现成功事件
        'App\Events\Order\WithdrawCreateSuccessEvent' => [
            \App\Listeners\Notice\NoticeListener::class,    //站内信
        ],

        //申请提现成功
        'App\Events\Order\WithdrawSuccessEvent' => [

        ],

        //提现失败
        'App\Events\Order\WithdrawFailEvent' => [

        ],

        //创建债权转让成功
        'App\Events\Project\CreateCreditAssignProjectSuccessEvent' => [
            \App\Listeners\Notice\NoticeListener::class,    //站内信
        ],

        //取消债权转让成功
        'App\Events\Project\CancelCreditAssignProjectSuccessEvent' => [
            \App\Listeners\Notice\NoticeListener::class,    //站内信
        ],

        //发布公告成功
        'App\Events\Article\CreateArticleSuccessEvent' => [
            \App\Listeners\Notice\SiteNoticeListener::class,    //公告站内信
        ],

        //家庭账户授权成功
        'App\Events\User\FamilyAuthSuccessEvent' => [
            \App\Listeners\Notice\NoticeListener::class,    //公告站内信
            \App\Listeners\Notice\SmsListener::class,    //发送短信
        ],

        //生成保全合同的队列
        'App\Events\User\BuildContractFileEvent' => [
            \App\Listeners\User\ContractFile\BuildContractListeners::class,    //生成保全合同的队列
        ],

        'App\Events\User\CreateContractFileEvent' => [
            \App\Listeners\User\ContractFile\CreateContractListeners::class,    //生成保全合同
        ],

        'App\Events\User\CheckContractEvent' => [
            \App\Listeners\User\ContractFile\CheckContractListeners::class,
        ],

        'App\Events\User\CheckContractDownEvent'=> [
            \App\Listeners\User\ContractFile\CheckContractDownUrlListeners::class,    //下载保全合同
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
