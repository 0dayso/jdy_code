<?php

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/10
 * Time: 下午12:15
 */
namespace App\Http\Models\Common;

use App\Http\Models\Model;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Lang\LangModel;
use Hash;

/**
 * 密码模块 密码规则
 * Class PasswordModel
 * @package App\Http\Models\Common\User
 */
class PasswordModel extends Model
{

    const
        PASSWORD_MIN_LIMIT           = 6,  // 密码最小长度

        PASSWORD_MAX_LIMIT           = 16, // 密码最大长度

        ADMIN_PASSWORD_MIN_LIMIT     = 8,  // 后台密码最小长度

        LAST_CONST                   = 0;


    public static $codeArr            = [
        'matchPassword' => 1,
        'validationPasswordMin' => 2,
        'validationPasswordMax' => 3,
        'validationPasswordRule' => 4,

    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_PASSWORD;

    private $salt1 = "^^*&%^$^%$^%HKJGd)(*)*_)*^*&%FUGHGKL987FJ_HJGGUYT";
    private $salt2 = "aa*&%^$^%$^%HKJGd)(*)*_)*kfhalsfhalsfhawoiryqw79812491231YTaa";

    /**
     * 移植九斗鱼密码生成
     * @param $password
     * @param int $saltLength
     * @return string
     */
    public static function encryptionPassword($password, $saltLength=32) {

        $saltLength = empty($saltLength) ? 32 : min($saltLength, 32);

        $salt       = substr(self::getToken32(), rand(0,5), $saltLength);

        $password   = md5($password . $salt);

        return sprintf('%s:%s', $password, $salt);
    }

    /**
     * 随机码 移植自九斗鱼
     * @return string
     */
    protected static function getToken32() {
        return md5(md5(rand(111111111, 999999999)) . time());
    }


    /**
     * 移植九斗鱼 密码匹配
     * @param $password
     * @param $dbPassword
     * @param bool|false $errorHandle
     * @param string $errorMsg
     * @return bool
     * @throws \Exception
     */
    public static function validatePassword($password, $dbPassword, $errorHandle = false, $errorMsg = '') {
        $parts              = explode(':', $dbPassword);
        if($parts[0] != md5($password . $parts[1])) {
            if($errorHandle) {
                if($errorMsg){
                    $msg = LangModel::getLang($errorMsg);
                }else {
                    $msg = '原登录密码错误';
                }
                throw new \Exception($msg, self::getFinalCode('matchPassword'));

            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * 密码加密 http://php.net/manual/zh/function.password-hash.php
     * @param $password
     * @param int $cost
     * @return bool|string
     */
    public static function encryptionPassword_bak($password, $cost = 10){
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    }


    /**
     * 验证密码 http://php.net/manual/zh/function.password-verify.php
     * @param $password
     * @param $hash
     * @return bool
     */
    public static function validatePassword_bak($password, $hash){
        // todo 兼容旧密码 || 迁移旧密码
        return password_verify($password, $hash);
    }

    /**
     * 验证密码：6-16位数字、字母和特殊字符组合（不全是字母、数字）
     * @param $password
     * @param int $min
     * @param int $max
     * @return array
     */
    public static function checkPasswordRule($password , $min=6 ,$max=16){
        $return = array(
            'status' => false,
            'msg'    => '必须'.$min.'-'.$max.'位密码',
        );

        $len = strlen($password);
        if($len >= $min && $len <= $max){
            $str = '/^[A-Za-z]+$/';
            $num = '/^\d+$/';
            $chinese = '/[\x7f-\xff]/';
            $return['status'] = true;
            preg_match($str,$password) && $return = ['status' => false , 'msg'=>'密码不能都是字母'];
            preg_match($num,$password) && $return = ['status' => false , 'msg'=>'密码不能都是数字'];
            preg_match($chinese,$password) && $return = ['status' => false , 'msg'=>'密码不能包括中文'];
        }
        return $return;
    }

    /**
     * 密码有效性验证
     * @param null $password
     * @return bool
     * @throws \Exception
     */
    public static function validationPassword($password =null){
        $passwordLength = strlen($password);
        //小于最小长度
        if($passwordLength < PasswordModel::PASSWORD_MIN_LIMIT) {
            $message = LangModel::getLang('MODEL_USER_PASSWORD_TOO_SHORT');
            $message = sprintf($message, PasswordModel::PASSWORD_MIN_LIMIT);
            \Log::Info('validationPasswordError', [$password]);
            throw new \Exception($message, self::getFinalCode('validationPasswordMin'));
        }

        //大于最大长度
        if($passwordLength > PasswordModel::PASSWORD_MAX_LIMIT) {
            $message = LangModel::getLang('MODEL_USER_PASSWORD_TOO_LONG');
            $message = sprintf($message, PasswordModel::PASSWORD_MAX_LIMIT);
            throw new \Exception($message, self::getFinalCode('validationPasswordMax'));
        }

        //不能是纯数字或者纯字母
        $check = self::checkPasswordRule($password , PasswordModel::PASSWORD_MIN_LIMIT , PasswordModel::PASSWORD_MAX_LIMIT);
        if(!$check['status']){
            $message = LangModel::getLang('MODEL_USER_PASSWORD_FORMAT_INVALID');
            throw new \Exception($message, self::getFinalCode('validationPasswordRule'));
        }
        return true;
    }

    /**
     * @param $oldPassword
     * @param $newPassword
     * @param $flag
     * @return bool
     * @throws \Exception
     * @desc 两密码比较验证，flag==fase验证不相等 flag==true验证相等
     */
    public static function validatePasswordIsSame($oldPassword,$newPassword,$flag){

        //验证不相等
        if($newPassword==$oldPassword && $flag==false){

            throw new \Exception(LangModel::getLang('MODEL_USER_PASSWORD_CONFIRM_CANT_SAME'), self::getFinalCode('validationPassword'));

        }
        //验证相等
        if($newPassword!=$oldPassword && $flag==true){

            throw new \Exception(LangModel::getLang('MODEL_USER_PASSWORD_CONFIRM_NOT_MATCH'), self::getFinalCode('validationPassword'));

        }

        return true;

    }

    /**
     * 后台密码有效性验证
     * @param null $password
     * @return bool
     * @throws \Exception
     */
    public static function validationAdminPassword($password =null){

        $match = '/^(?![0-9A-Z]+$)(?![0-9a-z]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,20}$/';

        if(!preg_match($match,$password)){
            throw new \Exception('密码格式:8~20位同时包含数字和大小写字母', self::getFinalCode('validationAdminPassword'));
        }

        return true;
    }

    /**
     * @param $password
     * @param $dbPassword
     * @return bool
     * @throws \Exception
     * @desc 验证后台登录密码是否正确(老版密码和新版密码)
     */
    public function validateAdminPassword($password,$dbPassword,$flag=false){

        //验证是否为老版密码
        $oldCheck = $this->checkOldAdminPassword($password,$dbPassword);

        //验证是否为新版密码
        if(!$oldCheck && !Hash::check($password, $dbPassword)){

            if($flag == false){
                throw new \Exception(LangModel::getLang('ERROR_EDIT_VERIFY_USER_PASSWORD'), self::getFinalCode('matchPassword'));
            }else{
                return false;
            }

        }

        return true;

    }

    /**
     * @param $password
     * @param $encryptPassword
     * @return bool
     * @desc 检测密码是否正确
     */
    public function checkOldAdminPassword($password, $encryptPassword){

        $allPasswd  = $this->encryptPassword($password);

        $passwd1    = $allPasswd["passwd1"];

        $passwd2    = $allPasswd["passwd2"];

        $passwd     = $allPasswd["passwd"];

        $encrpt1    = substr($encryptPassword, 0, 32);

        $encrpt2    = substr($encryptPassword, -32);

        if($passwd == $encryptPassword && $passwd1 == $encrpt1 && $passwd2 == $encrpt2 && $encrpt1 == hash("md5", $encrpt2 . $this->salt2)){

            return true;

        }else{

            return false;

        }

    }

    /**
     * @param $password
     * @return array
     * @desc 获取加密密码
     */
    protected function encryptPassword($password){

        $encryptPassword2 = hash("md5", $password . $this->salt1);

        $encryptPassword1 = hash("md5", $encryptPassword2 . $this->salt2);

        $encryptPassword  = $encryptPassword1 . $encryptPassword2;

        return ["passwd1" => $encryptPassword1, "passwd2" => $encryptPassword2, "passwd" => $encryptPassword];

    }

}
