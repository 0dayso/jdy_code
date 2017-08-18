<?php
/**
 * Create by PhpStorm
 * @author lin.guanghui
 * Date 16/07/21
 * Desc: PC端提现页面
 */
namespace App\Http\Controllers\Pc\Order;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pc\UserController;
use App\Http\Logics\Pay\WithdrawLogic;
use App\Http\Models\Pay\WithdrawModel;
use Illuminate\Http\Request;
use App\Http\Models\Common\CoreApi\OrderModel;
use Redirect;

class WithdrawController extends UserController
{

    public function __construct()
    {
        parent::__construct();

        $this->checkIdentity();

    }
    /**
     * @desc PC端提现页面
     * @author linguanghui
     * create by Phpstorm
     * Date 16/07/21
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $withdrawLogic = new WithdrawLogic();
        $withdrawModel = new WithdrawModel();
        //用户信息
        $userInfo = $withdrawLogic->getUser($this->getUserId());
        if(empty($userInfo)){
            return Redirect::to('/login');
        }
        $assign['userInfo'] = $userInfo;
        //提现卡
        $assign['withdrawCard'] = $withdrawLogic->getWithdrawCard($this->getUserId());
        //计算用户提现手续费
        $assign['commission'] = $withdrawLogic->getCommission($this->getUserId());
        //获取用户已经提现次数
        $assign['withDrawNum'] = $withdrawModel->getWithDrawNum($this->getUserId());
        //获取每月可免手续费提现次数
        $assign['maxFreeNum']  = $withdrawLogic->getMaxFreeNum();
        //获取用户超过提现次数后的手续费
        $assign['handlingFree']  = $withdrawLogic->getHandlingFree();
        //最小提现金额
        $assign['minMoney']            = $withdrawLogic->getMinWithdraw();
        return view('pc.order.withdraw',$assign);
    }
    /**
     * @desc 提现数据的提交
     * @author lingaunghui
     * @param $request
     * Create by Phpstorm
     * Date 16/07/21
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request){
        $data = $request->all();
        $data['cash'] = $data['withdraw_cash'];

        $withdrawLogic = new WithdrawLogic();

        $data['userId'] = $this->getUserId();

        //验证数据
        $vaild = $withdrawLogic->vaildData($data);

        if(!$vaild['status']){
            return Redirect::to('/pay/withdraw')->with('errors',$vaild['msg']);
        }

        //创建订单
        $orderId = $withdrawLogic->createOrder($data);

        if(!empty($orderId)){
            return Redirect::to('/pay/withdraw/success/'.$orderId['data']['order_id'])->withCookie('successData',$data);
        }
        return Redirect::to('/pay/withdraw/');
    }

    /**
     * @desc 提现成功页面
     * @author linguanghui
     * @param $orderId string
     * Date 16/07/21
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success($orderId){
        //获取订单信息
        $orderInfo = OrderModel::getOrderInfo($orderId);

        $withdrawLogic = new WithdrawLogic();
        //用户信息
        if(!empty($orderInfo)){
            $orderInfo['real_name'] = $withdrawLogic->getUser($orderInfo['user_id'])['real_name'];
        }else{
            $orderInfo['real_name'] = $withdrawLogic->getUser($this->getUserId())['real_name'];
            $orderInfo['card_number'] = $withdrawLogic->getWithdrawCard($this->getUserId())[0]['card_no'];
        }
        $assign = [
            'orderInfo' => $orderInfo
        ];
        return view('pc.order.withdrawSuccess', $assign);
    }
}