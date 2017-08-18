<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/13
 * Time: 上午11:02
 */

namespace App\Http\Models\Common;

use App\Http\Dbs\UserDb;
use App\Http\Models\Model;

use App\Http\Dbs\UserRegisterDb;
use App\Lang\LangModel;
use Log;
/**
 * 注册模型
 * Class User
 * @package App\Http\Models
 */
class UserModel extends Model
{

    public static $codeArr = [
        'checkUserBalance'                => 1,

        'phoneLength'                     => 2,
        'validPasswordLength'             => 3,
        'CheckUserStatus'                 => 4,
        'CheckUserActive'                 => 5,
        'isUserId'                        => 6,

        'create'                          => 7,
        'doActivate'                      => 8,
        'getBaseUserInfo'                 => 9,
        'checkUserExitsByUserId'          => 10,
        'checkUserVerify'                 => 11,
        'getUserInfo'                     => 12,
        'doModifyPassword'                => 13,
        'doModifyTradingPassword'         => 14,
        'modifyPhone'                     => 15,
        'getRegisterDbByPhone'            => 16,
        'checkTradePassword'              => 17,

        'userFrozen'                      => 18,
        'userUnFrozen'                    => 19,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_COMMON_USER;


    /**
     * 创建用户
     * @param $data
     * @throws \Exception
     */
    public function create($data){
         $user = UserRegisterDb::create($data);
        if(!$user) {
            throw new \Exception(LangModel::getLang('ERROR_USER_CREATE'), self::getFinalCode('create'));
        }else{
            return $user->id;
        }
    }


    /**
     * 激活用户失败
     * @param $userId
     * @throws \Exception
     */
    public function doActivate($userId){
        $userId = (int)$userId;
        $res    = UserRegisterDb::doActivate($userId);
        if(!$res)
            throw new \Exception(LangModel::getLang('ERROR_USER_DO_ACTIVE'), self::getFinalCode('doActivate'));

    }

    /**
     * @param $userId
     * @param $cash
     * @return bool
     * @throws \Exception
     * @desc 检测用户金额是否可以扣款
     */
    public function checkUserBalance($userId, $cash)
    {

        $user = $this->getObj($userId);

        if( abs($cash) > $user['balance'] ){

            throw new \Exception(LangModel::getLang('ERROR_USER_BALANCE'), self::getFinalCode('checkUserBalance'));

        }

        return true;

    }

    /**
     * @param $userId
     * @return bool
     * @throws \Exception
     * @desc 通过用户id检测用户是否存在
     */
    public function checkUserExitsByUserId($userId){
        $user = $this->getObj($userId);
        if( empty($user) ){
            throw new \Exception(LangModel::getLang('ERROR_USER_NOT_EXIST'), self::getFinalCode('getBaseUserInfo'));
        }
        return true;
    }

    /**
     * 通过手机号获取一条用户记录
     * @param $phone
     * @return mixed null || ['phone','password','status']
     * @throws \Exception
     */
    public static function getRegisterDbByPhone($phone = null){
        $recordObj = UserRegisterDb::getBaseUserInfoByPhone($phone);
        if(is_object($recordObj))
            return $recordObj->getAttributes();

        throw new \Exception(LangModel::getLang('ERROR_USER_NOT_EXIST'), self::getFinalCode('getRegisterDbByPhone'));
    }

    /**
     * 通过手机号获取一条用户记录 不抛出异常
     * @param $phone
     * @return mixed null ||[]
     * @throws \Exception
     */
    public static function getUserByPhone($phone = null){
        $recordObj = UserRegisterDb::getBaseUserInfoByPhone($phone);
        if(is_object($recordObj))
            return $recordObj->getAttributes();

        return null;
    }

    /**
     * @param $phone
     * @return mixed
     * @throws \Exception
     */
    public static function getBaseUserInfo($phone = null){
        return self::getRegisterDbByPhone($phone);
    }

    /**
     * @param $user_id
     * @return array
     */
    public static function getUserInfo($user_id){
        $userDb = new UserDb();
        $userInfo = $userDb->getInfoById($user_id);
        if(is_object($userInfo)){
            return $userInfo->toArray();
        }
        throw new \Exception(LangModel::getLang('ERROR_USER_NOT_EXIST'),self::getFinalCode('getUserInfo'));
    }

    /**
     * 是否合法手机号
     * @param string $value
     * @return bool
     */
    public static function isPhone($value = ''){
        $pattern        = '/^(13\d|14[57]|15[012356789]|18\d|17[01678])\d{8}$/';
        if(!preg_match($pattern, $value)) {
            return false;
        }
        return true;
    }

    /**
     * 长度手机限制
     * @param string $phone
     * @return bool
     * @throws \Exception
     */
    public static function phoneLength($phone = '', $phoneLength =11){
        $length = strlen(trim($phone));
         if($length != $phoneLength){
             throw new \Exception(LangModel::getLang('ERROR_USER_PHONE_LENGTH'), self::getFinalCode('phoneLength'));
         }
        return true;
    }

    /**
     * 是否注册成功用户
     * @param int $status
     * @return bool
     * @throws \Exception
     */
    public static function isActive($status = 0){
        if($status === UserRegisterDb::STATUS_ACTIVE){
            throw new \Exception(LangModel::getLang('ERROR_USER_PHONE_REPEAT'), self::getFinalCode('CheckUserStatus'));
        }
        return true;
    }

    /**
     * @param int $status
     * @return bool
     * @throws \Exception
     * @desc 是否为冻结用户
     */
    public static function isFrozen($status = 0){
        if($status != UserRegisterDb::STATUS_FROZEN){
            throw new \Exception(LangModel::getLang('ERROR_USER_STATUS_FROZEN'), self::getFinalCode('CheckUserStatus'));
        }
        return true;
    }


    /**
     * 是否注册未激活用户
     * @param int $status
     * @return bool
     * @throws \Exception
     */
    public static function isInActive($status = 0){
        if($status === UserRegisterDb::STATUS_INACTIVE){
            throw new \Exception(LangModel::getLang('ERROR_USER_PHONE_ACTIVE'), self::getFinalCode('CheckUserActive'));
        }
        return true;
    }
    /**
     * 密码长度验证
     * @param null $password
     * @param int $min 最小
     * @Param int $max 最大
     * @return bool
     * @throws \Exception
     */
    public static function validPasswordLength($password = null, $max=65, $min=32){
        $length = strlen(trim($password));
        if($length<$min || $length>$max){
            throw new \Exception(LangModel::getLang('ERROR_USER_PASSWORD_LENGTH'), self::getFinalCode('validPasswordLength'));
        }
        return true;
    }

    /**
     * 用户 ID
     * @param int $userId
     * @return bool
     * @throws \Exception
     */
    public static function isUserId($userId = 0){
        if((string)$userId !== (string)((int)$userId)) {
            throw new \Exception(LangModel::getLang('ERROR_INVALID_USER_ID'), self::getFinalCode('isUserId'));
        }
        return true;
    }

    /**
     * 创建账号前检测
     * @param array $data ['phone','password','confirmPassword']
     * @return bool
     * @throws \Exception
     */
    public static function beforeRegister($data=[]){

        $userModel = new UserModel();

        //手机长度限制
        UserModel::phoneLength($data['phone']);

        // 密码最长65位 最短32位
        UserModel::validPasswordLength($data['password_hash']);

        // 状态合法性
        $record = UserModel::getUserByPhone($data['phone']);
        if(!empty($record)){
            UserModel::isActive($record['status']);
            UserModel::isInActive($record['status']);
        }

        //如果传入 实名信息 则检测实名信息
        if(!empty($data['real_name']) && !empty($data['identity_card'])) {

            ValidateModel::isName($data['real_name']);

            ValidateModel::isIdCard($data['identity_card']);
            //判断用户是否已实名过
            if(!empty($record))
                $userModel->checkUserVerify($record['id']);
            //判断身份证是否已实名
            $userModel->checkIdCardUnique($data['identity_card']);
        }else{
            unset($data['real_name'], $data['identity_card']);
        }

        return $data;
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户对象
     */
    public function getObj($userId)
    {

        $db = new UserDb();

        return $db->getObj($userId);

    }


    /**
     * @param $userId
     * @$isThrow bool true
     * 判断用户是否已实名过
     */
    public function checkUserVerify($userId, $isThrow = true){

        $user = self::getUserInfo($userId);
        
        if($user['real_name'] !== '' && $user['identity_card'] !== ''){
            if($isThrow) {
                throw new \Exception(LangModel::getLang('ERROR_USER_VERIFIED'), self::getFinalCode('checkUserVerify'));
            }else{
                return true;
            }
        }

        if(!$isThrow) {
            return false;
        }
    }

    /**
     * @param $userId
     * @param $password
     * @throws \Exception
     * @desc 根据用户id更新密码
     */
    public function doModifyPassword($userId, $password)
    {

        $userDb = new UserDb();

        $res = $userDb->doModifyPassword($userId, $password);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_COMMON'), self::getFinalCode('doModifyPassword'));

        }

        return $res;

    }

    /**
     * @param $userId
     * @param $password
     * @throws \Exception
     * @desc 根据用户id更新交易
     */
    public function doModifyTradingPassword($userId, $tradingPassword)
    {

        $userDb = new UserDb();

        $res = $userDb->doModifyTradingPassword($userId, $tradingPassword);

        if (!$res) {

            throw new \Exception(LangModel::getLang('ERROR_COMMON'), self::getFinalCode('doModifyTradingPassword'));

        }

        return $res;
    }

    /**
     * 变更手机号
     *
     * @param int $phone 老手机号
     * @param int $new_phone  新手机号
     * @return mixed
     * @throws \Exception
     */
    public static function modifyPhone($phone = 0, $new_phone = 0){
        $record = UserModel::getUserByPhone($new_phone);
        if($record){
            throw new \Exception(LangModel::getLang('ERROR_USER_UPDATE_PHONE_EXIST'), self::getFinalCode('modifyPhone'));
        }
        $is = UserDb::where('phone', '=', $phone)->limit(1)->update(['phone' => $new_phone]);

        if(!$is) {
            throw new \Exception(LangModel::getLang('ERROR_USER_UPDATE_PHONE'), self::getFinalCode('modifyPhone'));
        }else{
            return $is;
        }
    }

    /**
     * @param $idCard
     * @throws \Exception
     * 身份证已被实名
     */
    public function checkIdCardUnique($idCard){

        $db = new UserDb();
        $result = $db->getByIdCard($idCard);

        if($result){
            
            throw new \Exception(LangModel::getLang('ERROR_ID_CARD_VERIFIED'), self::getFinalCode('checkIdCardUnique'));

        }
    }

    /**
     * @param $userId
     * @param $tradePassword
     * @return bool
     * @throws \Exception
     * @desc 验证交易密码
     */
    public function checkTradePassword($userId, $tradePassword){

        $db = new UserDb();

        $info = $db->getObj($userId);

        if(empty($info)){

            throw new \Exception(LangModel::getLang('ERROR_USER_NOT_EXIST'), self::getFinalCode('checkTradePassword'));

        }

        if(empty($info->trading_password)){

            throw new \Exception(LangModel::getLang('ERROR_USER_TRADING_PASSWORD_NO_SET'), self::getFinalCode('checkTradePassword'));

        }

        if($info->trading_password != $tradePassword){

            throw new \Exception(LangModel::getLang('ERROR_USER_TRADING_PASSWORD'), self::getFinalCode('checkTradePassword'));

        }

        return true;

    }

    /**
     * @desc 锁定|解锁用户账户
     * @author lgh
     * @param $userId
     * @param $status
     * @return mixed
     * @throws \Exception
     */
    public function doModifyStatusBlock($userId, $status){

        $userDb = new UserDb();

        $res = $userDb->modifyStatusBlock($userId, $status);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_COMMON'), self::getFinalCode('doModifyPassword'));

        }

        return $userId;

    }

