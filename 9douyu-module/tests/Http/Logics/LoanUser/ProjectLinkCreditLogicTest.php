<?php

/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/22
 * Time: 11:30 Am
 * @desc 测试借款人债权和项目关联逻辑
 */
namespace Tests\Http\Logics\LoanUser;

use App\Http\Models\Project\ProjectLinkCreditNewModel;
use App\Http\Dbs\Project\ProjectLinkCreditNewDb;

class ProjectLinkCreditLogicTest extends \TestCase
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
     * @desc 测试债权项目关联表添加的供给数据
     * @return array
     */
    public function dataCreditProject()
    {

        return [
            [
                'projectId' => 10020001,
                'creditId'  => 1,
            ]
            ];

    }


    /**
     * @desc 测试债权项目数据关联数据的添加
     * @dataProvider dataCreditProject
     * @return bool
     */
    public function testCreditLinkProjectAdd( $projectId, $creditId )
    {
        $projectLinkCreditModel =  new ProjectLinkCreditNewModel();

        $return  =  $projectLinkCreditModel->createProjectLinkCredit( $projectId, $creditId );

        $this->assertTrue( $return );
    }

    /**
     * @desc 测试通过项目ID获取债权的ID
     * @dataProvider dataCreditProject
     * @return bool
     */
    public function testGetCreditIdByProjectId( $projectId, $creditId )
    {


        $projectLinkCreditModel =  new ProjectLinkCreditNewModel();

        $return = $projectLinkCreditModel->getByProjectId( $projectId );

        $this->assertEquals( 1, $return );

    }

    /**
     * @desc 清除测试的数据
     * @dataProvider dataCreditProject
     * @return bool
     */
    public function testDelTestData( $projectId, $creditId )
    {
        $projectLinkCreditDb = new ProjectLinkCreditNewDb();

        $projectLinkCreditDb->where( 'project_id', $projectId )->delete();
    }
}
