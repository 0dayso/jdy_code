<?php
/**
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 16:02
 * Desc: 充值订单相关测试用例
 */

class RechargeTest extends TestCase{

    
    /**
     * @dataProvider createList
     * @param $userId   用户ID
     * @param $cash     充值金额
     * @param $orderId  订单号
     * @param $bankId   银行ID
     * @param $cardNo   银行卡号
     * @param $type     支付渠道
     * @param $from     三端来源
     * @param $version  APP版本号
     * @param $expected
     *
     */
    public function testAddOrder($userId,$cash,$orderId,$bankId,$cardNo,$type,$from,$version,$expected){

        $url = 'http://core.9douyu.com/recharge/order/create';

        $postData = [
            "user_id"   => $userId,
            "cash"      => $cash,
            "order_id"  => $orderId,
            "bank_id"   => $bankId,
            "card_no"   => $cardNo,
            "type"      => $type,
            "from"      => $from,
            "version"   => $version,
        ];


        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);

    }




    /**
     * @dataProvider succList
     * @param $tradeNo  交易流水号
     * @param $orderId  订单号
     * 充值成功
     */
    public function testSuccOrder($orderId,$tradeNo,$expected){


        $url = 'http://core.9douyu.com/recharge/order/success';

        $postData = [
            "trade_no" => $tradeNo,
            "order_id" => $orderId
        ];

        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }

    /**
     * @dataProvider failedList
     * @param $userId
     * @param $cash
     * @param $orderId
     * 充值失败
     */
    public function testFailedOrder($orderId,$tradeNo,$note,$expected){


        $url = 'http://core.9douyu.com/recharge/order/failed';

        $postData = [
            "order_id" => $orderId,
            "trade_no"  => $tradeNo,
            "note"      => $note
        ];

        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }


    /**
     * @dataProvider timeoutList
     * @param $orderId
     * @param $expected
     * 支付订单超时
     */
    public function testTimeoutOrder($orderId,$expected){

        $url = 'http://core.9douyu.com/recharge/order/timeout';

        $postData = [
            "order_id" => $orderId,
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
     * 创建订单测试数据
     * @return array
     * userId   用户ID
     * cash     充值金额
     * orderId  订单号
     * bankId   银行ID
     * cardNo   银行卡号
     * type     支付通道类型
     * from     三端来源
     * version  App版本号
     */
    public function createList()
    {

        return [
            [82692,100,'JDY_201605161001434879',6,'6214830104420491',1101,'pc','',200],    //成功
            [82692,100,'JDY_201605161001434880',6,'',1000,'android','2.3.101',200],    //成功
            [82692,200,'JDY_201605161001434879',6,'6214830104420491',1103,'pc','',500],    //失败,订单号重复
            [82692,100,'JDY_201605161001434881',6,'6214830104420491',1201,'ios','2.1.0',200],    //成功
            [100,200,'JDY_'.date('YmdHis').rand(1000,9999),6,'6214830104420491',1002,'app','2.1.0',500],    //用户不存在
            [82692,300,'JDY_'.date('YmdHis').rand(1000,9999),100,'6214830104420491',1002,'app','2.1.0',500],    //银行ID格式错误
            [-1,200,'JDY_'.date('YmdHis').rand(1000,9999),6,'6214830104420491',1103,'wap','',500],     //用户ID错误
            [82692,100,'abcdefdfd',6,'6214830104420491',1003,'wap','',500],                            //订单号格式错误
            [82692,100,'JDY_'.date('YmdHis').rand(1000,9999),6,'6214830104420491',10000,'wap','',500],  //支付渠道错误
            [82692,100,'JDY_'.date('YmdHis').rand(1000,9999),6,'6214830104420491',1102,'app','',500],  //订单来源平台不存在
            [82692,0,'JDY_'.date('YmdHis').rand(1000,9999),6,'6214830104420491',1001,'wap','',500],  //充值金额错误
            [82692,'abc','JDY_'.date('YmdHis').rand(1000,9999),6,'6214830104420491',1001,'wap','',500],  //充值金额错误
            [82692,200,'JDY_'.date('YmdHis').rand(1000,9999),6,'afdfd',1001,'wap','',200],  //网银支付不验证卡号,会将卡号处理成空


        ];
    }


    /**
     * 成功充值测试数据
     * @return array
     * order_id     订单号
     * trade_no     流水号
     *
     */
    public function succList(){

        return [
            ['JDY_201605161001434879','20140111145143456600',200],  //成功
            ['JDY_'.date('YmdHis').rand(1000,9999),'',500],    //订单号不存在
            ['abcdefdfd','',500],                            //订单号格式错误
        ];
    }

    /**
     * 失败充值测试数据
     * @return array
     * orderId      订单号
     * tradeNo      交易流水号
     * note         失败原因
     */
    public function failedList(){
        return [
            ['JDY_'.date('YmdHis').rand(1000,9999),'20140111145143456600','',500],  //订单号不存在
            ['JDY_201605161001434880','','信息不匹配',200],//成功
            ['JDY_'.date('YmdHis').rand(1000,9999),'20140111145143456600','帐户余额不足',500],     //用户ID错误
            ['abcdefdfd','','',500],                            //订单号格式错误
        ];
    }


    /**
     * 失败充值测试数据
     * @return array
     * orderId      订单号
     */
    public function timeoutList(){
        return [
            ['JDY_'.date('YmdHis').rand(1000,9999),500],  //订单号不存在
            ['abcdefdfd',500],          //订单号格式错误
            ['JDY_201605161001434881',200],//成功
        ];
    }
}