<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/3/03
 * Time: 10:34 Am
 * Desc: App4.0理财列表零钱计划测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Logics\Project\CurrentLogic;

class AppV4CurrentListLogicTest extends TestCase
{
    /**
     * @desc 活期数据供给
     * @return array
     */
    public function currentData(){


        $currentData = [
            [
            'current_data' => [
                'user_id' => '258082',//可改成自己给自己发一个新手加息券
                'client'  => 'ios',
                ],
            ]
            ];
        return $currentData;
    }

    /**
     * @desc 测试理财列表零钱计划
     * @dataProvider currentData
     */
    public function testCurrentListData($current_data){

        $currentLogic = new CurrentLogic();

        $currentList  = $currentLogic->getAppHomeV4Current($current_data['user_id'],$current_data['client']);

        //格式化活期列表数据
        $currentListData  = $currentLogic->formatAppV4ListCurrentData($currentList);

        //测试格式化是否包含key
        $this->assertArrayHasKey('left_amount_note', $currentListData);

        $currentListData1  = $currentLogic->formatAppV4ListCurrentData([]);

        $this->assertEquals([], $currentListData1);

    }
}
