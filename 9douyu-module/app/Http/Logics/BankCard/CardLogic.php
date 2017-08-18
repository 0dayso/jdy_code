<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/23
 * Time: 11:35
 */

namespace App\Http\Logics\BankCard;

use App\Http\Dbs\Bank\BankCardChangeLogDb;
use App\Http\Dbs\OrderDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Pay\WithdrawLogic;
use App\Http\Models\Bank\CardModel;
use App\Http\Models\Common\ServiceApi\BankCardModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolTime;
use App\Tools\ToolEnv;
use App\Http\Models\Common\CoreApi\UserModel;
use Cache;
use Log;
use Config;

class CardLogic extends Logic{

    private $validTime = 5;//5分钟的有效期

    const SECRET_KEY = 'xuN1mFEI3viLXMg7';

    //信用卡鉴权必传参数字段
    private $creditCardParamsList = [
        //'partner_id',         //商户ID
        'card_no',              //银行卡号
        'name',                 //姓名
        'phone',                //手机号
        'id_card',              //身份证号
        //'cvv2',               //信用卡后三位校验码
        //'validthru',          //信用卡有效期
        'sign'                  //签名
    ];

    private static  $bankBin= array(
        'DEBIT_CARD'        => '2',         //借记卡
        'CREDIT_CARD'       => '1',         //贷记卡
        'SEMI_CREDIT_CARD'  => '1',         //准贷记卡
        'PREPAID_CARD'      => '1',         //储值卡
    );

    /**
     * @param $name
     * @param $idCard
     * @param $cardNo
     * @param $phone
     * @param $partnerId
     * @param $tradeNo
     * @return mixed
     * 储蓄卡鉴权接口
     */
    public function checkDepositCard($name,$idCard,$cardNo,$phone,$partnerId,$tradeNo,$sign){


        $return = [
            'status'     => 'fail',
            'resultCode' => '',
            'errorMsg'   => '',
            'errorStatus'=> '1',
            'tradeTime'  => ToolTime::dbNow(),
            'tradeNo'    => $tradeNo,
        ];

        if(empty($name) || empty($idCard) || empty($cardNo) || empty($partnerId)){
            $return['resultCode'] = '10001';
            $return['errorMsg']     = '传入的参数不全';
            return $return;
        }

        //数据检测
        try{
            ValidateModel::isName($name);
            ValidateModel::isBankCard($cardNo);
            ValidateModel::isIdCard($idCard);

            if($phone){

                ValidateModel::isPhone($phone);
            }
        }catch (\Exception $e){

            $return['resultCode']   = '10001';
            $return['errorMsg']     = $e->getMessage();

            return $return;
        }
        //商户信息不存在
        $partnerKey = $this->checkPartnerId($partnerId);
        if($partnerKey === ''){

            $return['resultCode'] = '10003';
            $return['errorMsg']     = '商户信息不存在';
            return $return;
        }

        $list   = array(
            'userName'      => $name,
            'userIdentity'  => $idCard,
            'cardNo'        => $cardNo,
        );
        if($phone){
            $list['phone']  = $phone;
        }

        //验证签名是否正确
        if(!$this->checkSign($partnerKey,$sign,$list)){

            $return['resultCode'] = '10002';
            $return['errorMsg']     = '签名错误';
            return $return;
        }

        //是否存在缓存的结果
        $cacheKey            = strtoupper($sign);
        $cacheReturn         = Cache::get($cacheKey);

        if( empty($cacheReturn) ){

            //调用第三方服务接口进行鉴权
            $result = BankCardModel::checkDepositCard($cardNo,$name,$idCard,$phone);

            //异常错误
            if(!$result['status']){

                $return['resultCode']   = '10001';
                $return['errorMsg']     = $result['msg'];

                return $return;
            }
            //请求接口成功
            $return['status'] = 'succ';

            //取出验卡结果
            $result = $result['data'];

            //鉴权结果为成功
            if($result["status"] == 'success'){
                $return['errorStatus'] = '0';

            }else{
                $return['errorStatus'] = '1';
            }

            $return['resultCode']   = $result['result_code'];
            $return['errorMsg']     = $result['msg'];
            //记录数据
            $return['tradeNo']      = $tradeNo;

            Cache::put($cacheKey,json_encode($return),$this->validTime);

        }else{
            $return = json_decode($cacheReturn,true);
        }

        $return['tradeTime']      = ToolTime::dbNow();

        return $return;
    }

