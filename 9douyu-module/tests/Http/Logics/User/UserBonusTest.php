<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/2/22
 * Time: 下午6:49
 */

namespace Tests\Http\Logics\User;

use App\Http\Logics\Bonus\UserBonusLogic;

ini_set('date.timezone','Asia/Shanghai');
class UserBonusTest extends \TestCase
{

    public function sendTestData(){

        return [
            [
                'is' => true,
                'data' => [
                    "phone" => "18234475430",
                    "bonus_id" => "1",
                    "memo" => "测试".rand(1,9999),
                    "from_type" => "1",
                    "send_user_id" => "1",
                ]
            ],
            [
                'is' => false,
                'data' => [
                    "phone" => "18234475430",
                    "bonus_id" => "0",
                    "memo" => "测试".rand(1,9999),
                    "from_type" => "1",
                    "send_user_id" => "1",
                ]
            ],
            [
                'is' => true,
                'data' => [
                    "phone" => "18234475430",
                    "bonus_id" => "258",
                    "memo" => "测试".rand(1,9999),
                    "from_type" => "1",
                    "send_user_id" => "1",
                ]
            ],
            [
                'is' => false,
                'data' => [
                    "phone" => "18234475430",
                    "bonus_id" => "257",
                    "memo" => "测试".rand(1,9999),
                    "from_type" => "1",
                    "send_user_id" => "1",
                ]
            ],
        ];

    }

    /**
     * @param $is
     * @param $data
     * @dataProvider sendTestData
     */
    public function testDoSend($is, $data){
        $logic = new UserBonusLogic();
        $result = $logic -> doSendBonus($data);

        if($is === true) {
            $this->assertTrue($result['status']);
        }else{
            $this->assertNotTrue($result['status']);
        }
    }

}