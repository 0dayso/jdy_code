<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/18
 * Time: 下午2:29
 * Desc: 新的借款用户债权信息
 */

namespace App\Http\Controllers\Admin\Credit;

use App\Tools\ToolMoney;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\Credit\CreditAllRequest;
use App\Http\Logics\Credit\CreditAllLogic;
use App\Http\Logics\Credit\CreditUserLoanLogic;
use App\Http\Logics\Oss\OssLogic;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Logics\Credit\CreditLogic;
use App\Http\Models\Credit\CreditExtendModel;
use Validator;
use Log, Excel;

class CreditAllController extends AdminController
{
    /**
     * @desc 债权创建
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $viewData = [
            'source'                => CreditLogic::getSource(),
            'type'                  => CreditLogic::getType(),
            'repaymentMethod'       => CreditLogic::getRefundTypeForOperation(),
            'productLine'           => CreditLogic::getProductLine(),
            'sex'                   => CreditLogic::getSexData(),
            'record_type'           => ['批量录入','手动录入'],//录入方式
            ];
        return view('admin.credit.create.creditAll', $viewData );
    }

    /**
     * @desc 债权执行创建
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doCreate( Request  $request )
    {
        $data = $request->all();

        //录入方式
        $type = $data['record_type'];

        $creditAllRequest = new CreditAllRequest();

        $validator = Validator::make($request->all(), $creditAllRequest->rules( $type ), [], $creditAllRequest->attributes());

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $creditAllLogic = new CreditAllLogic();

        if( $type == 1 )
        {
            //批量录入
            $creditInfo        = $this->import( $request );
            $logicResult            = $creditAllLogic->doBatchImport($creditInfo);

        }else{

            $logicResult = $creditAllLogic->doCreate($data);
        }
        if($logicResult['status']){
            return redirect('/admin/credit/new/lists')->with('message', '创建债权成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
    }

    /**
     * @desc 批量导入债权文件上传处理
     * @param $request
     * @return array|bool|\Illuminate\Http\RedirectResponse
     */
    public function import( $request ){

        $creditUserLoanLogic = new CreditUserLoanLogic();
        $creditAllLogic = new CreditAllLogic();

        if(!$request->file('credit_list'))
            return false;

        try {
            /*$subPath = storage_path('admin/upload/creditAll');
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

            Log::info('上传文件路径' . $filePath);*/

            //Oss文件上传
            $file = $_FILES;

            $oss = new OssLogic();

            $uploadResult = $oss->putFile( $file['credit_list'], 'credit_all' );

            if( !$uploadResult['status'] ){

                return redirect()->back()->withInput($request->input())->with('fail', $uploadResult['msg']);
            }

            $filePath = $file['credit_list']['tmp_name'];

            $records = Excel::load($filePath, function ($reader) {

            }, 'UTF-8')->toArray();

            $return = [];
            $recordSave = [];

            //获取债权最大值的id
            $maxCreditId = $creditAllLogic->getMaxCreditId();

            foreach($records as $k => $record){

                $maxCreditId++;

                if(empty($record[0]))
                    continue;
                $recordSave['company_name'] = isset( $record[0] ) ? $record[0] : '';
                $recordSave['loan_username'] = isset( $record[1] ) ? $record[1] : '';
                $recordSave['loan_user_identity'] = isset( $record[2] ) ? $record[2] : '';
                $recordSave['loan_amounts'] = isset( $record[3] ) ? $record[3] : '';
                $recordSave['interest_rate'] = isset( $record[4] ) ? $record[4] : '';
                $recordSave['source'] = isset( $record[5] ) ? CreditLogic::getSouceByText( $record[5] ) : '';
                $recordSave['type'] = isset( $record[6] ) ? CreditLogic::getTypeByText( $record[6] ) : '';
                $recordSave['credit_tag'] = isset( $record[7] ) ? CreditLogic::getCreditTagByText( $record[7] ) : '';
//                $recordSave['project_publish_rate'] = isset( $record[10] ) ? $record[10] : '';
                $recordSave['repayment_method'] = isset( $record[8] ) ? $creditUserLoanLogic->getRefundTypeFromText( $record[8] ): '';
                $recordSave['loan_deadline']  = isset(  $record[9]  ) ? (int)$record[9]  : '' ;
                $recordSave['expiration_date'] = isset( $record[10] ) ? $record[10] : '';
                $recordSave['contract_no']  = isset(  $record[11]  ) ? $record[11]  : '' ;
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 债权列表(新)
     */
    public function newLists(Request $request){
        //参数
        $company_name  = trim($request->input('company_name'));      //企业名称
        $originalName = trim($request->input('loan_username'));      //债权人名
        $source       = trim($request->input('source'));             //债权来源
        $credit_tag   = trim($request->input('credit_tag'));         //产品线

        //搜索条件
        $condition   = [];

        if(!empty($company_name)){
            $condition[] = ['company_name', 'like','%' . $company_name . '%'];
        }
        if(!empty($source)){
            $condition['source']      = $source;
        }
        if(!empty($credit_tag)){
            $condition['credit_tag']  = $credit_tag;
        }
        if(!empty($originalName)){
            $condition[] = ['loan_username', 'like','%' . $originalName . '%'];
        }

        //列表
        $list = CreditAllLogic::getCreditLists($condition);

        $data                       = [];
        $data['list']               = CreditAllLogic::getProjectLinks($list);
        $data['list']               = self::formatNewOutput($data['list']);
        $data['source']             = CreditLogic::getSource();
        $data['type']               = CreditLogic::getType();
        $data['pageParam']          = ['company_name'=> $company_name, 'source'=> $source, 'credit_tag'=> $credit_tag, 'loan_username'=> $originalName];
        $data['productLine']        = CreditLogic::getProductLine();
        $data['repaymentMethod']    = CreditLogic::getRefundType();
        $data['dayOrMonth']         = CreditLogic::getLoanDeadlineDayOrMonth();

        return view('admin.credit.lists.credit-new-lists', $data);
    }

    /**
     * 格式列表输出金额
     * @param array $listData
     * @return array
     */
    protected static function formatNewOutput($listData = []){
        if($listData){

            foreach($listData as $list){
                $list->loan_amounts     = ToolMoney::formatDbCashDeleteTenThousand($list->loan_amounts);

                //债权项目关联表
                if(!empty($list->projectLinks)){
                    $projectLinks_array = [];
                    foreach($list->projectLinks as $projectLinkKey => $projectLink){
                        $projectLinks_array[] = ['project_id'=>$projectLink['project_id']];

                    }
                    $list->projectLinks_array = $projectLinks_array;
                }
            }
        }
        return $listData;
    }

    /**
     * @param $id 债权ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 债权编辑页面
     */
    public function edit($id){

        $logic = new CreditAllLogic();

        $credit = $logic->getCreditByCreditId($id);

        $extra = CreditExtendModel::getExtraByCreditId( $id );

        $viewData = [
            'source'                => CreditLogic::getSource(),
            'type'                  => CreditLogic::getType(),
            'repaymentMethod'       => CreditLogic::getRefundTypeForOperation(),
            'productLine'           => CreditLogic::getProductLine(),
            'sex'                   => CreditLogic::getSexData(),
            'data'                  => $credit,
            'extra'                 => $extra,
        ];

        return view('admin.credit.edit.creditAll', $viewData );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc    编辑债权主体
     */
    public function doEdit(Request $request){

        $data = $request->all();

        $creditAllLogic = new CreditAllLogic();

        $result = $creditAllLogic->doUpdate($data);

        if($result['status']){
            return redirect('/admin/credit/new/lists')->with('message', '编辑债权成功！');
        }else {
            return redirect('/admin/credit/edit/all/'.$data['id'])->with('fail', $result['msg']);
        }
    }
}
