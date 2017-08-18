<?php
/**
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 16:02
 * Desc: 绑卡、未绑卡用户限额列表
 */

class LimitTest extends TestCase{

    /**
     * @dataProvider userList
     * 绑卡用户限额列表
     */
    public function testBindUserLimit($userId){


        $url = 'http://core.9douyu.com/recharge/limit/user';

        $postData = [
            "user_id" => $userId,
        ];

        $this->request($url,$postData);
    }


    /**
     * @dataProvider userList
     * @param $userId
     * 未绑卡限额列表
     */
    public function testBankList($userId){


        $url = 'http://core.9douyu.com/recharge/limit/list';

        $postData = [
            "user_id" => $userId,
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
            [82692],
            [100],
            ['abc'],
            [0],
            [-1]
        ];
    }


}