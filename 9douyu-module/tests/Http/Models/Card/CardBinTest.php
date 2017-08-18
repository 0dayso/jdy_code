<?php

namespace Tests\Http\Models\Card;

use App\Http\Logics\BankCard\CardLogic;
use Config;

class CardBinTest extends \TestCase
{


    public function testData(){

        return [
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 1,
                    'card_no' => "6222020200050352547",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 2,
                    'card_no' => "6228450978011827774",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 3,
                    'card_no' => "4563510100858025012",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 4,
                    'card_no' => "6227000210100261534",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 5,
                    'card_no' => "6222600910027757531",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 6,
                    'card_no' => "6214832015369403",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 7,
                    'card_no' => "6225212082953271",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 8,
                    'card_no' => "6226200102561066",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 9,
                    'card_no' => "622908328825500217",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 10,
                    'card_no' => "6226681201001106",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 11,
                    'card_no' => "6029692013777287",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 12,
                    'card_no' => "6225684621000051287",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 13,
                    'card_no' => "6226901112307933",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 14,
                    'card_no' => "6210951000003318530",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 15,
                    'card_no' => "6230210350046839",
                ]
            ],
            [
                'is'   => false,
                'data' => [
                    'bank_id' => 17,
                    'card_no' => "6225380077733923",
                ]
            ],
            [
                'is'   => false,
                'data' => [
                    'bank_id' => 19,
                    'card_no' => "6217770006956498",
                ]
            ],
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 21,
                    'card_no' => "6222811310054332",
                ]
            ],
            [
                'is'   => false,
                'data' => [
                    'bank_id' => 1,
                    'card_no' => "6222811310054332",
                ]
            ],

            #TODO:完善bank_cardbin测试
            [
                'is'   => true,
                'data' => [
                    'bank_id' => 8,
                    'card_no' => "6216914301621867",
                ]
            ],
        ];

    }

    /**
     * @param $is
     * @param $data
     * @dataProvider testData
     */
    public function testCardInfo($is, $data ){
        $logic  = new CardLogic();

        $bank_id= $data["bank_id"];
        $cardNo = $data["card_no"];
        $result = $logic->getCardInfoV2($cardNo);

        $bankCode       = isset($result['data']['bank_code'])?$result['data']['bank_code']:"-1";

        $bankCodeList   = Config::get('bankcode.cardbin');

        $bankId = isset($bankCodeList[$bankCode]) ? $bankCodeList[$bankCode] : 0;

        echo " CARD- ".$cardNo. ' BANK- ' .$bank_id. ' -- '.$bankId."\n";

        if($is === true){

            $this->assertEquals($bankId, $bank_id);

        }else{

            $this->assertNotEquals($bankId, $bank_id);

        }
    }


}
