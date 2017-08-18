<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/8
 * Time: 下午6:36
 * Desc: 投资成功监听,判断项目是否满标请求借款人系统
 */

namespace App\Listeners\Invest\ProjectSuccess;

use App\Events\CommonEvent;
use App\Http\Dbs\Credit\CreditUserLoanDb;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\Common\LoanUserApi\LoanUserCreditApiModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Project\ProjectLinkCreditNewModel;
use Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class LoanUserListener implements ShouldQueue
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param CommonEvent $event
     */
    public function handle(CommonEvent $event)
    {
        $data = $event->getDataByKey('loan_user');

        if( $data['new'] > 0 && ($data['invested_amount'] + $data['invest_cash']) >= $data['total_amount'] ){


            $projectLinkCreditModel = new ProjectLinkCreditNewModel();

            $creditId = $projectLinkCreditModel->getByProjectId( $data['project_id'] );   //to do 通过项目ID获取已经匹配的债权id

            $creditDb = new CreditUserLoanDb();

            $creditInfo = $creditDb->getDetailByWhere(['id' => $creditId]);

            if( empty($creditInfo) ){

                return $this->sendWaringEmail('项目【 '.$data['project_id'].' 】借款系统满标放款失败,债权信息不存在!!!');

            }

            $sendData = [
                'credit_id'         => $creditInfo['id'],
                'interest_rate'     => $creditInfo['interest_rate'],
                'loan_days'         => $creditInfo['loan_days'],
                'loan_amounts'      => $creditInfo['loan_amounts'],
                'manage_fee'        => $creditInfo['manage_fee'],
                'credit_interest'   => IncomeModel::getInterestByParam($creditInfo['interest_rate'], $creditInfo['loan_deadline'], $creditInfo['loan_amounts'])
            ];

            $result = LoanUserCreditApiModel::sendMakeLoansNotice($sendData);

            if( !$result['status'] ){

                return $this->sendWaringEmail('项目【 '.$data['project_id'].' 】借款系统满标放款失败!!!');

            }

        }

    }

    /**
     * @param $msg
     * @desc 报警邮件
     */
    private function sendWaringEmail($msg){

        $receiveEmails = Config::get('email.monitor.accessToken');

        $model = new EmailModel();

        $title = '【Error】借款系统,满标放款失败,请紧急处理!!!';

        //$model->sendHtmlEmail($receiveEmails,$title,$msg);

    }

}
