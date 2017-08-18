<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/30
 * Time: 16:53
 */
namespace App\Http\Logics\User;

use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Common\PasswordModel;
use App\Http\Models\Common\TradingPasswordModel;
use App\Http\Models\Common\ValidateModel;
use App\Lang\AppLang;
use App\Lang\LangModel;
use Cache;

class PasswordLogic extends Logic{


    /**
     * @param $password
     * 验证用户交易密码是否正确
     */
    public function checkTradingPassword($password,$userId){

        try{

            //判断交易密码格式
            PasswordModel::validationPassword($password);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);

            $tradingPassword = $userInfo['trading_password'];

            //未设置过交易密码,应该设置当前密码为交易密码
            if(empty($tradingPassword) || $userInfo['password_hash'] === $tradingPassword){

                return self::callError(AppLang::APP_SET_TRAD_PASSWORD);

            }

            //判断交易密码是否正确
            TradingPasswordModel::checkPassword($password,$tradingPassword);

            return self::callSuccess([]);

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

    }

    /**
     * @param $password
     * @param $userId
     * @return array
     * @desc 由于App需要根据不一样的状态码,选择跳转的链接不一样,所以这里需要做版本兼容,返回4009
     * @author  gyl
     */
    public function checkTradingPasswordForApp($password,$userId){

        try{

            //判断交易密码格式
            PasswordModel::validationPassword($password);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);

            $tradingPassword = $userInfo['trading_password'];

            if(empty($tradingPassword) || $userInfo['password_hash'] === $tradingPassword){

                return $this->setTradingPassword($password, $userId);

            }

            //判断交易密码是否正确
            TradingPasswordModel::checkPassword($password,$tradingPassword);

            return self::callSuccess([]);

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }

    }

    /**
     * @param $password
     * @param $userId
     * @return array
     * 设置交易密码
     */
    public function setTradingPassword($password,$userId){

        try{

            //判断交易密码格式
            PasswordModel::validationPassword($password);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);


            $dbTradingPassword      = $userInfo['trading_password'];    //用户当前的交易密码

            $dbPassword             = $userInfo['password_hash'];       //用户当前的登录密码

            //已经设置过交易密码,不能重复设置
            if(!empty($dbTradingPassword) && $dbTradingPassword !== $dbPassword){

                return self::callError(LangModel::getLang('ERROR_USER_TRADING_PASSWORD_SET'));

            }

            $result = $this->tradingPasswordHandle($password,$dbTradingPassword,$dbPassword,$userId, false);

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
     * @param $password
     * @param $userId
     * @return array
     * 验证登录密码
     */
    public function checkPassword($password,$userId){

        try{

            //验证格式是否正确
            PasswordModel::validationPassword($password);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);

            PasswordModel::validatePassword($password,$userInfo['password_hash'],true);

            return self::callSuccess([]);

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }
    }




    /**
     * @param $password
     * @param $userId
     * @return array
     * 修改登录密码
     * 1.验证新的登录密码格式
     * 2.判断交易密码是否为空,若为空不对比新的登录密码是否与交易密码相同,
     *   若交易密码不为空,判断新的登录密码是否与交易密码相同
     * 3.判断新旧登录密码是否相同
     * 4.加密并更新登录密码
     */
    public function changePassword($password,$userId){

        try{

            //1.验证新的登录密码格式
            PasswordModel::validationPassword($password);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);

            $result = $this->passwordHandle($userInfo,$password,$userInfo['password_hash']);

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
     * @param $newPassword
     * @param $oldPassword
     * @param $userId
     * @return array
     * 修改交易密码
     * 1.验证新旧的交易密码格式是否正确
     * 2.判断交易密码是否为空
     * 3.判断旧的交易密码是否正确
     * 4.判断新旧交易密码是否相同
     * 5.判断新交易密码与登录密码是否相同
     * 6.修改交易密码
     */
    public function changeTradingPassword($newPassword,$oldPassword,$userId){

        try{

            //1.验证新老密码格式
            PasswordModel::validationPassword($newPassword);
            PasswordModel::validationPassword($oldPassword);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);

            $dbTradingPassword      = $userInfo['trading_password'];    //用户当前的交易密码
            $dbPassword             = $userInfo['password_hash'];       //用户当前的登录密码

            //2.判断交易密码是否为空
            if(empty($dbTradingPassword)){

                return self::callError(LangModel::getLang('ERROR_USER_TRADING_PASSWORD_EMPTY'));
            }

            //3.判断老的交易密码是否正确
            TradingPasswordModel::checkPassword($oldPassword,$dbTradingPassword);

            $result = $this->tradingPasswordHandle($newPassword,$dbTradingPassword,$dbPassword,$userId);

            if($result['status']){

                return self::callSuccess([], '修改成功');
            }else{

                return self::callError($result['msg']);
            }

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }
    }

    /**
     * @param $userInfo
     * @param $dbPassword
     * @return array
     * @throws \Exception
     * 设置新的登录密码公共方法
     */
    private function passwordHandle($userInfo,$newPassword,$dbPassword){

        //2.未设置过交易密码
        if(!empty($userInfo['trading_password'])){

            $result = PasswordModel::validatePassword($newPassword,$userInfo['trading_password']);

            if($result){
                return self::callError(LangModel::getLang('ERROR_USER_TRADING_PASSWORD_IS_SAME_AS_PASSWORD'));
            }
        }

        //3.判断新旧密码是否相同
        $result = PasswordModel::validatePassword($newPassword,$dbPassword);

        if($result){
            return self::callError(LangModel::getLang('ERROR_USER_PASSWORD_IS_SAME'));
        }

        //4.加密并更新
        $newPassword = PasswordModel::encryptionPassword($newPassword);

        UserModel::doPassword($userInfo['id'],$newPassword);

        return self::callSuccess([]);

    }


    /**
     * @param $newPassword
     * @param $dbTradingPassword
     * @param $dbPassword
     * @param $userId
     * @param $isModify
     * @return array
     * @throws \Exception
     * 修改交易密码公共处理方法
     */
    private function tradingPasswordHandle($newPassword,$dbTradingPassword,$dbPassword,$userId, $isModify = true){

        //3.判断新交易密码与登录密码是否相同
        $result = PasswordModel::validatePassword($newPassword,$dbPassword);

        if($result){
            return self::callError(LangModel::getLang('ERROR_USER_TRADING_PASSWORD_IS_SAME_AS_PASSWORD'));
        }
        //设置交易密码 不验证新旧交易密码是否相同【$dbTradingPassword 为空 报错 undefined offset 1】
        if($isModify) {
            //5.判断新的交易密码与原交易密码是否相同,若相同则提示错误
            $result = TradingPasswordModel::checkPassword($newPassword, $dbTradingPassword, false);

            if ($result) {
                return self::callError(LangModel::getLang('ERROR_USER_TRADING_PASSWORD_IS_SAME'));
            }
        }

        //6.交易密码加密
        $newPassword = TradingPasswordModel::generatePassword($newPassword);

        UserModel::doPassword($userId,$newPassword,'tradingPassword');

        return self::callSuccess([]);
    }



    /**
     * @param $newPassword
     * @param $userId
     * @param $repeatPwd
     * @return array
     * 修改交易密码
     * 1.验证新的交易密码格式是否正确
     * 2.判断交易密码是否为空
     * 3.判断新旧交易密码是否相同
     * 5.判断新交易密码与登录密码是否相同
     * 6.修改交易密码
     */
    public function modifyTradingPassword($newPassword,$userId,$repeatPwd=null){

        try{

            //检查两次密码是否一致
            if(!is_null($repeatPwd)){
                PasswordModel::validatePasswordIsSame($newPassword,$repeatPwd,true);
            }

            //1.验证新老密码格式
            PasswordModel::validationPassword($newPassword);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);

            $dbTradingPassword      = $userInfo['trading_password'];    //用户当前的交易密码
            $dbPassword             = $userInfo['password_hash'];       //用户当前的登录密码

            //2.判断交易密码是否为空
            //if(empty($dbTradingPassword)){

            //    return self::callError(LangModel::getLang('ERROR_USER_TRADING_PASSWORD_EMPTY'));
            //}

            $result = $this->tradingPasswordHandle($newPassword,$dbTradingPassword,$dbPassword,$userId,false);

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
     * 判断是否设置过交易密码
     */
    public function checkIsSetTradingPassword($userId){

        try{

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);

            $tradingPassword = $userInfo['trading_password'];
            $password        = $userInfo['password_hash'];

            //判断交易密码是否为空,或交易密码等于登录密码,表示未设置交易密码
            if(empty($tradingPassword) || $tradingPassword === $password){

                return self::callError('未设置过交易密码');

            }else{

                return self::callSuccess([]);
            }

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

    }


    /**
     * @param $phone
     * @param $password
     * @param $repeatPwd
     * @return array
     * 登录页面-忘记登录密码-设置新的登录密码
     */
    public function resetPassword($phone,$password,$repeatPwd=null){

        try{

            ValidateModel::isPhone($phone);

            PasswordModel::validationPassword($password);

            //检查两次密码是否一致
            if(!is_null($repeatPwd)){
                PasswordModel::validatePasswordIsSame($password,$repeatPwd,true);
            }

            $userInfo = UserModel::getBaseUserInfo($phone);

            if(empty($userInfo)){

                return self::callError(LangModel::getLang('ERROR_USER_NOT_EXIST'));
            }

            $result = $this->passwordHandle($userInfo,$password,$userInfo['password']);

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
     * @param $oldPassword
     * @param $newPassword
     * @param $repeatPwd
     * @return array
     * 个人信息-修改登录密码
     */
    public function updatePasswordV4($userId,$oldPassword,$newPassword,$repeatPwd){

        try{

            //验证新密码和重复密码是否一致
            PasswordModel::validatePasswordIsSame($newPassword,$repeatPwd,true);

            //验证新的登录密码格式
            PasswordModel::validationPassword($newPassword);

            //验证旧密码格式是否正确
            PasswordModel::validationPassword($oldPassword);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);

            //验证旧密码是否正确
            PasswordModel::validatePassword($oldPassword,$userInfo['password_hash'],true);

            //判断新密码与交易密码关系并执行修改
            $result = $this->passwordHandle($userInfo,$newPassword,$userInfo['password_hash']);

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
     * @param $oldPassword
     * @param $newPassword
     * @param $repeatPwd
     * @return array
     * 个人信息-修改交易密码
     */
    public function updateTradingPasswordV4($userId,$oldPassword,$newPassword,$repeatPwd){

        try{

            //验证新密码和重复密码是否一致
            PasswordModel::validatePasswordIsSame($newPassword,$repeatPwd,true);

            //验证新的交易密码格式
            PasswordModel::validationPassword($newPassword);

            //验证旧交易密码格式是否正确
            PasswordModel::validationPassword($oldPassword);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);
            $dbTradingPassword      = $userInfo['trading_password'];    //用户当前的交易密码
            $dbPassword             = $userInfo['password_hash'];       //用户当前的登录密码

            //验证旧交易密码是否正确
            TradingPasswordModel::checkPassword($oldPassword,$dbTradingPassword);

            $result = $this->tradingPasswordHandle($newPassword,$dbTradingPassword,$dbPassword,$userId);

            if($result['status']){

                return self::callSuccess([], '修改成功');
            }else{

                return self::callError($result['msg']);
            }

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }

    }

}

