<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/13
 * Time: 10:28
 * Desc: 零钱计划加息券计息测试用例
 */

class BonusTest extends TestCase{

    /**
     * 获取用户的绑定银行卡
     * @dataProvider bonusList
     * @param $userId
     */
    public function testBonusInterestAccrual($interestList, $expected)
    {

        $url = 'http://core.9douyu.com/current/bonusInterestAccrual';

        $postData = [
            'interest_list' => $interestList,
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
    public function bonusList()
    {

        $list = [
            [
                'user_id' => 82692,
                'rate'    => 2
            ],
            [
                'user_id'   => 82691,
                'rate'      => 3
            ],
            [
                'user_id'   => 100,
                'rate'      => 5
            ]
        ];

        $data = json_encode($list);
        return [
            [$data, 200],    //成功
        ];
    }

}