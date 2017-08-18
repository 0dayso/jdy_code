<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/5/18
 * Time: 下午5:26
 * Desc: 三端充值控制器
 */
namespace App\Http\Controllers\Pc\Pay;

use App\Http\Controllers\Pc\UserController;
use App\Http\Dbs\OrderDb;
use App\Http\Controllers\Controller;
use App\Http\Logics\Pay\RechargeLogic;
use App\Http\Logics\Pay\HnaOnlineLogic;
use App\Http\Logics\Pay\ReaOnlineLogic;
use App\Http\Logics\Pay\JdOnlineLogic;
use App\Http\Logics\Pay\LLAuthLogic;
use App\Http\Logics\Pay\SumaAuthLogic;
use App\Http\Logics\Pay\SumaOnlineLogic;
use App\Http\Logics\Pay\UCFAuthLogic;
use App\Http\Logics\Pay\YeeAuthLogic;
use App\Http\Logics\Pay\QdbWithHoldLogic;
use App\Http\Logics\Pay\UmpWithHoldLogic;
use App\Http\Logics\Pay\ReaWithHoldLogic;
use App\Http\Logics\Pay\BestWithHoldLogic;
use App\Http\Models\Pay\RechargeModel;
use App\Lang\LangModel;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;
use Redirect,Session;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\Pay\BFAuthLogic;
use App\Http\Logics\Recharge\PayLimitLogic;


class RechargeController extends UserController
{

    public function __construct()
    {
        parent::__construct();
        $this->checkIdentity();

    }

    /**
     * wap,pc 充值页
     * @param $from
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        $rechargeLogic = new RechargeLogic();
        
        $userId = $this->getUserId();

        //网银列表
        $unionPay = $rechargeLogic->getUnionPay();
        //快捷支付认证卡
        $assign = $rechargeLogic->getPayLimit($userId);
        //支付名称
        $logic      = new PayLimitLogic();
        $typeList   = $logic->getPayTypeName();
        $assign['unionPay']  = $unionPay;

        $assign['user']   = $this->getUser();
        $assign['typeList'] = $typeList;

        $assign['withdrawConfig']   =   $rechargeLogic->getWithdrawConfig();

        $assign['withholding_recharge_min_money']   = $rechargeLogic->getRechargeMinMoeny($userId);
        $assign['recharge_min_money']   = $rechargeLogic->getAuthMinMoney();
        return view('pc.pay.recharge',$assign);

    }


    /**
     * 三端提交数据
     * @param Request $request
     * @param $from
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request){

        setcookie('failOrder','');
        $userId = $this->getUserId();
        $payType = $request->input('payType');
        $cash = $request->input('cash');
        $from = RequestSourceLogic::getSource();
        $rechargeLogic = new RechargeLogic();
        if($payType == 1) {
            //网银充值
            $bankId = $request->input('bankId');
            $cardNo = '';
            #$data['bankCode'] = $request->input('bankCode');
            $payChannel = $rechargeLogic->getUnionType();
            $data['bankCode'] = $rechargeLogic->getAlias($payChannel,$bankId);
        }else{
            $rechargeModel = new RechargeModel();
            $authCard = $rechargeModel->getAuthCard($userId);
            //已绑卡用户
            if(!empty($authCard)) {

                $bankId = $authCard['bank_id'];
                $cardNo = $authCard['card_no'];
                //绑卡用户选择的支付通道
                $channel = $request->input('channel','');
                //无可用支付通道
                if(!$channel){
                    return Redirect::back()->with('errors','请选择支付通道');
                }
                //检查该渠道是否可以用于支付
                $result = $rechargeLogic->checkPayLimit($userId,$cash,$channel,$bankId);
                if(!$result['status']) {
                    return Redirect::back()->with('errors', $result['msg']);
                }

                $payChannel = $channel;
                
            }else{
                //未绑卡用户使用原有逻辑进行通道的匹配
                $bankId = $request->input('bankId');
                $cardNo = $request->input('card_no');
                //快捷选择支付通道
                $payChannel = $rechargeLogic->getPayChannel($userId,$cash,$bankId);
            }
            
            //无可用支付通道
            if(!$payChannel){
                return Redirect::back()->with('errors','支付限额已满');
            }
            $data['bankCode'] = $rechargeLogic->getAlias($payChannel,$bankId);
            $data['cardNo'] = $cardNo;
            $data['bankId'] = $bankId;

        }
        //创建订单
        $version = $request->input('version','');

        $order = $rechargeLogic->createOrder($userId,$cash,$bankId,$cardNo,$payChannel,$from,$version);

        if(!$order['status']){
            return Redirect::back()->with('errors', $order['msg']);
        }
        $orderId = $order['data']['order_id'];
        $data += [
            'userId'=>$userId,
            'cash'=>$cash,
            'orderId'=>$orderId,
            'from'=>$from,
        ];
        //分发通道
        $method = $rechargeLogic->getPayMethod($payChannel);

        if(!method_exists($this,$method)){
            return Redirect::back()->with('errors', LangModel::getLang('ERROR_PAY_NOUSE_CHANNEL'));
        }
        return call_user_func(array($this,$method),$data);
    }

    /**
     * 京东网银支付页面
     * @param $data
     */
    private function doJdOnline($data){
        $logic = new JdOnlineLogic();
        $result = $logic->submit($data['cash'],$data['orderId'],$data['bankCode'],$data['from']);
        $assign = $result['parameter'];
        $assign['apiGateWay'] = $result['url'];
        return view("pc.pay.jdonline",$assign);
    }

