<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/22
 * Time: 13:30 Pm
 * @desc 测试借款人债权新债权管理
 */

namespace Tests\Http\Logics\LoanUser;

use App\Http\Logics\Credit\CreditUserLoanLogic;
use App\Http\Models\Project\ProjectLinkCreditNewModel;
use App\Http\Dbs\Project\ProjectLinkCreditNewDb;
use App\Http\Dbs\Credit\CreditUserLoanDb;

class CreditLoanUserLogicTest extends \TestCase
{

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
     * @desc 债权数据内容验证数据供给
     * @return array
     */
    public function dataCreditValidtor( )
    {
        return [
            [
              'credit_source_validtor1' => [
                [
                    'credit_name' => '个人借贷1',
                    'loan_type' => '1',
                    'loan_type_note' => '个人',
                    'loan_phone' => '13569855440',
                    'loan_username' =>'张三',
                    'loan_user_identity' => '245890188112090000',
                    'bank_name' => '中国银行',
                    'bank_card' => '642390890899898',
                    'loan_amounts' =>'200001',
                    'manage_fee' =>  '750',
                    'interest_rate' =>   '9',
                    'repayment_method' =>  '10',
                    'repayment_method_note' =>  '到期还本息',
                    'loan_deadline'  =>   '31' ,
                    'loan_days'  =>   '7' ,
                    'contract_no'  => 'Jing-2017042201' ,
                    'credit_id'  => 1,
                ]
                ],
                'credit_source_validtor2' => [
                [
                    'credit_name' => '企业测试',
                    'loan_type' => '2',
                    'loan_type_note' => '企业',
                    'loan_phone' => '010-59889098',
                    'loan_username' =>'陈华信',
                    'loan_user_identity' => '30098019720908211X',
                    'bank_name' => '中国工商银行',
                    'bank_card' => '642329394928810',
                    'loan_amounts' =>'1000001',
                    'manage_fee' =>  '750',
                    'interest_rate' =>   '9',
                    'repayment_method' =>  '10',
                    'repayment_method_note' =>  '到期还本息',
                    'loan_deadline'  =>   '31' ,
                    'loan_days'  =>   '7' ,
                    'contract_no'  => 'Jing-2017042201' ,
                    'credit_id'  => 2,
                ]
              ]
            ]
            ];


    }


    /**
     * @desc 债权录入的原始数据
     * @return array
     */
    public function dataAddCreditSource( )
    {
        parent::setUp();

        $creditLoanLogic = new CreditUserLoanLogic();

        $maxId = $creditLoanLogic->getMaxCreditId();

        $dataCredit = [
            [
              'credit_source' => [
                [
                    'credit_name' => '车辆抵押贷款',
                    'loan_type' => '1',
                    'loan_type_note' => '个人',
                    'loan_phone' => '13569855440',
                    'loan_username' =>'张三',
                    'loan_user_identity' => '245890188112090000',
                    'bank_name' => '中国银行',
                    'bank_card' => '642390890899898',
                    'loan_amounts' =>'200000',
                    'manage_fee' =>  '750',
                    'interest_rate' =>   '9',
                    'repayment_method' =>  '10',
                    'repayment_method_note' =>  '到期还本息',
                    'loan_deadline'  =>   '31' ,
                    'loan_days'  =>   '7' ,
                    'contract_no'  => 'Jing-2017042201' ,
                    'credit_id'  => $maxId + 1,
                ],
                [
                    'credit_name' => '华信公司资金周转',
                    'loan_type' => '2',
                    'loan_type_note' => '企业',
                    'loan_phone' => '59889098',
                    'loan_username' =>'陈华信',
                    'loan_user_identity' => '30098019720908211X',
                    'bank_name' => '中国工商银行',
                    'bank_card' => '642329394928810',
                    'loan_amounts' =>'900000',
                    'manage_fee' =>  '750',
                    'interest_rate' =>   '9',
                    'repayment_method' =>  '10',
                    'repayment_method_note' =>  '到期还本息',
                    'loan_deadline'  =>   '31' ,
                    'loan_days'  =>   '7' ,
                    'contract_no'  => 'Jing-2017042201' ,
                    'credit_id'  => $maxId + 2,
                ],
              ]
            ]
            ];

        return $dataCredit;

    }