    /**
     * @param $phone
     * @param $name
     * @param $idCard
     * @param $cvv2
     * @param $validthru
     * 信用卡鉴权接口
     */
    public function checkCard($params){

        $paramKeys = array_keys($params);

        $diffArr = array_diff($this->creditCardParamsList,$paramKeys);

        if(!empty($diffArr)){

            return self::callError('缺少必传参数');
        }

        try{

            $cardNo     = $params['card_no'];
            $name       = $params['name'];
            $idCard     = $params['id_card'];
            $phone      = $params['phone'];

            $cvv2  = $validthru = '';
            //验证卡号
            ValidateModel::isPhone($phone);
            //验证姓名
            ValidateModel::isName($name);
            //验证卡号
            ValidateModel::isBankCard($cardNo);
            //验证身份证号
            ValidateModel::isIdCard($idCard);


            $list = [
                'name'      => $name,
                'id_card'   => $idCard,
                'card_no'   => $cardNo,
                'phone'     => $phone
            ];

            if(isset($params['cvv2'])){
                $cvv2           = $params['cvv2'];
                $list['cvv2']   = $cvv2;
            }

            if(isset($params['validthru'])){
                $validthru = $params['validthru'];
                $list['validthru'] = $validthru;
            }

            //签名验证
            if(!$this->checkSign(self::SECRET_KEY,$params['sign'],$list)){

                return self::callError('签名错误');
            }

            //信用卡鉴权接口
            if($cvv2 || $validthru){

                $result = BankCardModel::checkCreditCard($cardNo,$name,$idCard,$phone,$cvv2,$validthru);
            }else{
                //储蓄卡鉴权接口
                $result = BankCardModel::checkDepositCard($cardNo,$name,$idCard,$phone);

            }

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }

        return $result;
    }


    /**
     * @param $cardNo
     * @param $sign
     * 卡bin接口
     */
    public function fetchCardInfo($cardNo,$sign){

        try{

            //验证卡号
            ValidateModel::isBankCard($cardNo);

            $list = [
                'card_no'      => $cardNo,
            ];

            //签名验证
            if(!$this->checkSign(self::SECRET_KEY,$sign,$list)){

                return self::callError('签名错误');
            }
            #TODO: linglu-从数据库中获取所属银行
            #$result = BankCardModel::getCardInfo($cardNo);
            $result = $this->getCardInfoV2( $cardNo );
        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }

        return $result;
    }

