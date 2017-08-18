<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/8/1
 * Time: 上午11:30
 */

namespace App\Http\Logics\ThirdApi;

use App\Http\Dbs\Bank\BankDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Logic;

use App\Http\Logics\User\LoginLogic;

use App\Http\Logics\User\SessionLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\PasswordModel;

use App\Http\Models\Common\ServiceApi\SmsModel;

use App\Http\Models\Common\ValidateModel;
use App\Http\Models\User\UserModel;

use App\Http\Models\User\UserRegisterModel;

use App\Lang\LangModel;

use App\Tools\ToolEnv;

use App\Http\Models\Common\CoreApi\ProjectModel as CoreApiProjectModel;
use App\Http\Models\Common\CoreApi\BankCardModel as CoreApiBankCardModel;
use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;

use Log, Event, Cache;
/**
 * 普付宝logic
 *
 * Class PfbLogic
 * @package App\Http\Logics\ThirdApi
 */
class PfbLogic extends Logic
{
    const

        SUCCESS_STATUS       = 2000,      //成功状态
        ERROR_STATUS         = 4000,      //失败状态
        VERIFY_STATUS        = 4001,      //需要发送验证短信
        ERROR_TOKEN          = 4010,      //令牌token失效
        ERROR_SIGN           = 4020,      //签名错误
        ERROR_TOKEN_NOT_SAME = 4030,     //TOKEN与手机号不一致
        //加密KEY
        SIGN_KEY             = '88b2e6d3e5eacc043ad34076e26fdd95',

        END				     = true;

    protected static $freezeArr      = ['freeze','unfreeze'];

    /**
     * 获取用户ID
     * @return int
     */
    protected static function getUserId(){
        $session = SessionLogic::getTokenSession();
        if(isset($session['id'])){
            return $session['id'];
        }
        return 0;
    }

    /**
     * 老系统 请求token 【未注册用户注册后请求token】
     *
     * @param array $param
     * @return array
     */
    public static function requestToken($param = []){

        //验证签名
        $generateSign 	 = $param['phone'] . $param['name'] . $param['identityCard'];

        $chkSign 	     = PfbLogic::checkSign($generateSign, $param['sign']);

        if(!empty($chkSign)){

            return $chkSign;
        }

        $returnArray = [
            'status'            => self::ERROR_STATUS,
            'msg'               => '操作失败',
        ];

        try {
            $loginLogic            = new LoginLogic();
            $clientId              = $loginLogic->setClientId($param['client']);
            // 验证手机号 有效性
            UserModel::validationPhone($param['phone']);
            // 核心接口验证
            $userInfo = UserModel::getCoreApiBaseUserInfo($param['phone']);

            //【1】已有用户 - 已经实名
            if(!empty($userInfo) && !empty($userInfo['real_name']) && !empty($userInfo['identity_card']) && !empty($param['identityCard'])) {
                if ($userInfo['real_name'] == $param['name'] && $userInfo['identity_card'] == $param['identityCard']) {
                    // 生成token返回
                    $tokenData = app('oauth2')->createAccessToken($clientId, $userInfo['id']);
                    Log::info(__METHOD__ . ' createAccessToken:', [$tokenData]);
                    if ($tokenData) {
                        $returnArray = [
                            'status' => true,
                            'msg' => '操作成功',
                            'token' => $tokenData['access_token'],
                            'expire_time' => date('Y-m-d H:i:s', strtotime('+'.($tokenData['expires_in']/3600) . 'hour')),
                            'id' => $userInfo["id"]
                        ];
                        return $returnArray;
                    }
                } else {
                    $returnArray['msg'] = '参数不一致';
                    return $returnArray;
                }
            }else {
                //【2】新用户 - 注册+实名
                if (empty($userInfo)) {
                    //(1) 创建用户
                    $password = 'jiudouyu123';
                    $data = [
                        'phone'             => $param['phone'],
                        'password'          => PasswordModel::encryptionPassword($password),
//                        'real_name'         => $param['name'],
//                        'identity_card'     => $param['identityCard'],
                    ];

                    $userRegisterModel          = new UserRegisterModel();
                    $data['coreApiData']        = $userRegisterModel->doCoreApiRegister($data);
                    $userId                     = isset($data['coreApiData']['id']) ? $data['coreApiData']['id'] : 0;
                    //(2) 发送短信
                    $message = LangModel::getLang('PHONE_REGISTERED_PUFUBAO');
                    $message = sprintf($message, $password);
                    Log::info('普付宝注册成功 发送通知 ' . $message);
                    if(ToolEnv::getAppEnv() === 'production') {
                        SmsModel::sendNotice($param['phone'], $message);
                    }

                    //(3) 创建成功 [记录附加信息]
                    $data['request_source']     = $param['client'];
                    Event::fire(new \App\Events\User\RegisterSuccessEvent(
                        ['data' => $data]
                    ));

                } else {
                    //【3】已有用户未实名 - 更新实名信息
                    $userId                     = $userInfo['id'];
                    //\App\Http\Models\Common\CoreApi\UserModel::doRealName($userInfo["id"], $param['name'], $param['identityCard']);
                }

                if($userId > 0) {
                    // 生成token返回
                    $tokenData = app('oauth2')->createAccessToken($clientId, $userId);
                    if ($tokenData) {
                        $returnArray = [
                            'status' => self::SUCCESS_STATUS,
                            'msg' => '操作成功',
                            'item'  => [
                                        'token' => $tokenData['access_token'],
                                'expire_time' => date('Y-m-d H:i:s', strtotime('+'.($tokenData['expires_in']/3600) . 'hour')),
                                ],
                            //'id' => $userId,
                        ];
                        return $returnArray;
                    }
                }
            }
        }catch (\Exception $e) {
            $data['msg'] = $e->getMessage();
            $data['code'] = $e->getCode();
            $data['param'] = $param;
            Log::error(__METHOD__ . 'Error', $data);
            $returnArray['msg'] = $e->getMessage();
        }

        return $returnArray;
    }


