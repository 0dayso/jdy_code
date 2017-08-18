<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/08
 * Time: 下午4:55
 * Desc: 测试债权用户的匹配流程
 */


use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Models\Credit\CreditDisperseModel;
use App\Http\Logics\Credit\CreditDisperseLogic;
use App\Http\Logics\Credit\CreditLogic;
use App\Http\Models\Credit\UserCreditModel;

class CreditAccountMatchTest extends TestCase
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
     * @desc 债权匹配活期账户数据供给
     * @return array
     */
    public function dataCreditMatch(){

        $accounts  = [
            [
            'user_account' => [
                [
                'user_id'=> 258082,
                'after_balance' => 8045.45,
                ],
                [
                'user_id'=> 25342,
                'after_balance' => 6916.35,
                ],
            ],
             'able_crdit'=> [
                [
                'id'  => 1,
                'usable_amount'  => 1000,
                ],
                [
                'id'  => 2,
                'usable_amount'  => 2032,
                ],
                [
                'id'  => 3,
                'usable_amount'  => 5000,
                ],
                [
                'id'  => 4,
                'usable_amount'  => 4024,
                ],
                [
                'id'  => 5,
                'usable_amount'  => 3000,
                ],
            ]

            ]
            ];

        return $accounts;

    }


    /**
     * @desc 测试分割可用的债权数据
     * @dataProvider dataCreditMatch
     * @return array
     */
    public function testCutAbleCreditData( $user_count, $able_credit )
    {

        $testEmpty   = CreditLogic::doCutAbleCreditData( [] );

        $this->assertEquals( [], $testEmpty );

        $cutCreditResult = CreditLogic::doCutAbleCreditData( $able_credit );

        $this->assertArrayHasKey( 'hundred', $cutCreditResult );

        $this->assertCount( 150, $cutCreditResult['hundred'] );

        $this->assertCount( 56, $cutCreditResult['one'] );

    }

    /**
     * @desc 测试分割后的债权数据加入缓存list 测试相关数据
     * @dataProvider dataCreditMatch
     */
    public function testPutCutCreditCache( $user_account, $able_credit )
    {

        $cutCreditResult = CreditLogic::doCutAbleCreditData( $able_credit );

        //测试添加缓存数据为空
        $cacheEmpty = CreditLogic::pushTheCutCreditCache( [] );

        $this->assertFalse( $cacheEmpty );

        $cacheTrue = CreditLogic::pushTheCutCreditCache( $cutCreditResult, true );

        $this->assertTrue( $cacheTrue );

        //测试获取缓存数据传入空的key值
        $getCacheEmpty  = CreditLogic::getCutCreditCache( '' );

        $this->assertEquals( '', $getCacheEmpty );

        $getCacheHundred = CreditLogic::getCutCreditCache( 'CUT_CREDIT_HUNDRED' );

        $this->assertCount( 150, $getCacheHundred );

        $getCacheOne    = CreditLogic::getCutCreditCache( 'CUT_CREDIT_ONE' );

        $this->assertCount( 56, $getCacheOne );

    }



    /**
     * @desc 测试新的用户债权匹配操作
     * @dataProvider dataCreditMatch
     */
    public function testCutCreditUserMatch( $user_account, $able_credit )
    {
        $creditDisperseLogic = new CreditDisperseLogic();

        $investafter_balance = $leftafter_balance = $creditAmount = $userafter_balance = 0 ;

        $userId = $user_account[0]['user_id'];

        $testInvestEmpty = $creditDisperseLogic->doAccountCreditMatch( [] );


        $this->assertEquals( '匹配原始数据为空', $testInvestEmpty['msg'] );

        $investResult = $creditDisperseLogic->doAccountCreditMatch( $user_account );

        $this->assertCount( 2, $investResult );

        //检测匹配金额是否相等
        foreach( $investResult[$userId]  as $value )
        {
            $investafter_balance += $value['usable_amount'];
        }

        $this->assertEquals( (int)$user_account[0]['after_balance'], $investafter_balance );


        //测试剩余金额

        //用户总金额
        foreach( $user_account as $val)
        {
            $userafter_balance += (int)$val['after_balance'];
        }

        //债权总金额
        foreach( $able_credit as $val2 )
        {
            $creditAmount += $val2['usable_amount'];
        }

        $data = $creditDisperseLogic->getUnMatchList();

        $wait = $creditDisperseLogic->getWaitUpdateList( $data );

        foreach( $wait as $key => $val )
        {
            $leftafter_balance += $val['usable_amount'];
        }

        $this->assertEquals( $leftafter_balance, ($creditAmount - $userafter_balance) );

        $getLeftCacheOne    = CreditLogic::getCutCreditCache( 'CUT_CREDIT_ONE' );

        $this->assertCount( 95,  $getLeftCacheOne );

        //执行匹配成功后的数据处理
        $creditDisperseLogic->formatMatchInvestData( $investResult );

    }
}
