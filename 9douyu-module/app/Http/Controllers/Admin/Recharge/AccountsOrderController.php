<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/10
 * Time: 下午4:50
 */

namespace App\Http\Controllers\Admin\Recharge;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Dbs\Order\CheckOrderRecordDb;
use App\Http\Logics\Recharge\CheckBatchLogic;
use App\Http\Logics\Recharge\CheckOrderRecordLogic;
use App\Tools\AdminUser;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;


class AccountsOrderController extends AdminController
{


    CONST
        SIZE = 20;

    public function getList( Request $request)
    {
        $page   =   $request->get('page',1);

        $size   =   self::SIZE;

        $logic  =   new CheckOrderRecordLogic();

        $batchLogic =   new CheckBatchLogic();

        $list   =   $logic->getList($page , $size);

        $toolPaginate   = new ToolPaginate($list['total'], $page, $size, '/admin/accounts/checkList');

        $paginate       =   $toolPaginate->getPaginate();

        $checkRecord    =   $logic->getNotCheckRecordTotal();

        $accountsType   =   $batchLogic->getRechargeType();

        $viewData = [
            'list'        => $list['list'],
            'total'       => $list['total'],
            'paginate'    => $paginate,
            'accountsList'=> $accountsType,
            'checkRecord' => $checkRecord,
        ];

        return view('admin.accounts.checkList',$viewData);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 未处理的订单
     */
    public function untreated(Request $request)
    {
        $page   =   $request->get('page',1);

        $size   =   self::SIZE;

        $logic  =   new CheckOrderRecordLogic();

        $batchLogic =   new CheckBatchLogic();

        $status =   CheckOrderRecordDb::CHECK_STATUS_PENDING;

        $list   =   $logic->getList($page , $size,$status);

        $toolPaginate   = new ToolPaginate($list['total'], $page, $size, '/admin/accounts/untreated');

        $paginate       =   $toolPaginate->getPaginate();

        $checkRecord    =   $logic->getNotCheckRecordTotal();

        $accountsType   =   $batchLogic->getRechargeType();

        $viewData = [
            'list'        => $list['list'],
            'total'       => $list['total'],
            'paginate'    => $paginate,
            'accountsList'=> $accountsType,
            'checkRecord' => $checkRecord,
        ];
        
        return view('admin.accounts.untreatedList',$viewData);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 已经处理的订单
     */
    public function handled(Request $request)
    {
        $page   =   $request->get('page',1);

        $size   =   self::SIZE;

        $logic  =   new CheckOrderRecordLogic();

        $batchLogic =   new CheckBatchLogic();

        $status =   CheckOrderRecordDb::CHECK_STATUS_SUCCESS;

        $list   =   $logic->getList($page , $size,$status);

        $toolPaginate   = new ToolPaginate($list['total'], $page, $size, '/admin/accounts/handled');

        $paginate       =   $toolPaginate->getPaginate();

        $checkRecord    =   $logic->getNotCheckRecordTotal();

        $accountsType   =   $batchLogic->getRechargeType();

        $viewData = [
            'list'        => $list['list'],
            'total'       => $list['total'],
            'paginate'    => $paginate,
            'accountsList'=> $accountsType,
            'checkRecord' => $checkRecord,
        ];

        return view('admin.accounts.handledList',$viewData);
    }

    /**
     * @param Request $request
     * @desc 处理订单
     */
    public function doHandled(Request $request)
    {
        $id     =   $request->input('id');

        $note   =   $request->input('note');

        $logic  =   new CheckOrderRecordLogic();

        $data   =   [
            'is_check'   => CheckOrderRecordDb::CHECK_STATUS_SUCCESS,
            'admin_id'   => AdminUser::getAdminUserId(),
            'tackle_note'=> $note,
        ];

        $return =   $logic->doUpdate($id,$data);

        return json_encode($return);
    }
}