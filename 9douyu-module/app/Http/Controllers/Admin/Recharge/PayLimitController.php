<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/18
 * Time: 14:31
 */

namespace App\Http\Controllers\Admin\Recharge;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Recharge\PayLimitLogic;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Recharge\PayLimitRequest;

class PayLimitController extends AdminController{


    /**
     * @param Request $request
     * 支付限额列表入口页
     */
    public function index($pay_type = 'all',$bank_id = 0){

        $logic      = new PayLimitLogic();

        $bankId     = isset($_GET['bank_id']) ? $_GET['bank_id'] : $bank_id;

        $list       = $logic->getListByType($pay_type,$bankId);

        $bankList   = $logic->getBankList();

        $typeList   = $logic->getPayTypeName();

        $data['paginate']          = $list;
        $data['typeList']          = $typeList;
        $data['bankList']          = $bankList;
        $data['params']            = [
            'bank_id' => $bankId,
            'pay_type'  => $pay_type
        ];

        return view('admin.recharge.pay_limit.list', $data);

    }


    /**
     * @return mixed
     * 支付限额添加银行通道
     */
    public function create(){

        $logic = new PayLimitLogic();
        
        $data['bank_list'] = $logic->getAllBank();
        
        $data['type_list'] = $logic->getPayTypeName();

        return view('admin.recharge.pay_limit.create',$data);
    }


    /**
     * @param CreditRequest $request
     * 添加零钱计划债权
     */
    public function doCreate(PayLimitRequest $request){

        $data = $request->all();
        $logic      = new PayLimitLogic();

        $logicResult = $logic->doCreate($data);

        if($logicResult['status']){

            return redirect('/admin/paylimit/lists')->with('message', '添加银行限额成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
    }


    public function edit($id){

        $logic      = new PayLimitLogic();

        $list       = $logic->getById($id);
        
        return view('admin.recharge.pay_limit.edit', $list);
    }


    /**
     * @param Request $request
     * @return mixed
     * 编辑
     */
    public function doEdit(Request $request){

        $data = $request->all();

        $logic = new PayLimitLogic();

        $logicResult = $logic->doEdit($data);

        if($logicResult['status']){

            return redirect('/admin/paylimit/lists')->with('message', '编辑成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
    }


    /**
     * @param $id
     * @param $status
     * @return mixed
     * 禁用或启用
     */
    public function doEditStatus($id,$status){

        $logic = new PayLimitLogic();

        $logicResult = $logic->doEditStatus($id,$status);

        if($logicResult['status']) {

            $msg = '操作成功';
        }else{

            $msg = '操作失败';
        }


        return redirect('/admin/paylimit/lists')->with('message', $msg);

    }
}