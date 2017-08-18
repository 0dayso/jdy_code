<?php

namespace Tests\AppApi\Logic;
use App\Http\Logics\Current\CurrentUserLogic;

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/2
 * Time: 下午2:37
 */
class CurrentTest extends \TestCase
{


    public function normalData(){

        return [
            [
                'user_id'=>132519,
                'client'=>'ios',
                'status' => true,
                'code'   => 200,
            ],
            [
                'user_id'=>69,
                'client'=>'ios',
                'status' => true,
                'code'   => 200,
            ],
            [
                'user_id'=>0,
                'client'=>'ios',
                'status' => false,
                'code'   => 4006,
            ], //未登录
            [
                'user_id'=>132519,
                'client'=>'android',
                'status' => true,
                'code'   => 200,
            ],
            [
                'user_id'=>0,
                'client'=>'android',
                'status' => false,
                'code'   => 4006,
            ], //未登录
        ];

    }

    /**
     * @param $userId
     * @param $client
     * @param $status
     * @param $code
     * @dataProvider normalData
     *
     * 收益详情
     */
    public function testCurrentInterestHistory($userId, $client, $status, $code){

        $logic = new CurrentUserLogic();

        $result = $logic->getAppCurrentInterestList($userId);

        $this->assertEquals($result['status'], $status);
        $this->assertEquals($result['code'], $code);

    }

    /**
     * @param $userId
     * @param $client
     * @param $status
     * @param $code
     * @dataProvider normalData
     *
     * 项目详情
     */
    public function testGetCurrentDetail($userId, $client, $status, $code){

        $logic = new CurrentUserLogic();

        $result = $logic->getAppV4Detail($userId, $client);

        $this->assertEquals($result['status'], true);
        $this->assertEquals($result['code'], 200);

    }

    /**
     * @param $userId
     * @param $client
     * @param $status
     * @param $code
     * @dataProvider normalData
     *
     * 活期红包
     */
    public function testGetCurrentAbleBonus($userId, $client, $status, $code){

        $logic = new CurrentUserLogic();

        $result = $logic->getAppV4CurrentUserAbleBonus($userId, $client);

        $this->assertEquals($result['status'], $status);
        $this->assertEquals($result['code'], $code);

    }

    /**
     * @return array
     *
     * 活期投资测试用例的数据供给
     */
    public function currentInvestData(){

        return [
            [
                'user_id'   => 132519,
                'cash'      => 1,
                'client'    => 'ios',
                'bonus_id'  => '0',
                'status'    => true,
                'code'      => 200,
            ],

            [
                'user_id'   => 0,
                'cash'      => 1,
                'client'    => 'ios',
                'bonus_id'  => '0',
                'status'    => false,
                'code'      => 4006,
            ],

            [
                'user_id'   => 132519,
                'cash'      => 1,
                'client'    => 'ios',
                'bonus_id'  => '1',
                'status'    => false,
                'code'      => 4000,
                'key'       => 1,
            ],

            [
                'user_id'   => 132519,
                'cash'      => 200001,
                'client'    => 'ios',
                'bonus_id'  => '1',
                'status'    => false,
                'code'      => 4000,
            ],
            [
                'user_id'   => 132519,
                'cash'      => 0.01,
                'client'    => 'android',
                'bonus_id'  => '0',
                'status'    => false,
                'code'      => 4000,
            ],

        ];

    }

    /**
     * @param $userId
     * @param $cash
     * @param $client
     * @param $bonusId
     * @dataProvider currentInvestData
     * 活期转入
     */
    public function testCurrentInvest($userId, $cash, $client, $bonusId, $status, $code){

        $logic = new CurrentUserLogic();

        $result = $logic->currentAppV4Invest($userId, $cash, $client, $bonusId);

        $this->assertEquals($result['status'], $status);
        $this->assertEquals($result['code'], $code);

    }

    /**
     * @param $userId
     * @param $cash
     * @param $client
     * @param $bonusId
     * @param $status
     * @param $code
     *
     * @dataProvider currentInvestData
     *
     * 活期转入
     */
    public function testCurrentInvestOut($userId, $cash, $client, $bonusId, $status, $code){

        $logic = new CurrentUserLogic();

        $result = $logic->currentAppV4InvestOut($userId, $cash, $client);

        if(!$bonusId && $cash>1){
            $this->assertEquals($result['status'], $status);
            $this->assertEquals($result['code'], $code);
        }

        if($cash < 1){

            $this->assertEquals($result['status'], true);
            $this->assertEquals($result['code'], 200);

        }

    }

    /**
     * @return array
     */
    public function getInterestData(){

        return [
            [
                'cash' => 1000,
                'rate' => 7,
                'addRate' => 8,
            ],
            [
                'cash' => 0,
                'rate' => 7,
                'addRate' => 8,
            ]

        ];

    }

    /**
     * @param $cash
     * @param $rate
     * @param $addRate
     * @dataProvider getInterestData
     *
     * 活期预期收益
     */
    public function testCurrentGetInterest($cash, $rate, $addRate){


        $logic = new CurrentUserLogic();

        $result = $logic->currentAppV4GetInterest($cash, $rate, $addRate);

        $interest = round($cash / 365 * ($rate+$addRate) / 100,2);

        $this->assertEquals($result['status'], true);
        $this->assertEquals($result['data']['interest']+$result['data']['add_interest'], $interest);

    }

    /**
     * @return array
     *
     * 活期使用优惠券的数据供给
     *
     */
    public function usedUserBonusData(){

        return [
            [
                'user_id'   => 132519,
                'bonus_id'  => 2048063,
                'client'    => 'ios',
                'status'    => false,
            ],
            [
                'user_id'   => 132519,
                'bonus_id'  => 1,
                'client'    => 'ios',
                'status'    => false,
            ],
            [
                'user_id'   => 132519,
                'bonus_id'  => 1,
                'client'    => 'ios',
                'status'    => false,
            ],

        ];

    }

    /**
     * @param $userId
     * @param $bonusId
     * @param $client
     * @param $status
     *
     * @dataProvider usedUserBonusData
     *
     * 活期使用加息券
     */
    public function testCurrentUsedUserBonus($userId, $bonusId, $client, $status){

        $logic = new CurrentUserLogic();

        $result = $logic->currentAppV4UsedBonus($userId, $bonusId, $client);

        $this->assertEquals($result['status'], $status);

    }

}