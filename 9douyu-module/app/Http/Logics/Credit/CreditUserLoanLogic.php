<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/18
 * Time: Pm 04:15
 * */

namespace App\Http\Logics\Credit;

use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Credit\CreditUserLoanModel;

use App\Http\Models\Common\LoanUserApi\LoanUserCreditApiModel;
use App\Http\Models\Project\ProjectLinkCreditNewModel;
use App\Http\Dbs\Credit\CreditDb;
use App\Http\Dbs\Credit\CreditUserLoanDb;
use Log;
use Cache;
use App\Tools\ToolMoney;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Mockery\Exception;

/**
 * @desc 添加分散债权
 * Class CreditDisperseLogic
 * @package App\Http\Logics\Credit
 */

class CreditUserLoanLogic extends CreditLogic
{


    /**
     * @desc 创建分散债权信息
     * @author linguanghui
     * @param $data
     * @return array
     */
    public function doCreate( $data )
    {

        $attributes = [
                'id'                  => $data['credit_id'],
                'credit_name'         =>  $data['credit_name'] ,
                'loan_type'            =>   $data['loan_type'] ,
                'loan_amounts'         =>  $data['loan_amounts'] ,
                'can_use_amounts'      =>  $data['loan_amounts'] ,
                'manage_fee'           =>  $data['manage_fee'] ,
                'interest_rate'        => $data['interest_rate'] ,
                'repayment_method'     =>  $data['repayment_method'] ,
//                'project_publish_rate' =>$data['project_publish_rate'] ,
                'loan_deadline'        =>  $data['loan_deadline'] ,
                'loan_days'            => $data['loan_days'] ,
                'status_code'          => CreditUserLoanDb::STATUS_UNUSED,
                'loan_phone'           => $data['loan_phone'],
                'loan_username'        => $data['loan_username'],
                'loan_user_identity'  => $data['loan_user_identity'],
                'bank_name'  => $data['bank_name'],
                'bank_card'  => $data['bank_card'],
                'contract_no'     => $data['contract_no']
            ];

        try {
            CreditUserLoanModel::checkAddCreditData( $attributes );

            unset( $data['_token'] );
            unset( $data['record_type'] );
            unset( $data['credit_list'] );

            $creditInfo[] = $data;

            //执行借款人系统债权录入操作
            $loanResult = $this->doSendLoanSystemCreditData( $creditInfo );

            $return = CreditUserLoanModel::doCreate( $attributes );

        }catch ( \Exception $e ) {
            $attributes['data']           = $attributes;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError( $e->getMessage() );
        }

        return self::callSuccess( [$return] );
    }

    /**
     * @desc 批量添加新版定期债权
     * @author linguanghui
     * @param $creditInfo
     * @return array
     */
    public function doBatchImport( $creditInfo )
    {
        if( empty( $creditInfo ) )
        {
            return self::callError( '批量上传数据为空' );
        }

        $formatCreditInfo  =  [];

        try {

            $formatCreditInfo = $this->formatImportCredit( $creditInfo );

            //执行借款人系统债权录入操作
            $loanResult = $this->doSendLoanSystemCreditData( $creditInfo );

            $return = CreditUserLoanModel::doBatchCreate( $formatCreditInfo );

        }catch ( \Exception $e ) {
            $attributes['data']           = $formatCreditInfo;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error( __METHOD__.'Error', $creditInfo );

            return self::callError( $e->getMessage() );
        }


        if( !$loanResult['status'] ){

            //执行邮件报警
            $receiveEmails = \Config::get('email.monitor.accessToken');

            $emailModel = new EmailModel();

            $subject = '【Error】批量添加新版定期债权,请管理员尽快排查';

            $emailModel->sendHtmlEmail($receiveEmails, $subject, json_encode($loanResult['data']));

        }

        return self::callSuccess( [$return] );

    }

    /**
     * @desc 获取借款人体系的债权列表
     * @author linguanghui
     * @param $condition array
     * @return array
     */
    public function getCreditUserLoanList( $status, $size )
    {

        $creditUserLoanModel  = new CreditUserLoanModel();

        return $creditUserLoanModel->getCreditUserLoanListByStatus($status, $size);

    }


    /**
     * @desc 获取未使用的债权列表[发布项目＋编辑项目时使用]
     * @param $projectId
     * @return array
     */
    public function getAbleCreditList( $projectId = '' )
    {
        $ableCreditList = $usedCreditInfo = [];

        $creditUserLoanModel  = new CreditUserLoanModel();

        $projectLinkCreditModel = new ProjectLinkCreditNewModel();

        if( !empty( $projectId ) )
        {
            $creditId = $projectLinkCreditModel->getByProjectId( $projectId );   //to do 通过项目ID获取已经匹配的债权id

            $usedCreditInfo = $creditUserLoanModel->getCreditInfoById( $creditId );
        }

        $ableCreditList  =  $creditUserLoanModel->getAbleCreditList();

        $creditList = array_merge_recursive( $usedCreditInfo,  $ableCreditList );

        return self::callSuccess( $creditList );
    }

