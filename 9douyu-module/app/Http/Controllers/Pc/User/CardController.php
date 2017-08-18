<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/7/11
 * Time: 下午4:47
 * Desc: 银行卡管理
 */

namespace App\Http\Controllers\Pc\User;

use App\Http\Controllers\Pc\UserController;
use App\Http\Logics\BankCard\CardLogic;
use App\Http\Logics\Pay\WithdrawLogic;
use App\Http\Logics\User\UserLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CardController extends UserController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 用户提现银行卡列表
     */
    public function index()
    {

        $this->checkIdentity();
        
        $userId = $this->getUserId();

        $withDrawLogic = new WithdrawLogic();

        $result = $withDrawLogic->getWithdrawCardForApp($userId);

        $data['cards'] = isset($result['data']) ? $result['data'] : '';

        return view('pc.user.card', $data);

    }

    /**
     * @desc 添加提现银行卡的页面
     * @author linguanghui
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addBankCard(){

        $userId = $this->getUserId();

        $userLogic  =  new UserLogic();

        $data['userInfo']   =  $userLogic->getUser($userId);

        return view('pc.user.addBankCard',$data);

    }

    /**
     * @desc 提交添加提现银行卡的信息
     * @param Request $request
     * @return mixed
     */
    public function submit(Request $request){
        
        $data = $request->all();
        $cardLogic  = new CardLogic();
        //添加提现银行卡
        $return = $cardLogic->doAddBankCard($data);
        if($return['status']){
            return Redirect::to('user/bankcard/success');
        }
        return Redirect::to('user/bankcard/add')->with('errors',$return['msg']);
    }

    /**
     * @desc 添加提现银行卡成功页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success(){
        return view('pc.user.addWithdrawCardSuccess');
    }

}