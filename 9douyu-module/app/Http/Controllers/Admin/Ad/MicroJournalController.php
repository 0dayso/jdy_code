<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/19
 * Time: 下午2:32
 */

namespace App\Http\Controllers\Admin\Ad;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Micro\MicroJournalLogic;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;

class MicroJournalController extends AdminController
{
    protected $homeName = '微刊管理';

    const PAGE_SIZE = 20; //设置列表每页条数
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 列表数据
     */
    public function getMicroJournalList( Request $request)
    {

        $page       = $request->input('page', 1);

        $size       = self::PAGE_SIZE;

        $logic      = new MicroJournalLogic();

        $list       = $logic->getMicroJournalList( $page, $size );

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/micro');

        $paginate   = $toolPaginate->getPaginate();

        $viewDate   = [
            'list'   => $list['list'],
            'total'  => $list['total'],
            'paginate' => $paginate,
        ];
        return view('admin.micro.microList', $viewDate);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 添加数据
     */
    public function addMicroJournal()
    {
        return view('admin.micro.addMicro');
    }

    /**
     * @param Request $request
     * @desc 执行添加
     */
    public function doAdd(Request $request)
    {
        $data       =   $request->all();

        $logic      =   new MicroJournalLogic();

        $return     =   $logic->doAdd($data);
        
        if( $return['status'] == false ){

            return redirect()->back()->withInput($request->input())->with('fail', $return['msg']);
        }

        return redirect('/admin/micro/addMicro')->with('success', "添加成功");

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 编辑数据
     */
    public function editMicroJournal( Request $request)
    {
        $id     =   $request->input('id');

        $logic  =   new MicroJournalLogic();

        $result =   $logic->getById($id);

        if( empty($result) ){

            return redirect('/admin/micro')->with('fail', "数据不存在");
        }
        $viewData   =   [
            'info'  =>$result,
        ];
        return view('admin.micro.editMicro', $viewData);
    }

    /**
     * @param Request $request
     * @desc 执行编辑
     */
    public function doEdit( Request $request)
    {
        $data       =   $request->all();

        $id         =   $request->input('id');

        $logic      =   new MicroJournalLogic();

        $return     =   $logic->doEdit($id, $data);

        if( $return['status'] == false ){

            return redirect()->back()->withInput($request->input())->with('fail', $return['msg']);
        }

        return redirect('/admin/micro/editMicro?id='.$id)->with('success', "修改微刊信息成功");

    }

    /**
     * @param Request $request
     * @desc 删除数据
     */
    public function delete( Request $request)
    {

        $id         =   $request->input('id');

        $logic      =   new MicroJournalLogic();

        $return     =   $logic->doDelete($id);

        if( $return['status'] == false ){

            return redirect()->back()->withInput($request->input())->with('fail', $return['msg']);
        }

        return redirect('/admin/micro')->with('success', '微信信息删除成功');

    }
}