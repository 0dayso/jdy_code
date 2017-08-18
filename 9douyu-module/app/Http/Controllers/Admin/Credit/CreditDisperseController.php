<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/3/20
 * Time: 下午4:05
 * Desc: 分散新债权控制器
 */

namespace App\Http\Controllers\Admin\Credit;

use App\Http\Dbs\Credit\CreditDisperseDb;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Requests\Admin\Credit\CreateDisperseCreditRequest as DisperseRequest;
use Validator;
use App\Http\Logics\Oss\OssLogic;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Logics\Credit\CreditDisperseLogic;
use Excel, Log;

class CreditDisperseController extends AdminController{
    /**
     * @desc 创建分散债权的form表单
     * @author linguanghui
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){

        $disperseData  = [
            'record_type' => ['批量录入', '手动录入'],//录入方式
            ];

        return view('admin.credit.create.disperseCredit', $disperseData);
    }

    /**
     * @desc 债权录入操作
     * @author linguanghui
     * @param CreateDisperseCreditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doCreate(Request $request){

        $disperseRequest = new DisperseRequest();
        $validator = Validator::make($request->all(), $disperseRequest->rules(), [], $disperseRequest->attributes());

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();


        //录入方式
        $type = $data['record_type'];
        $creditDisperseLogic = new CreditDisperseLogic();

        if($type == 1){
            //批量录入
            $creditInfo        = $this->import( $request );
            $result            = $creditDisperseLogic->doBatchImport($creditInfo);
        }else{
            //手动录入
            $result = $creditDisperseLogic->doCreate( $data );
        }

        if($result['status']){
            return redirect('/admin/credit/lists/disperse')->with('message', '录入债权成功！');
        }else{
            return redirect()->back()->withInput()->with('fail', '债权录入失败！');
        }

    }

    /**
     * @desc 债权列表
     * @authro linguanghui
     * @return view
     */
    public function lists( Request $request ){

        $size = $request->input('size', 100);

        $status = $request->input('status', CreditDisperseDb::STATUS_CODE_ACTIVE);

        $creditDisperseLogic = new CreditDisperseLogic();

        $data['status']  = $status;
        $data['data']  = $creditDisperseLogic->getCreditDisperseList($status, $size);

        return view('admin.credit.lists.disperseCredit', $data);

    }

    /**
     * @desc 发布债权为可匹配的状态
     * @param Request $request
     * @return string
     */
    public function doOnline( Request $request ){

        $id = $request->input('id');

        $creditDisperseLogic = new CreditDisperseLogic();

        $return = $creditDisperseLogic->doCreditOnline( $id );

        return $this->ajaxJson( $return );
    }


    public function edit(){


    }

    /**
     * @desc 债权批量债权
     * @return array|bool|\Illuminate\Http\RedirectResponse
     */
    public function import( $request ){

        if(!$request->file('credit_list'))
            return false;

        try {
            /*$subPath = storage_path('admin/upload/credit_disperse');
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

            $uploadResult = $oss->putFile( $file['credit_list'], 'credit_disperse' );

            if( !$uploadResult['status'] ){

                return redirect()->back()->withInput($request->input())->with('fail', $uploadResult['msg']);
            }

            $filePath = $file['credit_list']['tmp_name'];

            $records = Excel::load($filePath, function ($reader) {

            }, 'UTF-8')->toArray();

            $return = [];
            $recordSave = [];

            foreach($records as $k => $record){
                if(empty($record[0]))
                    continue;
                $recordSave['credit_name'] = isset( $record[0] ) ? $record[0] : '';
                $recordSave['loan_realname'] = isset( $record[1] ) ? $record[1] : '';
                $recordSave['loan_idcard'] = isset( $record[2] ) ? $record[2] : '';
                $recordSave['amounts'] = isset( $record[3] ) ? $record[3] : '';
                $recordSave['interest_rate'] = isset( $record[4] ) ? $record[4] : '';
                $recordSave['loan_deadline'] = isset( $record[5] ) ? $record[5] : '';
                $recordSave['start_time'] = ( isset( $record[6] ) && is_object( $record[6] ) ) ? date( 'Y-m-d', strtotime( $record[6]->format( 'Y-m-d' ) ) ) : $record[6];
                $recordSave['end_time'] = ( isset( $record[7] ) && is_object( $record[7] ) ) ? date( 'Y-m-d', strtotime( $record[7]->format( 'Y-m-d' ) ) ) : $record[7];
                $recordSave['contract_no']  = isset(  $record[8]  ) ? $record[8]  : '' ;

                $return[] = $recordSave;
            }

            Log::info('表格数据：', [$records, $return]);
            return $return;

        }catch (\Exception $e){
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);
        }
    }
}
