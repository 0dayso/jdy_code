<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/11/10
 * Time: 下午5:12
 * Desc: 邀请加息券
 */

class InviteRatesController extends \TestCase{

    public function testData(){

        return [
            [
                'user_id'           => 200,
                'days'              => 3,
                'admin_id'          => 1,
                'rate'              => 2.00,
                'use_expire_time'   => '2017-01-01'
            ],
            [
                'user_id'           => 200,
                'days'              => 5,
                'admin_id'          => 1,
                'rate'              => 4.00,
                'use_expire_time'   => '2017-01-01'
            ]
        ];

    }

    public function testDataTwo(){

        return [
            [
                'user_id'           => 200
            ]
        ];

    }

    /**
     * @param $data
     * @dataProvider testDataTwo
     */
    public function testDoDel($userId){

        $model = new \App\Http\Models\Invite\InviteRatesModel();

        $model->doDelByUserId($userId);

    }

    /**
     * @param $data
     * @dataProvider testData
     */
    public function testDoAdd($userId, $days, $adminId, $rate, $useExpireTime){

        $logic = new \App\Http\Logics\Invite\InviteRatesLogic();

        $data = [
            'user_id'           => $userId,
            'days'              => $days,
            'admin_id'          => $adminId,
            'rate'              => $rate,
            'use_expire_time'   => $useExpireTime
        ];

        $res = $logic->doAdd($data);

        $this->assertEquals(200, $res['code']);

    }

    /**
     * @param $data
     * @dataProvider testDataTwo
     */
    public function testGetListStepOne($userId){

        $logic = new \App\Http\Logics\Invite\InviteRatesLogic();

        $res = $logic->getCanUseListByUserId($userId);

        $this->assertEquals(2, count($res['data']));

    }

    /**
     * @param $data
     * @dataProvider testDataTwo
     */
    public function testDoUseStepOne($userId){

        $logic = new \App\Http\Logics\Invite\InviteRatesLogic();

        $res = $logic->getCanUseListByUserId($userId);

        $id = $res['data'][0]['id'];

        $res = $logic->doUse($id, $userId);

        $this->assertEquals(200, $res['code']);

    }

    /**
     * @param $data
     * @dataProvider testDataTwo
     */
    public function testDoUseStepTwo($userId){

        $logic = new \App\Http\Logics\Invite\InviteRatesLogic();

        $res = $logic->getCanUseListByUserId($userId);

        $id = $res['data'][0]['id'];

        $res = $logic->doUse($id, $userId);

        $this->assertEquals(500, $res['code']);

    }

    /**
     * @param $data
     * @dataProvider testDataTwo
     */
    public function testGetUsing($userId){

        $logic = new \App\Http\Logics\Invite\InviteRatesLogic();

        $res = $logic->getUsingRateByUserIds([$userId]);

        $this->assertEquals(1, count($res['data']));

    }

}