<?php
/**
 * Created by PhpStorm.
 * User: scofie
 * Date: 17/05/09
 * Time: 下午19:34
 */

namespace Test\Http\Controllers\Activity;
use App\Http\Logics\Activity\Statistics\ActivityStatisticsLogic;

class ActivityStatistics extends \TestCase{

    /**
     * @desc 正确的数据
     * @return array
     */
    public function dataRightData(){

        return [
            [
             'data'=> [
                        'user_id'  =>   '1425545',
                        'act_id'   =>   '105',
                        'invest_id' =>  '315450',
                        'cash'     =>   '8000' ,
                    ]
                ]
        ];
    }

    /**
     * @return array
     * @desc 投资记录不存在
     */
     public function dataNotInvestRecord()
     {
         return [
                    [
             'data'=>
                 //投资id 不存在
                 [
                     'user_id'  =>   '1425545',
                     'act_id'   =>   '105',
                     'invest_id' =>  '222222',
                     'cash'     =>   '8000' ,
                 ],
             ]
         ];
     }

    /**
     * @return array
     * @desc 用户不一致
     */
    public function dataUserIdError()
    {
        return [
            [
                'data'=>
                    //用户id 不一致
                    [
                        'user_id'  =>   '1425540',
                        'act_id'   =>   '105',
                        'invest_id' =>  '315450',
                        'cash'     =>   '8000' ,
                    ],
            ]
        ];
    }

    /**
     * @return array
     * @desc 活动不存在
     */
    public function dataActIdEmpty()
    {
        return [
            [
                'data'=>
                    [
                        'user_id'  =>   '1425545',
                        'act_id'   =>   '200',
                        'invest_id' =>  '315450',
                        'cash'     =>   '8000' ,
                    ],
            ]
        ];
    }
    /**
     * @return array
     * @desc 金额不一致
     */
    public function dataCashError()
    {
        return [
            [
                'data'=>
                    [
                        'user_id'  =>   '1425545',
                        'act_id'   =>   '114',
                        'invest_id' =>  '315450',
                        'cash'     =>   '800' ,
                    ],
            ]
        ];
    }

    /**
     * @desc 正确的操作
     * @dataProvider dataRightData
     */
    public function testInRecordRight($data){

        $logic   = new ActivityStatisticsLogic();

        $res = $logic->checkInRecord($data);

        print_r($res);

        $this->assertEquals('200', $res['code']);
    }
    /**
     * @desc 投资记录不存在
     * @dataProvider dataNotInvestRecord
     */
    public function testNotInvestRecord($data){

        $logic   = new ActivityStatisticsLogic();

        $res = $logic->checkInRecord($data);

        print_r($res);

        $this->assertEquals('200', $res['code']);
    }
    /**
     * @desc 用户不一致
     * @dataProvider dataUserIdError
     */
    public function testUserIdError($data){

        $logic   = new ActivityStatisticsLogic();

        $res = $logic->checkInRecord($data);

        print_r($res);

        $this->assertEquals('200', $res['code']);
    }
    /**
     * @desc 活动不存在
     * @dataProvider dataActIdEmpty
     */
    public function testActIdEmpty($data){

        $logic   = new ActivityStatisticsLogic();

        $res = $logic->checkInRecord($data);

        print_r($res);

        $this->assertEquals('200', $res['code']);
    }
    /**
     * @desc 金额不一致
     * @dataProvider dataCashError
     */
    public function testCashError($data){

        $logic   = new ActivityStatisticsLogic();

        $res = $logic->checkInRecord($data);

        print_r($res);

        $this->assertEquals('200', $res['code']);
    }

}
