<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/5/18
 * Time: 18:07
 */
namespace App\Http\Logics\Pay;
use App\Http\Dbs\Bank\BankDb;
use App\Http\Dbs\Order\PayLimitDb;
use App\Http\Dbs\Order\UserPayListDb;
use App\Http\Logics\Recharge\PayLimitLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Pay\LimitModel;
use App\Http\Models\Pay\RechargeModel;
use App\Http\Models\Pay\WithdrawModel;
use App\Http\Logics\Logic;
use App\Http\Models\Pay\RouteModel;
use App\Http\Models\Bank\CardModel;
use App\Http\Dbs\OrderDb;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolOrder;
use App\Lang\AppLang;
use Cache;
use Config;

//支付
use App\Http\Logics\Pay\YeeAuthLogic as YeeAuthLogic;
use App\Http\Logics\Pay\LLAuthLogic as LLAuthLogic;
use App\Http\Logics\Pay\JdOnlineLogic as JdOnlineLogic;
use App\Http\Logics\Pay\UmpWithHoldLogic as UmpWithHoldLogic;
use App\Http\Logics\Pay\QdbWithHoldLogic as QdbWithHoldLogic;
use App\Http\Logics\Pay\ReaWithHoldLogic as ReaWithHoldLogic;

use App\Tools\ToolVersion;
use EasyWeChat\Payment\Order;
use Log;

class RechargeLogic extends Logic{

    //支付相关类调用配置
    public static $payConfig = [
        'JdOnline'             => JdOnlineLogic::class,         //京东网银
        'LLAuth'               => LLAuthLogic::class,           //连连认证
        'YeeAuth'              => YeeAuthLogic::class,          //易宝认证
        'BFAuth'               => BFAuthLogic::class,
        'UCFAuth'              => UCFAuthLogic::class,
        'SumaAuth'             => SumaAuthLogic::class,         //丰付认证支付
        'QdbWithholding'       => QdbWithHoldLogic::class,   //钱袋宝代扣
        'UmpWithholding'       => UmpWithHoldLogic::class,   //联动优势代扣
        'ReaWithholding'       => ReaWithHoldLogic::class       //融宝代扣
    ];
    
    /**
     * @param $orderId
     * @param $userId
     * @param $cash
     * 更新用户成功充值记录
     */
    public function updateUserRechargeRecord($orderId){

        try{


            //更新用户成功充值记录
            $model = new RechargeModel();
            $model->updateRecord($orderId);

        }catch(\Exception $e){

            $data = [
                'order_id' => $orderId,
                'msg'      => $e->getMessage(),
                'code'     => $e->getCode()
            ];

            Log::error(__METHOD__.'Error',$data);

            return self::callError($e->getMessage());
        }

        return self::callSuccess(['order_id' => $orderId]);
    }

    /**
     * 获取用户认证卡信息和限额
     * @param int $userId
     * @return array
     */
    public function getPayLimit($userId,$version = '',$isApp = false){

        $result = [];

        if($userId>0){
            //初始值
            $result['authCard']['limit'] = [];
            $result['isBind']   = true;

            $rechargeModel = new RechargeModel();
            //绑卡信息
            $authCard = $rechargeModel->getAuthCard($userId);
            
            $result['authCard']['info'] = $authCard;
            //已绑卡
            if($authCard){

                $result['authCard']['info']['bank_name'] = CardModel::getBankName($authCard['bank_id']);

                //最小充值金额
                $rechargeModel = new RechargeModel();
                $minRechargeCash = $rechargeModel->getRechargeMinMoeny($userId);

                $data = LimitModel::getBindUserLimit($userId,$authCard['bank_id'],$minRechargeCash,$version,$isApp);

                $maxCash = $data['maxCash'];
                $result['authCard']['limit']['cash'] = ToolMoney::formatDbCashDelete($maxCash);

                $result['authCard']['list'] = $data['list'];
            }else{
                //未绑卡情况
                $result['isBind'] = false;
                $list =  LimitModel::getBankLimit($version,$isApp);

                foreach($list as $k=>$val){
                    $list[$k]['cash'] = ToolMoney::formatDbCashDelete($val['cash']);
                }
                $result['authBanks'] = $list;
            }
        }
        return $result;
    }


    /**
     * 获取当前网银类型
     * @return int
     */
    public function getUnionType(){
        $rechargeModel = new RechargeModel();
        $type = $rechargeModel->getUnionType();
        return $type;
    }

