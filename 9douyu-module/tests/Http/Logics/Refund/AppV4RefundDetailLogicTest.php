<?php
/**
 * Created by Vim
 * User: linguanghui
 * Date: 17/3/10
 * Time: 14:54 Pm
 * Desc: App4.0用户中心－回款详情相关测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Logics\Project\RefundRecordLogic;

class AppV4RefundDetailLogicTest extends TestCase
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
     * @desc 用户回款明细
     * @return array
     */
    public function refundDetailData(){

        $refundDetail =[
            [
            'id1' => 0,
            'id2' => 6336781,
            'id3' => 6336782,
            ]
            ];
        return $refundDetail;
    }

    /**
     * @desc 测试获取用户回款计划
     * @dataProvider refundDetailData
     */
    public function testGetRefundDetail($data1,$data2,$data3){

        $refundRecordLogic = new RefundRecordLogic();

        $refundDetail1  = $refundRecordLogic->getRefundRecordById($data1);

        $this->assertEquals([], $refundDetail1);


        $refundDetail2  = $refundRecordLogic->getRefundRecordById($data2);

        $this->assertEquals(1, count($refundDetail2));

        $this->assertArrayHasKey('product_line_note', $refundDetail2[0]);

        //测试最后一期有加息券回款
        $refundDetail3  = $refundRecordLogic->getRefundRecordById($data3);

        $this->assertArrayHasKey('bonus_cash', $refundDetail3[0]);

    }

    /**
     * @desc 测试格式化用户回款详情
     * @dataProvider refundDetailData
     */
    public function testFormatRefundDetail($data1,$data2,$data3){

        $refundRecordLogic = new RefundRecordLogic();

        $formatRefundDetail = $refundRecordLogic->formatAppV4RecordDetail([]);

        $this->assertEquals([], $formatRefundDetail);

        //不包含红包的利息
        $refundDetail2  = $refundRecordLogic->getRefundRecordById($data2);

        $formatRefundDetail2 = $refundRecordLogic->formatAppV4RecordDetail($refundDetail2);

        $this->assertArrayNotHasKey('bonus_award_note', $formatRefundDetail2);

        //包含红包的利息
        $refundDetail3  = $refundRecordLogic->getRefundRecordById($data3);

        $formatRefundDetail3 = $refundRecordLogic->formatAppV4RecordDetail($refundDetail3);

        $this->assertArrayHasKey('bonus_award_note', $formatRefundDetail3);

    }
}
