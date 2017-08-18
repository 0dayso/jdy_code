<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/3/03
 * Time: 16:34 Pm
 * Desc: App4.0理财列表债权转让测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Logics\CreditAssign\CreditAssignLogic;

class AppV4AssignListTest extends TestCase
{

   /**
     * @desc 债权转让数据供给
     * @return array
     */
    public function assignData(){
        $assignArr = [
            [
            'assignData1' =>[
                'page'=>1,
                'size'=>6,
            ],
            'assignData2' =>[
                'page'=>1000,
                'size'=>6,
            ],
            ]
            ];
        return $assignArr;

    }


    /**
     * @desc 理财列表债权转让数据
     * @dataProvider assignData
     */
    public function testGetAssignData($assignData1, $assignData2){

        $creditAssignLogic = new CreditAssignLogic();

        $creditAssignData1 = $creditAssignLogic->assignAppV4Project($assignData1['page'], $assignData2['size']);

        $this->assertArrayHasKey('assign_principal', $creditAssignData1['data'][0]);

        $creditAssignData2 = $creditAssignLogic->assignAppV4Project($assignData2['page'], $assignData2['size']);

        $this->assertEquals('没有更多债转项目信息了', $creditAssignData2['msg']);

    }

    /**
     * @desc 测试债权转让数据格式化
     * @dataProvider assignData
     */
    public function testFormatAssignData($assignData1, $assignData2){

        $creditAssignLogic = new CreditAssignLogic();

        $creditAssignData = $creditAssignLogic->assignAppV4Project($assignData1['page'], $assignData2['size']);

        $formatAssignData = $creditAssignLogic->formatAppV4AssignProject($creditAssignData['data']);

        $this->assertArrayHasKey('assign_name', $formatAssignData[0]);

        $formatAssigniNullData = $creditAssignLogic->formatAppV4AssignProject([]);

        $this->assertEquals([], $formatAssigniNullData);

    }

}