    /**
     * @desc 执行发布项目和债权相关连的相关操作
     * @param $projectId int 项目id
     * @param $creditId  int 债权ID
     * $retrun array
     */
    public function doPublishProjecAction( $projectId , $creditId )
    {
        $projectLinkCreditModel = new ProjectLinkCreditNewModel();

        $creditUserLoanModel  = new CreditUserLoanModel();

        self::beginTransaction();
        try{

            //创建债权和项目的关联关系记录
            $projectLinkCreditModel->createProjectLinkCredit( $projectId, $creditId );

            //更新债权的状态未已关联
            $creditUserLoanModel->doUpdateCreditStatus( $creditId, CreditUserLoanDb::STATUS_ACTIVE );

            self::commit();
        }catch( \Exception $e ){
            self::rollback();

            Log::Error( __Class__.__Method__.'项目发布', ['msg' => $e->getMessage() ]);
            return self::callError( $e->getMessage() );
        }
        return self::callSuccess();
    }

    /**
     * @desc 更新已发布的债权相关操作
     * @param $projectId int 项目id
     * @param $creditId  int 债权ID
     * $retrun array
     */
    public function doUpdatePublishProjectAction( $projectId, $creditId )
    {

        $projectLinkCreditModel = new ProjectLinkCreditNewModel();

        $creditUserLoanModel  = new CreditUserLoanModel();

        $oldCreditId = $projectLinkCreditModel->getByProjectId( $projectId ); //获取已经匹配过的id

        //如果新旧债权相等直接返回
        if( $creditId == $oldCreditId )
        {
            return self::callSuccess();
        }

        self::beginTransaction();
        try{
            //更新项目债权关联表
            $projectLinkCreditModel->updateProjectCreditId( $projectId, $creditId );

            //更新以关联的债权状态未为关联
            $creditUserLoanModel->doUpdateCreditStatus( $oldCreditId, CreditUserLoanDb::STATUS_UNUSED );

            //更新选中债权为关联
            $creditUserLoanModel->doUpdateCreditStatus( $creditId, CreditUserLoanDb::STATUS_ACTIVE );

            self::commit();
        }catch( \Exception $e ){
            self::rollback();

            Log::Error( __Class__.__Method__.'更新项目发布', ['msg' => $e->getMessage() ]);
            return self::callError( $e->getMessage() );
        }

        return self::callSuccess();

    }

    /**
     * @desc更新债权的状态
     * @param $id int
     * @param $status
     * @return bool
     */
    public function doUpdateCreditStatus( $id, $status )
    {
        $creditUserLoanModel  = new CreditUserLoanModel();

        $result = $creditUserLoanModel->doUpdateCreditStatus( $id , $status );

        if( !$result )
            return self::callError( '更新债权状态失败' );

        return self::callSuccess();
    }

    /**
     * @desc 格式化导入债权的信息
     * @author linganghui
     * @param $creditInfo array
     * @return array
     */
    public function formatImportCredit( $creditInfo )
    {
        $formatCreditInfo = [] ;

        if(empty( $creditInfo ))
            return [];

        foreach( $creditInfo as $key => $value )
        {


            CreditUserLoanModel::checkAddCreditData( $value );

           // if( $result['status'] == false)
           // {
           //     return self::callError( $result['msg'] );
           // }
            $formatCreditInfo[ $key ] = [
                'id'                 => $value['credit_id'],
                'credit_name'         =>  $value['credit_name'] ,
                'loan_type'         =>  $value['loan_type'] ,
                'loan_amounts'         =>  $value['loan_amounts'] ,
                'can_use_amounts'      =>  $value['loan_amounts'] ,
                'manage_fee'           =>  $value['manage_fee'] ,
                'interest_rate'        => $value['interest_rate'] ,
                'repayment_method'     =>  $value['repayment_method'] ,
    //            'project_publish_rate' =>$value['project_publish_rate'] ,
                'loan_deadline'        =>  $value['loan_deadline'] ,
                'loan_days'            => $value['loan_days'] ,
                'status_code'          => CreditUserLoanDb::STATUS_UNUSED,
                'loan_phone'           => $value['loan_phone'],
                'loan_username'        => $value['loan_username'],
                'loan_user_identity'  => $value['loan_user_identity'],
                'contract_no'     => $value['contract_no'] ,
                ];

        }

        return $formatCreditInfo ;
    }



    /**
     * @desc 通过还款的名称获取还款type
     * @author linguanghui
     * @param $repaymentMethod 还款名称
     * @return int
     */
    public function getRefundTypeFromText( $repaymentMethod )
    {

        $refundType =  0;

        $refundTypeList  = CreditLogic::getRefundTypeForOperation();

        foreach( $refundTypeList as $key => $name )
        {
            if( $repaymentMethod == $name )
            {
                $refundType = $key;
            }
        }

        return $refundType;
    }

    /**
     * @desc 通过借款类型获名称取借款type
     * @author linguanghui
     * @param $loanTypeNote 借款类型名称
     * @return int
     */
    public function getLoanTypeFromText( $loanTypeNote )
    {
        $loanType  = 0;

        $loanTypeList  = CreditUserLoanModel::setLoanType();

        foreach( $loanTypeList as $key => $name )
        {
            if( $loanTypeNote == $name )
            {
                $loanType = $key;
            }
        }
        return $loanType;
    }

    /**
     * @desc 获取债权最大值
     * return int
     */
    public function getMaxCreditId()
    {
        //获取最大的债权ID
        $creditUserLoanDb  =  new CreditUserLoanDb();

        $maxCreditId = $creditUserLoanDb->getMaxCreditId();

        return $maxCreditId ? $maxCreditId : 0;
    }

    /**
     * @desc 执行传送借款人债权数据到借款人系统的操作
     * @author linguanghui
     * @param $creditInfo array
     * @return bool
     */
    public function doSendLoanSystemCreditData( $creditInfo )
    {

        return LoanUserCreditApiModel::sendLoanUserCreditData( $creditInfo );
    }

}