    /**
     * 老系统刷新token 逻辑
     *
     * @param array $param
     * @return array
     */
    public static function refreshToken($param = []){

        //验证签名
        $chkSign 	     = PfbLogic::checkSign($param['phone'], $param['sign']);

        if(!empty($chkSign)){

            return $chkSign;
        }

        $returnArray = [
            'status'            => self::ERROR_STATUS,
            'msg'               => '操作失败',
        ];

        try {
            $loginLogic            = new LoginLogic();
            $clientId              = $loginLogic->setClientId($param['client']);
            // 验证手机号 有效性
            UserModel::validationPhone($param['phone']);
            // 核心接口验证
            $userInfo = UserModel::getCoreApiBaseUserInfo($param['phone']);

            if(empty($userInfo))
                return null;
            // 生成token返回
            $tokenData = app('oauth2')->createAccessToken($clientId, $userInfo['id']);
            if ($tokenData) {
                $returnArray = [
                    'status' => self::SUCCESS_STATUS,
                    'msg'    => '操作成功',
                    'item'   => [
                                'token' => $tokenData['access_token'],
                                'expire_time' => date('Y-m-d H:i:s', strtotime('+'.($tokenData['expires_in']/3600) . 'hour')),
                    ],
                    //'id' => $userInfo["id"]
                ];
                return $returnArray;
            }
        }catch (\Exception $e) {
            $data['msg'] = $e->getMessage();
            $data['code'] = $e->getCode();
            $data['param'] = $param;
            Log::error(__METHOD__ . 'Error', $data);
            $returnArray['msg'] = $e->getMessage();
        }

        return $returnArray;
    }

