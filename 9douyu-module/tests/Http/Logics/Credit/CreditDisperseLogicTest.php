<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/3/21
 * Time: 下午2:55
 * @desc 测试新债权相关的数据操作
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Models\Credit\CreditDisperseModel;
use App\Http\Logics\Credit\CreditDisperseLogic;
use App\Http\Logics\Credit\CreditLogic;

class CreditDisperseLogicTest extends TestCase
{

    /**
     * @desc 手动录入债权的数据供给
     * @author linguanghui
     * @return array
     */
    public function dataHandleDisperse(){

        $attributes = [
            [
            'disperse_data'=>[
               [
                'credit_name'     => '活期宝1',
                'amounts'  => '49999',
                'interest_rate'   => '10',
                'loan_deadline'   => '30',
                'status'          => '100',
                'start_time'      => '2017-03-21',
                'end_time'        => date( 'Y-m-d', strtotime('-1 days') ),
                'loan_realname'   => '林**',
                'loan_idcard'     => '3823**************29',
                'contract_no'     => 'HQB-201703010009',
            ],
            [
                'credit_name'     => '活期宝2',
                'amounts'  => '49999',
                'interest_rate'   => '10',
                'loan_deadline'   => '30',
                'status'          => '100',
                'start_time'      => '2017-03-21',
                'end_time'        => date( 'Y-m-d', strtotime('+1 days') ),
                'loan_realname'   => '林**',
                'loan_idcard'     => '3823**************29',
                'contract_no'     => 'HQB-201703010009',
            ],

            ]
            ]
            ];

        return $attributes;
    }

    /**
     * @desc 测试添加新的分散的债权
     * @dataProvider dataHandleDisperse
     * @author linguanghui
     */
    public function testAddDisperseCredit($disperse_data){

        $creditDisperseLogic  = new CreditDisperseLogic();

        $creditDisperseData = $creditDisperseLogic->doCreate( $disperse_data[0] );

        $this->assertContains(['status','code','msg','data'],$creditDisperseData);

        $this->assertEquals('200', $creditDisperseData['code']);

        $this->assertCount(1,$creditDisperseData['data']);
    }

    /**
     * @desc 测试发布债权
     * @dataProvider dataHandleDisperse
     * @author linguanghui
     */
    public function testPublishCredit( $disperse_data )
    {
        $creditDisperseLogic  = new CreditDisperseLogic();

        $creditDisperseData = $creditDisperseLogic->doCreate( $disperse_data[1] );

        $return = $creditDisperseLogic->doCreditOnline ( $creditDisperseData['data'][0] );

        $this->assertEquals( true, $return['status'] );
    }

    /**
     * @desc 测试设置过期债权的状态
     * @return mixed
     */
    public function testSetCreditExpire()
    {

        $creditDisperseModel  = new CreditDisperseModel();

        $result = $creditDisperseModel->setCreditExpireStatus( );

        $this->assertTrue( $result );
    }

    /**
     * @desc 测试初始化债权数据
     * @author linguanghui
     * @return mixed
     */
    public function testInitCreditData(){

        $creditDisperseModel  = new CreditDisperseModel();

        $result = $creditDisperseModel->initCreditData();


        $this->assertTrue($result);

    }

    /**
     * @desc 测试获取可用的债权列表数据
     * @author linguanghui
     * @return array
     */
    public function testGetAbleCreditData()
    {

        $creditDisperseLogic = new CreditDisperseLogic();

        $ableCreditList = $creditDisperseLogic->getAbleCreditList();

        $this->assertContains( ['status', 'code', 'msg','data'], $ableCreditList );

        $ableCreditData = $ableCreditList['data'];

        $this->assertGreaterThan( 0, $ableCreditData );

        $this->assertArrayHasKey( 'usable_amount', $ableCreditData[0] );
    }




}
