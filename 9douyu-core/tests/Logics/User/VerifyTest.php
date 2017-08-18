<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 16:02
 * Desc: 实名 + 绑卡
 */

class VerifyTest extends TestCase{

    /**
     * @dataProvider userList
     * @param $userId
     * @param $name
     * @param $cardNo
     * @param $bankId
     * @param $idCard
     */
    public function testVerifyUser($userId,$name,$cardNo,$bankId,$idCard){

        $url = 'http://core.9douyu.com/user/verify';

        $postData = [
            "user_id" => $userId,
            "name"    => $name,
            "card_no" => $cardNo,
            "bank_id" => $bankId,
            "id_card" => $idCard,
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
            [82692,'张爽','6214830104420491',6,'429004199005040339'],//成功
            [82691,'张爽','6214830104420491',6,'429004199005040339'],//用户不存在
            [0,'张爽','6214830104420491',6,'429004199005040339'],//无效的用户ID
            [-1,'张爽','6214830104420491',6,'429004199005040339'],//无效的用户ID
            ['abc','张爽','6214830104420491',6,'429004199005040339'],//无效的用户ID
            [82692,'abc','6214830104420491',6,'429004199005040339'],//无效的姓名
            [82692,-1,'6214830104420491',6,'429004199005040339'],//无效的姓名
            [82692,'张爽','6214830104420',6,'429004199005040339'],//无效的银行卡号
            [82692,'张爽','6214830104420491',0,'429004199005040339'],//无效的银行ID
            [82692,'张爽','6214830104420491',100,'429004199005040339'],//无效的银行ID
            [82692,'张爽','6214830104420491',6,'42900419900504033'],//无效的身份证号
            [82692,'张爽','6214830104420491',6,'429004199005040338'],//无效的身份证号
        ];
    }
}