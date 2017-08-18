<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 17/05/08
 * Time: 17:09
 * Desc: 获取债权信息[房抵、信贷、保理......]
 */

namespace App\Http\Logics\Data;

use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Credit\Credit;
use App\Http\Models\Credit\CreditModel;
use App\Tools\ToolArray;
use App\Tools\ExportFile;
use App\Tools\ToolTime;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Models\Project\ProjectLinkCreditNewModel;
use App\Http\Models\Credit\CreditAllModel;
use App\Http\Dbs\Credit\CreditDb;

use App\Http\Models\Common\ServiceApi\EmailModel;
use Log;

class CreditListOutLogic extends Logic
{

    /**
     * @desc 导入债权的信息
     * @param $type
     * @param $data
     * @return array
     */
    public function getOutCreditData( $type = CreditDb::SOURCE_HOUSING_MORTGAGE, $data ='' ){

        //获取相关的债权类型
        $creditIds = $this->getCreditLinkNew( $data );

        $creditArr = $this->getCreditList( $type, $creditIds);

        return $creditArr;
    }
    /**
     * @desc 获取房抵和信贷的债权数据
     * @param $type int
     * @param $creditIds array
     * @author lgh-dev
     * @return array
     */
    public function getCreditList( $type = CreditDb::SOURCE_HOUSING_MORTGAGE, $creditIds = []){

        $creditExcept = [];

        if( empty( $creditIds ) )
        {
            return [];
        }

        $creditList = CreditAllModel::getCreditDetailById( $creditIds );

        if( empty( $creditList ) )
        {
            return [];
        }


        foreach($creditList as $key => $value){

            $creditExcept[$key]['loan_username'] = !empty($value['loan_username']) ? $value['loan_username'] : '第三方借款人姓名列表(隐藏)';
            $creditExcept[$key]['loan_user_identity'] = !empty($value['loan_user_identity']) ? $value['loan_user_identity'] : '第三方借款人身份证列表(隐藏)';
            $creditExcept[$key]['loan_amounts'] = $value['loan_amounts'];
            $creditExcept[$key]['contract_no'] = $value['contract_no'];
            $creditExcept[$key]['source'] = CreditModel::getSourceByKey($value['source']);

        }

        return $creditExcept;
    }
    /**
     * @desc 根据项目相关的条件获取债权项目关联的内容
     * @param $data
     * @return array
     */
    public function getCreditLinkNew( $data ){

        $projectLogic = new ProjectLogic();
        $projectLinkCreditNewModel = new ProjectLinkCreditNewModel();

        $projectList = $projectLogic->getFinishedProjectList( $data );

        $projectIds = ToolArray::arrayToIds( $projectList, 'id' );

        $projectLinkNew = $projectLinkCreditNewModel->getByProjectIds( $projectIds );

        $creditIds = empty( $projectLinkNew ) ? [] : ToolArray::arrayToIds( $projectLinkNew, 'credit_id' );

        return  $creditIds;
    }

    /**
     * @desc 格式化导出的债权数据
     * @param $creditList array 债权信息
     * @return array
     */
    public function sendCreditEmailData( $creditList, $title = '债权信息' )
    {
        if( empty( $creditList ) )
            return self::callError( '导出的信息为空' );

        $dataStatisticsLogic = new DataStatisticsLogic();
        $emailModel = new EmailModel();
        //接受者邮箱
        $receiveEmails = $dataStatisticsLogic->getMailTaskEmailConfig('rechargeWithdrawData');

        //邮件内容
        $content = $this->formatEmailContent( $creditList, $title );

        //内容附件
        $savePath = $this->formatAttachment( $creditList );
        //发送邮件
        $result = $emailModel->sendHtmlEmail($receiveEmails, $title, $content, $savePath );

        if( $result['status'] == false )
        {
             Log::Error('债权发送邮件失败:'.\GuzzleHttp\json_encode($result));
        }else{
             foreach($savePath as $file) {
                 @unlink($file);
             }
             Log::info('债权导出邮件发送成功');
        }

        return $result;
    }

    /**
     * @desc 格式化发送邮件的内容
     * @param $creditList 债权列表信息
     * @return string
     */
    public function formatEmailContent( $creditList )
    {
        if( empty( $creditList ) )
        {
            return [];
        }

        $dataStatisticsLogic = new DataStatisticsLogic();
        $body = "<br/>";
        $body  .= $dataStatisticsLogic->getCss();
        $body .="<table class='table'><tr><th>借款人</th><th>身份证</th><th>借款金额</th><th>合同编号</th><th>来源</th></tr>";

        foreach ( $creditList as $key =>$value )
        {
            $body .="<tr><td>{$value['loan_username']}</td><td>{$value['loan_user_identity']}</td><td>{$value['loan_amounts']}</td><td>{$value['contract_no']}</td><td>{$value['source']}</td></tr>";
        }
        $body .="</table>";

        return $body;
    }

    /**
     * @desc 格式化债权导出数据的附件
     * @param $creditList array
     * @return array
     */
    public function formatAttachment( $creditList )
    {
         $excelData[] =  "借款人, 身份证, 借款金额, 合同编号";

         foreach( $creditList as $value )
         {
             $creditArr  =  [ $value['loan_username'], $value['loan_user_identity'], $value['loan_amounts'], $value['contract_no'] ];

             $excelData[] = implode( ',', $creditArr );
         }
         $fileName = 'credit'.ToolTime::dbDate().'.csv';

         //不存在目录时创建目录
         $dirPath = base_path() . '/public/uploads/credit/';
         if (!is_dir($dirPath)) {
             mkdir($dirPath, 0777);
             chmod($dirPath, 0777);
         };
         //写入文件
         $savePath = $dirPath.$fileName;
         @file_put_contents($savePath, implode("\n", $excelData));
         return [$savePath];
    }
}
