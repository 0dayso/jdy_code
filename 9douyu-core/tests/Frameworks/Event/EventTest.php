<?php
/**
 * 事件
 * User: zjmainstay
 * Date: 16/4/13
 * Time: 14:53
 */
class EventTest extends TestCase
{
    /**
     * 事件注册
     *
     * @return void
     */
    public function testRegisterEvent()
    {
        Event::fire(new App\Events\Api\User\RegisterSuccessEvent( ['event_name' => 'App\Events\Api\Invest\InvestSuccessEvent', 'user_id' => '2', 'phone' => '13581913818',] ));
        Event::fire(new App\Events\Api\Invest\InvestSuccessEvent( ['event_name' => 'App\Events\Api\Invest\InvestSuccessEvent', 'user_id' => '3', 'cash' => '10000', 'project_id' => '1030',] ));
        Event::fire('App\Events\Api\Invest\InvestSuccessEvent',  [['event_name' => 'App\Events\Api\Invest\InvestSuccessEvent', 'user_id' => '4', 'cash' => '10000', 'project_id' => '1030',]] );
    }


    /**
     * 成功充值回调处理失败
     */
    public function testRecharge(){

        $params = [
            'event_name' => 'App\Events\Order\RechargeNoticeHandleFailedEvent',
            'user_id'    => '82692',
            'order_id'   => 'JDY_201605041923395831',
            'cash'       => 100,
            'msg'        => '订单号不存在'
        ];

        \Event::fire('App\Events\Order\RechargeNoticeHandleFailedEvent',[$params]);

    }

    /**
     * 取消提现
     */
    public function testCancelWithdraw(){
        //取消成功发送短信

        $params = [
            'event_name'    => 'App\Events\Order\WithdrawCancelSuccessEvent',
            'user_id'       => '82692',
            'order_id'      => 'JDY_201605041923395831'
        ];
        \Event::fire('App\Events\Order\WithdrawCancelSuccessEvent',[$params]);
    }

    /**
     * 绑卡失败事件
     */
    public function testBindCard(){
        //绑卡失败添加处理事件(发送邮件通知)
        $params = [
            'event_name' => 'App\Events\BankCard\BindCardFailedEvent',
            'order_id' => 'JDY_201605041923395831',
            'user_id' => 82692,
            'msg' => '绑卡失败'
        ];

        \Event::fire('App\Events\BankCard\BindCardFailedEvent',[$params]);

    }
}