    /**
     * TODO:    读取数据库的方式-根据银行卡获取所属银行
     * @param   $cardNo
     * @desc    卡bin接口
     */
    public function getCardInfoV2( $cardNo ){
        #TODO: 过滤卡号中空格
        $cardNo         = str_replace(' ', '', $cardNo);
        $verify_code    = substr($cardNo, 0, 6);
        $bankInfo       = CardModel::getBankInfoByCardNo($verify_code);

        if($bankInfo){
            $card_type  = isset(self::$bankBin[$bankInfo["card_type"]]) ? self::$bankBin[$bankInfo["card_type"]] : "";
            $result     = [
                "bank_code" => $bankInfo["bin"],
                "bank_name" => $bankInfo["bank_name"],
                "card_type" => $card_type,
                "ret_code"  => "0000",
                "ret_msg"   => "交易成功",
            ];
        }else{
            $result = BankCardModel::getCardInfo($cardNo);
            $result = isset($result["data"])?$result["data"]:$result;
            \Log::info(__METHOD__.' : '.__LINE__.' LL-BIN-', $result);
            if(isset($result["ret_code"]) && $result["ret_code"] == "0000"){
                //完善bank_cardbin-数据表
                $cardType   = $result["card_type"]  == 2 ? "DEBIT_CARD" : "CREDIT_CARD";

                $bankCode       = $result['bank_code'];
                $bankCodeList   = Config::get('bankcode.cardbin');
                $bankId         = isset($bankCodeList[$bankCode]) ? $bankCodeList[$bankCode] : 0;
                $bankUCFAuth    = Config::get('bankcode.UCFAuth');
                $bank_code      = isset($bankUCFAuth[$bankId])? $bankUCFAuth[$bankId]["code"] : "";
                $data   = [
                   "verify_code"    => $verify_code,
                   "verify_length"  => 6,
                   "pan_length"     => strlen($cardNo),
                   "bin"            => $result["bank_code"],
                   "card_name"      => $result["bank_name"],
                   "card_type"      => $cardType,
                   "bank_code"      => $bank_code,
                   "bank_name"      => $result["bank_name"],
                   "create_time"    => date("Y-m-d H:i:s"),
                ];

                CardModel::addBankCardBin( $data );
            }else{
                $result =   [
                    "ret_code"  => "1088",
                    "ret_msg"   => "抱歉，本系统不支持该卡"
                ];
            }

        }

        return self::callSuccess($result);

    }


    /**
     * @param $partnerId
     * @return bool
     * 检查商户ID是否合法
     */
    private function checkPartnerId($partnerId){

        //判断是否是合法的商户号
        $config = SystemConfigModel::getConfig("SUNBILL_VERIFY_CONFIG");

        if(isset($config[$partnerId])){
            return $config[$partnerId];
        }else{
            return '';
        }

    }