    /**
     * 执行发送短信
     * @param $data
     * @return array
     */
    public function doSms($data = []){

        $phone      = $data['phone'];

        $type       = $data['type'];

        $auth_sign  = $phone.$type;

        $result     = $this->checkParam($phone,$auth_sign,$data['token'],$data['sign'],$data['userId']);

        if($result['status'] != self::SUCCESS_STATUS){

            return $result;
        }

        $status     = self::ERROR_STATUS;

        try {
            //检查手机号
            ValidateModel::isPhone($phone);

            $code = $this->getSmsCode($phone,$type);

            $contentArr = array(
                "pfb_bind"     => "【九斗鱼】授权绑定验证码：" . $code . "，30分钟内有效",//绑定验证
                "pfb_transfer" => "【九斗鱼】申请质押验证码：" . $code . "，30分钟内有效",//申请质押验证
                "pfb_check"    => "【九斗鱼】检验账户验证码：" . $code . ",30分钟内有效",//检验已有账户的真实性
            );

            if (!array_key_exists($type, $contentArr)) {
                $msg = "发送类型错误";
            } else {
                //发送短信
                SmsModel::sendVerify($phone, $contentArr[$type]);
                $msg    = "短信发送成功";
                $status = self::SUCCESS_STATUS;
            }
        }catch (\Exception $e){
            $msg    = $e->getMessage();
        }

        return ['status' => $status, 'msg' => $msg];

    }

    /**
     * 通过不同类型获取验证码
     * @param int $phone
     * @param string $type
     * @return number
     */
    private function getSmsCode($phone, $type){

        $cacheKey   = "sms{$phone}_{$type}";
        $ttl        = 30;

        $code       = Cache::get($cacheKey);

        if(empty($code)){
            //验证码 存在memecache里面30分钟
            $code   = \App\Http\Models\Common\SmsModel::getRandCode();

            Cache::put($cacheKey,$code,$ttl);
        }

        return $code;
    }

    public function chkSms($data = []){

        $phone      = $data['phone'];

        $type       = $data['type'];

        $code       = $data['code'];

        $auth_sign 	= $phone.$code.$type;

        $result     = $this->checkParam($phone,$auth_sign,$data['token'],$data['sign'],$data['userId']);

        if($result['status'] != self::SUCCESS_STATUS){

            return $result;
        }

        $cacheKey   = "sms{$phone}_{$type}";

        $oldCode    =  Cache::get($cacheKey);

        if($code != $oldCode){
            $returnArr = array(
                "status"    => self::ERROR_STATUS,
                "msg"       => "验证码错误",
                "item"      => array('oldCode'=>$oldCode)
            );
        }else{
            $returnArr = array(
                "status"    => self::SUCCESS_STATUS,
                "msg"       => "验证码正确"
            );
        }

        return $returnArr;
    }

    /**
     * @param $phone
     * @param $auth_sign
     * @param $token
     * @param $sign
     * @param $userId
     * @return array|bool|string
     * @desc 验证参数是否正确
     */
    private function checkParam($phone= '',$auth_sign = '',$token = '',$sign = '',$userId = 0){

        //验证签名
        $chkSign 	     = PfbLogic::checkSign($auth_sign, $sign);

        Log::info('checkParam 1 ', [$chkSign]);

        if(!empty($chkSign)){

            return $chkSign;
        }

        //验证令牌
        $res             = PfbLogic::verifyToken($token,$userId);

        Log::info('checkParam 2 ', [$res]);

        if($res["status"] == self::ERROR_TOKEN){

            return $res;
        }

//        //检测token与手机号的一致性
        $verifyTP       = PfbLogic::verifyTokenPhone($token,$phone);

        Log::info('checkParam 2 ', [$verifyTP]);

        if(!empty($verifyTP)){

            return $verifyTP;
        }

        $res = ['status' => self::SUCCESS_STATUS, 'userId'=> $res['userId']];

        return $res;

    }

    /**
     * 检验token是否失效
     * @param $token
     * @param $userId
     * @return bool
     */
    public static function verifyToken($token,$userId = 0){
        $userId = self::getUserId();
        if(empty($userId)){

            $res = array(
                "status"  => self::ERROR_TOKEN,
                "msg"     => " 令牌已失效"
            );
        }else {
            $res = array(
                "status" => self::SUCCESS_STATUS,
                "msg" => '',
                "userId" => $userId
            );
        }

        return $res;
    }

