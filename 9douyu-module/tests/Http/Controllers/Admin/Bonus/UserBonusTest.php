<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/7
 * Time: 下午5:14
 */

namespace Test\Http\Controllers\Admin\Bonus;


class UserBonusTest extends \TestCase
{


    public function test(){

        $this->visit('/admin/bonus/send')
            ->see('发放优惠券');

    }

    public function testData(){

        return [
            [
                'is' => true,
                'data' => [
                    "user_id" => [1],
                    "phone" => "",
                    "bonus_id" => "1",
                    "memo" => "测试".rand(1,9999),
                ]
            ],
            [
                'is' => false,
                'data' => [
                    "user_id" => [1],
                    "phone" => "",
                    "bonus_id" => "0",
                    "memo" => "测试fail".rand(1,9999),
                ]
            ]
        ];

    }


    /**
     * @param $is
     * @param $data
     * @dataProvider testData
     */
    public function testDoCreate($is, $data){
        if($is === true) {
            $this->post('/admin/bonus/doSend', $data)
                ->assertRedirectedTo('/admin/bonus/send', ['message'=>'优惠券发送成功']);
        }else{
            $this->post('/admin/bonus/doSend', $data)
                ->assertHasOldInput();
        }
    }
}