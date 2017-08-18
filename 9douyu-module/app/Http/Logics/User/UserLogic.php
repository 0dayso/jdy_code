<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/12
 * Time: 下午4:04
 */

namespace App\Http\Logics\User;


use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Dbs\Bank\BankDb;
use App\Http\Dbs\Bank\CardErrorLogDb;
use App\Http\Dbs\Bonus\UserBonusDb;
use App\Http\Dbs\Identity\CardDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Dbs\User\AvatarDb;
use App\Http\Dbs\User\LoginHistoryDb;
use App\Http\Dbs\User\UserInfoDb;
use App\Http\Dbs\UserDb;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Logic;

use App\Http\Logics\RequestSourceLogic;
use App\Http\Models\Activity\ActivityFundHistoryModel;
use App\Http\Models\AppButton\AppButtonModel;
use App\Http\Models\Common\CoreApi\BankCardModel;
use App\Http\Models\Common\PasswordModel;
use App\Http\Models\Bank\CardModel;
use App\Http\Models\Common\CoreApi\CurrentModel;
use App\Http\Models\Common\TradingPasswordModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\User\UserInfoModel;
use App\Http\Models\User\UserModel;
use App\Lang\AppLang;
use App\Lang\LangModel;
use App\Tools\ToolEnv;
use App\Tools\ToolOrder;
use App\Tools\ToolStr;
use Validator;

use App\Tools\ToolMoney;

use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;
use App\Http\Models\Common\SmsModel as Sms;
use Mockery\CountValidator\Exception;
use Log;
use Session;
use Cache;
use App\Tools\ToolArray;


class UserLogic extends Logic
{

    /**
     * @param $userId
     * @return array
     * @desc 用户中心首页数据
     */
    public function getUserInfoAccount($userId){

        $model = new UserModel();

        $return = $model -> getCoreApiUserInfoAccount( $userId );

        return $return;

    }


    /**
     * @param $userId
     * @return mixed
     * @desc 通过用户Id获取用户可用加息券总数
     */
    public function getUserTotalBonus($userId){

        $db = new UserBonusDb();

        return $db -> getTotalBonusByUserId($userId);

    }


