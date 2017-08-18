<?php

namespace App\Providers;

use App\Listeners\QueryListener;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        //注册成功事件
        'App\Events\Api\User\RegisterSuccessEvent' => [
            'App\Listeners\Api\User\RegisterSuccessListener',       //用户处理
            'App\Listeners\Api\Award\RegisterSuccessListener',      //奖励处理
        ],

        //投资定期成功事件
        'App\Events\Api\Invest\ProjectSuccessEvent' => [
            'App\Listeners\Project\AutoPublishProjectListener',         //自动发布项目
            'App\Listeners\Refund\CreateProjectRecordListener',         //生成定期回款计划
            'App\Listeners\Refund\CreateNewProjectRecordListener',         //生成定期回款计划
            'App\Listeners\Project\SdfProjectRefundListener',           //闪电付息利息回款

        ],

        //回款成功
        //'App\Events\Api\Refund\ProjectSuccessEvent' => [           //定期项目回款成功
        'App\Events\Refund\ProjectSuccessEvent' => [                    //定期项目回款成功
            'App\Listeners\Project\ProjectEndListener',                 //项目完结事件
            'App\Listeners\Email\RefundSuccessListener',                //回款成功通知邮件
            'App\Listeners\Refund\BeforeRefundSmsListener',             //发送提前回款的短信
            'App\Listeners\Invest\CurrentAutoListener'                  //回款自动进活期
        ],

        'App\Events\BankCard\BindCardFailedEvent' => [                       //支付回调绑卡失败
            'App\Events\BankCard\BindCardFailedEvent'
        ],
        'App\Events\Order\RechargeNoticeHandleFailedEvent' => [           //成功支付回调处理失败，出现掉单
            'App\Events\Order\RechargeSuccessHandleFailedEvent'
        ],

        'App\Events\Order\WithdrawCancleSuccessEvent' => [                //取消提现成功事件
            'App\Events\Order\WithdrawCancleSuccessEvent'
        ],

        'App\Events\Api\Order\WithdrawHandleFailedEvent' => [
            'App\Listeners\Api\Order\WithdrawHandleFailedListener',       //提现批量对账处理失败
            'App\Listeners\Withdraw\WithdrawErrorListener'                //提现失败触发报警
        ],

        'App\Events\Current\RefundAutoInvestEvent' => [
            'App\Listeners\Api\Current\RefundAutoInvestListener',       //回款自动进活
        ],
        'App\Events\Api\*' => [      //*/
            'App\Listeners\Api\Common\AsyncListener',               //全局异步监听器
        ],

        'Illuminate\Database\Events\QueryExecuted' => [
            QueryListener::class,
        ],

        //投资债转成功事件
        'App\Events\Invest\CreditAssignSuccessEvent' => [
            \App\Listeners\Invest\CreditAssignSuccess\SendSMSListener::class,           //发送投资成功短信
        ],

    ];

    /**
     * The subscriber classes to register.
     * @var array
     */
    protected $subscribe = [
        //
    ];
}
