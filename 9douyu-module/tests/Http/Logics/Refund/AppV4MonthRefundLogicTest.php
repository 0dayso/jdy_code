<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/3/09
 * Time: 13:53 Pm
 * Desc: App4.0用户中心－本月全部回款相关测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Logics\Project\RefundRecordLogic;

class AppV4MonthRefundLogicTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * @desc 获取当月回款数据供给
     * @return array
     */
    public function monthRefundData(){

        $monthRefundData = [
            [
            'month_data1'  =>[
                 'user_id' => 258082,
                 'month'   => '2017-01',
              ],
            ]
            ];
        return $monthRefundData;
    }

    /**
     * @desc 测试本月全部回款数据获取
     * @dataProvider monthRefundData
     */
    public function testGetMonthRefundList($month_data1){

        $refundRecordLogic = new RefundRecordLogic();

        //本月回款数据
        $monthRefundRecordData = $refundRecordLogic->refundPlanByDate($month_data1['user_id'], $month_data1['month']);

        $this->assertTrue($monthRefundRecordData['status']);
        $this->assertArrayHasKey('refund', $monthRefundRecordData['data']);
    }

    /**
     * @desc 格式化本月全部回款数据测试
     * @dataProvider monthRefundData
     */
    public function testFormatMonthRefundData($month_data1){

        $refundRecordLogic = new RefundRecordLogic();

        //本月回款数据
        $monthRefundRecordData = $refundRecordLogic->refundPlanByDate($month_data1['user_id'], $month_data1['month']);

        $formatMonthData1 = $refundRecordLogic->formatAppV4MonthRefundList([]);

        $this->assertEquals([], $formatMonthData1);

        $formatMonthData2 = $refundRecordLogic->formatAppV4MonthRefundList($monthRefundRecordData, $month_data1['month']);

        #print_r($formatMonthData2);exit;

        //验证是否包含某个key
        $this->assertArrayHasKey('refund', $formatMonthData2);
        $this->assertArrayHasKey('refunded', $formatMonthData2);

        $this->assertArrayHasKey('refund_principal_note', $formatMonthData2['refund']);
        $this->assertArrayHasKey('refunded_principal_note', $formatMonthData2['refunded']);

        $this->assertContains('待回款本金', $formatMonthData2['refund']);
    }
}