    /**
     * 账户中心零钱计划页面
     */
    public function getCurrentFund($userId){

        $data = CurrentModel::getCurrentUserFund($userId);

        $fundList = $data['fund_list'];

        //零钱计划资金流水金额转化
        if($fundList){

            foreach($fundList['data'] as $k=>$val){

                $fundList['data'][$k]['balance_change'] = ToolMoney::formatDbCashDelete($val['balance_change']);
                $fundList['data'][$k]['balance_before'] = ToolMoney::formatDbCashDelete($val['balance_before']);
                $fundList['data'][$k]['balance']        = ToolMoney::formatDbCashDelete($val['balance']);

            }

            $data['fund_list'] = $fundList;
        }


        //近一周利率列表
        $interestList = $data['interest_list'];
        if($interestList){

            foreach($interestList as $k=>$val){

                $interestList[$k]['principal']  = ToolMoney::formatDbCashDelete($val['principal']);
                $interestList[$k]['interest']   = ToolMoney::formatDbCashDelete($val['interest']);

            }
            $data['interest_list']    = $interestList;

        }

        //零钱计划账户信息
        $accountInfo = $data['account_info'];

        if($accountInfo){

            $accountInfo['cash']                    = ToolMoney::formatDbCashDelete($accountInfo['cash']);
            $accountInfo['interest']                = ToolMoney::formatDbCashDelete($accountInfo['interest']);
            $accountInfo['yesterday_interest']      = ToolMoney::formatDbCashDelete($accountInfo['yesterday_interest']);

            $data['account_info']   = $accountInfo;
        }

        return self::callSuccess($data);
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public static function modifyPhone($data = [])
    {
        $session = SessionLogic::getTokenSession();
        try {

            // 验证图片验证码
            if($data['captcha'] != Session::get('captcha')){
                throw new \Exception('图形码验证失败');
            }
            Session::forget('captcha');

            // 验证手机号 有效性
            UserModel::validationPhone($data['phone']);

            //验证验证码有效性
            $checkPhoneCode = Sms::checkPhoneCode($data['code'], $data['phone']);
            if (!$checkPhoneCode['status']) {
                throw new \Exception('短信验证码验证失败');
            }
            $return = CoreApiUserModel::doModifyPhone($session['phone'], $data['phone']);

        } catch (\Exception $e) {
            $attributes['data'] = $data;
            $attributes['msg'] = $e->getMessage();
            $attributes['code'] = $e->getCode();
            Log::error(__METHOD__ . 'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess($return);

    }

    /**
     * @param $userId
     * @param $name
     * @param $cardNo
     * @param $idCard
     * 用户实名+绑卡
     */
    public function verify($userId,$name,$cardNo,$idCard,$from){


        $log = [
            'user_id'   => $userId,
            'name'      => trim($name),
            'card_no'   => $cardNo,
            'id_card'   => $idCard,
            'from'      => $from
        ];

        try{

            //身份证号验证
            ValidateModel::isIdCard($idCard);
            //姓名格式判断
            ValidateModel::isName($name);
            //银行卡号格式判断
            ValidateModel::isBankCard($cardNo);

            //检查是否有联动优势的验卡失败结果,若存在,抛异常
            CardModel::checkUserBankCard($cardNo,$name,$idCard);

            $user = UserModel::getUserInfo($userId);

            $bankId = 1;

            if( ToolEnv::getAppEnv() == 'production' ){

                //获取银行卡的相关信息
                $bankId = CardModel::getCardInfo($cardNo);

                //先使用融宝鉴权
                $return = CardModel::checkCardByRea($cardNo,$name,$idCard,$user['phone']);

                //融宝鉴权失败,则使用联动优势鉴权
                if(!$return){
                    CardModel::checkCardByUmp($cardNo,$name,$idCard,$user['phone']);
                }

            }

            //判断是否有支持该卡的银行
            //CardModel::checkInvalidPayTypeBankId($bankId);

            //调用核心进行实名+绑卡操作
            UserModel::doVerify($userId,$name,$cardNo,$bankId,$idCard);

            //记录成功日志
            $identityDb = new CardDb();
            $identityDb->addRecored($name,$idCard,$from);

            Log::info(__METHOD__.'Success',$log);

        }catch (\Exception $e){

            Log::error(__METHOD__.'Error',['log'=> $log, 'error' => $e->getMessage()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();

    }

    /**
     * 输入格式化
     *
     * @param array $data
     * @return array
     */
    public static function modifyPhoneFormatInput($data = []){
        $input            = [];
        $input['captcha'] = isset($data['captcha']) ? $data['captcha'] : null;
        $input['phone']   = isset($data['phone']) ? $data['phone'] : null;
        $input['code']    = isset($data['code']) ? $data['code'] : null;
        $input['token'] = isset($data['captcha']) ? $data['token'] : null;
        return $input;
    }

    /**
     * @param $userId
     * @param $oldPassword
     * @param $newPassword
     * @param $confirmPassword 【微信端 不要求输入确认密码】
     * @param $type [password|tradingPassword]
     * @return array
     * @desc 修改密码／交易密码
     */
    public function changePassword($userId,$oldPassword,$newPassword,$confirmPassword = null, $type='password'){

        $user = $this->getUser($userId);
        $dbPassword = $type=='password'?$user['password_hash']:$user['trading_password'];
        $errorMsg   = $type=='password'?'ERROR_EDIT_VERIFY_USER_PASSWORD':'ERROR_EDIT_VERIFY_TRADING_PASSWORD';

        try{
            //输入验证
            PasswordModel::validationPassword($oldPassword);
            PasswordModel::validationPassword($newPassword);
            if(!is_null($confirmPassword))
                PasswordModel::validationPassword($confirmPassword);
            //旧密码匹配验证
            PasswordModel::validatePassword($oldPassword,$dbPassword,true,$errorMsg);
            //旧密码与新密码比较验证
            PasswordModel::validatePasswordIsSame($oldPassword,$newPassword,false);
            //新密码与确认密码比较验证
            if(!is_null($confirmPassword))
                PasswordModel::validatePasswordIsSame($newPassword,$confirmPassword,true);
            //设置密码
            $newPassword = PasswordModel::encryptionPassword($newPassword);
            \App\Http\Models\Common\CoreApi\UserModel::doPassword($userId,$newPassword,$type);

        }catch (\Exception $e){
            return self::callError($e->getMessage());
        }
        return self::callSuccess();

    }



    /**
     * @param $userId
     * 获取零钱计划用户收益列表
     */
    public function getCurrentInterestList($userId){

        $result = CurrentModel::getInterestList($userId);

        $return  = [
            'total_interest'    => 0,
            'list'              => [[]]
        ];

        if(!empty($result)){

            $return['total_interest'] = ToolMoney::formatDbCashDelete($result['total_interest']);

            $interestList  = $result['interest_list'];

            if(!empty($interestList)){
                foreach($interestList as $k => $val){

                    $list[] = [
                        'interest'  => ToolMoney::formatDbCashDelete($val['interest']),
                        'principal' => ToolMoney::formatDbCashDelete($val['principal']),
                        'date'      => $val['interest_date'],
                        'rate'      => $val['total_rate'],
                    ];
                }
            }else{
                $list = [[]];
            }

            $return['list'] = $list;

        }

        return self::callSuccess($return);
    }

    /**
     * @param $userId
     * @return array
     * @desc app账户中心数据接口
     */
    public function getAppUserInfo($userId){

        $userAccount['uid']     = $userId;

        try{

            //用户信息
            $userInfo = CoreApiUserModel::getCoreApiUserInfo($userId);

            //用户资产
            $userAccount = CoreApiUserModel::getCoreApiUserInfoAccount($userId);

            $userAccount['balance'] = empty($userInfo['balance'])?0:$userInfo['balance'];

            $data['items'] = $this->formatAppUserInfoAccount($userAccount);

            //用户银行卡相关信息
            $userBankCardInfo = BankCardModel::getUserBindCard($userId);

            if(!empty($userBankCardInfo)){
                $userBankCardInfo['real_name'] = $userInfo['real_name'];
            }

            $data['items']['user_bank']  = $this->formatAppUserBank($userBankCardInfo);

            $data['items']['bank_card_notice'] = AppLang::APP_BANK_CARD_NOTICE;

            //九宫格内容相关
            #$appButtonModel = new AppButtonModel();
            #$appButton = $appButtonModel -> getAppUserCenterButton();
            #$data['user_pic_url'] = $this -> formatAppUserPic($appButton, $userId);


            $appUserButton = AdLogic::getAppUserInfoButton(30, $userId);
            $data['user_pic_url']   = $appUserButton;

        }catch(\Exception $e){

            return self::callError( $e->getMessage() );

        }

        return self::callSuccess( $data );

    }

    /**
     * @param $data
     * @return array
     * @desc app账户中心数据格式化
     */
    public function formatAppUserInfoAccount( $data ){

        if(empty($data)){
            return [];
        }

        $balance = ToolMoney::formatDbCashDelete($data['balance']);

        //零钱计划投资金额
        $currentCash        = empty($data['current']['cash'])?0:ToolMoney::formatDbCashDelete($data['current']['cash']);
        //零钱计划已收利息
        $currentInterest    = empty($data['current']['interest'])?0:ToolMoney::formatDbCashDelete($data['current']['interest']);
        //定期投资金额
        $investJsx          = empty($data['project']['product_line'][ProjectDb::PROJECT_PRODUCT_LINE_JSX]) ? '' :  $data['project']['product_line'][ProjectDb::PROJECT_PRODUCT_LINE_JSX];
        $investJax          = empty($data['project']['product_line'][ProjectDb::PROJECT_PRODUCT_LINE_JAX]) ? '' :  $data['project']['product_line'][ProjectDb::PROJECT_PRODUCT_LINE_JAX];
        $investSdf          = empty($data['project']['product_line'][ProjectDb::PROJECT_PRODUCT_LINE_SDF]) ? '' :  $data['project']['product_line'][ProjectDb::PROJECT_PRODUCT_LINE_SDF];
        $investJsxCash      = empty($investJsx['principal']) ? 0: ToolMoney::formatDbCashDelete($investJsx['principal']);
        $investJsxInterest  = empty($investJsx['interest'])  ? 0: ToolMoney::formatDbCashDelete($investJsx['interest']);
        $investJaxCash      = empty($investJax['principal']) ? 0: ToolMoney::formatDbCashDelete($investJax['principal']);
        $investJaxInterest  = empty($investJax['interest'])  ? 0: ToolMoney::formatDbCashDelete($investJax['interest']);
        $investSdfCash      = empty($investSdf['principal']) ? 0: ToolMoney::formatDbCashDelete($investSdf['principal']);
        $investSdfInterest  = empty($investSdf['interest'])  ? 0: ToolMoney::formatDbCashDelete($investSdf['interest']);
        //定期已回款利息
        $investRefundInterest = empty($data['project']['refund_interest']) ? 0 : ToolMoney::formatDbCashDelete($data['project']['refund_interest']);

        $investTotalCash    = $investJsxCash + $investJsxInterest + $investJaxCash + $investJaxInterest + $investSdfCash + $investSdfInterest;

        $result = [

            'total_cash'            => number_format($currentCash + $balance + $investTotalCash,2), //总资产
            'total_interest'        => number_format($currentInterest + $investRefundInterest,2),                                           //累计收益
            'yesterday_interest'    => 0,                                                                                  //昨日收益
            'doing_invest_amount'   => number_format($investTotalCash,2),                                   //定期资产
            'current_cash'          => number_format($currentCash,2),                         //零钱计划
            'investing_cash'        => number_format($currentCash + $investJsxCash + $investJaxCash + $investSdfCash,2),                    //在投本金
            'balance'               => number_format($balance,2),

        ];

        return $result;

    }

    /**
     * @param $data
     * @return array
     * @desc 用户银行相关信息格式化
     */
    public function formatAppUserBank( $data ){

        if(empty($data)){
            return [];
        }

        //若存在认证银行卡，获取银行卡对应的银行名称
        $domain = env("APP_URL_WX");
        $cardNo = $data['card_no'];
        $bankId = $data['bank_id'];

        $bankDb = new BankDb();

        $bankInfo = $bankDb->getBankName($bankId);
        $bank['card_tail'] = substr($cardNo,-4);
        $bank['card_name'] = $bankInfo['name'];
        $bank['crad_number'] = substr($cardNo,0,4).'******'.substr($cardNo,-4);
        $bank['card_number'] = substr($cardNo,0,4).'******'.substr($cardNo,-4);
        $bank['image'] = ToolOrder::getBankImage($bankId);
        $realName       =   trim($data['real_name']);

        if(empty($data['real_name'])) {
            $bank['real_name'] = '';
        }else{
            $bank['real_name'] = '*' . substr($realName, 3);

        }

        return $bank;

    }

    /**
     * @param $data
     * @return array
     * @desc 用户中心接口
     */
    public function formatAppUserPic( $data, $userId=0 ){

        if(empty($data)){
            return [];
        }

        $result = [];

        foreach($data as $key => $value){

            $result[$key] = [
                'name'          => $value['name'],
                'picture_id'    => $value['picture_id'],
                'position_num'  => $value['position_num'],
                'pic_url'       => $value['pic_url'],
            ];

            if(!empty($value['share'])){

                $result[$key]['share'] = [$value['share']];

                $result[$key]['share'][0]['share_url'] = isset($value['share']['invite_url']) ? env('APP_URL_WX').'/register?inviteId='.$userId : $value['share']['share_url'];

                $result[$key]['location_url']   = $value['share']['share_url'];

            }

        }

        return $result;
    }

    /**
     * @param $phone
     * @param $code
     * @param string $type
     * 修改手机号
     */
    public function doEditPhone($userId,$phone,$code,$type='modify_phone'){

        try{

            //验证验证码是否正确
            $sms = new SmsLogic();
            $result = $sms->checkCodeByType($phone,$code,$type);

            if(!$result['status']){

                return self::callError($result['msg']);
            }

            //获取用户信息,然后修改手机号
            $userInfo = UserModel::getUserInfo($userId);
            $dbPhone = $userInfo['phone'];

            $result = \App\Http\Models\Common\CoreApi\UserModel::doModifyPhone($dbPhone,$phone);
            if($result['status']){

                return self::callSuccess([]);
            }else{
                return self::callError($result['msg']);
            }

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

    }

    /**
     * @param $userId
     * @param $idCard
     * @return array
     * 验证用户身份证号是否正确
     */
    public function verifyIdentity($userId,$idCard){

        try{

            ValidateModel::isIdCard($idCard);

            //获取用户信息
            $userInfo = UserModel::getUserInfo($userId);

            if(strtoupper($idCard) == strtoupper($userInfo['identity_card'])){
                return self::callSuccess([]);
            }else{
                return self::callError('身份证号不一致');
            }

        }catch(\Exception $e){

            return self::callError($e->getMessage());

        }
    }

    /**
     * 获取实名认证状态
     * @return array
     */
    public static function verifyStatus(){
        $userInfo = SessionLogic::getTokenSession();

        if(empty($userInfo)){
            return self::callError( '未登录', 4010);
        }else{

            $items = !empty($userInfo["real_name"]) ? 1 : 0;

            $return = [
                'items'=> (string)$items,
            ];

            $msg  = ($items == 1) ? '已实名认证' : '未实名认证';

            return self::callSuccess($return, $msg);
        }
    }

    /**
     * @return array
     * @desc 昨日收益
     */
    public function getUserYesterdayInterest(){

        $data = [

            'total'             => 0,
            'total_interest'    => 0,
            'project_list'      => [],

        ];

        return self::callSuccess($data);

    }
    /**
     * @param $userId
     * @return array
     * 根据用户ID获取用户信息
     */
    public function  getUserInfoById($userId){

        $return = \App\Http\Models\Common\CoreApi\UserModel::getCoreApiUserInfo($userId);
        $userInfoDb = new UserInfoDb();

        if(!empty($return)){
            $userInfo = $userInfoDb -> getByUserId($return['id']);
            if(!empty($userInfo)){
                $return['user_info'] = $userInfo;
            }
            $return['balance'] = ToolMoney::formatDbCashDelete($return['balance']);
        }

        return $return;
    }

    /**
     * @param $params
     * @return array
     * 根据余额和未投资天数获取用户信息
     */
    public function  getNoInvestUser($params){

        $return = \App\Http\Models\Common\CoreApi\UserModel::getNoInvestUser($params);

        if(!empty($return)){
            return $return;
        }else{
            $return = [
                'data'  => [],
                'total' => '',
            ];
            return $return;
        }
    }

    /**
     * @param $user
     * 获取用户实名状态信息
     */
    public static function getUserAuthStatus($user){

        if(empty($user)){
            $return = [
                'is_login'          => 'off',
                'name_checked'      => 'off',
                'password_checked'  => 'off',
            ];
            return $return;
        }

        $authData = UserModel::getUserAuthStatus($user);

        $return = [
            'is_login'          => 'on',
            'name_checked'      => $authData['name_checked'] ? 'on' : 'off',
            'password_checked'  => $authData['password_checked'] ? 'on' : 'off',
        ];

        return $return;
    }

    /**
     * @desc 获取用户信息列表[后台]
     * @param $param
     * @param $page
     * @param $size
     * @return null|void
     */
    public function getUserListAll($page,$size, $param){

        $return  =  \App\Http\Models\Common\CoreApi\UserModel::getUserListAll($page, $size, $param);

        if($return['status']){
            $result = $return['data'];

            if(isset($result['data']) && $result['data']){

                $userList = $result['data'];

                //批量获取多个用户的扩展信息
                $userIds = ToolArray::arrayToIds($userList,'id');

                $userInfoDb = new UserInfoDb();
                $userInfoList = $userInfoDb->getByUserIds($userIds);

                $userInfoList = ToolArray::arrayToKey($userInfoList,'user_id');

                foreach($userList as $key => $val){

                    $result['data'][$key]['status'] = UserModel::getUserStatus($val['status_code']);

                    if(isset($userInfoList[$val['id']])){
                        $userInfo = $userInfoList[$val['id']];
                    }else{
                        $userInfo = [];
                    }
                    $result['data'][$key]['user_info'] = $userInfo;
                }

            }

            return $result;

        }else{
            return $return;

        }

    }

    /**
     * @desc 获取用户统计
     * @author lgh
     * @param $param
     * @return null|void
     */
    public function getUserStatistics($param){

        $return = \App\Http\Models\Common\CoreApi\UserModel::getUserStatistics($param);

        if($return['status']){
            return $return['data'];
        }else{
            return $return;
        }
    }
    /**
     * @param $start
     * @param $end
     * @return mixed
     * @desc 某个时间段内的注册总数
     */
    public function getUserAmountByDate($start,$end){

        $model = new CoreApiUserModel();

        $res   = $model->getUserAmountByDate($start,$end);

        //return $res['data'];
        return $res;

    }

    /**
     * @return null|void
     * @desc 获取总注册数
     */
    public function getUserTotal(){

        $model = new CoreApiUserModel();

        $res   = $model->getUserTotal();

        $total = empty($res['data']) ? 0 : $res['data']['total'];

        return $total;
    }

    /**
     * @desc 锁定用户状态
     * @author lgh
     * @param $userId
     * @param $status
     * @return null|void
     */
    public function doUserStatusBlock($userId, $status){

        $model = new CoreApiUserModel();

        $return   = $model->doUserStatusBlock($userId, $status);

        return $return;
    }

    /**
     * @desc 获取当日生日的用户
     * @return null|void
     */
    public function getBirthdayUser(){

        $return = \App\Http\Models\Common\CoreApi\UserModel::getBirthdayUser();

        if($return['status']){
            return $return['data'];
        }else{
            return $return;
        }

    }

    /**
     * @desc 通过多个身份证号(逗号隔开)获取用户信息
     * @param $identityCards
     * @return null|void
     */
    public function getUserByIdCards($identityCards){

        $model = new CoreApiUserModel();

        $return = $model->getUserByIdCards($identityCards);

        if($return['status']){
            return $return['data'];
        }else{
            return $return;
        }
    }

    /**
     * @desc  通过多个手机号(逗号隔开)获取用户信息
     * @param $phones
     * @return null|void
     */
    public function getUserByPhones($phones){

        if(is_array($phones)) $phones = implode(',', $phones);

        $model = new CoreApiUserModel();
        $return = $model->getUserByPhones($phones);

        if($return['status']){
            return $return['data'];
        }else{
            return $return;
        }
    }

    /**
     * @param $phone
     * @param $cash
     * @param $note
     * @param $code
     * @param $confirmCode
     * @return array
     * @desc 后台用户账户加/扣款
     */
    public function doChangeBalance($phone, $cash, $note, $code, $confirmCode, $type=0){

        if( !$type ){

            return self::callError('类型有误');

        }



        try{

            self::beginTransaction();

            ValidateModel::isPhone($phone);
            ValidateModel::isDecimalCash($cash);
            ValidateModel::isEmpty($note, '备注');
            ValidateModel::isEmpty($code, '安全验证码');

            if($code != $confirmCode){
                return self::callError('安全验证码错误');
            }

            $activityFundHistory = new ActivityFundHistoryModel();

            $model          = new CoreApiUserModel();

            $userInfo       = $model ->getBaseUserInfo($phone);

            $userId         = $userInfo['id'];

            $tradePassword  = $userInfo['trading_password'];

            $data = [
                'user_id'           => $userId,
                'balance_change'    => $cash,
                'source'            => ActivityFundHistoryDb::SOURCE_ADMIN_ADD_BALANCE,
                'note'              => $note,
            ];

            $ticketId = ToolStr::getRandTicket();

            if($type == 1){
                $activityFundHistory->doIncrease($data);
                $result         = $model -> doIncBalance($userId, $cash, $tradePassword, $note, $ticketId);
            }elseif($type == 2){
                $activityFundHistory->doDecrease($data);
                $result         = $model -> doDelBalance($userId, $cash, $tradePassword, $note, $ticketId);
            }

            self::commit();

        }catch(\Exception $e){

            self::rollback();

            Log::error('doAddBalance', [$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);

    }

    /**
     * @param $userId
     * @return array
     * @desc 账户冻结
     */
    public function doUserFrozen( $userId ){

        if(empty($userId)){

            return self::callError('参数错误');

        }

        $model = new CoreApiUserModel();

        $result = $model->doUserFrozen( $userId );

        if($result['status']){

            return self::callSuccess($result['data']);

        }

        return self::callError($result['msg']);

    }

    /**
     * @param $userId
     * @return array
     * @desc 账户解冻
     */
    public function doUserUnFrozen( $userId ){

        if(empty($userId)){

            return self::callError('参数错误');

        }

        $model = new CoreApiUserModel();

        $result = $model->doUserUnFrozen( $userId );

        if($result['status']){

            return self::callSuccess($result['data']);

        }

        return self::callError($result['msg']);

    }

    /**
     * @param int $userId
     * @param int $size
     * @return array|mixed
     * @desc  获取登录信息
     */
    public function getUserLoginInfo( $userId = 0,$size = 30)
    {
        if( $userId ==0 || empty($userId) ){

            return [];
        }

        $userLoginDb    =   new LoginHistoryDb();

        return $userLoginDb->getLoginListByUserId($userId,$size);
    }

    /**
     * @desc AppV4.0用户资产页数据格式化
     * @param $data array
     * @param $userId int
     * $return array
     */
    public function formatAppV4UserInfo($data, $userId){
        $userInfo = [];
        if(empty($data) || empty($userId) || $userId ==0){
            return [];
        }

        $userBonusLogic = new UserBonusLogic();
        $ableBonusCount = $userBonusLogic->getAbleUserBonusCount($userId);

        //去除银行相关数据
        if(isset($data['items']['user_bank'])){
            unset($data['items']['user_bank']);
        }
        if(isset($data['items']['bank_card_notice'])){
            unset($data['items']['bank_card_notice']);
        }
        $userInfo = $data['items'];
        $userInfo['recharge_note'] = '充值';
        $userInfo['withdraw_note'] = '提现';
        $userInfo['total_cash_note'] = '总资产(元)';
        $userInfo['balance_note'] = '账户余额';
        $userInfo['current_cash_note'] = '零钱计划';
        $userInfo['investing_cash_note'] = '优选理财';
        $userInfo['balance_note1'] = '账户余额(元)';
        $userInfo['investing_cash_note1'] = '在投本金(元)';
        $userInfo['total_interest_note1'] = '累计收益(元)';


        //AppV4用户中心按钮列表
        $appUserButton = AdLogic::getUseAbleListByPositionId(25);

        $appUserButton = AdLogic::formatAppV4UserInfoButton($appUserButton,$userId);
        #print_r($appUserButton);exit;
        $userInfo['button_list'] = $appUserButton;

        //格式化按钮数据
        foreach($appUserButton as $key=>$value){
            if(isset($value['position_num'])){
                if($value['position_num'] == 1){
                    $userInfo['button_list'][$key]['note'] = $userInfo['doing_invest_amount'];
                }
                if($value['position_num'] == 2){
                    $userInfo['button_list'][$key]['note'] = $userInfo['current_cash'];
                }
                if($value['position_num'] == 21){
                    $userInfo['button_list'][$key]['count']  = $ableBonusCount;
                    $userInfo['button_list'][$key]['note']  = '张可用';
                }

                if($value['position_num']>=10 && $value['position_num']<20){
                    $userInfo['button_list'][$key]['note'] = empty($value['word']) ? '' : $value['word'];

                    unset($userInfo['button_list'][$key]['position_num']);
                }

            }
            unset($userInfo['button_list'][$key]['word']);

        }


        return $userInfo;
    }

    /**
     * @param $userId
     * @return array
     * @desc App4.0个人信息设置中心数据接口
     */
    public function getAppV4UserInfo( $userId ){

        //用户信息
        $userInfo = $this->getUserInfoById($userId);

        //用户头像
        $userAvatar = AvatarDb::getUserAvatarByUserId($userInfo['id']);

        $avatar = '';
        if(isset($userAvatar['avatar_url'])){
            $avatarUrl  = $userAvatar['avatar_url'];
            $avatar   = strpos($avatarUrl,'ttp://www.9douyu.com') ? assetUrlByCdn(substr($avatarUrl,strlen('http://www.9douyu.com'))) : assetUrlByCdn($avatarUrl);
        }

        //用户银行卡相关信息
        $userBankCardInfo = BankCardModel::getUserBindCard($userId);
        if(!empty($userBankCardInfo)){
            $userBankCardInfo['real_name'] = $userInfo['real_name'];
        }

        $data = [];
        if(!empty($userInfo)){
            $data['avatar']    = $avatar;
            $data['phone']     = empty($userInfo['phone']) ? '' : $userInfo['phone'];
            $data['real_name'] = empty($userInfo['real_name']) ? '' : $userInfo['real_name'];
            $data['email']     = empty($userInfo['user_info']['email']) ? '' : $userInfo['user_info']['email'];
            $data['address']   = empty($userInfo['user_info']['address_text']) ? '' : $userInfo['user_info']['address_text'];

            $data['user_bank']  = $this->formatAppUserBank($userBankCardInfo);
            $data['user_bank']['bank_card_notice'] = AppLang::APP_BANK_CARD_NOTICE;

        }

        return self::callSuccess($data);

    }

    /**
     * @param $phone
     * @param $name
     * @param $identity
     * @return array
     * 验证用户电话号,姓名,身份证号是否一致(实名验证)
     */
    public function checkUserBaseInfo($phone,$name,$identity){

        try{
            ValidateModel::isPhone($phone);

            ValidateModel::isName($name);

            ValidateModel::isIdCard($identity);
            //获取用户信息
            $userInfo = UserModel::getCoreApiBaseUserInfo($phone);

            $identityNum = isset($userInfo['identity_card']) ? $userInfo['identity_card'] : '';
            $realName = isset($userInfo['real_name']) ? $userInfo['real_name'] : '';

            if(strtoupper($identity) == strtoupper($identityNum) && $name == $realName){
                return self::callSuccess([]);
            }else{
                return self::callError('身份验证失败');
            }

        }catch(\Exception $e){

            return self::callError($e->getMessage());

        }
    }

    /**
     * @param $userId
     * @param $name
     * @param $cardNo
     * @param $idCard
     * 用户实名+绑卡
     */
    public function doVerifyTradingPassword($userId,$name,$cardNo,$idCard,$from, $tradingPassword, $bankIdInput=null){


        $log = [
            'user_id'   => $userId,
            'name'      => trim($name),
            'card_no'   => $cardNo,
            'id_card'   => $idCard,
            'from'      => $from,
            'tradingPassword'=> $tradingPassword
        ];

        try{

            $name = str_replace(' ', '', trim($name));

            //身份证号验证
            ValidateModel::isIdCard($idCard);
            //姓名格式判断
            ValidateModel::isName($name);
            //银行卡号格式判断
            ValidateModel::isBankCard($cardNo);
            //判断交易密码格式
            PasswordModel::validationPassword($tradingPassword);

            //获取银行卡的相关信息
            if($bankIdInput !== null){
                $bankId = $bankIdInput;
            }else{
                $bankId = CardModel::getCardInfo($cardNo);
            }

            //检查是否有联动优势的验卡失败结果,若存在,抛异常
            CardModel::checkUserBankCard($cardNo,$name,$idCard);

            $user = UserModel::getUserInfo($userId);

            //获取绑定的银行卡信息
            $bindCardInfo = \App\Http\Models\Common\CoreApi\BankCardModel::getUserBindCard($userId);

            if(!empty($bindCardInfo)){

                return self::callError('您已绑定银行卡,请勿重新操作!');

            }

            //如果是正式环境,调用第三方验证身份证信息,否则按照正确处理
            if( env('APP_ENV') == 'production' ){

                //先使用融宝鉴权
                $return = CardModel::checkCardByRea($cardNo,$name,$idCard,$user['phone']);

                //融宝鉴权失败,则使用联动优势鉴权
                if(!$return){
                    CardModel::checkCardByUmp($cardNo,$name,$idCard,$user['phone']);
                }

            }

            //调用核心进行实名+绑卡操作+交易密码
            $tradingPassword = TradingPasswordModel::generatePassword($tradingPassword);

            UserModel::doVerifyTradingPassword($userId,$name,$cardNo,$bankId,$idCard, $tradingPassword);

            //记录成功日志
            $identityDb = new CardDb();

            $identityDb->addRecored($name,$idCard,$from);

            $data = $this->getCardInfo( $userId );

            Log::info(__METHOD__.'Success',$log);

        }catch (\Exception $e){

            Log::error(__METHOD__.'Error',['log'=> $log, 'error' => $e->getMessage()]);

            return self::callError($e->getMessage(), AppLogic::CODE_ERROR);
        }

        return self::callSuccess($data);

    }

    /**
     * @param $userId
     * @return array
     *
     */
    public function getCardInfo( $userId ){

        //用户信息
        $userInfo = $this->getUserInfoById($userId);

        //用户银行卡相关信息
        $userBankCardInfo = BankCardModel::getUserBindCard($userId);
        if(!empty($userBankCardInfo)){
            $userBankCardInfo['real_name'] = $userInfo['real_name'];
        }

        $data = [];

        if(!empty($userInfo)){

            $data  = $this->formatAppUserBank($userBankCardInfo);

        }

        return $data;

    }

    /**
     * 获取实名认证状态
     * @return array
     */
    public static function verifyStatus4(){
        $userInfo = SessionLogic::getTokenSession();

        if(empty($userInfo)){
            return self::callError( '未登录', AppLogic::CODE_ERROR);
        }else{
            $items = !empty($userInfo["real_name"]) ? 1 : 0;

            $msg   = ($items == 1) ? '已实名认证' : '未实名认证';

            $return = [
                'status_code' => (string)$items,
                'msg'         => $msg,
            ];

            return self::callSuccess($return, $msg);
        }
    }

    /**
     * @param $phone
     * @return array
     * @desc 根据手机号判断用户是否实名制(已实名返回'on',未实名返回'off')
     */
    public function checkIsRealName($phone){

        $userInfo = \App\Http\Models\Common\CoreApi\UserModel::getBaseUserInfo($phone);
        $userInfo['password_hash'] = !empty($userInfo['password']) ? $userInfo['password'] : '';
        $authData = UserModel::getUserAuthStatus($userInfo);

        $result['is_real'] = $authData['name_checked'] ? 'on' : 'off';
        $result['phone']   = $phone;

        return self::callSuccess($result);

    }

}
