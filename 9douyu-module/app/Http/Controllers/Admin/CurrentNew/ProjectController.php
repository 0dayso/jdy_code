<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/21
 * Time: 下午4:21
 */

namespace App\Http\Controllers\Admin\CurrentNew;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\CurrentNew\ProjectLogic;
use Illuminate\Http\Request;

class ProjectController extends AdminController
{

    const PAGE_SIZE = 20; //设置列表每页条数

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 项目列表
     */
    public function index(Request $request){

        $page       = $request->input('page',1);

        $logic = new ProjectLogic();

        $data = $logic->getAdminProjectList($page, self::PAGE_SIZE);

        $assign['projectList'] = $data['data'];

        //page分页信息
        $assign['pageInfo'] =[
            "total"         => $data['total'],
            "last_page"     => $data['last_page'],
            "per_page"      => $data['per_page'],
            "current_page"  => $data['current_page'],
            "url"           => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"],
        ];

        return view('admin.current_new.project.list', $assign);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 创建项目
     */
    public function create(){

        return view('admin.current_new.project.create');

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 执行创建项目
     */
    public function doCreate(Request $request){

        $name        = $request->input('name','');              //项目名称
        $totalAmount = $request->input('total_amount','');      //融资总额
        $publishAt   = $request->input('publish_at','');        //发布时间
        $status      = $request->input('status','');            //状态

        $logic      = new ProjectLogic();
        $logicResult = $logic->create($name, $totalAmount, $status, $publishAt);

        if($logicResult['status']){

            return redirect('/admin/currentNew/project/lists')->with('message', '创建项目成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * 编辑
     */
    public function edit($id){

        $logic = new ProjectLogic();

        $data = $logic->getProjectInfoById($id);

        if(empty($data)){

            return redirect('/admin/currentNew/project/lists')->with('message', '项目不存在！');

        }

        return view('admin.current_new.project.edit', $data);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 执行编辑
     */
    public function doEdit(Request $request){

        $id             = $request->input('id', '');
        $name           = $request->input('name', '');
        $cash           = (int)$request->input('total_amount', '');
        $status         = $request->input('status', '');
        $publishAt      = $request->input('publish_at', '');

        $logic = new ProjectLogic();

        $data = $logic->edit($id, $name, $cash, $status, $publishAt);

        if($data['status']){

            return redirect('/admin/currentNew/project/lists')->with('message', '修改项目成功！');

        }else {

            return redirect()->back()->withInput($request->input())->with('fail', $data['msg']);

        }

    }

}