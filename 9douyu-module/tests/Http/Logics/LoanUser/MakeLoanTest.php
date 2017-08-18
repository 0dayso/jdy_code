<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/4/25
 * Time: 下午6:29
 */

namespace Tests\Http\Logics\LoanUser;

use App\Http\Dbs\Credit\CreditUserLoanDb;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\Common\LoanUserApi\LoanUserCreditApiModel;
use App\Http\Models\Credit\CreditUserLoanModel;
use App\Http\Models\Project\ProjectLinkCreditNewModel;

class MakeLoanTest extends \TestCase{


    /**
     * @param $projectId
     * @return mixed
     * @dataProvider projectIds
     */
    public function testMakeLoan($projectId){

        $projectLinkCreditModel = new ProjectLinkCreditNewModel();

        $creditId = $projectLinkCreditModel->getByProjectId( $projectId );   //to do 通过项目ID获取已经匹配的债权id

        $creditDb = new CreditUserLoanDb();

        $creditInfo = $creditDb->getDetailByWhere(['id' => $creditId]);

        $sendData = [
            'credit_id'         => $creditInfo['id'],
            'interest_rate'     => $creditInfo['interest_rate'],
            'loan_days'         => $creditInfo['loan_deadline'],
            'loan_amounts'      => $creditInfo['loan_amounts'],
            'credit_interest'   => IncomeModel::getInterestByParam($creditInfo['interest_rate'], $creditInfo['loan_days'], $creditInfo['loan_amounts'])
        ];

        LoanUserCreditApiModel::sendMakeLoansNotice($sendData);

    }



    public function projectIds(){

        return [
            [1],
            [2]
        ];

    }
}