<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 债权控制器
 */

namespace App\Http\Controllers\Admin\Credit;

use App\Http\Controllers\Admin\AdminController;

use App\Http\Logics\Credit\CreditLogic;

use App\Http\Logics\Credit\CreditThirdLogic;

use App\Http\Requests\Admin\Credit\CreateThirdCreditRequest;

use App\Http\Dbs\Credit\CreditDb;

use Excel, Log;

class CreditThirdController extends AdminController implements CreditController{

    /**
     * 创建债权 form 表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){

        $viewData = [
            'currentSource'         => CreditDb::SOURCE_THIRD_CREDIT,
            'currentType'           => CreditDb::TYPE_BASE,
            'source'                => CreditLogic::getSource(),
            'type'                  => CreditLogic::getType(),
            'productLine'           => CreditLogic::getProductLine(),
            'repaymentMethod'       => CreditLogic::getRefundTypeForOperation(),
            'star'                  => CreditLogic::getStar(),
            'sex'                   => CreditLogic::getSexData(),
            'risk'                  => CreditLogic::getRiskcalcLevel(),
        ];
        return view('admin.credit.create.thirdCredit', $viewData);
    }

    /**
     * 创建债权
     *
     * @param CreateThirdCreditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doCreate(CreateThirdCreditRequest $request){
        $data        = $request->all();
        $creditInfo        = $this->import($request);
        if($creditInfo) {
            $data['credit_list'] = json_encode($creditInfo);
        }else{
            $data['credit_list'] = '';
        }

        $logicResult = CreditThirdLogic::doCreate($data);
        if($logicResult['status']){
            return redirect('/admin/credit/lists/third')->with('message', '创建债权成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', '数据库操作返回异常！');
        }
    }

    /**
     * 债权列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function lists(){
        $condition                  = []; //todo 查询条件

        $data['list']               = CreditThirdLogic::getList($condition);
        $data['productLine']        = CreditLogic::getProductLine();
        $data['repaymentMethod']    = CreditLogic::getRefundType();
        $data['dayOrMonth']         = CreditLogic::getLoanDeadlineDayOrMonth();
        return view('admin.credit.lists.thirdCredit', $data);
    }

    /**
     * 编辑债权视图
     * @param int $id 指定债权ID
     * @return mixed
     */
    public function edit($id, \Illuminate\Http\Request  $request){

        $logicResult = CreditThirdLogic::findById($id);

        if(!$logicResult['status'] && empty($logicResult['data']['obj'])){
            return redirect()->back()->withInput($request->input())->with('fail', '找不到该债权！');
        }

        $viewData = [
            'obj'                   => $logicResult['data']['obj'],
            'currentSource'         => CreditDb::SOURCE_THIRD_CREDIT,
            'currentType'           => CreditDb::TYPE_BASE,
            'source'                => CreditLogic::getSource(),
            'type'                  => CreditLogic::getType(),
            'productLine'           => CreditLogic::getProductLine(),
            'repaymentMethod'       => CreditLogic::getRefundTypeForOperation(),
            'star'                  => CreditLogic::getStar(),
            'sex'                   => CreditLogic::getSexData(),
        ];

        return view('admin.credit.edit.thirdCredit', $viewData);
    }


    /**
     * 执行编辑债权
     * @param CreateThirdCreditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doEdit(CreateThirdCreditRequest $request){
        $data        = $request->all();
        $creditInfo  = $this->import($request);

        if($creditInfo) {
            $data['credit_list'] = json_encode($creditInfo);
        }else{
            $data['credit_list'] = '';
        }

        $logicResult = CreditThirdLogic::doUpdate($data);
        if($logicResult['status']){
            return redirect('/admin/credit/lists/third')->with('message', '编辑债权成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
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
            $subPath = storage_path('admin/upload/credit');
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

            Log::info(__METHOD__ . ' 上传文件详情：', $data);

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

            Log::info('表格数据：', [$records, $return]);
            return $return;

        }catch (\Exception $e){
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);
        }
        return false;
    }


}