    /**
     * 获取当前网银列表
     * @return array
     */
    public function getUnionPay(){
        $type = $this->getUnionType();
        $rechargeModel = new RechargeModel();
        $bankList = $rechargeModel->getUnion($type);
        return $bankList;
    }

    /**
     * 获取快捷支付银行列表和限额
     * @return array
     */
    public function getAuthBanks($userId){
        $rechargeModel = new RechargeModel();
        $authBanks = $rechargeModel->getAuthBanks($userId);
        return $authBanks;
    }

    /**
     * 选择支付通道
     * @param $userId
     * @param $cash
     * @param $bankId
     * @return int
     */
    public function getPayChannel($userId,$cash,$bankId,$version='',$client=''){

        $rechargeModel = new RechargeModel();
        //绑卡信息
        $authCard = $rechargeModel->getAuthCard($userId);
        
        if($authCard){
            $isBind = true;
        }else{
            $isBind = false;
        }
        //$showData = $this->getShowData($version,$client);

        $isApp = $this->isApp($client);
        //匹配支付通道
        $payType = RouteModel::getValidChannel($userId,$cash,$bankId,$isBind,$version,$isApp);
        return $payType;
    }

    /**
     * 创建订单
     * @param $userId
     * @param $cash
     * @param $bankId
     * @param $cardNo
     * @param $payChannel
     * @param $from
     * @param $version
     * @param $orderId      九斗鱼对接module使用
     * @return int
     */
    public function createOrder($userId,$cash,$bankId,$cardNo,$payChannel,$from,$version,$orderId=''){

        $params  = [
            'order_id'  => empty($orderId) ? ToolOrder::generateOrder() : $orderId,
            'user_id'   => $userId,
            'cash'      => $cash,
            'bank_id'   => $bankId,
            'card_no'   => $cardNo,
            'type'      => $payChannel,
            'from'      => $from,
            'version'   => $version
        ];

        $minMoney   = $this->getRechargeMinMoeny($userId);
        //TODO:判断用户充值金额必须不小于最小充值金额
        if($cash < $minMoney){
            return self::callError('充值金额不能小于最小充值金额');
        }else{
            $order = OrderModel::doCreateRechargeOrder($params);

            if($order['status']){
                return self::callSuccess($order['data']);
            }else{
                return self::callError($order['msg']);
            }
        }

    }

    /**
     * 获取通道method
     * @param int $payType
     * $return string
     */
    public function getPayMethod($payType){
        $rechargeModel = new RechargeModel();
        $method = $rechargeModel->getPayMethod($payType);
        return $method;
    }

    /**
     * 获取bank——code
     * @param $type
     * @param $bank_id
     * @return string
     */
    public function getAlias($type,$bank_id){

        //网银支付bank code才有意义
        if($type >= OrderDb::RECHARGE_CBPAY_ONLINE_TYPE && $type < OrderDb::RECHARGE_LLPAY_AUTH_TYPE){

            $rechargeModel = new RechargeModel();
            $bank = $rechargeModel->getAlias($type,$bank_id);
            return $bank['alias'];

        }

        return '';


    }

    /**
     * 清空用户昨日充值记录
     */
    public function clearUserDayRecord(){

        $db     = new UserPayListDb();
        $db->clearDayCash();
    }

    /**
     * 清空用户上个月充值记录
     */
    public function clearUserMonthRecord(){

        $db     = new UserPayListDb();
        $db->clearMonthCash();
    }


    /**
     * @param $orderId
     * @param $tradeNo
     * 支付成功订单状态处理
     */
    public function doSucc($orderId,$tradeNo){

        $result = OrderModel::doSuccRechargeOrder($orderId,$tradeNo);
        if($result['status']){

            return self::callSuccess();
        }else{
            return self::callError($result['msg']);
        }
    }


    /**
     * @param $orderId
     * @param $tradeNo
     * @param $note
     * @return array
     * 支付失败订单处理
     */
    public function doFailed($orderId,$tradeNo,$note){

        $result = OrderModel::doFailedRechargeOrder($orderId,$tradeNo,$note);

        if($result['status']){

            return self::callSuccess();
        }else{
            return self::callError($result['msg']);
        }
    }

    /**
     * [获取最低充值金额]
     * @param  [int] $userId 
     * @return [float] 
     */
    public function getRechargeMinMoeny($userId){

        $rechargeModel = new RechargeModel();

        $min_money = $rechargeModel->getRechargeMinMoeny($userId);

        return $min_money;
    }

