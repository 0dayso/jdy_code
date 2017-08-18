<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/4/17
 * Time: 下午7:31
 * Desc: 新的借款用户债权信息
 */

namespace App\Http\Controllers\Admin\Credit;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\Credit\CreditLoanUserRequest as LoanUserRequest;
use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Logics\Credit\CreditLogic;
use App\Http\Logics\Credit\CreditUserLoanLogic;
use App\Http\Models\Credit\CreditUserLoanModel;
use App\Http\Dbs\Credit\CreditUserLoanDb;
use Excel, Log;

class CreditUserLoanController extends AdminController
{

    /**
     * @desc 创建借款用户的债权信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create( )
    {
        $viewData = [
            'loanType'              => CreditUserLoanModel::setLoanType(),
            'repaymentMethod'       => CreditLogic::getRefundTypeForOperation(),
            'record_type'           => ['批量录入', '手动录入'],//录入方式
        ];

        return view('admin.credit.create.loanUserCredit', $viewData );
    }

    /**
     * @desc 执行创建债权操作
     * @author linguanghui
     * @param CreateDisperseCreditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doCreate( Request $request )
    {
        $data  =  $request->all();

        //录入方式
        $type = $data['record_type'];
        $loanType  = $data['loan_type'];

        $loanUserRequest = new LoanUserRequest();

        $validator = Validator::make($request->all(), $loanUserRequest->rules( $type, $loanType), [], $loanUserRequest->attributes());

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $creditUserLoanLogic = new CreditUserLoanLogic();

        if( $type == 1 )
        {
            //批量录入
            $creditInfo        = $this->import( $request );
            $logicResult            = $creditUserLoanLogic->doBatchImport($creditInfo);

        }else{

            //获取债权最大值的id
            $maxCreditId = $creditUserLoanLogic->getMaxCreditId();
            $data['credit_id']  = $maxCreditId + 1;

            $data['loan_type_note']  = CreditUserLoanModel::setLoanType()[$data['loan_type']];

            $data['repayment_method_note']  =  CreditLogic::getRefundTypeForOperation()[ $data['repayment_method'] ];

            $logicResult = $creditUserLoanLogic->doCreate($data);
        }
        if($logicResult['status']){
            return redirect('/admin/credit/lists/loanUser')->with('message', '创建债权成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
    }

    /**
     * @desc 用户借款债权列表
     */
    public function lists( Request $request )
    {

        $size  = $request->input( 'size', 100 );

        $creditUserLoanLogic = new CreditUserLoanLogic();

        $status = $request->input('status', CreditUserLoanDb::STATUS_UNUSED);

        $data['data']  = $creditUserLoanLogic->getCreditUserLoanList($status, $size);

        $data['repaymentMethod']  = CreditLogic::getRefundTypeForOperation();

        $data['loanType']  = CreditUserLoanModel::setLoanType();

        $data['status']  =  $status;

        return view('admin.credit.lists.loanUserCredit', $data);

    }

    /**
     * @desc 债权编辑页面
     */
    public function edit($id, \Illuminate\Http\Request  $request)
    {

    }

    /**
     * @desc 执行债权编辑的操作
     */
    public function doEdit( )
    {

    }

    /**
     * @desc 批量导入债权文件上传处理
     * @param $request
     * @return array|bool|\Illuminate\Http\RedirectResponse
     */
    public function import( $request ){

        $creditUserLoanLogic = new CreditUserLoanLogic();

        if(!$request->file('credit_list'))
            return false;

        try {
            $subPath = storage_path('admin/upload/credit_loan_user');
            $isHas = file_exists($subPath);
            Log::info('上传文件夹：' . $subPath);
            if(!$isHas){
                $isMake = mkdir($subPath, 0777, true);
                Log::info('创建文件夹：' . $isMake);
                if($isMake === false){
                    throw new \Exception('创建上传文件夹失败: ' . $subPath);
                }
            }

            $storage = new \Upload\Storage\FileSystem($subPath);
            $file = new \Upload\File('credit_list', $storage);
            $newFilename = date('Y-m-d') . uniqid();
            $file->setName($newFilename);

            $file->addValidations(array(
                //new \Upload\Validation\Mimetype(array('image/png', 'image/gif'))
                new \Upload\Validation\Mimetype(
                    [
                        'application/vnd.ms-excel',
                        'application/x-excel',
                        'application/vnd.ms-office',
                        'application/octet-stream',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ]),
                // Ensure file is no larger than 5M (use "B", "K", M", or "G")
                new \Upload\Validation\Size('30M')
            ));

            // Access data about the file that has been uploaded
            $data = array(
                'name' => $file->getNameWithExtension(),
                'extension' => $file->getExtension(),
                'mime' => $file->getMimetype(),
                'size' => $file->getSize(),
                'md5' => $file->getMd5(),
                'dimensions' => $file->getDimensions()
            );

            Log::info(__METHOD__ . ' 债权批量导入文件详情：', $data);

            $isUpload = $file->upload();
            //导入
            if (!$isUpload)
                return redirect()->back()->withInput($request->input())->with('fail', '上传文件错误！');

            $filePath = $subPath . '/' . $data['name'];

            Log::info('上传文件路径' . $filePath);

            $records = Excel::load($filePath, function ($reader) {

            }, 'UTF-8')->toArray();

            $return = [];
            $recordSave = [];

            //获取债权最大值的id
            $maxCreditId = $creditUserLoanLogic->getMaxCreditId();

            foreach($records as $k => $record){

                $maxCreditId++;

                if(empty($record[0]))
                    continue;
                $recordSave['credit_name'] = isset( $record[0] ) ? $record[0] : '';
                $recordSave['loan_type'] = isset( $record[1] ) ? $creditUserLoanLogic->getLoanTypeFromText( $record[1] ): '';
                $recordSave['loan_type_note'] = isset( $record[1] ) ? $record[1] : '';
                $recordSave['loan_phone'] = isset( $record[2] ) ? (int)$record[2] : '';
                $recordSave['loan_username'] = isset( $record[3] ) ? $record[3] : '';
                $recordSave['loan_user_identity'] = isset( $record[4] ) ? $record[4] : '';
                $recordSave['bank_name'] = isset( $record[5] ) ? $record[5] : '';
                $recordSave['bank_card'] = isset( $record[6] ) ? $record[6] : '';
                $recordSave['loan_amounts'] = isset( $record[7] ) ? $record[7] : '';
                $recordSave['manage_fee'] = isset( $record[8] ) ? $record[8] : '';
                $recordSave['interest_rate'] = isset( $record[9] ) ? $record[9] : '';
//                $recordSave['project_publish_rate'] = isset( $record[10] ) ? $record[10] : '';
                $recordSave['repayment_method'] = isset( $record[10] ) ? $creditUserLoanLogic->getRefundTypeFromText( $record[10] ): '';
                $recordSave['repayment_method_note'] = isset( $record[10] ) ? $record[10] : '';
                $recordSave['loan_deadline']  = isset(  $record[11]  ) ? (int)$record[11]  : '' ;
                $recordSave['loan_days']  = isset(  $record[12]  ) ? (int)$record[12]  : '' ;
                $recordSave['contract_no']  = isset(  $record[13]  ) ? $record[13]  : '' ;
                $recordSave['credit_id']  = $maxCreditId;

                $return[] = $recordSave;
            }

            Log::info('批量录入借款人体系债权的表格数据：',  $return);
            return $return;

        }catch (\Exception $e){
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);
        }
    }
}
