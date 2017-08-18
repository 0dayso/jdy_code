<?php

/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/12/12
 * Time: 16:17
 * Desc:
 */
class ProjectFinishTest extends TestCase
{
    /**
     * @desc 数据供给
     * @return array
     */
    public function dataProvider(){

        $data = [
            0 => 88,
            1 => 104,
            2 => 105,
            3 => 8,
            4 => 12,
            5 => 117,
        ];
        return [[
            'porject_ids' => $data
        ]];
    }

    /**
     * @param $project_ids
     * @dataProvider dataProvider
     */
    public function testFinishProject($project_ids){

        $endTime  = \App\Tools\ToolTime::dbDate();

        $projectLogic = new \App\Http\Logics\Project\ProjectLogic();

        $projectLogic->doProjectEnd($project_ids, $endTime);

    }


}