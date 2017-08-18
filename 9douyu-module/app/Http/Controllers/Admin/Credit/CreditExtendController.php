<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/5/18
 * Time: 下午2:29
 * Desc: 债权扩展信息
 */
namespace App\Http\Controllers\Admin\Credit;

use App\Http\Logics\Credit\CreditAllLogic;
use App\Http\Models\Credit\CreditAllModel;
use Illuminate\Http\Request;
use App\Http\Logics\Oss\OssLogic;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Logics\Credit\CreditLogic;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Models\Credit\CreditExtendModel;
use App\Http\Logics\Credit\CreditExtendLogic;
use Log, Excel;

class CreditExtendController extends AdminController
{
    /**
     * @desc 债权扩展信息编辑
     * @param $type int
     * @param $creditId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit( $type, $creditId )
    {
        //获取债权类型
        $typeList = CreditLogic::getType()+CreditLogic::getSource();

        //获取债权扩展信息
//        $extra = CreditExtendModel::getExtraByCreditId( $creditId );
        $creditInfo = CreditAllModel::getCreditDetailById( $creditId );

        $viewData = [
            'title'     => $typeList[$type].'信息编辑',
            'credit_id' => $creditId,
            'star'      => CreditLogic::getStar(),
            'risk'      => CreditLogic::getRiskcalcLevel(),
            'sex'       => CreditLogic::getSexData(),
            'obj'       => !empty($creditInfo[0]) ? $creditInfo[0] :[],
            'source'                => CreditLogic::getSource(),
            'type'                  => CreditLogic::getType(),
            'repaymentMethod'       => CreditLogic::getRefundTypeForOperation(),
            'productLine'           => CreditLogic::getProductLine(),
        ];

        return view('admin.credit.extend.edit'.$type, $viewData );
    }

    /**
     * @desc 执行编辑债权的扩展信息
     * @param Request $request
     * @return mixed
     */
    public function doEdit( Request $request )
    {
        $data = $request->input();

        if( isset( $data['_token'] ) )
            unset( $data['_token'] );

        $creditInfo  = $this->import($request);

        if($creditInfo) {
            $data['credit_list'] = json_encode($creditInfo);
        }

        $creditExtendLogic = new CreditExtendLogic( );

        $result = $creditExtendLogic->doUpdate( $data );

        if($result['status']){
            return redirect('/admin/credit/new/lists')->with('message', '编辑债权扩展信息成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $result['msg']);
        }
    }

    /**
     * 上传快金债权
     *
     * @param $request
     * @return array|bool|\Illuminate\Http\RedirectResponse
     */
    protected function import($request){

        if(!$request->file('credit_list'))
            return false;


        try {
            /*$subPath = storage_path('admin/upload/credit');
            $isHas = file_exists($subPath);
            Log::info(__CLASS__.__METHOD__.__LINE__.'Upload file：' . $subPath);
            if(!$isHas){
                $isMake = mkdir($subPath, 0777, true);
                Log::info(__CLASS__.__METHOD__.'create dir：' . $isMake);
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

            Log::info(__CLASS__.__METHOD__ . ' The Upload File Detail：', $data);

            $isUpload = $file->upload();
            //导入
            if (!$isUpload)
                return redirect()->back()->withInput($request->input())->with('fail', '上传文件错误！');

            $filePath = $subPath . '/' . $data['name'];

            Log::info(__CLASS__.__METHOD__.'上传文件路径' . $filePath);*/


            //Oss文件上传
            $file = $_FILES;

            $oss = new OssLogic();

            $uploadResult = $oss->putFile( $file['credit_list'], 'third_credit' );

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
                $recordSave['realname'] = isset($record[0]) ? $record[0] : '';
                $recordSave['identity_card'] = isset($record[1]) ? $record[1] : '';
                $recordSave['amount'] = isset($record[2]) ? $record[2] : '';
                $recordSave['time'] = (isset($record[3]) && is_object($record[3])) ? date('Y-m-d', strtotime($record[3]->format('Y-m-d'))) : $record[3];
                $recordSave['refund_time'] = (isset($record[4]) && is_object($record[4])) ? date('Y-m-d', strtotime($record[4]->format('Y-m-d'))) : $record[4];
                $recordSave['address'] = isset($record[5]) ? $record[5] : '';

                $return[] = $recordSave;
            }

            Log::info(__CLASS__.__METHOD__.'third_detail_data：', [$records, $return]);
            return $return;

        }catch (\Exception $e){
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);
        }
        return false;
    }
}
