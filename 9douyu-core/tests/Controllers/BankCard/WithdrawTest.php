<?php
/**
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 15:43
 * Desc: 获取用户的提现银行卡列表
 */
class WithdrawTest extends TestCase{
    /**
     * 获取用户的绑定银行卡
     * @dataProvider userList
     * @param $userId
     */

    public function testGetWithdrawCard($userId,$expected)
    {

        $url = 'http://core.9douyu.com/withdraw/card/get';

        $postData = [
            "user_id" => $userId,
        ];

        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }


    /**
     * 添加现提现银行卡
     * @dataProvider paramsList
     * @param $userId
     * @param $bankId
     * @param $cardNo
     */

    public function testAddCard($userId,$bankId,$cardNo,$expected){

        $url = 'http://core.9douyu.com/withdraw/card/create';

        $postData = [
            "user_id" => $userId,
            "bank_id" => $bankId,
            "card_no" => $cardNo
        ];

        $args['expected'] = $expected;

        $this->postRequest($url, $postData, array(), array(), $args);
    }


    /**
     * @param $userId
     * @param $cardNo
     * @dataProvider deleteList
     */

    public function testDeleteCard($userId,$cardNo,$expected){

        $url = 'http://core.9douyu.com/withdraw/card/delete';

        $postData = [
            "user_id" => $userId,
            "card_no" => $cardNo
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
     * 添加提现银行卡测试数据
     * @return array
     *
     */
    public function paramsList(){

        return [
            [82692,6,6214830104420491,200],     //正确的数据
            [82692,6,6214830104420491,500],     //错误,重复添加
            [82692,100,6214830104420491,500],   //银行ID不合法
            [82692,12,11111111111111,500],      //银行卡不合法
            [2,6,6214830104420491,500],         //用户ID不存在
            ['abc',5,6214830104420491,500],     //用户ID不合法
            [-1,5,6214830104420491,500],        //用户ID不合法
            [0,5,6214830104420491,500],         //用户ID不合法
            [82692,-1,6214830104420491,500],    //银行ID不合法
            [82692,0,6214830104420491,500],     //银行ID不合法
            [82692,'abc',6214830104420491,500], //银行ID不合法
            [82692,'abc','ffffff',500],         //银行卡号不合法


        ];
    }


    /**
     * @return array
     * 删除用户银行卡测试数据
     */
    public function deleteList(){
        return [
            [82692,6214830104420491,200],     //根据数据库来判断
            [0,6222021001098937862,500],      //用户ID不合法
            [-1,6214830104420491,500],        //用户ID不合法
            [82692,621483010442049134,500],   //银行卡号不合法
        ];
    }




    /**
     * 获取提现银行卡列表测试数据
     * @return array
     */
    public function userList()
    {
        return [
            [82692,500],   //成功
            [100,500],     //成功
            [0,500],       //无效的用户ID
            [-1,500],      //无效的用户ID
            ['abc',500]    //无效的用户ID
        ];
    }
}