    /**
     * 非代扣充值最小额度（网银充值）
     * @return [float] 
     */
    public function getAuthMinMoney() {

        $rechargeModel = new RechargeModel();

        $min_money = $rechargeModel->getAuthMinMoney();

        return $min_money;
    }

    /**
     * [提现config]
     * @return [array]
     */
    public function getWithdrawConfig(){

        $rechargeModel = WithdrawModel::getWithdrawConfig();

        return $rechargeModel;
    }


    /**
     * APP用户充值卡返回数据
     * @param  int $userId 
     * @return array
     */
    public function getRechargeCardsForApp($userId,$version,$client)
    {

        //$showData = $this->getShowData($version,$client);

        $isApp = $this->isApp($client);
        
        $bankList = $this->getPayLimit($userId,$version,$isApp);

        if($bankList['isBind']) {

            $bankId = $bankList["authCard"]['info']['bank_id'];

            $limit  = $bankList['authCard']['limit']['cash'];

            $banks  = CardModel::getBanks();

            //可选通道列表
            if($bankList['authCard']['list']){
                $logic      = new PayLimitLogic();
                $typeList   = $logic->getPayTypeName();

                $list = $bankList['authCard']['list'];
                #APP-端 推荐渠道
                $recommendChannel   = SystemConfigModel::getConfig('APP_RECOMMEND_CHANNEL');
                $recommendArr   = [];
                $commonArr      = [];
                foreach($list as $val){

                    $payType = $val['pay_type'];
                    $val['channel_name'] = $typeList[$payType]['name'];
                    if(isset($recommendChannel[$payType]) && $recommendChannel[$payType] == 1){
                        $val['image'] = assetUrlByCdn('/static/app/images/channel/'.$payType.'-app-recommend.png');
                        $recommendArr[] = $val;
                    }else{
                        //todo 换成图片实存路径
                        $val['image'] = assetUrlByCdn('/static/app/images/channel/'.$payType.'-app.png');
                        $commonArr[]    = $val;
                    }
                }

                $channelList    = array_merge($recommendArr,$commonArr);

            }else{
                $channelList = [[]];
            }

            $res[] = [
                "id"             => $userId,
                "name"           => $banks[$bankId]['name'],
                "bank_id"        => $bankId,
                "card_number"    => ToolOrder::hideCardNumber($bankList['authCard']['info']['card_no']),
                "pay_type"       => OrderDb::RECHARGE_APP_JUMP_WX_TYPE,
                "note"           => ToolOrder::rechargeBankLimit($limit),
                "limit"          => $limit,
                "image"          => ToolOrder::getBankImage($bankId),
                "channel_list"   => $channelList
            ];

        } else {

            $res[] = [];
        }

        $userInfo = $this->getUser($userId);

        $min_money = $this->getRechargeMinMoeny($userId);

        $result = [
            'items' => $res,
            'user_name' => $userInfo['real_name'], 
            'min_money'  => $min_money, 
            'min_money_note' => ToolOrder::minRechargeCash($min_money), 
            'recharge_note' => AppLang::APP_RECHARGE_NOTE,
            'desc_url'      => env('APP_URL_WX').'/article/rechargeIntro',
        ];

        return self::callSuccess($result);
    }

