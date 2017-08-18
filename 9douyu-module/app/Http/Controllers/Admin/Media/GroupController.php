<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/30
 * Time: 14:49
 */

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Media\GroupLogic;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Media\GroupRequest;

class GroupController extends AdminController{

    /**
     * 自媒体分组列表页
     */
    public function index(){

        $logic  = new GroupLogic();
        
        $list   = $logic->getList();

        $viewData['paginate'] = $list;

        return view('admin.media.group.list',$viewData);
        
    }


    /**
     * @param $id
     * 自媒体分组编辑
     */
    public function edit($id){

        $logic      = new GroupLogic();
        $list       = $logic->getById($id);

        return view('admin.media.group.edit', $list);
    }


    /**
     * @param Request $request
     * 保存编辑的分组信息
     */
    public function doEdit(GroupRequest $request){

        $logic      = new GroupLogic();

        $id     = $request->input('id',0);
        $name   = $request->input('name','');
        $desc   = $request->input('desc','');

        $logicResult = $logic->doEdit($id,$name,$desc);

        if($logicResult['status']){
            return redirect()->back()->withInput($request->input())->with('message', '编辑成功');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
    }


    /**
     * @return mixed
     * 添加分组页面
     */
    public function create(){
        
        return view('admin.media.group.create',[]);
    }

    /**
     * @param Request $request
     * 保存添加的分组信息
     */
    public function doCreate(GroupRequest $request){

        $name   = $request->input('name','');
        $desc   = $request->input('desc','');

        $logic = new GroupLogic();
        $logicResult = $logic->create($name,$desc);

        if($logicResult['status']){

            return redirect('/admin/media/group/lists')->with('message', '添加成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
        
    }


    /**
     * @param $id
     * @return mixed
     * 删除指定的分组
     */
    public function delete($id){

        $logic = new GroupLogic();

        $logicResult = $logic->delete($id);

        if($logicResult['status']){

            $msg = '删除成功';
        }else {

            $msg = $logicResult['msg'];
        }

        return redirect('/admin/media/group/lists')->with('message', $msg);


    }
}