<?php
/**
 * @desc    快金我要借款测试用例
 * @date    2017年03月07日
 * @author  @linglu
 */

class TimeCashController extends \TestCase{

    public function testData(){

        return [
            [
                'is' => true,
                'data' => [
                    "name"  => "测试",
                    "phone" => "18511852344",
                    "loan_amount"   => "1",
                    "loan_time"     => "2",
                    "refund_type"   => "2",
                ]
            ],
            [
                'is' => false,
                'data' => [
                    "name"  => "",
                    "phone" => "18511852344",
                    "loan_amount"   => "1",
                    "loan_time"     => "2",
                    "refund_type"   => "2",
                ]
            ],
            [
                'is' => false,
                'data' => [
                    "name"  => "测试",
                    "phone" => "",
                    "loan_amount"   => "1",
                    "loan_time"     => "2",
                    "refund_type"   => "2",
                ]
            ],
            #借款金额验证
            [
                'is' => false,
                'data' => [
                    "name"  => "测试",
                    "phone" => "18511852344",
                    "loan_amount"   => "",
                    "loan_time"     => "2",
                    "refund_type"   => "2",
                ]
            ],
            #借款期限验证
            [
                'is' => false,
                'data' => [
                    "name"  => "测试",
                    "phone" => "18511852344",
                    "loan_amount"   => "1",
                    "loan_time"     => "",
                    "refund_type"   => "2",
                ]
            ],
            #还款类型验证
            [
                'is' => false,
                'data' => [
                    "name"  => "测试",
                    "phone" => "18511852344",
                    "loan_amount"   => "1",
                    "loan_time"     => "2",
                    "refund_type"   => "",
                ]
            ],
            #手机格式验证
            [
                'is' => false,
                'data' => [
                    "name"  => "测试",
                    "phone" => "185118523",
                    "loan_amount"   => "1",
                    "loan_time"     => "2",
                    "refund_type"   => "3",
                ]
            ]
        ];

    }


    /**
     * @param $is
     * @param $data
     * @dataProvider testData
     */
    public function testDoAdd($is, $data){

        $logic = new App\Http\Logics\TimeCash\TimeCashLogic;

        $res = $logic->doAddLoan($data);
        if($is === true) {
            $this->assertEquals(200, $res['code']);
        }else{
            $this->assertEquals(500, $res['code']);
        }

    }

    /**
     * @desc    发邮件
     */
    public function testSendEmail(){

        $logic = new App\Http\Logics\TimeCash\TimeCashLogic;

        $logic->getLoanRecord();

    }
}