<?php
/**
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 16:02
 * Desc: 提现订单相关的测试用例
 */

class WithdrawTest extends TestCase{


    /**
     * @dataProvider createOrderList
     * @param $userId
     * @param $cash
     * @param $orderId
     * @param $handingFee
     * @param $bankId
     * @param $cardNo
     * @param $type
     * @param $from
     * @param $version
     * @param $expected
     */
    public function testAddOrder($userId,$cash,$orderId,$handingFee,$bankId,$cardNo,$type,$from,$version,$expected){

        $url = 'http://core.9douyu.com/withdraw/order/create';

        $postData = [
            "user_id"   => $userId,
            "cash"      => $cash,
            "order_id"  => $orderId,
            "bank_id"   => $bankId,
            "card_no"   => $cardNo,
            "type"      => $type,
            "from"      => $from,
            "version"   => $version,
            "handing_fee"   => $handingFee
        ];

        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }


    /**
     * @dataProvider succList
     * @param $userId
     * @param $cash
     * @param $orderId
     * 提现成功
     */

    public function testSuccOrder($orderId,$tradeNo,$expected){



        $url = 'http://core.9douyu.com/withdraw/order/success';

        $postData = [
            "trade_no" => $tradeNo,
            "order_id" => $orderId
        ];

        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }

    /**
     * @dataProvider failedList
     * @param $tradeNo      交易流水号
     * @param $note         失败原因
     * @param $orderId      订单号
     * 提现失败
     */

    public function testFailedOrder($orderId,$tradeNo,$note,$expected){

        $url = 'http://core.9douyu.com/withdraw/order/failed';

        $postData = [
            "note"          => $note,
            "trade_no"      => $tradeNo,
            "order_id"      => $orderId
        ];

        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }


    /**
     * @dataProvider submitList
     * @param $orderId
     * 提现提交银行处理
     */

    public function testSubmitToBankOrder($orderId,$expected){


        $url = 'http://core.9douyu.com/withdraw/order/submitToBank';

        $postData = [
            "order_id" => $orderId
        ];

        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }


    /**
     * @dataProvider cancelList
     * @param $note     取消原因
     * @param $orderId  订单号
     * 取消提现
     */

    public function testCancelOrder($orderId,$note,$expected){

        $url = 'http://core.9douyu.com/withdraw/order/cancel';

        $postData = [
            "note" => $note,
            "order_id" => $orderId
        ];

        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }




    /**
     * @param $response
     * 请求响应结果处理
     */
    public function response($response, $args){

        $return = json_decode($response['content'],true);

        $this->assertEquals($return['code'], $args['expected'], $return['msg']);

    }

    /**
     * 测试数据
     * @return array
     */
    public function succList()
    {
        return [
            ['JDY_'.date('YmdHis').rand(1000,9999),'',500],//订单号不存在
            ['JDY_201605161001434882','2016052345',200],//成功
            ['JD_'.date('YmdHis').rand(1000,9999),'',500],//订单号格式错误

        ];
    }


    /**
     * 测试数据
     * @return array
     */
    public function failedList()
    {
        return [
            ['JDY_'.date('YmdHis').rand(1000,9999),'','信息不匹配',500],//订单号不存在
            ['JDY_201605161001434883','2016052345','',200],//成功
            ['JD_'.date('YmdHis').rand(1000,9999),'','',500],//订单号格式错误
        ];
    }


    /**
     * 测试数据
     * @return array
     */
    public function submitList()
    {
        return [
            ['JDY_'.date('YmdHis').rand(1000,9999),500],//订单号不存在
            ['JDY_201605161001434884',200],//成功
            ['JD_'.date('YmdHis').rand(1000,9999),500],//订单号格式错误
        ];
    }


    /**
     * 测试数据
     * @return array
     */
    public function cancelList()
    {
        return [
            ['JDY_'.date('YmdHis').rand(1000,9999),'',500],//订单号不存在
            ['JDY_201605161001434885','',500],//取消原因不能为空
            ['JDY_201605161001434885','手续费过高',200],//成功
            ['JD_'.date('YmdHis').rand(1000,9999),'',500],//订单号格式错误
        ];
    }
    /**
     * 创建订单测试数据
     * @return array
     * userId       用户ID
     * cash         提现金额
     * orderId      订单号
     * handingFee   手续费
     * bankId       银行ID
     * cardNo       银行卡号
     * type         提现类型 2000
     * from         三端来源
     * version      APP端版本号
     */
    public function createOrderList()
    {
        return [
            [82692,100,'JDY_201605161001434882',0,6,'6214830104420491',2000,'pc','',200],    //成功
            [82692,100,'JDY_201605161001434883',5,6,'6214830104420491',2000,'android','2.3.101',200],    //成功
            [82692,200,'JDY_201605161001434882',0,6,'6214830104420491',2000,'pc','',500],    //失败,订单号重复
            [82692,100,'JDY_201605161001434884',0,6,'6214830104420491',2000,'ios','2.1.0',200],    //成功
            [82692,100,'JDY_201605161001434885',0,6,'6214830104420491',2000,'ios','2.1.0',200],    //成功
            [100,200,'JDY_'.date('YmdHis').rand(1000,9999),0,6,'6214830104420491',2000,'app','2.1.0',500],    //用户不存在
            [82692,300,'JDY_'.date('YmdHis').rand(1000,9999),5,100,'6214830104420491',2000,'app','2.1.0',500],    //银行ID格式错误
            [-1,200,'JDY_'.date('YmdHis').rand(1000,9999),0,6,'6214830104420491',2000,'wap','',500],     //用户ID错误
            [82692,100,'abcdefdfd',0,6,'6214830104420491',2000,'wap','',500],                            //订单号格式错误

        ];
    }



}