<?php

namespace Tests\AppApi\Logic;

use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Project\ProjectAppLogic;

class ProjectTest extends \TestCase
{


    /**
     * 项目详情接口
     */
    public function testProjectDetail(){

        $projectAppLogic = new ProjectAppLogic();

        $result = $projectAppLogic->getAppV4ProjectDetail(3466, 132519);

        $this->assertEquals($result['status'], 200);

        try{

            $result = $projectAppLogic->getAppV4ProjectDetail(0, 132519);

            $this->assertEquals($result['status'], false);

        }catch(\Exception $e){

            $this->assertEquals($e->getCode(), 101011004);

        }

    }


    public function getProjectInvestRecordsData(){

        return [
            ['3466', 1],
            ['3465', 1],
            [0, 1],
        ];

    }

    /**
     * @param $projectId
     * @param $p
     * 获取投资记录
     *
     * @dataProvider getProjectInvestRecordsData
     */
    public function testGetProjectInvestRecords($projectId , $p){

        $projectAppLogic = new ProjectAppLogic();

        $result = $projectAppLogic->getAppV4ProjectInvestRecords($projectId, $p);

        $this->assertEquals($result['status'], 200);

    }

    public function getProjectRefundRecordData(){

        return [

            ['2'],
            ['3'],
            ['3466']

        ];

    }

    /**
     * @param $projectId
     * 回款计划
     * @dataProvider getProjectRefundRecordData
     */
    public function testGetProjectRefundRecord( $projectId ){

        $projectAppLogic = new ProjectAppLogic();

        $result = $projectAppLogic->getAppV4ProjectRefundRecord( $projectId );

        $this->assertEquals($result['status'], 200);

    }


    public function getAppV4ProjectAbleUserBonusData(){

        return [

            ['132519', 3466, 'ios'],
            ['132519', 3466, 'android'],
            ['132519', 2992, 'ios'],

        ];

    }

    /**
     * @param $userId
     * @param $projectId
     * @param $client
     *
     * @dataProvider getAppV4ProjectAbleUserBonusData
     *
     * 用户优惠券
     */

    public function testGetAppV4ProjectAbleUserBonus($userId, $projectId, $client){

        $projectAppLogic = new ProjectAppLogic();

        $result = $projectAppLogic->getAppV4ProjectAbleUserBonus( $userId, $projectId, $client );

        $this->assertEquals($result['status'], 200);

    }

    public function getAppV4ProjectInvestData(){

        return [

            ['132519', 3466, 100, 'qwe123', 0, 'ios'],
            ['132519', 3466, 100, 'qwe123', 0, 'ios'],
            ['132519', 3466, 100, 'qwe123', 0, 'android'],

        ];

    }

    /**
     * @param $userId
     * @param $projectId
     * @param $cash
     * @param $tradePassword
     * @param $userBonusId
     * @param $source
     *
     * @dataProvider getAppV4ProjectInvestData
     */
    public function testAppV4ProjectInvest($userId,$projectId,$cash,$tradePassword,$userBonusId,$source){

        $termLogic          = new TermLogic();

        $invest = $termLogic->doInvest($userId,$projectId,$cash,$tradePassword,$userBonusId,$source);

        $this->assertEquals($invest['status'], true);

    }


}