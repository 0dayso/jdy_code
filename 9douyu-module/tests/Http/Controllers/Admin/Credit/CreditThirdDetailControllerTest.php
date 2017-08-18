<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/11/15
 * Time: 12:30
 * Desc: 添加创建第三方债权人的测试用例
 */

class CreditThirdDetailControllerTest extends \TestCase{

    /**
     * 提供第三方债权创建成功测试event事件机制的供给数据
     * @return array
     */
    public function dataThirdCreditEvent(){

        $creditThreeData = [
            [
            'credit_third_id' => 1,
            'credit_list' => '[{"realname":"\u6c88*","identity_card":"14010719******0628","amount":1000,"time":"2016-11-07","refund_time":"2016-12-07","address":""},{"realname":"\u5f90**","identity_card":"33022719******275X","amount":1000,"time":"2016-11-07","refund_time":"2016-12-07","address":""}]',
            ]
        ];

        return $creditThreeData;
    }

    /**
     * @desc 测试直接调用logic创建债权人详情数据供给
     * @return array
     */
    public function dataThirdCredit(){
        $thirdThirdDetailData = [
            [
                'credit_detail' => [
                    0 =>  [
                        "credit_third_id" => 1,
                        "name" => "沈*",
                        "id_card" => "14010719******0628",
                        "amount" => 1000,
                        "usable_amount" => 1000,
                        "loan_time" => "2016-11-07",
                        "refund_time" => "2016-12-07",
                        "status" => 100,
                        ],
                    1 =>  [
                        "credit_third_id" => 1,
                        "name" => "徐**",
                        "id_card" => "33022719******275X",
                        "amount" => 1000,
                        "usable_amount" => 1000,
                        "loan_time" => "2016-11-07",
                        "refund_time" => "2016-12-07",
                        "status" => 100,
                      ]
                ]
            ],
        ];

        return $thirdThirdDetailData;
    }

    /**
     * @desc 测试event事件创建债权人详情
     * @dataProvider dataThirdCreditEvent
     */
    public function testCreditThirdDetailEvent($credit_id, $credit_list){

        $data = [
            'credit_id'   => $credit_id,
            'credit_list' => $credit_list
        ];

        $return = \Event::fire(new \App\Events\Admin\Credit\CreditThirdDetailEvent(
            ['data'=> $data]
        ));

        $this->assertEquals('200', $return[0]['code']);
    }

    /**
     * @desc 测试Logic创建债权人详情信息
     * @param $credit_detail
     * @dataProvider dataThirdCredit
     */
    public function testDpCreateThirdDetailLogic($credit_detail){
        if(!empty($credit_detail)){
            //拆分数据
            $chunkArray = array_chunk($credit_detail,50);

            foreach($chunkArray as $key=>$value){
                $return = \App\Http\Logics\Credit\CreditThirdDetailLogic::doCreateDetail($value);
            }

            $this->assertEquals('200', $return['code']);
        }
    }

}