    /**
     * APP用户充值卡返回数据
     * @param  int $userId
     * @return array
     */
    public function getRechargeCardsForApp4($userId,$version,$client)
    {

        //$showData = $this->getShowData($version,$client);

        $isApp = $this->isApp($client);

        $bankList = $this->getPayLimit($userId,$version,$isApp);

        if($bankList['isBind']) {

            $bankId = $bankList["authCard"]['info']['bank_id'];

            $limit  = $bankList['authCard']['limit']['cash'];

            $banks  = CardModel::getBanks();

            //可选通道列表
            if($bankList['authCard']['list']){
                $logic      = new PayLimitLogic();
                $typeList   = $logic->getPayTypeName();

                $list = $bankList['authCard']['list'];
                #APP-端 推荐渠道
                $recommendChannel   = SystemConfigModel::getConfig('APP_RECOMMEND_CHANNEL');
                $recommendArr   = [];
                $commonArr      = [];
                foreach($list as $val){

                    $payType = $val['pay_type'];
                    $val['channel_name'] = $typeList[$payType]['name'];
                    $val['image'] = assetUrlByCdn('/static/app/images/channel/'.$payType.'-app.png');
                    if(isset($recommendChannel[$payType]) && $recommendChannel[$payType] == 1){
                        //$val['image'] = assetUrlByCdn('/static/app/images/channel/'.$payType.'-app-recommend.png');
                        $val['is_recommend']  = 1;
                        $recommendArr[] = $val;
                    }else{
                        //todo 换成图片实存路径
                        $commonArr[]    = $val;
                    }
                }

                $channelList    = array_merge($recommendArr,$commonArr);

            }else{
                $channelList = [[]];
            }

            $res = [
                "id"             => $userId,
                "name"           => $banks[$bankId]['name'],
                "bank_id"        => $bankId,
                "card_number"    => ToolOrder::hideCardNumber($bankList['authCard']['info']['card_no']),
                "pay_type"       => OrderDb::RECHARGE_APP_JUMP_WX_TYPE,
                "note"           => ToolOrder::rechargeBankLimit($limit),
                "limit"          => $limit,
                "image"          => ToolOrder::getBankImage($bankId),
                "channel_list"   => $channelList
            ];

        } else {

            $res = [];
        }

        $userInfo = $this->getUser($userId);

        $min_money = $this->getRechargeMinMoeny($userId);

        $result = [
            'user_name' => $userInfo['real_name'],
            'min_money'  => $min_money,
            'min_money_note' => ToolOrder::minRechargeCash($min_money),
            'recharge_note' => AppLang::APP_RECHARGE_NOTE,
            'desc_url'      => env('APP_URL_WX').'/article/rechargeIntro',
        ];

        if(!empty($res)){
            $result = array_merge($res, $result);
        }

        return self::callSuccess($result);
    }


    /**
     * @param $client
     * @return bool
     * 判断是否是APP端
     */
    private function isApp($client){

        if($client == RequestSourceLogic::SOURCE_ANDROID
            || $client == RequestSourceLogic::SOURCE_IOS){

            return true;
        }else{

            return false;
        }
    }
    private function getShowData($version,$client){

        $showBFChannel = true;
        //App端3.0.0以上版本显示宝付
        if($version && ToolVersion::compare_version($version,self::BF_SHOW_VERSION) <= 0){
            $showBFChannel = false;
        }

        //IOS 2.2.6不显示连连支付充值
        $showLLChannel = true;

        if($version && $client == RequestSourceLogic::SOURCE_IOS
            && ToolVersion::compare_version($version,self::LL_UNSHOW_VERSION) == 0){
            $showLLChannel = false;

        }

        return [
            'showBFChannel'  => $showBFChannel,
            'showLLChannel'    => $showLLChannel
        ];
    }

    /**
     * APP可充值卡列表返回数据
     * @param  int $userId 
     * @return array
     */
    public function getRechargeBanksForApp($version,$client) {
        
        //$showData = $this->getShowData($version,$client);

        $isApp = $this->isApp($client);

        $list =  LimitModel::getBankLimit($version,$isApp);
        $banks  = CardModel::getBanks();

        foreach($list as $k=>$val){

            $limit = ToolMoney::formatDbCashDelete($val['cash']);

            $bankId = $val['bank_id'];

            $result[] = [
                'limit'          => $limit,
                'bank_id'        => $bankId,
                'id'             => $bankId,
                'name'           => $banks[$bankId]['name'],
                "image"          => ToolOrder::getBankImage($bankId),
                "note_limit"     => $limit,
                "note"           => ToolOrder::rechargeBankLimit($limit)
            ];

        }       
        
        return self::callSuccess($result);
    }

    /**
     * @param $orderId
     * @return array
     * 放弃支付
     */
    public function giveUpRecharge($orderId){

        $result = OrderModel::doTimeoutRechargeOrder($orderId);

        if($result['status']){

            return self::callSuccess([]);
        }else{

            return self::callError($result['msg']);
        }
    }


    /**
     * @param $param
     * @return array
     * 回调解密公共函数
     */
    public static function returnDecrypt($param){

        $return = [
            'status' => false,
            'cash'  => 0,
            'msg'  => '支付失败'
        ];

        if(!$param){

            return $return;
        }

        $rechargeModel = new RechargeModel();
        $result = $rechargeModel->payService($param);

        if(isset($result['trade_status'])){

            if(isset($result['order_id']) && $result['order_id']) {

                $orderId = $result['order_id'];

                //充值成功
                if($result['trade_status'] == OrderDb::TRADE_SUCCESS){

                    $return['status'] = true;

                    $orderInfo = OrderModel::getOrderInfo($orderId);

                    if($orderInfo){

                        $return['cash'] = $orderInfo['cash'];
                    }
                    //失败
                }else{

                    $msg = $result['msg'];

                    $return['msg'] = $msg;

                    OrderModel::doTimeoutRechargeOrder($orderId,$msg);
                }

            }
            

        }

        return $return;
    }