    /**
     * 验证签名失败处理
     *
     * @param $generateSign
     * @param $inputSign
     * @return mixed
     */
    public static function checkSign($generateSign = '', $inputSign = ''){
        $result = array(
            "status"    => self::ERROR_STATUS,
            "msg"       => "签名错误"
        );

        if(empty($generateSign)|| empty($inputSign)){

            return $result;
        }

        Log::info(__METHOD__ . '计算的签名：md5(' .  $generateSign .' + ' . self::SIGN_KEY .')');

        $generateSign  = md5($generateSign . self::SIGN_KEY);

        Log::info(__METHOD__ . '计算的签名：' .  $generateSign . '传入的签名:'. $inputSign);

        if($generateSign != $inputSign){

            return $result;
        }else{

            return '';
        }
    }

    /**
     * 验证token与手机号的一致性
     * @param $token
     * @param $phone
     * @return mixed
     */
    public static function verifyTokenPhone($token,$phone){

        $session = SessionLogic::getTokenSession();

        if(empty($session['phone']) || !empty($session['phone']) && $session['phone'] != $phone) {
            $result = array(
                "status" => self::ERROR_TOKEN_NOT_SAME,
                "msg" => "令牌错误"
            );

            return $result;
        }else{

            return '';
        }
    }

    /**
     * @param string $auth_sign
     * @param string $token
     * @param string $sign
     * @param int $userId
     * @return array|bool|string
     * @desc 检验TOKEN和SIGN
     */
    private function checkSignToken($auth_sign = '',$token = '',$sign = '',$userId = 0){

        //验证签名
        $chkSign 	     = PfbLogic::checkSign($auth_sign, $sign);

        if(!empty($chkSign)){

            return $chkSign;
        }

        //验证令牌
        $res             = PfbLogic::verifyToken($token,$userId);

        if($res["status"] == self::ERROR_TOKEN){

            return $res;
        }


        return $res = ['status' => self::SUCCESS_STATUS, 'userId'=> $res['userId']];
    }

    /**
     * @param array $data
     * @return array|bool|string
     * @desc 处理用户订单，冻结或解冻
     */
    public function dealOrder($data = []){

        $result = [
            'status'    => self::ERROR_STATUS,
            'msg'       => ''
        ];

        if(!in_array($data['type'],self::$freezeArr)){

            $result['msg'] = '类型错误';

            return $result;
        }

        $auth_sign = $data['orderId'].$data['type'];

        $returnRes = $this->checkSignToken($auth_sign,$data['token'],$data['sign'],$data['userId']);

        if($returnRes['status'] != self::SUCCESS_STATUS){

            return $returnRes;
        }

        $userId    = $returnRes['userId'];

        $logic     = new InvestPfbLogic();

        $delResult = $logic->dealOrder($data['orderId'],$data['type'],$userId);

        $detail    = array();

        $delRes    = $delResult['data'];

        if($delRes['status']){

            $code    = self::SUCCESS_STATUS;

            $msg     = "处理订单成功";

            //根据订单ID获取项目信息
            $list    = $logic->getMortgageByInvestIds($delRes['ids']);

            $detail  = $this->formatInvestProject($list['data']);

        }else{

            $code    = self::ERROR_STATUS;

            $msg     = "处理订单失败";

        }

        $result     = ['status' => $code, 'msg' => $msg, 'item' => $detail];

        return $result;

    }

