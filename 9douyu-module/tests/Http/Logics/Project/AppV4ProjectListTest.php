<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/3/03
 * Time: 10:34 Am
 * Desc: App4.0理财列表定期理财测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Project\ProjectLogic;

class AppV4ProjectListTest extends TestCase
{

    /**
     * @desc 定期项目数据供给
     * @return array
     */
    public function projectData(){
        $projectArr = [
            [
            'projectData1' =>[
                'project_line'=>[100,200],
                'page'=>1,
                'size'=>6,
                'status'=>[130,150,160],
            ],
            'projectData2' =>[
                'project_line'=>[100,200],
                'page'=>1000,
                'size'=>6,
                'status'=>[130,150,160],
            ],
            ]
            ];
        return $projectArr;

    }

    /**
     * @desc 理财项目列表的数据获取测试
     * @dataProvider projectData
     */
    public function testGetProjectListData($projectData1, $projectData2){

        $projectLogic = new ProjectLogic();

        $projectList1 = $projectLogic->getAppV4ProjectList($projectData1['project_line'],$projectData1['page'], $projectData1['size'], $projectData1['status']);

        $this->assertArrayHasKey('except_year_rate', $projectList1[0]);

        $projectList2 = $projectLogic->getAppV4ProjectList($projectData2['project_line'],$projectData2['page'], $projectData2['size'], $projectData2['status']);

        $this->assertEquals([], $projectList2['list']);

    }


    /**
     * @desc 理财项目定期列表格式化测试
     * @dataProvider projectData
     */
    public function testFormatListData($projectData1, $projectData2){

        $projectLogic = new ProjectLogic();

        $data = \App\Http\Models\Common\CoreApi\ProjectModel::getProjectList($projectData1['project_line'],$projectData1['page'], $projectData1['size'], $projectData1['status']);

        if(array_key_exists('total',$data)){
            unset($data['total']);
        }

        $formatData1 = $projectLogic->formatAppV4Project($data['list']);

        $this->assertArrayHasKey('activity_url', $formatData1[0]);

        $formatData2 = $projectLogic->formatAppV4Project([]);

        $this->assertEquals([], $formatData2);

    }

}
