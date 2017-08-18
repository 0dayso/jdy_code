<?php
/**
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 16:02
 * Desc: 充值订单相关测试用例
 */

class RechargeTest extends TestCase{


    /**
     * @dataProvider batchCheckAccountList
     * @param $orderData
     * @param $expected
     * 将请求的数据入队列
     */
    public function testBatchCheckAccount($orderData,$expected){

        $url = 'http://core.9douyu.com/withdraw/order/batchCheckAccount';

        $postData = [
            "order_data"   => $orderData,
        ];


        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);

    }


    /**
     * @dataProvider batchSubmitToBankList
     * @param $expected
     * 提示批量提交至银行
     * 仅入队列
     */
    public function testBatchSubmitToBank($expected){


        $url = 'http://core.9douyu.com/withdraw/order/batchSubmitToBank';

        $postData = [];

        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }


    public function batchSubmitToBankList(){


        return [
            [200],                            //正确
        ];
    }

    public function batchCheckAccountList(){


        $data = [
            [
                'order_id' => 'JDY_'.date('YmdHis').rand(1000,9999),
                'status'   => 200,
                'note'     => ''
            ],
            [
                'order_id' => 'JDY_'.date('YmdHis').rand(1000,9999),
                'status'   => 500,
                'note'     => ''
            ],
            [
                'order_id' => 'JDY_'.date('YmdHis').rand(1000,9999),
                'status'   => 500,
                'note'     => '账户余额不足'
            ],
            [
                'order_id' => 'JDY_'.date('YmdHis').rand(1000,9999),
                'status'   => 500,
                'note'     => '信息不匹配'
            ],
            [
                'order_id' => 'JDY_'.date('YmdHis').rand(1000,9999),
                'status'   => 500,
                'note'     => '银行卡错误'
            ],
            [
                'order_id' => 'JDY_'.date('YmdHis').rand(1000,9999),
                'status'   => 500,
                'note'     => ''
            ],
        ];

        $params = json_encode($data);
        return [
            [$params,200],                            //正确
        ];
    }


}