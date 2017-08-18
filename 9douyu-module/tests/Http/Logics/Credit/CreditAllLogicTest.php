<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/5/17
 * Time: 11:14 Am
 * Desc: 债权合并后逻辑处理的测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Logics\Credit\CreditAllLogic;
use App\Http\Models\Credit\CreditAllModel;

class CreditAllLogicTest extends TestCase
{

    /**
     * @desc 债权合并测试添加
     */
    public function creditAddData( )
    {
        return [
            [ true,
            [
                'id'          => 1000000,
                'company_name' => '快金债权1',
                'loan_username' => '刘佳佳',
                'loan_user_identity' => '104590198009099009',
                'loan_amounts' => '1000000',
                'interest_rate' => '10',
                'repayment_method' => '20',
                'expiration_date' => '2017-08-17',
                'loan_deadline' => '3',
                'contract_no' => 'DSN20170517',
                'type' => '50',
                'source' => '30',
                'credit_tag' => '103',
            ]
            ],
            [ true,
            [
                'id'          => 1000001,
                'company_name' => '快金债权2',
                'loan_username' => '李四',
                'loan_user_identity' => '103232199004093920',
                'loan_amounts' => '3000000',
                'interest_rate' => '12',
                'repayment_method' => '20',
                'expiration_date' => '2017-11-17',
                'loan_deadline' => '6',
                'contract_no' => 'JDY2017051706',
                'type' => '60',
                'source' => '30',
                'credit_tag' => '106',
            ]
            ],
            [ false,
            [
                'id'          => 1000002,
                'company_name' => '快金债权2',
                'loan_username' => '李四',
                'loan_user_identity' => '103232199004093920',
                'loan_amounts' => '3000000',
                'interest_rate' => '12',
                'repayment_method' => '20',
                'expiration_date' => '2017-11-17',
                'loan_deadline' => '6',
                'contract_no' => 'JDY2017051706',
                'type' => '60',
                'source' => '30',
                'credit_tag' => '106',
            ]
            ],
            ];

    }

    /**
     * @desc 测试债权合并后创建
     * @dataProvider creditAddData
     */
    public function testCreditAllCreate( $status, $data )
    {
        $logic = new CreditAllLogic();

        $result = $logic->doCreate( $data );

        $this->assertEquals( $status, $result['status'] );
    }

    public function creditUpdateData()
    {
        return [
            [ true,
            [
                'id'          => 1000000,
                'company_name' => '债权修改1',
                'loan_username' => '刘佳佳',
                'loan_user_identity' => '104590198009099009',
                'loan_amounts' => '2000000',
                'interest_rate' => '11',
                'repayment_method' => '30',
                'expiration_date' => '2017-08-17',
                'loan_deadline' => '3',
                'contract_no' => 'DSN20170517',
                'type' => '50',
                'source' => '20',
                'credit_tag' => '103',
            ]
            ],
            [ true,
            [
                'id'          => 1000001,
                'company_name' => '修改债权2',
                'loan_username' => '赵四',
                'loan_user_identity' => '103232199004093920',
                'loan_amounts' => '4000000',
                'interest_rate' => '12',
                'repayment_method' => '40',
                'expiration_date' => '2017-11-17',
                'loan_deadline' => '6',
                'contract_no' => 'JDY2017051706',
                'type' => '60',
                'source' => '40',
                'credit_tag' => '106',
            ]
            ],
            [ false,
            [
                'id'          => 1000002,
                'company_name' => '快金债权2',
                'loan_username' => '李四',
                'loan_user_identity' => '103232199004093920',
                'loan_amounts' => '3000000',
                'interest_rate' => '12',
                'repayment_method' => '20',
                'expiration_date' => '2017-11-17',
                'loan_deadline' => '6',
                'contract_no' => 'JDY2017051706',
                'type' => '60',
                'source' => '30',
                'credit_tag' => '106',
            ]
            ],
            ];
    }

    /**
     * @desc 测试债权编辑
     * @dataProvider creditUpdateData
     */
    public function testCreditAllUpdate( $status, $data )
    {
        $logic = new CreditAllLogic();

        $result = $logic->doUpdate( $data );

        $this->assertEquals( $status, $result['status'] );
    }

    public function statusData()
    {
        return [
            [ true, 1000000, 200],
            [ true, [1000000,1000001], 200 ],
            ];
    }
    /**
     * @desc 测试更新债权的状态
     * @dataProvider statusData
     */
    public function testUpdateStatus( $status, $creditId, $creditStatus )
    {
        $result = CreditAllModel::updateCreditStatus( $creditId, $creditStatus );

    }

}