    /**
     * 融宝网银支付页面
     */
    private function doReaOnline($data){
        $logic = new ReaOnlineLogic();
        $result = $logic->submit($data['cash'],$data['orderId'],$data['bankCode'],$data['from']);
        $assign = $result['parameter'];
        $assign['apiGateWay'] = $result['url'];
        return view("pc.pay.reaonline",$assign);
    }

    /**
     * 新生网银支付页面
     */
    private function doHnaOnline($data){
        $logic = new HnaOnlineLogic();
        $result = $logic->submit($data['cash'],$data['orderId'],$data['bankCode'],$data['from']);
        $assign = $result['parameter'];
        $assign['apiGateWay'] = $result['url'];
        return view("pc.pay.hnaonline",$assign);
    }


    /**
     * 连连支付
     * @param $data
     */
    private function doLLAuth($data){

        $logic = new LLAuthLogic();

        $result = $logic->submit($data['userId'],$data['cardNo'],$data['cash'],$data['orderId'],$data['from']);

        Session::put('payCash',$data['cash']);

        if($data['from']=='wap'){

            $assign['parameter'] = json_encode($result['parameter']);
        }else{
            $assign = $result['parameter'];
        }

        $assign['apiGateWay'] = $result['url'];

        return view($data['from'].".pay.llauth",$assign);
    }

    /**
     * @param $data
     * @return mixed
     * 宝付快捷支付
     */
    private function doBFAuth($data){

        $logic = new BFAuthLogic();

        $result = $logic->submit($data['userId'],$data['cardNo'],$data['cash'],$data['orderId'],$data['from'],$data['bankId']);

        Session::put('payCash',$data['cash']);

        $assign = $result['parameter'];

        $assign['apiGateWay'] = $result['url'];

        return view($data['from'].".pay.bfauth",$assign);

    }

    /**
     * @param $data
     * @return mixed
     * 宝付快捷支付
     */
    private function doUCFAuth($data){

        $logic = new UCFAuthLogic();

        $result = $logic->submit($data['userId'],$data['cardNo'],$data['cash'],$data['orderId'],$data['from'],$data['bankId']);
        Session::put('payCash',$data['cash']);

        $assign = $result['parameter'];

        $assign['apiGateWay'] = $result['url'];

        return view($data['from'].".pay.ucfauth",$assign);

    }

    /**
     * 易宝支付
     * @param $data
     */
    private function doYeeAuth($data){
        $logic = new YeeAuthLogic();
        $result = $logic->submit($data['userId'],$data['cardNo'],$data['cash'],$data['orderId'],$data['from']);
        Session::put('payCash',$data['cash']);
        return Redirect::to($result['url'])->send();
    }

    /**
     * 钱袋宝支付页面
     * @param $data
     */
    private function doQdbWithHold($data){
        $logic = new QdbWithHoldLogic();
        $user = $logic->getUser($data['userId']);
        $data['phone'] = $user['phone'];
        $data['realName'] = $user['real_name'];
        $data['identityCard'] = $user['identity_card'];
        return view($data['from'].".pay.qdbwithhold",$data);
    }

    /**
     * 钱袋宝充值提交
     * @param Request $request
     */
    public function qdbSubmit(Request $request){
        $logic = new QdbWithHoldLogic();
        $result = $logic->submit($request->input('order_id'),$request->input('code'));
        Session::put('payCash',$request->input('cash'));
        if($result['status']==OrderDb::TRADE_SUCCESS){
            return Redirect::to('/pay/success/'.$request->input('from'));
        }else{
            return Redirect::to('/pay/fail/'.$request->input('from'));
        }
    }

    /**
     * 联动优势支付页面
     * @param $data
     */
    private function doUmpWithHold($data){
        $logic = new UmpWithHoldLogic();
        $user = $logic->getUser($data['userId']);
        $data['phone'] = $user['phone'];
        $data['realName'] = $user['real_name'];
        $data['identityCard'] = $user['identity_card'];
        return view($data['from'].".pay.umpwithhold",$data);
    }

