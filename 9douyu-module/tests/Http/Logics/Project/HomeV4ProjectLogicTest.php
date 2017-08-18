<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/3/02
 * Time: 18:00 Pm
 * Desc: App4.0首页零钱计划和定期项目测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\Project\CurrentLogic;

class HomeV4ProjectLogicTest extends TestCase
{

    /**
     * @desc 活期数据供给
     * @return array
     */
    public function currentData(){


        $currentData = [
            [
            'current_data1' => [
                'user_id' => '258082',//可改成自己给自己发一个新手加息券
                'client'  => 'ios',
                ],
            'current_data2' => [
                'user_id'  => 0,
                'client'   => 'ios',
                ],
            ]
            ];
        return $currentData;
    }

    /**
     * @desc 定期理财测试供给数据
     */
    public function projectData(){

        $projectData = [
            [
            'project_data1' => [
                'jax' => [
                  'id' => '3116',
                  'name' => '九安心',
                  'status' => 130,
                ],

                'one' => [
                  'id' => '3116',
                  'name' => '九省心1',
                  'status' => 130,
                ],
                'three' => [
                  'id' => '3116',
                  'name' => '九省心3',
                  'status' => 130,
                ],
                'six' => [
                  'id' => '3116',
                  'name' => '九省心6',
                  'status' => 130,
                ],
                'twelve' => [
                  'id' => '3116',
                  'name' => '九省心12',
                  'status' => 150,
                ],

                ],
              'project_data2' => [
                'jax' => [
                  'id' => '3116',
                  'name' => '九安心',
                  'status' => 150,
                ],

                'one' => [
                  'id' => '3116',
                  'name' => '九省心1',
                  'status' => 150,
                ],
                'three' => [
                  'id' => '3116',
                  'name' => '九省心3',
                  'status' => 150,
                ],
                'six' => [
                  'id' => '3116',
                  'name' => '九省心6',
                  'status' => 150,
                ],
                'twelve' => [
                  'id' => '3116',
                  'name' => '九省心12',
                  'status' => 150,
                ],

                ],

            ]
            ];
        return $projectData;

    }

    /**
     * @desc 测试appV4首页活期理财数据
     * @dataProvider currentData
     */
    public function testCurrentHomeData($currentData1, $currentData2){

        $currentLogic = new CurrentLogic();

        //用户登陆后
        $currentDetail1 = $currentLogic->getAppHomeV4Current($currentData1['user_id'], $currentData1['client']);

        $this->assertArrayHasKey('bonus_rate', $currentDetail1);

        $this->assertEquals(0, $currentDetail1['is_new_user_show']);

        //测试user_id ＝0 用户未登录
        $currentDetail2 = $currentLogic->getAppHomeV4Current($currentData2['user_id'], $currentData2['client']);

        $this->assertEquals(1, $currentDetail2['is_new_user_show']);

        $this->assertArrayHasKey('bonus_rate', $currentDetail1);

        //活期数据格式化测试
        $formatCurrentDetail1  = $currentLogic->formatAppHomeV4CurrentData($currentDetail1);

        //断言格式化后的的数据包含money_note的key
        $this->assertArrayHasKey('money_note', $formatCurrentDetail1);

        $formatCurrentDetail2  = $currentLogic->formatAppHomeV4CurrentData([]);

        $this->assertEquals([], $formatCurrentDetail2);
    }


    /**
     * @desc AppV4首页定期理财测试
     * @dataProvider projectData
     */
    public function testProjectHomeData($project_data1, $project_data2){

       $projectLogic = new ProjectLogic();

       $projectArr = $projectLogic->getIndexProjectPack();

       //断言含有九安心key
       $this->assertArrayHasKey('jax', $projectArr);

       //项目都可投断言取一月期
       $projectData1 = $projectLogic->getAppV4HomeProject($project_data1);

       $this->assertArrayHasKey('one', $projectData1);

       //全部售罄取三月期
       $projectData2 = $projectLogic->getAppV4HomeProject($project_data2);

       $this->assertArrayHasKey('three', $projectData2);

       //传入首页项目包为空断言返回为空
       $projectData3 = $projectLogic->getAppV4HomeProject([]);

       $this->assertEquals([], $projectData3);

       //断言格式化数据返回
       $formatProjectData1 = $projectLogic->formatAppV4HomeProjectDetail($projectLogic->getAppV4HomeProject($projectArr));

       $this->assertArrayHasKey('except_year_rate', $formatProjectData1);

       //传入空值测试格式化定期返回项目为空
       $formatProjectData2 = $projectLogic->formatAppV4HomeProjectDetail([]);

       $this->assertEquals([], $formatProjectData2);



    }

}
