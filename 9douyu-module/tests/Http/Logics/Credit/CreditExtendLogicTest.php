<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/5/17
 * Time: 11:14 Am
 * Desc: 债权扩展表处理的测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Dbs\Credit\CreditAllDb;
use App\Http\Dbs\Credit\CreditExtendDb;
use App\Http\Logics\Credit\CreditExtendLogic;

class CreditExtendLogicTest extends TestCase
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

    public function creditIdData()
    {
        return [
            [ true,  1000000 ],
            [ true, 1000001 ],
            ];
    }

    /**
     * @desc 测试更新扩展信息
     * @dataProvider creditIdData
     */
    public function testUpdateExtend( $status, $creditId )
    {
        $data = CreditAllDb::where( 'id', $creditId )
            ->get()
            ->toArray();

        $extendLogic = new CreditExtendLogic();

        $attributes = $data[0];

        $result = $extendLogic->doUpdate( $attributes );

        $this->assertEquals( $status, $result['status'] );
    }

    /**
     * @desc 清除测试数据
     * @dataProvider creditIdData
     */
    public function testTruncateData( $status, $creditId )
    {
        $result = CreditAllDb::where( 'id', $creditId )
            ->delete();
        CreditExtendDb::where( 'credit_id', $creditId )
            ->delete();
    }
}
