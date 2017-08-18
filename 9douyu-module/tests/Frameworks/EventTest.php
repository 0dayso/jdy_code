<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/15
 * Time: 14:26
 * Desc: 事件测试用例
 */

class EventTest extends TestCase{

    /**
     *
     * 零钱计划投资成功事件
     */
    public function testCurrentInvestSuccess(){
        $params = [
            'event_name'    => 'App\Events\Invest\CurrentSuccessEvent',
            'event_desc'    => '零钱计划投资成功事件',
            'bonus_id'      => 1,                       //红包ID
            'user_id'       => 82692,                     //用户ID
            'cash'          => 10000,                       //用户零钱计划转入金额
            'left_amount'   => 10000, //项目剩余可投金额
        ];

        \Event::fire(new \App\Events\Invest\CurrentSuccessEvent($params));

    }



    /**
     *
     * 零钱计划投资前事件
     */
    public function testCurrentInvestBefore(){

        $params = [
            'event_name'    => 'App\Events\Invest\CurrentBeforeEvent',
            'event_desc'    => '零钱计划投资前事件',
            'bonus_id'      => 1,                       //红包ID
            'user_id'       => 82692,                     //用户ID
            'from'          => 'app',
            'cash'          => 1000
        ];

        \Event::fire(new \App\Events\Invest\CurrentBeforeEvent($params));

    }

    /**
     * 充值成功事件
     */
    public function testRechargeSuccess(){


        $params = [
            'event_name'    => 'App\Events\Pay\RechargeSuccessEvent',
            'event_desc'    => '充值成功事件',
            'order_id'      => 'JDY_201606052200248596',
        ];

        \Event::fire(new \App\Events\Pay\RechargeSuccessEvent($params));
    }


    /**
     * 注册成功事件
     */
    public function testRegisterSuccessEvent()
    {

        $data = [
            'channel' => 'uc01',
            'coreApiData' => ['id' => 1418839],
            'channel_id' => 1422290
        ];

        \Event::fire(new \App\Events\User\RegisterSuccessEvent(
            ['data' => $data]
        ));
    }


    /**
     * 合伙人收益转出
     */
    public function testPartner(){

        $params = [
            'event_name'        => 'App\Events\Award\PartnerCommissionTransferEvent',
            'event_desc'        => '合伙人佣金转出事件',
            'user_id'           => 82692,                    //红包ID
            'cash'              => 1,                       //用户零钱计划转入金额
            'trading_password'  => '26d72fa598213d8ccda11548675f58fb:c2db4a381f749d834bc30d2688a1e', //项目剩余可投金额
            'ticket_id'         => \App\Tools\ToolStr::getRandTicket()
        ];

        \Event::fire('App\Events\Award\PartnerCommissionTransferEvent',[$params]);
    }
}