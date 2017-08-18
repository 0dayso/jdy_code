<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/4
 * Time: 下午5:14
 * Desc: 发布项目
 */

class PublishTest extends TestCase
{

    /**
     * @param $projectId
     * @dataProvider testProjectIds
     */
    public function testInvesting($projectId)
    {

        $logic = new \App\Http\Logics\Project\ProjectLogic();

        $result = $logic->updateStatusInvesting($projectId);

        $this->assertEquals($result['code'],\App\Http\Logics\Logic::CODE_SUCCESS);

    }

    public function testRefunding($projectId)
    {

        $logic = new \App\Http\Logics\Project\ProjectLogic();

        $result = $logic->updateStatusRefunding($projectId);

        $this->assertEquals($result['code'],\App\Http\Logics\Logic::CODE_SUCCESS);

    }


    public function testProjectIds()
    {

        return [
            [1],
            [2],
            [3]
        ];

    }

}