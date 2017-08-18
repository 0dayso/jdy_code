<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 17/01/23
 * Time: 下午19:34
 */

namespace Test\Http\Controllers\Activity;

use App\Http\Logics\Activity\GuessRiddlesLogic;

class GuessRiddlesTest extends \TestCase{

    /**
     * @desc 猜灯谜操作数据供给
     * @return array
     */
    public function dataGuessRiddles(){

        return [
            [
             'data'=> [
              'user_id'=>258082,
              'riddles_id'=> 1,
              'type'      =>100,
              'answer'    => '九',
              'activity_key' => 'ACTIVITY_LANTURE_FESTIVAL'
              ],
           ]
        ];
    }


    /**
     * @desc 测试猜灯谜未登录返回
     */
    public function testGuessRiddlesNotLogin(){

        //用户未登录的数据
        $dataNotLogin = [
              'user_id'=>0,
              'riddles_id'=> 1,
              'type'      =>100,
              'answer'    => '九',
              'activity_key' => 'ACTIVITY_LANTURE_FESTIVAL'
              ];

        $guessLogic = new GuessRiddlesLogic();

        $res = $guessLogic->doRiddles($dataNotLogin);
        $this->assertEquals('您还未登录系统,请先登录', $res['msg']);
    }

    /**
     * @desc 测试猜灯谜答案为空
     * @return bool
     */
    public function testGuessRiddlesIsNull(){

        //用户未登录的数据
        $dataNotIsNull = [
              'user_id'=>258082,
              'riddles_id'=> 1,
              'type'      =>1010,
              'answer'    => '',
              'activity_key' => 'ACTIVITY_LANTURE_FESTIVAL'
              ];

        $guessLogic = new GuessRiddlesLogic();

        $res = $guessLogic->doRiddles($dataNotIsNull);
        $this->assertEquals('灯谜答案不能为空', $res['msg']);
    }

    /**
     * @desc 测试猜灯谜答案不正确
     * @return bool
     */
    public function testGuessRiddlesIsNoRight(){

        //用户未登录的数据
        $dataIsNotRight = [
              'user_id'=>258082,
              'riddles_id'=> 1,
              'type'      =>1010,
              'anwer'    => '哈哈',
              'activity_key' => 'ACTIVITY_LANTURE_FESTIVAL'
              ];

        $guessLogic = new GuessRiddlesLogic();

        $res = $guessLogic->doRiddles($dataIsNotRight);
        $this->assertEquals('灯谜答案不正确', $res['msg']);
    }


    /**
     * @desc 测试猜灯谜操作
     * @dataProvider dataGuessRiddles
     */
    public function testGuessRiddles($data){
        $guessLogic = new GuessRiddlesLogic();

        $res = $guessLogic->doRiddles($data);
        print_r($res);
        $this->assertEquals('200', $res['code']);
    }

}