    /**
     * @desc 测试债权数据验证
     * @dataProvider dataCreditValidtor
     * @return bool
     */
    public function testCreditDataValidtor( $credit_source_validtor1, $credit_source_validtor2 )
    {

        $creditLoanLogic = new CreditUserLoanLogic();

        $checkResult1  =  $creditLoanLogic->doBatchImport( $credit_source_validtor1 );

        $this->assertContains( '借款金额 不能大于 200000', $checkResult1['msg'] );
        //$checkResult  =  CreditUserLoanModel::checkAddCreditData( $credit_source_validtor[0] );

        $checkResult2  =  $creditLoanLogic->doBatchImport( $credit_source_validtor2 );

        $this->assertContains( '借款金额 不能大于 1000000', $checkResult2['msg'] );

    }

    /**
     * @desc 测试债权的批量录入
     * @dataProvider dataAddCreditSource
     * @return bool
     */
    public function testCreditBatchImport( $credit_source  )
    {

        $creditLoanLogic = new CreditUserLoanLogic();

        $testEmpty  =  $creditLoanLogic->doBatchImport( [] );

        $this->assertEquals( '批量上传数据为空', $testEmpty['msg'] );

        $return  =  $creditLoanLogic->doBatchImport( $credit_source );

        $this->assertTrue( $return['status'] );
    }


    /**
     * @desc 测试获取债权列表中的最大id
     * @return assert
     */
    public function testGetMaxCreditId( )
    {
        $creditLoanLogic = new CreditUserLoanLogic();

        $maxId = $creditLoanLogic->getMaxCreditId();

        $this->assertGreaterThanOrEqual( 0, $maxId );
    }


    /**
     * @desc 测试获取发布项目的债权列表[不含projectId]
     * @return bool
     */
    public function testGetAbleCreditListNoPid( )
    {

        $creditUserLoanLogic = new CreditUserLoanLogic();

        $return  = $creditUserLoanLogic->getAbleCreditList( );

        $this->assertTrue( $return['status'] );
    }


    /**
     * @desc 测试获取发布项目的未使用的债权列表
     * @dataProvider dataCreditProject
     * @return bool
     */
    public function testGetAbleCreditListPid( $projectId, $creditId )
    {
        //添加债权项目管理数据
        $projectLinkCreditModel =  new ProjectLinkCreditNewModel();

        $projectLinkCreditModel->createProjectLinkCredit( $projectId, $creditId );

        $creditUserLoanLogic = new CreditUserLoanLogic();

        $return  = $creditUserLoanLogic->getAbleCreditList( $projectId );

        $this->assertTrue( $return['status'] );

        //清理测试数据
        $projectLinkCreditDb = new ProjectLinkCreditNewDb();

        $projectLinkCreditDb->where( 'project_id', $projectId )->delete();
    }


    /**
     * @desc 测试更新债权的状态
     */
    public function testUpdateCreditStatus( )
    {

        parent::setUp();

        $creditLoanLogic = new CreditUserLoanLogic();

        $maxId = $creditLoanLogic->getMaxCreditId();

        $return = $creditLoanLogic->doUpdateCreditStatus( $maxId, CreditUserLoanDb::STATUS_ACTIVE );

        $this->assertTrue( $return['status'] );

    }

    /**
     * @desc 测试发布项目时关联操作
     * @dataProvider dataCreditProject
     * @return bool
     */
    public function testDoPublishProjectAction( $projectId, $creditId )
    {
        $creditUserLoanLogic = new CreditUserLoanLogic();

        $return = $creditUserLoanLogic->doPublishProjecAction( $projectId, $creditId );

        $this->assertTrue( $return['status'] );
    }

    /**
     * @desc 测试发布项目编辑时关联操作接口
     * @dataProvider dataCreditProject
     * @return bool
     */
    public function testDoUpdateProjectAction( $projectId, $creditId )
    {
        $creditUserLoanLogic = new CreditUserLoanLogic();

        $return = $creditUserLoanLogic->doUpdatePublishProjectAction( $projectId, 2 );

        $this->assertTrue( $return['status'] );

        //清理测试数据
        $projectLinkCreditDb = new ProjectLinkCreditNewDb();

        $projectLinkCreditDb->where( 'project_id', $projectId )->delete();
    }
}
