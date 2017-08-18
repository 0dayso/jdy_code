<?php

/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/2
 * Time: 上午11:17
 */

namespace Tests\Http\Logics\Fund;

use App\Http\Logics\Fund\FundHistoryLogic;

class FundHistoryV4LogicTest extends \TestCase
{

    public function sendTestData(){

        return [
            //资金明细首页
            [
                'is'   => true,
                'data' => [
                    'type' => '',
                    'user_id' => 31,
                    'page' => null,
                    'size' => null,
                    'start_time' => null,
                    'end_time' => null,
                ]
            ],
            //显示全部信息
            [
                'is'   => true,
                'data' => [
                    'type' => 'all',
                    'user_id' => 31,
                    'page' => null,
                    'size' => null,
                    'start_time' => null,
                    'end_time' => null,
                ]
            ],
            //根据日期搜索
            [
                'is'   => true,
                'data' => [
                    'type' => '',
                    'user_id' => 31,
                    'page' => null,
                    'size' => null,
                    'start_time' => '2016-10-02',
                    'end_time' => '2016-10-23',
                ]
            ],
            //类型+日期
            [
                'is'   => true,
                'data' => [
                    'type' => 'invest',
                    'user_id' => 31,
                    'page' => 1,
                    'size' => 5,
                    'start_time' => '2016-10-02',
                    'end_time' => '2016-10-23',
                ]
            ],
            //类型+日期+翻页
            [
                'is'   => true,
                'data' => [
                    'type' => 'investCurrent',
                    'user_id' => 31,
                    'page' => 2,
                    'size' => 5,
                    'start_time' => '2016-10-02',
                    'end_time' => '2016-10-23',
                ]
            ],
            //未登录
            [
                'is'   => false,
                'data' => [
                    'type' => '',
                    'user_id' => 0,
                    'page' => 1,
                    'size' => 3,
                    'start_time' => null,
                    'end_time' => null,
                ]
            ],
        ];

    }

    /**
     * @param $is
     * @param $data
     * @dataProvider sendTestData
     */
    public function testGetApp4List($is,$data){

        $logic = new FundHistoryLogic();

        $res = $logic->getApp4List($data);

        //没有用户id,返回错误状态
        if($is === true){
            $this->assertNotEmpty($res['data']['list']);
            //如果类型是''或者'all',则没有totalCash参数(累计值)
            if($data['type'] == 'all' || $data['type'] == ''){
                $this->assertArrayNotHasKey('totalCash', $res['data']);
            }else{
                $this->assertArrayHasKey('totalCash', $res['data']);
            }
        }else{
            $this->assertNotTrue($res['status']);
        }

    }

}
