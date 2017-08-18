<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/3/30
 * Time: 下午11:16
 */
class ResetUnMatchTest extends TestCase{


    public function creditData(){

       return [
           ['id' => 1, 'usable_amount' => 100, \App\Http\Logics\Credit\CreditLogic::KEY_HUNDRED_CREDIT],
           ['id' => 1, 'usable_amount' => 100, \App\Http\Logics\Credit\CreditLogic::KEY_HUNDRED_CREDIT],
           ['id' => 2, 'usable_amount' => 100, \App\Http\Logics\Credit\CreditLogic::KEY_HUNDRED_CREDIT],
           ['id' => 2, 'usable_amount' => 100, \App\Http\Logics\Credit\CreditLogic::KEY_HUNDRED_CREDIT],
           ['id' => 1, 'usable_amount' => 1, \App\Http\Logics\Credit\CreditLogic::KEY_ONE_CREDIT],
           ['id' => 1, 'usable_amount' => 1, \App\Http\Logics\Credit\CreditLogic::KEY_ONE_CREDIT],
           ['id' => 2, 'usable_amount' => 1, \App\Http\Logics\Credit\CreditLogic::KEY_ONE_CREDIT],
           ['id' => 2, 'usable_amount' => 1, \App\Http\Logics\Credit\CreditLogic::KEY_ONE_CREDIT],
       ];

    }

    public function testDoInsertData(){

        \DB::table('credit_disperse')->truncate();

        \DB::table('credit_disperse')->insert([
            ['amounts' => '9999999.00'],
            ['amounts' => '8999999.00'],
            ['amounts' => '7999999.00']
        ]);
    }



    /**
     * @param $id
     * @param $usAbleAmount
     * @dataProvider creditData
     */
    public function testSaveRedis($id, $usAbleAmount, $key){
        
        $logic = new \App\Http\Logics\Credit\CreditDisperseLogic();

        $result = $logic->doPushRedisData($key, ['id' => $id, 'usable_amount' => $usAbleAmount]);

        $this->assertNotEquals(0, $result);

    }

    /**
     * 获取redis数据
     */
    public function testGetRedisData(){

        $logic = new \App\Http\Logics\Credit\CreditDisperseLogic();

        $result = $logic->resetUnMatchCredit();

        $this->assertEquals(true, $result['status']);

    }

    public function ableCreditData(){

        return [
            ['id' => 4, 'usable_amount' => 500099, 5000, 99],
            ['id' => 5, 'usable_amount' => 300099, 3000, 99],
        ];

    }

    /**
     * @param $amount
     * @param $total
     * @dataProvider ableCreditData
     */
    public function testDoCutAbleCreditData($id, $amount, $hundredTotal, $oneTotal){

        $data = [
            [
                'id'            => $id,
                'usable_amount' => $amount
            ]

        ];

        $result = \App\Http\Logics\Credit\CreditLogic::doCutAbleCreditData($data);

        $this->assertEquals($hundredTotal, count($result['hundred']));

        $this->assertEquals($oneTotal, count($result['one']));

    }

    /**
     * test 事件
     */
    public function testEvent(){

        \Event::fire(new \App\Events\Admin\Credit\MatchSuccessEvent());
        
    }

    /**
     * @throws Exception
     * @desc 添加债权匹配结果
     */
    public function testDoAddUserCredit(){

        \DB::table('user_credit')->truncate();

        $data = [
            ['user_id' => 1, 'credit_id' => 1, 'amount' => 1],
            ['user_id' => 2, 'credit_id' => 1, 'amount' => 10],
            ['user_id' => 3, 'credit_id' => 1, 'amount' => 10],
        ];

        $model = new \App\Http\Models\Credit\UserCreditModel();

        $result = $model->doAdd($data);

        $this->assertEquals(true, $result);

    }

    public function testGetUserCreditList(){

        $model = new \App\Http\Models\Credit\UserCreditModel();

        $list = $model->getListByUserId(1, 1);

    }


}
