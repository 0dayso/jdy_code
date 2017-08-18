<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/17
 * Time: 上午10:32
 */

namespace Tests\Http\Logics\User;

use App\Http\Logics\User\UserLogic;

class RiskAssessmentLogicTest extends \TestCase
{

    public function sendTestData(){

        return [
            [
                'userId' => 31,
                'data1'   => [
                    'param' => [
                        "question1" => "a",
                        "question2" => "a",
                        "question3" => "a",
                        "question4" => "a",
                        "question5" => "a",
                    ],
                    'status' => true,
                ],
                'data2'  => [
                    'param'  => [
                        "question6" => "a",
                        "question7" => "a",
                        "question8" => "a",
                        "question9" => "a",
                        "question10"=> "a",
                    ],
                    'status' => true,
                ],
            ],
            [
                'userId' => 33,
                'data1'   => [
                    'param' => [
                        "question1" => "b",
                        "question2" => "b",
                        "question3" => "b",
                        "question4" => "b",
                        "question5" => "",
                    ],
                    'status' => false,
                ],
                'data2'  => [
                    'param'  => [
                        "question6" => "b",
                        "question7" => "b",
                        "question8" => "b",
                        "question9" => "b",
                        "question10"=> "b",
                    ],
                    'status' => false,
                ],
            ],
            [
                'userId' => 91,
                'data1'   => [
                    'param' => [
                        "question1" => "c",
                        "question2" => "c",
                        "question3" => "c",
                        "question4" => "c",
                        "question5" => "c",
                    ],
                    'status' => true,
                ],
                'data2'  => [
                    'param'  => [
                        "question6" => "c",
                        "question7" => "c",
                        "question8" => "c",
                        "question9" => "c",
                        "question10"=> "",
                    ],
                    'status' => false,
                ],
            ],
        ];

    }

    /**
     * @param $userId
     * @param $data1
     * @param $data2
     * @dataProvider sendTestData
     */
    public function testDoSickAssessment($userId, $data1, $data2){

        $logic = new UserLogic();

        $result = $logic -> doSickAssessment($userId,$data1['param']);

        $this->assertEquals($data1['status'],$result['status']);

        $doResult = $logic -> doSickAssessmentSecond($userId,$data2['param']);

        $this->assertEquals($data2['status'],$doResult['status']);

    }
}