    /**
     * @desc 获取当日生日的用户
     * @return mixed
     * @throws \Exception
     */
    public function getBirthdayUser(){
        $userDb = new UserDb();

        $res = $userDb->getBirthdayUser();

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_COMMON'), self::getFinalCode('getBirthdayUser'));

        }
        return $res;
    }

    /**
     * @desc 通过多个身份证号获取用户数据
     * @param $idCards
     * @return mixed
     * @throws \Exception
     */
    public function getUserByIdCards($idCards){

        $userDb = new UserDb();

        $res = $userDb->getUserByIdCards($idCards);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_COMMON'), self::getFinalCode('getBirthdayUser'));

        }
        return $res;

    }

    /**
     * @desc 通过多个手机号获取用户数据
     * @param $phones
     * @return mixed
     * @throws \Exception
     */
    public function getUserByPhones($phones){

        $userDb = new UserDb();

        $res = $userDb->getByPhones($phones);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_COMMON'), self::getFinalCode('getBirthdayUser'));

        }
        return $res;

    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     * @desc 实网冻结
     */
    public function userFrozen( $userId ){

        $db = new UserDb();

        $res = $db->userFrozen($userId);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_USER_FROZEN'), self::getFinalCode('userFrozen'));

        }

        return $res;

    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     * @desc 实网解冻
     */
    public function userUnFrozen( $userId ){

        $db = new UserDb();

        $res = $db->userUnFrozen($userId);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_USER_UNFROZEN'), self::getFinalCode('userUnFrozen'));

        }

        return $res;

    }


}