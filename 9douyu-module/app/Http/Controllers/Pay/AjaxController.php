<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/5/18
 * Time: 下午5:26
 * Desc: 三端充值ajax控制器
 */
namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;

use App\Http\Logics\Pay\SumaAuthLogic;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Http\Models\Common\NationalModel;
use App\Http\Models\Common\SmsModel;
use App\Http\Models\Pay\RechargeModel;
use App\Lang\LangModel;
use Illuminate\Http\Request;
use Response,Log;

class AjaxController extends Controller
{

    /**
     * 签约
     * @param Request $request
     * @return array
     */
    public function sendSign(Request $request){
        $type = $request->input('type');
        $method = $type.'Sign';
        if(method_exists($this,$method)){
            return call_user_func(array($this,$method),$request);
        }else{
            return ['status'=>'fail','msg'=>'方法不存在'];
        }
    }

    /**
     * 发送验证码
     * @param Request $request
     * @return array
     */
    public function sendCode(Request $request){
        $type = $request->input('type');
        $method = $type.'Code';
        if(method_exists($this,$method)){
            return call_user_func(array($this,$method),$request);
        }else{
            return ['status'=>'fail','msg'=>'方法不存在'];
        }
    }

    /**
     * 钱袋宝签约
     * @param Request $request
     * @return mixed
     */
    private function qdbSign($request){
        $param = [
            'method'=>'signed',
            'driver'=>'QdbWithholding',
            'id_card'=>$request->input('id_card'),
            'order_id'=>$request->input('order_id'),
            'card_no'=>$request->input('card_no'),
            'name'=>$request->input('name'),
            'cash'=>$request->input('cash'),
            'phone'=>$request->input('phone'),
            'notify_url'=>NationalModel::createNoticeUrl('QdbWithHold'),
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }

    /**
     * 钱袋宝发送验证码
     * @param $request
     * @return mixed
     */
    private function qdbCode($request){
        $param = [
            'method'=>'sendCode',
            'driver'=>'QdbWithholding',
            'order_id'=>$request->input('order_id'),
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }

    /**
     * 联动优势验卡
     * @param Request $request
     * @return mixed
     */
    private function umpSign($request){

        $return = [
            'status' => 'success',
            'msg' => '鉴权成功!'
        ];

        return self::returnJson($return);
        
        $param = [
            'method'=>'checkCard',
            'driver'=>'UmpWithholding',
            'card_no'=>$request->input('card_no'),
            'name'=>$request->input('name'),
            'id_card'=>$request->input('id_card'),
            'phone'=>$request->input('phone'),
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }

    /**
     * 联动优势发送验证码－九斗鱼发
     * @param Request $request
     */
    private function umpCode($request){
        $code = SmsModel::getRandCode();
        $phone = $request->input('phone');
        SmsModel::setPhoneVerifyCode($code,$phone);
        $msg = sprintf(LangModel::getLang('SMS_PAY_VERIFY_CODE'),$code);
        $result = SmsModel::verifySms($phone,$msg);
        Log::info(__METHOD__.'['.$phone.']:'.$msg);
        return $result;
    }

    /**
     * check验证码
     * @param $request
     * @return bool
     */
    public function checkCode(Request $request){
        $code = $request->input('code');
        $phone = $request->input('phone');
        $res = SmsModel::checkPhoneCode($code,$phone);
        return $res;
    }

    /**
     * 融宝签约
     * @param Request $request
     * @return mixed
     */
    private function reaSign($request){
        #TODO: 根据订单ID，获取订单信息, 防止修改金额
        $orderId    =  $request->input('order_id');
        if(!$orderId){
            Log::info(__METHOD__.'['.$orderId.']:'."订单号不存在");
            $result['status']   = 'fail';
            $result['msg']      = '订单号不存在';
            return json_encode($result);
        }
        $orderInfo  = OrderModel::getOrderInfo($orderId);
        if(!$orderInfo){
            Log::info(__METHOD__.'['.$orderId.']:'."订单号不存在");
            $result['status']   = 'fail';
            $result['msg']      = '订单号不存在';
            return json_encode($result);
        }
        $formCash   = $request->input('cash');
        $orderCash  = $orderInfo['cash']?intval($orderInfo['cash']):'-1';
        if($formCash != $orderCash ){
            Log::info(__METHOD__.'['.$orderId.']:'."充值金额与订单金额不一致！");
            $result['status']   = 'fail';
            $result['msg']      = '充值金额与订单金额不一致';
            return json_encode($result);
        }
        $param = [
            'method'=>'signed',
            'driver'=>'ReaWithholding',
            'user_id'=>$this->getUserId(),
            'card_no'=>$request->input('card_no'),
            'name'=>$request->input('name'),
            'id_card'=>$request->input('id_card'),
            'order_id'=>$request->input('order_id'),
            'cash'=>$orderCash,
            'phone'=>$request->input('phone'),
            'notify_url'=>NationalModel::createNoticeUrl('ReaWithHold'),
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return json_encode($result);
    }

    /**
     * 融宝发送验证码
     * @param $request
     * @return mixed
     */
    private function reaCode($request){
        $param = [
            'method'=>'sendCode',
            'driver'=>'ReaWithholding',
            'order_id'=>$request->input('order_id'),
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }

    /**
     * 翼支付鉴权
     * @param Request $request
     * @return mixed
     */
    private function bestSign($request){
        $param = [
            'method'=>'signed',
            'driver'=>'BestWithholding',
            'order_id'=>$request->input('order_id'),
            'card_no'=>$request->input('card_no'),
            'id_card'=>$request->input('id_card'),
            'name'=>$request->input('name'),
            'bank_code'=>$request->input('bank_code'),
        ];
        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);
        return $result;
    }

    /**
     * 翼支付发送验证码－九斗鱼发
     * @param Request $request
     */
    private function bestCode($request){
        $code = SmsModel::getRandCode();
        $phone = $request->input('phone');
        SmsModel::setPhoneVerifyCode($code,$phone);
        $msg = sprintf(LangModel::getLang('SMS_PAY_VERIFY_CODE'),$code);
        $result = SmsModel::verifySms($phone,$msg);
        Log::info(__METHOD__.'['.$phone.']:'.$msg);
        return $result;
    }

    /**
     * @desc    丰付支付
     **/
    private function sumaSign($request){
        $userId     = $this->getUserId();
        $orderId    = $request->input('order_id','');
        $phone      = $request->input('phone',  '');
        $name       = $request->input('name',   '');
        $idCard     = $request->input('id_card','');
        $platform   = $request->input('from',   'pc');
        $bankId     = $request->input('bank_id','');
        $bankAccount= $request->input('card_no','');
        $isFirst    = $request->input('is_first','1');

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
            'isFirst'       => $isFirst,

        ];

        $sumaData = $logic->sendCode($requestData);
        $result = ['status'=>'fail','msg'=>'方法不存在'];
        if($sumaData && $sumaData["result"] == "00000"){
            $result["status"]   = "success";
            $result["msg"]      = "";
            $result["randomValidateId"] = $sumaData["randomValidateId"];
            $result["tradeId"]          = $sumaData['tradeId'];
        }else{
            $result["msg"]  = isset($sumaData["errorMsg"])?$sumaData["errorMsg"]:"获取失败";
            $result["randomValidateId"] = "";
            $result["tradeId"]          = "";
        }

        return json_encode($result);
    }

}