    /**
     * @desc 获取相对应的支付逻辑层实例
     * @param $payType
     * @return mixed
     */
    public static function getPayInstance($payType){

        return new self::$payConfig[$payType];
    }


    /**
     * @param $orderId
     * @return array|null|void
     * 掉单加币处理
     */
    public static function doMissOrderHandle($orderId){

        
        $result = OrderModel::doMissOrderHandle(['order_id' =>$orderId]);

        return $result;

    }


    public static function missOrderSearch($orderId){
        
        if(!$orderId){

            return self::callError('订单号不能为空');
        }

        $result = OrderModel::getOrderInfo($orderId);

        if(empty($result)){

            return self::callError('订单号不存在');
        }


        
        return self::callSuccess($result);
    }

    /**
     * 获取京东网银银行编码
     */
    public function getJdOnlineBankList(){
        
        $db = new \App\Http\Dbs\Bank\BankListDb();

        #$type = \App\Http\Dbs\Bank\BankListDb::RECHARGE_ONLINE_BANKING;
        $type   = $this->getUnionType();
        $bankList = $db->getBankListByType($type);

        $bankDb = new BankDb();

        $bank = $bankDb->getAllBank();


        $bank = ToolArray::arrayToKey($bank,'id');

        foreach($bankList as $val){

            $list[] = [

                'type' => $val['type'],
                'code'  => $val['alias'],
                'bank_id' => $val['bank_id'],
                'status' => $val['status'],
                'name' => $bank[$val['bank_id']]['name']
            ];
        }

        return $list;
    }


    /**
     * @param $bankId
     * @param $type
     * @param $status
     * @return array
     * 编辑银行在前端显示的状态
     */
    public function doEditStatus($bankId,$type,$status){


        if($status == \App\Http\Dbs\Bank\BankListDb::STATUS_SHOW){

            $dbStatus = \App\Http\Dbs\Bank\BankListDb::STATUS_HIDDEN;
        }else{

            $dbStatus = \App\Http\Dbs\Bank\BankListDb::STATUS_SHOW;
        }

        $db = new \App\Http\Dbs\Bank\BankListDb();

        $result = $db->doEdit($bankId,$type,['status' => $dbStatus]);

        if($result){
            return self::callSuccess();
        }else{

            return self::callError('操作成功');
        }

    }

    /**
     * @param $userId
     * @param $cash
     * @param $payType
     */
    public function checkPayLimit($userId,$cash,$payType,$bankId){

        $limitLogic = new PayLimitLogic();
        $typeArr = $limitLogic->getPayTypeName();

        if(!isset($typeArr[$payType])){

            return self::callError('无效的支付通道!');

        }

        //获取当前银行支持的支付渠道列表,并判断是否支持选择的支付渠道
        $limitDb = new PayLimitDb();
        $typeList = $limitDb->getLimitByBank($bankId);
        $typeList = ToolArray::arrayToKey($typeList,'pay_type');

        if(!isset($typeList[$payType])){

            return self::callError('支付渠道不支持该银行,请重新选择!');
        }

        //单日限额
        $dayLimit  = $typeList[$payType]['day_limit'];
        //单笔限额
        $limit = $typeList[$payType]['limit'];

        //获取用户今日使用该渠道已成功支付的金额
        $userPayDb = new UserPayListDb();

        $object = $userPayDb->getUserPayList($userId,$bankId,$payType);

        if($object){
            //当日剩余限额
            $dayLimit = $dayLimit - $object->day_cash;

        }

        //当笔限额与当日剩余限额进行对比,取较小值

        $usableLimit = min($dayLimit,$limit);

        if($cash > $usableLimit){

            return self::callError('您当前最多可充值'.$usableLimit.'元');
        }

        return self::callSuccess();

    }

