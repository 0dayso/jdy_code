<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/8
 * Time: 下午2:18
 */

namespace App\Http\Controllers\Admin\Recharge;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Dbs\Order\CheckBatchDb;
use App\Http\Logics\Recharge\CheckBatchLogic;
use App\Http\Logics\Recharge\CheckOrderRecordLogic;
use Illuminate\Http\Request;
use App\Tools\ToolPaginate;

class CheckBatchController extends AdminController
{

    CONST
        SIZE = 20;

    public function getList( Request $request)
    {

        $page   =   $request->get('page',1);

        $size   =   self::SIZE;

        $logic  =   new CheckBatchLogic();

        $orderLogic =   new CheckOrderRecordLogic();

        $list   =   $logic->getList($page , $size);

        $toolPaginate   = new ToolPaginate($list['total'], $page, $size, '/admin/accounts/batchList');

        $paginate       =   $toolPaginate->getPaginate();

        $accountsType   =   $logic->getRechargeType();

        $reviewStatus   =   $logic->getReviewStatus();

        $checkRecord    =   $orderLogic->getNotCheckRecordTotal();

        $viewData = [
            'list'        => $list['list'],
            'total'       => $list['total'],
            'paginate'    => $paginate,
            'accountsList'=> $accountsType,
            'reviewStatus'=> $reviewStatus,
            'checkRecord' => $checkRecord,
        ];

        return view('admin.accounts.batchList',$viewData);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 添加记录
     */
    public function doAddBatch( Request $request)
    {
        $data       =   $request->all();

        $logic      =   new CheckBatchLogic();

        $return     =   $logic->doAdd($data);

        if( $return['status'] ){

            return redirect()->back()->withInput($request->input())->with('success', '添加成功！');

        }

        return redirect()->back()->withInput($request->input())->with('fail', $return['msg']);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 审核对账的记录
     */
    public function doReview( Request $request )
    {
        $id     =   $request->input('id');
        
        $logic  =   new CheckBatchLogic();

        $data   =   [
            'status'    =>  CheckBatchDb::STATUS_WAIT_CHECK,
        ];

        $return =   $logic->doEdit($id ,$data);

        if( $return['status'] ){

            return redirect()->back()->withInput($request->input())->with('success', '审核成功！');

        }

        return redirect()->back()->withInput($request->input())->with('fail', $return['msg']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 删除
     */
    public function doDelete( Request $request)
    {
        $id     =   $request->input('id');

        $logic  =   new CheckBatchLogic();

        $return =   $logic->doDelete( $id );

        if( $return['status'] ){

            return redirect()->back()->withInput($request->input())->with('success', '删除成功！');

        }

        return redirect()->back()->withInput($request->input())->with('fail', $return['msg']);

    }
}