    /**
     * @param $phone
     * @param $sign
     * @param $token
     * @param int $userId
     * @return mixed
     * @desc 获取可质押订单列表
     */
    public function getOrder($phone,$sign,$token,$userId=0){

        $returnRes      = $this->checkParam($phone,$phone,$token,$sign,$userId);

        Log::info(__METHOD__ . '订单调试:', [$returnRes]);

        if($returnRes['status'] != self::SUCCESS_STATUS){

            return $returnRes;
        }

        $userId         = $returnRes['userId'];

        //根据用户ID获取可质押订单列表
        $investLogic    = new InvestPfbLogic();

        $list           = $investLogic->getUserUnPledgeInvest($userId);
        $invest         = $this->formatInvestProject($list['data']);

        $result['item'] = $invest;
        $result['bank'] = $this->getUserBank($userId);

        return $result;

    }

    /**
     * @param $phone
     * @param $token
     * @param $sign
     * @param int $userId
     * @return array|bool|string
     * @desc 获取用户银行卡信息
     */
    public function getBank($phone,$token,$sign,$userId=0){

        $result       = $this->checkParam($phone,$phone,$token,$sign,$userId);

        if($result['status'] != self::SUCCESS_STATUS){

            return $result;
        }

        $userId       = $result['userId'];

        $bankInfo     = $this->getUserBank($userId);

        $data['item'] = $bankInfo;

        return $data;
    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户银行卡令牌
     */
    private function getUserBank($userId){

        //用户信息
        $userInfo   = CoreApiUserModel::getCoreApiUserInfo($userId);

        //获取用户的银行卡信息
        $bank       = CoreApiBankCardModel::getUserBindCard($userId);

        $card_no    = '';
        $bank_name  = '';
        $bank_code  = '';
        $url        = '';

        if(!empty($bank)) {

            $card_no    = $bank['card_no'];
            $bankId     = $bank['bank_id'];

            $bankDb     = new BankDb();

            $bankInfo   = $bankDb->getBankName($bankId);
            $bank_name  = $bankInfo['name'];
        }else{

            $url        = "http://".env('APP_DOMAIN_WX')."/recharge/index";
        }

        $bankInfo = [
            'cardNo'    => $card_no,
            'bankName'  => $bank_name,
            'realName'  => $userInfo['real_name'],
            'bankCode'  => $bank_code,
            'url'       => $url
        ];

        return $bankInfo;
    }

    /**
     * @param $phone
     * @param $token
     * @param $sign
     * @param int $userId
     * @return array|bool|string
     * @desc 获取用户已质押订单列表
     */
    public function getFreezeOrder($phone,$token,$sign,$userId=0){

        $returnRes      = $this->checkParam($phone,$phone,$token,$sign,$userId);

        if($returnRes['status'] != self::SUCCESS_STATUS){

            return $returnRes;
        }

        $userId         = $returnRes['userId'];

        //根据用户ID获取已质押的订单列表
        $investLogic    = new InvestPfbLogic();

        $list           = $investLogic->getUserPledgeInvest($userId);

        $invest         = $this->formatInvestProject($list['data']);

        $result['item'] = $invest;

        return $result;
    }

    /**
     * @param $phone
     * @param $token
     * @param $sign
     * @param int $userId
     * @return array|bool|string
     * @desc 获取用户资金信息
     */
    public function getBalance($phone,$token,$sign,$userId=0){

        $returnRes      = $this->checkParam($phone,$phone,$token,$sign,$userId);

        if($returnRes['status'] != self::SUCCESS_STATUS){

            return $returnRes;
        }

        $userId         = $returnRes['userId'];

        //获取用户资金信息，总金额、质押金额、未质押金额
        $investLogic    = new InvestPfbLogic();

        $list           = $investLogic->getUserPledgeBalance($userId);

        $result['item'] = $list['data'];

        return $result;
    }

    /**
     * @return mixed
     * @desc 获取首页项目信息
     */
    public function getProjectRate(){

        //获取用户可质押的项目信息，取2条数据
        $list         = CoreApiProjectModel::getPfbList(1,2);

        $project      = array();

        foreach($list as $k=>$v){

            $project[$k]['id']                = $v["id"];
            $project[$k]['profit_percentage'] = money_format($v['profit_percentage'],0);
            $project[$k]['invest_time']       = $v['format_invest_time'];
            $project[$k]['invest_time_unit']  = $v['invest_time_unit'];
            if($v['status'] < ProjectDb::STATUS_INVESTING){
                $type = '未开始';
            }else if($v['status'] == ProjectDb::STATUS_INVESTING){
                $type = '立即投资';
            }else{
                $type = '已售罄';
            }
            $project[$k]['project_type']      = $type;
        }

        if(empty($project)){
            $project = array(
                0 => array('project_type' => ''),
                1 => array('project_type' => '')
            );
        }
        $data['item'] = $project;

        return $data;
    }

    /**
     * @param $type
     * @param $token
     * @param $userId
     * @return array
     * @desc 验证并获取项目信息
     */
    public function getProject($type,$token,$userId=0){

        //验证令牌
        $res             = PfbLogic::verifyToken($token,$userId);

        if($res["status"] == self::ERROR_TOKEN){

            return $res;
        }

        if($type != 'all'){

            // 获取可质押项目的信息，取2条数据
            $project      = CoreApiProjectModel::getPfbList(1,2);

            if($type == 'up' && !empty($project[0])){

                $id       = $project[0]['id'];
            }elseif(!empty($project[1])){

                $id       = $project[1]['id'];
            }

            $url          = "http://".env('APP_DOMAIN_WX')."/project/detail/".$id;

        }else{

            $url          = "http://".env('APP_DOMAIN_WX')."/pfb/projectList";   //普付宝用户看到的理财列表页
        }

        //跳转页面
        $httpQuery = [
            'version' => '1.0',
            'client'  => 'pfb',
            'token'   => $token,
        ];

        $query = http_build_query($httpQuery);

        $url = env('APP_URL_ANDROID') . "/app_sign_login?".$query.'&url='.$url;


        $result = array(
            'item'      => array('url' => $url,'token'=>$token)
        );

        return $result;
    }

    /**
     * 格式化投资记录
     * @param $list
     * @return array
     */
    private function formatInvestProject($list){

        if(empty($list)){

            return array();
        }

        $data = array();

        foreach($list as $k=>$v){

            $data[$k]['orderId']     = $v['id'];
            $data[$k]['endDate']     = $v['end_at'];
            $data[$k]['projectName'] = $v['name'].''.$v['project_id'];
            $data[$k]['cash']        = $v['cash'];
        }

        return $data;

    }


    /**
     * 检测是否有贷款质押金额
     * 账户保留的账户余额＋零钱计划>=全部抵押冻结订单下已回款项目的本息之和
     * @param int $userId
     * @return number
     * @author meijia
     */
    public static function checkPledgeAmount($oriCash)
    {

        // 用户余额
        $session     = SessionLogic::getTokenSession();

        if(empty($session)){
            throw new \Exception('请重新登录');
        }

        $userBalance = $session['balance'];

        $logic       = new UserLogic();

        //用户资产 - 零钱计划
        $userAccount = $logic->getUserInfoAccount($session['id']);
        if(empty($userAccount['current'])){
            $userAccount['current']['cash'] = 0;
        }

        $userCurrentBalance = $userAccount['current']['cash'];


        //根据用户ID获取已质押的订单列表
        $investLogic    = new InvestPfbLogic();

        $list           = $investLogic->getUserPledgeInvest($session['id']);

        $investPfbFrozen = 0;

        if(!empty($list['data'])){
            foreach($list['data'] as $k => $record){
                $investPfbFrozen += $record['cash'];
            }
        }

        if(empty($investPfbFrozen))
            return true;

        Log::info(__METHOD__, [$userCurrentBalance, $userBalance, $investPfbFrozen, $oriCash]);
        if (($userCurrentBalance + $userBalance) - $investPfbFrozen >=  $oriCash) {

            return true;
        }

        $canUse = round(($userCurrentBalance + $userBalance)-$investPfbFrozen,2);


        throw new \Exception("当前可提现金额为".($canUse)."元，请结清贷款后再提现剩余资金");
    }
}