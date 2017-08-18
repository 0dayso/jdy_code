<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/14
 * Time: 下午6:27
 * Desc: 短信推送
 */

namespace App\Http\Controllers\Admin\Batch;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Dbs\Batch\BatchListDb;
use App\Http\Logics\Batch\BatchListLogic;
use App\Tools\AdminUser;
use App\Tools\FileUpload;
use Illuminate\Http\Request;
use App\Http\Logics\Oss\OssLogic;

class BatchListController extends AdminController{


    public function index( Request $request )
    {

        $type = $request->input('type');

        //获取短信发送列表
        $logic = new BatchListLogic();

        $list = $logic->getListByType($type);

        $data['list'] = $list;

        $data['type'] = BatchListDb::getTypeArr();

        return view('admin.batch/index', $data);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 添加
     */
    public function doAdd( Request $request )
    {

        $data['type'] = $request->input('type');

        $data['content'] = $request->input('content');

        //$file = $request->file('file');

        //$fileUpload = new FileUpload();

        //$uploadResult = $fileUpload->saveFile($file);

        //oss文件上传
        $file = $_FILES;

        $toolsUpload = new OssLogic();

        $uploadResult = $toolsUpload->putFile( $file['file'] );

        if( !$uploadResult['status'] ){

            return redirect()->back()->withInput($request->input())->with('fail', $uploadResult['msg']);

        }

        $data['file_path'] = '/'.$uploadResult['data']['path'].'/'.$uploadResult['data']['name'];

        $data['note'] = $request->input('note');

        $data['type'] = $request->input('type');

        $data['admin_id'] = AdminUser::getAdminUserId();

        $logic = new BatchListLogic();

        $res = $logic->doAdd($data);

        if( $res['status'] ){

            return redirect()->back()->withInput($request->input())->with('success', '添加成功！');

        }else{

            return redirect()->back()->withInput($request->input())->with('fail', $res['msg']);

        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @desc 标记审核成功
     */
    public function audit( $id )
    {

        $logic = new BatchListLogic();

        $res = $logic->doAuditById($id);

        if( $res ){

            return redirect()->back()->with('success', '审核成功！');

        }else{

            return redirect()->back()->with('fail', $res['msg']);

        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @desc 删除
     */
    public function del( $id ){

        $logic = new BatchListLogic();

        $res = $logic->doDelById($id);

        if( $res ){

            return redirect()->back()->with('success', '删除成功！');

        }else{

            return redirect()->back()->with('fail', '删除成功');

        }
    }

}
