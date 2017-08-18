<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 16:03
 */

namespace App\Http\Controllers\Admin\Current;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Current\CreditLogic;
use App\Http\Requests\Admin\Current\CreditRequest;
use App\Tools\AdminUser;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;

class CreditController extends AdminController{

    /**
     * 零钱计划债权列表详情
     */
    public function creditList(){

        $condition                  = []; //todo 查询条件

        $list = CreditLogic::getList($condition);

        //金额转化为万元
        if($list['data']){

            foreach($list['data'] as $key=>$val){

                $totalAmount = ToolMoney::formatDbCashDeleteTenThousand($val['total_amount']);
                $totalAmount = ToolMoney::formatDbCashDelete($totalAmount);
                $list['data'][$key]['total_amount'] = $totalAmount;

            }
        }

        $data['paginate']           = $list;
        $data['refundTypeList']     = CreditLogic::getRefundType();

        return view('admin.current.lists.credit', $data);

    }

    /**
     * @return mixed
     * 创建零钱计划债权 form 表单
     */
    public function create(){

        $viewData = [
            'type'  => CreditLogic::getRefundType(),//获取还款类型
        ];
        return view('admin.current.create.credit', $viewData);

    }

    /**
     * @param CreditRequest $request
     * 添加零钱计划债权
     */
    public function doCreate(CreditRequest $request){
        
        $data = $request->all();
        
        $data['total_amount'] = CreditLogic::formatCash($data['total_amount']);
        $data['manage_id']    = AdminUser::getAdminUserId();

        $logicResult = CreditLogic::doCreate($data);

        if($logicResult['status']){

            return redirect('/admin/current/credit/lists')->with('message', '创建债权成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
    }


    /**
     * @param $id
     * @return mixed
     * 编辑零钱计划债权
     */
    public function edit($id,Request $request){

        $logicResult = CreditLogic::findById($id);

        if(!$logicResult['status'] && empty($logicResult['data']['obj'])){

            return redirect()->back()->withInput($request->input())->with('fail', '找不到该债权！');

        }

        $viewData = [
            'obj'                   => $logicResult['data']['obj'],
            'type'  => CreditLogic::getRefundType(),//获取还款类型
        ];
        return view('admin.current.edit.credit', $viewData);

    }


    /**
     * @param CreditRequest $request
     * 编辑零钱计划债权
     */
    public function doEdit(CreditRequest $request){
        
        $data = $request->all();

        $data['total_amount'] = CreditLogic::formatCash($data['total_amount']);
        $data['manage_id']    = AdminUser::getAdminUserId();

        $logicResult = CreditLogic::doEdit($data);

        if($logicResult['status']){

            return redirect('/admin/current/credit/lists')->with('message', '编辑债权成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
    }


    /**
     * @param $id
     * @return mixed
     * 获取债权详情列表
     */
    public function creditDetailList($id){

        $list = CreditLogic::getDetailList($id);

        foreach($list['data'] as $key=>$val){
            $list['data'][$key]['amount'] = ToolMoney::formatDbCashDelete($list['data'][$key]['amount']);
        }

        $data['paginate'] = $list;

        return view('admin.current.lists.creditDetail', $data);

    }
}