<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/10/11
 * Time: 14:52
 */

namespace App\Http\Controllers\Admin\Recharge;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Pay\RechargeLogic;

class OnlineController extends AdminController{


    /**
     * 京东网银支持的银行列表
     */
    public function index(){

        $logic = new RechargeLogic();
        $result = $logic->getJdOnlineBankList();

        return view('admin.recharge.bank_code.online',['list' => $result]);
    }


    /**
     * @param $bankId
     * @param $status
     * @param $type
     * 编辑显示状态
     */
    public function doEditStatus($bankId,$status,$type){

        $logic = new RechargeLogic();
        $logicResult = $logic->doEditStatus($bankId,$type,$status);
        
        if($logicResult['status']) {

            $msg = '操作成功';
        }else{

            $msg = '操作失败';
        }

        return redirect('/admin/bankcode/online/lists')->with('message', $msg);
    }
}