    /**
     * 联动优势充值提交
     * @param Request $request
     */
    public function umpSubmit(Request $request){
        $logic = new UmpWithHoldLogic();
        $result = $logic->submit($this->getUserId(),$request->input('order_id'),$request->input('card_no'),$request->input('cash'));
        Session::put('payCash',$request->input('cash'));
        if($result['status']==OrderDb::TRADE_SUCCESS){
            return Redirect::to('/pay/success/'.$request->input('from'));
        }else{
            return Redirect::to('/pay/fail/'.$request->input('from'));
        }
    }

    /**
     * 融宝支付页面
     * @param $data
     */
    private function doReaWithHold($data){
        $logic = new ReaWithHoldLogic();
        $user = $logic->getUser($data['userId']);
        $data['phone'] = $user['phone'];
        $data['realName'] = $user['real_name'];
        $data['identityCard'] = $user['identity_card'];
        return view($data['from'].".pay.reawithhold",$data);
    }

    /**
     * 融宝充值提交
     * @param Request $request
     */
    public function reaSubmit(Request $request){
        $logic = new ReaWithHoldLogic();
        $result = $logic->submit($request->input('order_id'),$request->input('code'));
        Session::put('payCash',$request->input('cash'));
        if($result['status']==OrderDb::TRADE_SUCCESS){
            return Redirect::to('/pay/success/'.$request->input('from'));
        }else{
            return Redirect::to('/pay/fail/'.$request->input('from'));
        }
    }

    /**
     * 翼支付 支付页面
     * @param $data
     */
    private function doBestWithHold($data){
        $logic = new BestWithHoldLogic();
        $user = $logic->getUser($data['userId']);
        $data['phone'] = $user['phone'];
        $data['realName'] = $user['real_name'];
        $data['identityCard'] = $user['identity_card'];
        return view($data['from'].".pay.bestwithhold",$data);
    }

    /**
     * 翼支付充值提交
     * @param Request $request
     */
    public function bestSubmit(Request $request){
        $logic = new BestWithHoldLogic();
        $result = $logic->submit($this->getUserId(),$request->input('order_id'),$request->input('cash'),$request->input('card_no'));
        Session::put('payCash',$request->input('cash'));
        if($result['status']==OrderDb::TRADE_SUCCESS){
            return Redirect::to('/pay/success/'.$request->input('from'));
        }else{
            return Redirect::to('/pay/fail/'.$request->input('from'));
        }
    }


    /**
     * @desc    丰付支付页面
     * @param   $data
     */
    private function doSumaAuth($data){
        $logic  = new SumaAuthLogic();
        $user   = $logic->getUser($data['userId']);
        $data['phone']          = $user['phone'];
        $data['realName']       = $user['real_name'];
        $data['identityCard']   = $user['identity_card'];

        $sumaOrder = $logic->createOrder($data['orderId'],$data['cash'],$data['userId'],$data['from']);

        if($sumaOrder["result"]=="00000"){
            #丰付订单创建成功-跳转短信验证页面
            return view("pc.pay.sumaauth",$data);
        }else{
            $msg = isset($sumaOrder['errorMsg'])?$sumaOrder['errorMsg']:"支付失败";
            //echo $msg;exit;
            return Redirect::back()->with('errors',$msg);
        }
    }


    /**
     * @desc    丰付充值提交
     * @param   Request $request
     */
    public function sumaSubmit(Request $request){

        $userId     = $this->getUserId();
        $orderId    = $request->input('order_id','');
        $phone      = $request->input('phone',   '');
        $name       = $request->input('name',    '');
        $idCard     = $request->input('id_card', '');
        $platform   = $request->input('from',    '');
        $bankId     = $request->input('bank_id', '');
        $bankAccount= $request->input('card_no', '');
        $randomCode = $request->input('code',    '');
        $randomValidateId   = $request->input('randomValidateId','');
        $tradeId            = $request->input('tradeId', '');

        $logic  = new SumaAuthLogic();

        $requestData    = [
            'platform'      => $platform,
            'order_id'      => $orderId,
            'mobilePhone'   => $phone,
            'bank_id'       => $bankId,
            'bankAccount'   => $bankAccount,
            'userId'        => $userId,
            'name'          => $name,
            'idCard'        => $idCard,
            'randomCode'    => $randomCode,
            'tradeId'       => $tradeId,
            'randomValidateId'  => $randomValidateId,
        ];

        $sumaData = $logic->submit($requestData);
        Session::put('payCash',$request->input('cash'));
        //var_dump($sumaData);exit;
        if($sumaData['status']==OrderDb::TRADE_SUCCESS){
            return Redirect::to('/pay/success/'.$request->input('from'));
        }else{
            return Redirect::to('/pay/fail/'.$request->input('from'));
        }

    }


    /**
     * 丰付网银支付页面
     * @param $data
     */
    private function doSumaOnline($data){
        $logic  = new SumaOnlineLogic();
        $result = $logic->submit($data['cash'],$data['orderId'],$data['bankCode'],$data['from']);
        $assign = $result['parameter'];
        $assign['apiGateWay'] = $result['url'];
        return view("pc.pay.sumaonline", $assign);
    }


}