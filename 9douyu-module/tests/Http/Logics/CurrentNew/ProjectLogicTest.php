<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/21
 * Time: 下午4:02
 */

namespace Tests\Http\Logics\CurrentNew;


use App\Http\Logics\CurrentNew\ProjectLogic;

class ProjectLogicTest extends \TestCase
{

    public function dataCreateProject(){

        $nameStr = date('ymd', time());

        return [
            [
                'project_name' => '新版零钱计划第'.$nameStr.'001期',
                'total_amount' => rand(1,30)*10000,
            ],
            [
                'project_name' => '新版零钱计划第'.$nameStr.'002期',
                'total_amount' => rand(1,30)*10000,
            ],
            [
                'project_name' => '新版零钱计划第'.$nameStr.'003期',
                'total_amount' => rand(1,30)*10000,
            ],
            [
                'project_name' => '新版零钱计划第'.$nameStr.'004期',
                'total_amount' => rand(1,30)*10000,
            ],
            [
                'project_name' => '新版零钱计划第'.$nameStr.'005期',
                'total_amount' => rand(1,30)*10000,
            ],
        ];

    }

    /**
     * @param $projectName
     * @param $totalAmount
     * @dataProvider dataCreateProject
     */
    public function testCreateProject($projectName, $totalAmount){

        $logic = new ProjectLogic();

        $result = $logic->create($projectName, $totalAmount);

        $this->assertEquals($result['status'] , 200);

    }

    /**
     * 项目列表
     */
    public function testGetAdminProjectList(){

        $logic = new ProjectLogic();

        $result = $logic->getAdminProjectList(1, 20);

        $this->assertNotEmpty($result['data']);

    }

}