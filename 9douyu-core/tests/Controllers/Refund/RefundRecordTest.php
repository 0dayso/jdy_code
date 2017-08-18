<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/12
 * Time: 下午4:44
 * Desc: 回款记录相关
 */

class RefundRecordTest extends TestCase
{

    /**
     * @param $userId
     * @param $sDate
     * @param $eDate
     * @dataProvider userList
     */
    public function testRefundedList($userId, $sDate, $eDate)
    {

        $logic = new \App\Http\Logics\Refund\RefundRecordLogic();

        $result = $logic->getRefundedList($userId, $sDate, $eDate);

        $this->assertEquals(\App\Http\Logics\Logic::CODE_SUCCESS, $result['code'], $result['msg']);


    }

    /**
     * @param $userId
     * @param $sDate
     * @param $eDate
     * @dataProvider userList
     */
    public function testRefundingList($userId, $sDate, $eDate)
    {

        $logic = new \App\Http\Logics\Refund\RefundRecordLogic();

        $result = $logic->getRefundingList($userId, $sDate, $eDate);

        $this->assertEquals(\App\Http\Logics\Logic::CODE_SUCCESS, $result['code'] , $result['msg']);


    }

    /**
     * @return array
     * @desc 返回参数说明：userid ，起始日期多代表本月的回款
     */
    public function userList()
    {

        return [
            [1, '2016-01-01', '2016-01-31'],
            [1, '2016-03-01', '2016-03-31'],
            [1, '2016-06-01', '2016-06-30'],
            [1, '2016-07-01', '2016-07-31'],
        ];

    }

}