    /**
     * @param $secretKey
     * @param $sign
     * @param $params
     * @return bool
     * 检查签名是否正确
     */
    private function checkSign($secretKey,$sign,$params){

        Log::info(__METHOD__.'Info',$params);

        ksort($params);   //排序关联数组
        $str        = '';
        foreach($params as $key=>$val){
            $str   .= $key.'='.$val.'&';
        }
        $str        = substr($str,0,-1).$secretKey;

        $genSign    = md5($str);

        if($sign === $genSign){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @desc 添加提现银行卡
     * @param $data
     * @return array
     */
    public function doAddBankCard($data){

        try{

            $cardNo = $data['card_no'];

            //姓名格式判断
            ValidateModel::isName($data['real_name']);

            //银行卡号格式判断
            ValidateModel::isBankCard($cardNo);

            //融宝储蓄卡鉴权接口
            CardModel::checkCardByRea($cardNo,$data['real_name'],$data['id_card'],$data['phone']);

            //获取银行卡的相关信息
            $bankId = CardModel::getCardInfo($cardNo);

            //调用核心绑定提现银行卡接口
            $res = \App\Http\Models\Common\CoreApi\BankCardModel::doCreateWithdrawCard($data['user_id'],$bankId,$cardNo);

            if ($res['status']) {

                Log::info('绑定提现银行卡成功', $data);
                return self::callSuccess();

            } else {
                Log::error(__METHOD__.'Error',['log'=> $data, 'error' => $e->getMessage()]);
                return self::callError($res['msg']);
            }

        }catch(\Exception $e){

            Log::error(__METHOD__.'Error',['log'=> $data, 'error' => $e->getMessage()]);
            return self::callError($e->getMessage());
        }
        return self::callSuccess();
    }

    /**
     * @desc [管理后台]更换银行卡逻辑处理
     * @author lgh
     * @param $param
     * @return array
     */
    public function doChangeBankCard($param){
        $bankCardChangeLog  =  new BankCardChangeLogDb();
        try{
            //验证表单提交信息是否为空
            if(empty($param)){
                return self::callError('信息为空');
            }
            //通过手机号获取用户信息
            $userInfo = UserModel::getBaseUserInfo($param['phone']);
            //验证
            if(empty($userInfo)){
                return self::callError('没有获取到用户信息');
            }

            $userId    =  $userInfo['id'];
            $bankId    =  $param['bank_id'];
            $idCard    =  $param['id_card'];
            $realName  =  $userInfo['real_name'];
            $oldCardNo =  $param['old_card'];
            $newCardNo =  $param['new_card'];

            if($oldCardNo == $newCardNo){
                return self::callError('新旧银行卡号一样');
            }
            //获取绑定的银行卡信息
            $bindCardInfo = \App\Http\Models\Common\CoreApi\BankCardModel::getUserBindCard($userId);

            if(empty($bindCardInfo)){
                return self::callError('没有绑定银行卡');
            }
            //检测填写的银行卡信息是否正确
            if($bindCardInfo['card_no'] != $oldCardNo){
                return self::callError('旧银行卡信息不对');
            }

            //银行卡号格式判断
            ValidateModel::isBankCard($newCardNo);

            //融宝储蓄卡鉴权接口银行卡
            CardModel::checkCardByAdmin($newCardNo,$realName,$idCard);

            //获取银行卡的相关信息
            $getBankId = ( ToolEnv::getAppEnv() == 'production') ? CardModel::getCardInfo($newCardNo) : 1;

            if( $getBankId != $bankId )
            {
                return self::callError( '新银行卡的银行名称选择不对' );
            }

            $return = \App\Http\Models\Common\CoreApi\BankCardModel::doChangeBindCard($userId,$bankId,$oldCardNo,$newCardNo);

            if( $return['status'] == false ){
                return self::callError( $return['msg'] );
            }
            //添加银行卡更换纪录
            $res = $bankCardChangeLog->add($userId, $oldCardNo, $newCardNo);
        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }
        return self::callSuccess($res, '更换银行卡成功');
    }

    /**
     * @desc [管理后台]检测银行卡实名信息
     * @param $param
     * @return array
     */
    public function checkUserCard($param){

        try{
            //验证表单提交信息是否为空
            if(empty($param)){
                return self::callError('信息为空');
            }
            //通过手机号获取用户信息
            $userInfo = UserModel::getBaseUserInfo($param['phone']);
            //验证
            if(empty($userInfo)){
                return self::callError('没有获取到用户信息');
            }

            $userId    =  $userInfo['id'];
            $phone     =  $param['phone'];
            $idCard    =  $param['id_card'];
            $realName  =  $param['real_name'];
            $cardNo =  $param['bank_card'];

            //获取绑定的银行卡信息
            $bindCardInfo = \App\Http\Models\Common\CoreApi\BankCardModel::getUserBindCard($userId);

            if(empty($bindCardInfo)){
                return self::callError('没有绑定银行卡');
            }

            //姓名格式判断
            ValidateModel::isName($realName);

            //银行卡号格式判断
            ValidateModel::isBankCard($cardNo);

            //融宝储蓄卡鉴权接口
            $res = CardModel::checkCardByAdmin($cardNo,$realName,$idCard);
        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }
        return self::callSuccess($res,'鉴权成功');

    }

    /**
     * @param $phone
     * @param $cardNo
     * @return array
     * 先锋支付银行卡解绑
     */
    public function ucfUnbind($phone,$cardNo){

        try{

            if(!$phone || !$cardNo){

                return self::callError('信息缺失');
            }
            //通过手机号获取用户信息
            $userInfo = UserModel::getBaseUserInfo($phone);
            //验证
            if(empty($userInfo)){
                return self::callError('用户信息不存在');
            }

            //银行卡号格式判断
            ValidateModel::isBankCard($cardNo);

            //融宝储蓄卡鉴权接口
            $res = BankCardModel::ucfUnbind($userInfo['id'],$cardNo);
            if($res['trade_status'] == OrderDb::TRADE_SUCCESS){

                return self::callSuccess([],'解绑成功');
            }else{

                return self::callError($res['msg']);
            }

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }
    }

}