    /**
     * @param $userId
     * @param $bankId
     * @param $cardNo
     * @param $cash
     * @param $channel
     * @param $client
     * @param $version
     * @return array
     * App人工选择支付通道创建订单逻辑
     */
    public function makeOrder($userId,$bankId,$cardNo,$cash,$channel,$client,$version){


        try{

            ValidateModel::isCash($cash);

            //绑卡信息
            $rechargeModel = new RechargeModel();

            $authCard = $rechargeModel->getAuthCard($userId);
            if(!empty($authCard)) {
                $bankId = $authCard['bank_id'];
                $cardNo = $authCard['card_no'];

            } else {
                ValidateModel::isBankId($bankId);
            }

            //检查该渠道是否可以用于支付
            $result = $this->checkPayLimit($userId,$cash,$channel,$bankId);
            //是否有可用通道
            if(!$result['status']){
                return self::callError($result['msg']);
            }
            //创建订单
            $order = $this->createOrder($userId,$cash,$bankId,$cardNo,$channel,$client,$version);

            if(!$order['status']){

                return self::callError($order['msg']);

            } else {

                $orderId = $order['data']['order_id'];

                Cache::put('client', $client, 10);
                Cache::put('version',$version, 10);

                $data = [
                    'userId'        => $userId,
                    'bankId'        => $bankId,
                    'cash'          => $cash,
                    'cardNo'        => $cardNo,
                    'orderId'       => $orderId,
                    'payChannel'    => $channel,
                ];
                //分发通道
                return self::callSuccess($data);
            }

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }


    }

    /**
     * @desc    处理异步回调订单信息
     * @param   $searchInfo     查单信息
     * @param   $orderId        查单信息
     * @param   $result         支付返回信息
     * @return  boolean true/false
     ***/
    public  static function toNotifyOrder($result){
        $rechargeModel = new RechargeModel();

        #检测九斗鱼订单与第三方订单信息是否一致
        $checkResult = self::checkNotifyData($result);

        //接收回调数据后的订单处理
        if($result['trade_status']==OrderDb::TRADE_SUCCESS && $checkResult['result']=="success"){
            $orderDone = $rechargeModel->paySuccess($result['order_id'],$result['trade_no']);
        }else{
            $msg    = $checkResult['title']?$checkResult['title'] : $result['msg'];
            $orderDone = $rechargeModel->payFail($result['order_id'],$msg);
        }
        return $orderDone;
    }

    /**
     * @desc  根据订单ID，获取订单信息, 第三方查单信息，验证订单金额与支付金额是否一致
     * @param $orderId      订单ID
     * @param $searchInfo   第三方查单信息
     *
     **/
    public static function checkNotifyData($resultInfo=[]){
        $orderId= $resultInfo["order_id"];
        $result = 'success';
        $title  = '';
        #TODO: 获取订单信息
        $orderInfo  = OrderModel::getOrderInfo($orderId);
        if(!$orderInfo){
            $result = 'fail';
            $title  = "九斗鱼系统-订单不存在";
            $msg    = "订单编号：".$orderId."，在九斗鱼系统不存在！";
            \Log::error(__METHOD__." : ".__LINE__."Error", [$msg]);
            #TODO 发送邮件
            self::rechargeErrorOrder($title,$msg);

        }else{
            $payAmount  = isset($resultInfo['amount'])?intval($resultInfo['amount']):'-1';
            $orderCash  = intval($orderInfo['cash']);
            if($payAmount != $orderCash ){
                $result = 'fail';
                $title  = "订单金额与支付金额不一致";
                $msg    = "订单金额与支付金额不一致,用户ID：".$orderInfo['user_id']." 订单编号：".$orderId." 订单金额：".$orderCash." ,实际支付金额：".$payAmount;
                #TODO 发送邮件
                self::rechargeErrorOrder($title,$msg);
                \Log::error(__METHOD__." : ".__LINE__."Error", [$msg]);
                \Log::error(__METHOD__." : ".__LINE__."Error", $orderInfo);
            }
        }

        return [
            'result'=> $result,
            'title' => $title,
        ];

    }


    /**
     * @desc    第三方异步回调-订单验证-异常订单邮件发送
     * @param   $title  标题
     * @param   $msg    信息
     **/
    public static function rechargeErrorOrder($title='',$msg=''){

        $receiveEmails = Config::get('email.monitor.accessToken');
        $model  = new EmailModel();
        try{

            $title  = $title? $title:"充值订单异常";

            $msg    = $msg  ? $msg  :"充值订单异常";

            $model->sendHtmlEmail($receiveEmails,$title,$msg);

        }catch (\Exception $e){

            \Log::Error(__METHOD__.'Error',['msg' => $e->getMessage()]);

        }

    }
}

