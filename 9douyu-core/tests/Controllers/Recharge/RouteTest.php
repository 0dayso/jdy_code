<?php
/**
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 16:02
 * Desc: 支付路由相关测试用例
 */

class RouteTest extends TestCase{


    /**
     * @param $userId
     * @param $cash
     * @param $bankId
     * @dataProvider userList
     */
    public function testChoiceRoute($userId,$cash,$bankId){


        $url = 'http://core.9douyu.com/recharge/route';

        $postData = [
            "user_id" => $userId,
            "bank_id" => $bankId,
            "cash" => $cash

        ];

        $this->request($url,$postData);
    }


    /**
     * @param $url
     * @param $postData
     * @throws \Ares333\CurlMulti\Exception
     * 发送Http请求
     */
    public function request($url,$postData){

        $curl = new \Ares333\CurlMulti\Core();

        $curl->add(array (
            'url' => $url,
            'opt' => array(
                CURLOPT_POST=>true,
                CURLOPT_POSTFIELDS => $postData,
            ),
        ), array($this, 'response'));

        $curl->start();


    }

    /**
     * @param $response
     * 请求响应结果处理
     */
    public function response($response){

        $return = json_decode($response['content'],true);
        $this->assertEquals($return['code'],200,$return['msg']);

    }


    /**
     * 测试数据
     * @return array
     */
    public function userList()
    {
        return [
            [82692,10000,6],
            [100,100000,5],
            ['abc',1000,6],
            [0,10000,100],
            [-1,'abc',-1]
        ];
    }


}