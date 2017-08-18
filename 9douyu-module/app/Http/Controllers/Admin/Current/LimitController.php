<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/22
 * Time: 下午2:51
 */

namespace App\Http\Controllers\Admin\Current;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Current\CashLimitLogic;
use App\Lang\LangModel;
use App\Tools\AdminUser;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;

class LimitController extends AdminController
{
    protected $homeName = '额度管理';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.current.limit.create');
    }


    /**
     * @param Request $request
     * @return array $list
     * @DESC 列表
     */
    public function lists( Request $request )
    {

        $page       = $request->input('page', 1);

        $phone      = $request->input('phone');

        $size       = 20;

        $logic      = new CashLimitLogic();

        $list       = $logic->getList( $page, $size ,$phone);

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/current/limit/lists');

        $paginate   = $toolPaginate->getPaginate();

        $viewDate   = [
            'home'  => $this -> homeName,
            'phone' => $phone,
            'title' => '额度列表',
            'list'   => $list['list'],
            'total'  => $list['total'],
            'paginate' => $paginate,
        ];

        return view('admin.current.limit.lists',$viewDate);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 执行添加
     */
    public function doCreate(Request $request)
    {
        $data       =   $request->all();
        $data['admin_id'] = AdminUser::getAdminUserId();

        $logic      =   new CashLimitLogic();

        $return     =   $logic->doCreate($data);

        \Log::Info(__CLASS__.__METHOD__.__LINE__,[$return]);

        if($return['status']){

            return redirect('admin/current/limit/create')->with('message', LangModel::getLang('ERROR_CURRENT_CASH_LIMIT_ADD_SUCCESS'));

        }else {

            return redirect()->back()->withInput($request->input())->with('fail', LangModel::getLang('ERROR_CURRENT_CASH_LIMIT_ADD_FAILED'));

        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 编辑限额
     */
    public function edit( Request $request)
    {
        $id     =   $request->input('id');

        $logic  =   new CashLimitLogic();

        $return =   $logic->getById($id);

        return view('admin.current.limit.edit',$return);
    }

    public function doEdit( Request $request)
    {
        $id         =   $request->input('id');

        $cash       =   $request->input('cash');

        $inCash     =   $request->input('in_cash');

        $status     =   $request->input('status');

        $manageId   =   AdminUser::getAdminUserId();

        $logic      =   new CashLimitLogic();

        $return     =   $logic->doEdit($id,$cash,$inCash,$status,$manageId);

        if( $return['status'] ){

            return redirect('admin/current/limit/edit?id='.$id)->with('message', LangModel::getLang('ERROR_CURRENT_CASH_LIMIT_EDIT_SUCCESS'));
        }

        return redirect()->back()->withInput($request->input())->with('message', LangModel::getLang('ERROR_CURRENT_CASH_LIMIT_EDIT_FAILED'));
    }
}