<?php
/**
 * Created by PhpStorm.
 * User: liu.qiuhui
 * Date: 17/4/24
 * Time: 16:34 Pm
 */


class ProjectTest extends TestCase
{

   /**
     * @desc 债权转让数据供给
     * @return array
     */
    public function projectIdData(){

        return [
            ['project_id' => 3474],
            ['project_id' => 3475],
        ];

    }

    /**
     * @param $projectId
     * @dataProvider  projectIdData
     */
    public function testDoPublish( $projectId ){

        $logic = new \App\Http\Logics\Project\ProjectLogic();

        $result = $logic->doPublishCreditToLoanUser($projectId);

        $this->assertEquals(true, $result['status']);

    }


}
