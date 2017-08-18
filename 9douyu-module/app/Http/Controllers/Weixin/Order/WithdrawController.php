<?php
/**
 * @desc wap
 * @author lin.guanghui
 * create by Phpstorm
 * Date 16/07/26 Time Pm 18:27
 */
namespace App\Http\Controllers\Weixin\Order;


use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\Pay\RechargeLogic;
use App\Http\Models\Common\CoreApi\BankCardModel;
use App\Http\Logics\Pay\WithdrawLogic;
use App\Http\Models\Pay\LimitModel;
use App\Http\Models\Pay\WithdrawModel;
use Illuminate\Http\Request;
use App\Http\Models\Common\CoreApi\OrderModel;
use Redirect;

class WithdrawController extends UserController{


    public function __construct()
    {
        parent::__construct();

        $this->checkIdentity();//判断用户是否实名
    }

    /**
     * @desc 提现页面首页
     * @author linguanghui
     * create by Phpstorm
     * Date 16/07/21
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $withdrawLogic = new WithdrawLogic();
        $withdrawModel = new WithdrawModel();
        $rechargeLogic = new RechargeLogic();

        $userId = $this->getUserId();

        //用户信息
        $userInfo = $withdrawLogic->getUser($userId);
        if(empty($userInfo)){
            return Redirect::to('/login');
        }

        $assign = [
            'userInfo'            =>   $userInfo, //用户信息
            'withdrawCard'        =>   $withdrawLogic->getWithdrawCard($userId), //用户提现银行卡
            'commission'          =>   $withdrawLogic->getCommission($userId),   //计算用户提现手续费
            'withDrawNum'         =>   $withdrawModel->getWithDrawNum($userId),  //获取用户已经提现的次数
            'maxFreeNum'          =>   $withdrawLogic->getMaxFreeNum(),    //获取每月可免手续费提现次数
            'handlingFree'        =>   $withdrawLogic->getHandlingFree(),  //获取用户超过提现次数后的手续费
            'minMoney'            =>   $withdrawLogic->getMinWithdraw(),   //最小提现金额
            'authBanks'           =>   LimitModel::getBankLimit('', '')          //银行列表
        ];
        return view('wap.order.withdraw', $assign);
    }
    /**
     *
     */
    public function withdrawPreview(Request $request){
        $withdrawLogic = new WithdrawLogic();
        //用户信息
        $userInfo = $withdrawLogic->getUser($this->getUserId());
        if(empty($userInfo)){
            return Redirect::to('/login');
        }

        $data = $request->all();
        $data['cash'] = isset($data['withdraw_cash'])?$data['withdraw_cash']:'0';

        return view('wap.order.withdrawpreview',$data);
    }
    /**
     * @desc 提现数据提交页面
     * @author lingaunghui
     * @param $request
     * Create by Phpstorm
     * Date 16/07/27
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request){
        $data = $request->all();

        $withdrawLogic = new WithdrawLogic();

        $data['userId'] = $this->getUserId();

        //验证数据
        $vaild = $withdrawLogic->vaildData($data);

        if(!$vaild['status']){
            //self::returnJson($vaild);
            return Redirect::to('/withdraw/preview')->with('errors',$vaild['msg']);
        }

        //创建订单
        $orderId = $withdrawLogic->createOrder($data);

        if(!empty($orderId)){
            return Redirect::to('/withdraw/success/'.$orderId['data']['order_id'])->with('successData',$data);
        }
        return Redirect::to('/withdraw');
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
        return view('wap.order.withdrawsuccess', $assign);
    }
}