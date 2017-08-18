<?php
/**
 * create by phpstorm
 * User: lgh
 * Date: 16/08/23
 * Time: 15:47 Pm
 * @desc 银行卡管理后台
 */
namespace App\Http\Controllers\Admin\BankCard;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\BankCard\CardLogic;
use App\Http\Models\Bank\CardModel;
use App\Http\Models\Common\CoreApi\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Class BankCardController
 * @package App\Http\Controllers\Admmin\BankCard
 */
class BankCardController extends AdminController{
    const PAGE_SIZE = 20; //设置列表每页条数

    /**
     * @desc 更换银行卡的页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changeCard(){

        //获取所有银行卡列表
        $assign['banks']  = CardModel::getBanks();

        return view('admin.bank.changeCard',$assign);
    }

    /**
     * @desc 更换银行卡操作
     * @param Request $request
     * @return mixed
     */
    public function doChangeCard(Request $request){

        $cardLogic  = new CardLogic();

        $param = $request->all();
        if($param){
            //更换银行卡的操作
            $return = $cardLogic->doChangeBankCard($param);
        }
        return Redirect::to('/admin/bankcard/change')->withInput($request->input())->with('errorMsg', $return['msg']);
    }

    /**
     * @desc 检测银行卡号实名信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkUserCard(){
        return view('admin.bank.checkUserCard');
    }

    /**
     * @desc 检测银行卡实名信息
     * @param Request $request
     * @return mixed
     */
    public function doCheckUserCard(Request $request){
        $cardLogic  = new CardLogic();
        $param = $request->all();

        if($param){
            //更换银行卡的操作
            $return = $cardLogic->checkUserCard($param);
        }

        return Redirect::to('/admin/bankcard/checkUserCard')->with('errorMsg', $return['msg']);
    }

    /**
     * @desc  Ajax获取检测银行卡实名用户的信息
     * @param Request $request
     * @return string
     */
    public function getCheckUserInfo(Request $request){

        $phone  = $request->input('phone');

        $return  =  UserModel::getBaseUserInfo($phone);

        if($return){
            $userInfo['status'] = true;
        }else{
            $return = [
                'status'=> false,
                'msg'   => '没有获取到用户信息'
            ];
        }

        return self::ajaxJson($return);
    }


    /**
     * @return mixed
     * 先锋支付银行卡解绑
     */
    public function unbind(){

        return view('admin.bank.ucfunbind');

    }

    /**
     * @param Request $request
     * @return mixed
     * 先锋支付银行卡解绑
     */
    public function doUnbind(Request $request){

        $phone = $request->input('phone');
        $cardNo = $request->input('card_no');

        $cardLogic = new CardLogic();

        $logicResult = $cardLogic->ucfUnbind($phone,$cardNo);
        if($logicResult['status']){

            return redirect('/admin/bankcard/unbind')->with('message', '解绑成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('message', $logicResult['msg']);
        }

    }
}
