<?php

/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/2
 * Time: 上午11:17
 */

namespace Tests\Http\Logics\Bonus;


use App\Http\Logics\Bonus\UserBonusLogic;

class GetUserBonusListLogicTest extends \TestCase
{

    public function sendTestData(){

        return [
            [
                'is'   => true,
                'data' => [
                    'user_id' => 31,
                    'page' => 1,
                    'size' => 10,
                    'type' => 1,
                ]
            ],
            [
                'is'   => false,
                'data' => [
                    'user_id' => 31,
                    'page' => 1,
                    'size' => 10,
                    'type' => 4,
                ]
            ],
        ];

    }

    /**
     * @param $is
     * @param $data
     * @dataProvider sendTestData
     */
    public function testGetUserBonusList($is,$data){

        $logic = new UserBonusLogic();

        $res = $logic->getUserBonusList($data['user_id'], $data['page'], $data['size'], $data['type']);

        if($is === true){
            $this->assertNotEmpty($res);
        }else{
            $this->assertEmpty($res);
        }

    }

}
