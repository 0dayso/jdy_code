<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/3/08
 * Time: 13:04 Pm
 * Desc: App4.0用户中心－回款相关测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Logics\Project\RefundRecordLogic;

class AppV4RefundLogicTest extends TestCase
{

    /**
     * @desc 本月回款数据测试供给函数
     * @return array
     */
    public function monthRefundRecordData(){

        $refundRecordData = [
            [
            'data1' =>[
                'user_id'=>258082,
                'month'=>'2016-01',
            ],
            ]
            ];

        return $refundRecordData;
    }

    /**
     * @desc 当日回款数据测试供给函数
     * @return array
     */
    public function dayRefundRecordData(){

        $refundRecordData = [
            [
            'data2' =>[
                'user_id'=>258082,
                'date'=>'2017-01-29',
            ],
            ]
            ];

        return $refundRecordData;
    }

    /**
     * @desc 测试获取本月的回款记录的逻辑
     * @dataProvider monthRefundRecordData
     */
    public function testMonthRefundRecord($data1){

        $refundRecordLogic = new RefundRecordLogic();

        $refundRecordData = $refundRecordLogic->refundPlanByDate($data1['user_id'], $data1['month']);

        $this->assertArrayHasKey('refunded', $refundRecordData['data']);

        $this->assertArrayHasKey('refund', $refundRecordData['data']);
    }

    /**
     * @desc 测试获取本月回款金额数据统计功能
     * @dataProvider monthRefundRecordData
     */
    public function testRefundMonthAmount($data1){

        $refundRecordLogic = new RefundRecordLogic();

        $refundRecordData = $refundRecordLogic->refundPlanByDate($data1['user_id'], $data1['month']);

        $this->assertTrue($refundRecordData['status']);

        $refundMonthAmount  = $refundRecordLogic->getRefundMonthAmount($refundRecordData['data']); //获取本月回款金额统计数据

        //测试本月待回款的本金＋利息＝总金额
        $this->assertEquals($refundMonthAmount['refund_cash'],($refundMonthAmount['refund_interest']+$refundMonthAmount['refund_principal']));

        //断言本月已回款的本金＋利息＝总金额
        $this->assertEquals($refundMonthAmount['refunded_cash'],($refundMonthAmount['refunded_interest']+$refundMonthAmount['refunded_principal']));


        //测试格式化金额统计数据
        $monthAmountData = $refundRecordLogic->formatAppV4RefundAmountData([]);

        $this->assertEquals([], $monthAmountData);

        $monthAmountData1 = $refundRecordLogic->formatAppV4RefundAmountData($refundMonthAmount);

        $this->assertArrayHasKey('refund_cash_note', $monthAmountData1);

    }

    /**
     * @desc 获取回款日期格式化
     * @dataProvider monthRefundRecordData
     */
    public function testRefundDate($data1){

        $refundRecordLogic = new RefundRecordLogic();

        $refundRecordData = $refundRecordLogic->refundPlanByDate($data1['user_id'], $data1['month']);

        $refundDate = $refundRecordLogic->formatAppV4RefundDate([]);

        $this->assertEquals([], $refundDate);


        $refundDate1 = $refundRecordLogic->formatAppV4RefundDate($refundRecordData['data']);

        $this->assertArrayHasKey('refunded_date', $refundDate1);

    }

    /**
     * @desc 测试当天回款记录数据列表
     * @dataProvider dayRefundRecordData
     */
    public function testDayRefundList($data2){

        $refundRecordLogic = new RefundRecordLogic();

        //测试获取用户当日记录
        $dayRefundList = $refundRecordLogic->getRefundListByDay($data2['user_id'], $data2['date']);

        $this->assertArrayHasKey('periods', $dayRefundList[0]);
        $this->assertArrayHasKey('current_periods', $dayRefundList[0]);

        //测试当日回款记录格式化

        $formatDayList = $refundRecordLogic->formatAppV4RefundDayList([]);

        $this->assertEquals([], $formatDayList);

        $formatList1 = $refundRecordLogic->formatAppV4RefundDayList($dayRefundList);

        $this->assertArrayHasKey('periods_note', $formatList1[0]);
    }
}
