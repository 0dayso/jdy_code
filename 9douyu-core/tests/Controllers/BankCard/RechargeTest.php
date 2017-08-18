<?php
/**
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 15:23
 * Desc: 获取用户的提现银行卡
 */

class RechargeTest extends TestCase{


    /**
     * 获取用户的绑定银行卡
     * @dataProvider userList
     * @param $userId
     */
    public function testGetBindCard($userId, $expected)
    {

        $url = 'http://core.9douyu.com/recharge/card/get';

        $postData = [
            'user_id' => $userId,
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
    public function userList()
    {
        return [
            ['82692', 200],    //成功
            ['100', 200],      //成功
            ['0', 500],        //失败，无效的用户ID
            ['-1', 500],       //失败，无效的用户ID
            ['abc', 500]     //失败，无效的用户ID
        ];
    }


}