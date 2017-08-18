<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/30
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Media\ChannelLogic;
use App\Http\Logics\Media\GroupLogic;
use App\Http\Requests\Admin\Media\ChannelRequest;
use Illuminate\Http\Request;

class ChannelController extends AdminController{

    /**
     * 自媒体渠道列表页
     */
    public function index(Request $request){

        $logic  = new ChannelLogic();

        $params =   [
            'name'      =>  $request->input('name'),
            'group_id'  =>  $request->input('group_id'),
        ];
        $list   = $logic->getList($params);
        $groupList  =   (new GroupLogic())->getList();
        $viewData['paginate'] = $list;
        $viewData['groupList'] = $groupList['data'];
        $viewData['params']    = $params;

        return view('admin.media.channel.list',$viewData);

    }


    /**
     * @param $id
     * 自媒体渠道编辑
     */
    public function edit($id){

        $logic      = new ChannelLogic();
        $list       = $logic->getById($id);

        $logic      = new ChannelLogic();
        $groupList  = $logic->getGroupList();
        $list['group_list'] = $groupList;


        return view('admin.media.channel.edit', $list);
    }


    /**
     * @param Request $request
     * 保存编辑的渠道信息
     */
    public function doEdit(ChannelRequest $request){

        $logic      = new ChannelLogic();

        $data     = $request->all();


        $logicResult = $logic->doEdit($data);

        if($logicResult['status']){
            return redirect()->back()->withInput($request->input())->with('message', '编辑成功');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
    }


    /**
     * @return mixed
     * 添加渠道页面
     */
    public function create(){

        $logic      = new ChannelLogic();
        $groupList  = $logic->getGroupList();

        return view('admin.media.channel.create',['group_list' => $groupList]);
    }

    /**
     * @param Request $request
     * 保存添加的渠道信息
     */
    public function doCreate(ChannelRequest $request){

        $data = $request->all();
        
        $logic = new ChannelLogic();
        $logicResult = $logic->create($data);

        if($logicResult['status']){

            return redirect('/admin/media/channel/lists')->with('message', '添加成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }

    }


    /**
     * @param $id
     * @return mixed
     * 删除指定的渠道
     */
    public function delete($id){

        $logic = new ChannelLogic();

        $logicResult = $logic->delete($id);

        if($logicResult['status']){

            $msg = '删除成功';
        }else {

            $msg = $logicResult['msg'];
        }

        return redirect('/admin/media/channel/lists')->with('message', $msg);


    }

}