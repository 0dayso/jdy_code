<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 债权房产抵押控制器
 */

namespace App\Http\Controllers\Admin\Credit;

use App\Http\Controllers\Admin\AdminController;

use App\Http\Logics\Credit\CreditLogic;

use App\Http\Logics\Credit\CreditHousingLogic;

use App\Http\Requests\Admin\Credit\CreateCreditHousingMortgageRequest;

use App\Http\Dbs\Credit\CreditDb;

class CreditHousingController extends AdminController implements CreditController{

    /**
     * 创建债权 form 表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){

        $viewData = [
            'currentSource'         => CreditDb::SOURCE_HOUSING_MORTGAGE,
            'currentType'           => CreditDb::TYPE_BASE,
            'source'                => CreditLogic::getSource(),
            'type'                  => CreditLogic::getType(),
            'productLine'           => CreditLogic::getProductLine(),
            'repaymentMethod'       => CreditLogic::getRefundTypeForOperation(),
            'star'                  => CreditLogic::getStar(),
            'sex'                   => CreditLogic::getSexData(),
            'creditor'              => CreditLogic::getCreditor(),
        ];
        return view('admin.credit.create.housingMortgage', $viewData);
    }

    /**
     * 创建债权
     *
     * @param CreateCreditHousingMortgageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doCreate(CreateCreditHousingMortgageRequest $request){
        $data        = $request->all();
        $logicResult = CreditHousingLogic::doCreate($data);
        if($logicResult['status']){
            return redirect('/admin/credit/lists/housing')->with('message', '创建债权成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', '数据库操作返回异常！');
        }
    }

    /**
     * 债权列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function lists(){
        $condition                  = []; //todo 查询条件

        $data['list']               = CreditHousingLogic::getList($condition);
        $data['productLine']        = CreditLogic::getProductLine();
        $data['repaymentMethod']    = CreditLogic::getRefundType();
        $data['dayOrMonth']         = CreditLogic::getLoanDeadlineDayOrMonth();
        return view('admin.credit.lists.housingMortgage', $data);
    }

    /**
     * 编辑债权视图
     * @param int $id 指定债权ID
     * @return mixed
     */
    public function edit($id, \Illuminate\Http\Request  $request){

        $logicResult = CreditHousingLogic::findById($id);

        if(!$logicResult['status'] && empty($logicResult['data']['obj'])){
            return redirect()->back()->withInput($request->input())->with('fail', '找不到该债权！');
        }

        $viewData = [
            'obj'                   => $logicResult['data']['obj'],
            'currentSource'         => CreditDb::SOURCE_HOUSING_MORTGAGE,
            'currentType'           => CreditDb::TYPE_BASE,
            'source'                => CreditLogic::getSource(),
            'type'                  => CreditLogic::getType(),
            'productLine'           => CreditLogic::getProductLine(),
            'repaymentMethod'       => CreditLogic::getRefundTypeForOperation(),
            'star'                  => CreditLogic::getStar(),
            'sex'                   => CreditLogic::getSexData(),
            'creditor'              => CreditLogic::getCreditor(),
        ];

        return view('admin.credit.edit.housingMortgage', $viewData);
    }

    /**
     * 执行编辑债权
     * @param CreateCreditHousingMortgageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doEdit(CreateCreditHousingMortgageRequest $request){
        $data        = $request->all();
        $logicResult = CreditHousingLogic::doUpdate($data);
        if($logicResult['status']){
            return redirect('/admin/credit/lists/housing')->with('message', '编辑债权成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }
    }

}