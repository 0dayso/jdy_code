<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/29
 * Time: 下午1:54
 */

namespace App\Http\Logics\AdminUsers;

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Logics\Logic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\AdminUsers\AdminUsersModel;
use App\Http\Models\Common\PasswordModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\User\UserModel;
use App\Tools\AdminUser;
use App\Tools\ToolStr;
use App\Tools\ToolTime;

class AdminUsersLogic extends Logic
{

    /**
     * @param $userId
     * @param $oldPwd
     * @param $newPwd
     * @param $secondPwd
     * @return array
     * @throws \Exception
     * @desc 修改管理员密码
     */
    public function resetPassword($userId, $oldPwd, $newPwd, $secondPwd){

        $userModel = new AdminUsersModel();

        $userInfo = $userModel->getUserInfoById($userId);

        try{
            //新密码格式验证
            PasswordModel::validationAdminPassword($newPwd);

            //旧密码匹配验证
            $model = new PasswordModel();
            $model -> validateAdminPassword($oldPwd,$userInfo['password']);

            //旧密码与新密码比较验证
            PasswordModel::validatePasswordIsSame($oldPwd,$newPwd,false);
            //新密码与确认密码比较验证
            PasswordModel::validatePasswordIsSame($newPwd,$secondPwd,true);

            //设置密码
            $md5Password = bcrypt($newPwd);
            $data = [
                'password'=>$md5Password,
                'reset_time' => ToolTime::dbNow()
            ];
            $userModel -> doUpdate($userId,$data);

            $key = AdminUser::UPDATE_PWD_STATUS.$userId;

            if(\Cache::has($key)){

                \Cache::forget($key);

            }

        }catch (\Exception $e){
            return self::callError($e->getMessage());
        }
        return self::callSuccess('修改密码成功,请重新登录!');

    }

    /**
     * @param string $email
     * @param string $verify
     * @return array
     * @desc 发送重置的默认密码
     */
    public function sendEmailPassword( $email = '' , $verify ='' )
    {
        $adminModule    =   new AdminUsersModel();

        $useModel       =   new UserModel();

        if( empty($email) || empty($verify) ) {

            return self::callError('邮箱地址和工号不可为空');
        }

        try{
            self::beginTransaction();

            //验证邮箱是否有效
            $useModel->checkEmailFormat( $email );

            $adminInfo  =   $adminModule->getUserInfoByEmail( $email );

            $adminModule->checkVerifyFormat( $verify,$adminInfo['verify'] );

            $defaultPassword    =   self::setRoundDefaultPassword($verify);

            $resetPassword      =   bcrypt( $defaultPassword );
            //设置密码
            $resetData  =   [
                'password'      =>  $resetPassword,
                'reset_time'    =>  ToolTime::dbNow(),
            ];

            $adminModule -> doUpdate( $adminInfo['id'] , $resetData );

            $adminKey   =   AdminUser::UPDATE_PWD_STATUS.$adminInfo['id'];

            if(\Cache::has($adminKey)){

                \Cache::forget($adminKey);

            }

            $formatEmail    =   [ $email   =>   $email ];

            $this->sendResetPasswordEmail( $formatEmail , self::setSendContent( $email , $defaultPassword ) );

            self::commit();

        }catch ( \Exception $e ){

            self::rollback();

            \Log::error('sendAdminEmailPasswordError', [ $email , $e->getMessage()] );

            return self::callError($e->getMessage());
        }

        \Log::info('sendAdminEmailPasswordSuccess', [ $email,  $defaultPassword ] );

        return self::callSuccess('密码重置成功,请登录邮件查询');
    }

    /**
     * @param $verify
     * @return string
     * @desc 随机生成一个8位的密码
     */
    protected function setRoundDefaultPassword( $verify ='' )
    {
        if( empty($verify) ) {

            $verify =   rand(1000,9999) ;
        }

        return $defaultPassword    = ToolStr::getRandStr(4,'upper-lower') . $verify ;
    }

    /**
     * @param $email
     * @param $content
     * @desc 发送新的密码给知道的邮箱地址
     */
    protected function sendResetPasswordEmail( $email ,$content )
    {
        $emailModel      =   new EmailModel();

        $title           =   '后台数据操作者密码重置邮件';

        return $emailModel->sendHtmlEmail( $email , $title ,$content );
    }

    /**
     * @param $email
     * @param $passWord
     * @return string
     * @desc 生成邮件内容
     */
    protected function setSendContent( $email ,  $passWord ) {

        $body   =   "<h1>【密码重置成功通知】</h1>";

        $body   .=   "<br>";

        $body   .=  '<p> 用户:' . $email. '密码成功更新为:' . '【 ' . $passWord . ' 】</p>';

        $body   .=  '<p>请打开操作管理登录界面,使用最新的密码进行登录!</p>';

        $body   .=  '<p color="red" >【Warning】登录成功后请及时修改密码,请勿将密码外泄!!</p>';

        return $body;
    }

    /**
     * @desc manager 连续登录的最大时长 单位分钟
     */
    public static function getManagerLoginStatusMaxTime()
    {
        $config     =   self::getManagerActionValidConfig();

        return isset( $config['MANAGER_LOGIN_STATUS_MAX_TIME'] ) ? $config['MANAGER_LOGIN_STATUS_MAX_TIME'] : 240 ;
    }
    /**
     * @desc 登录无任何操作，MaxTime后强制退出,单位分钟
     */
    public static function getManagerNothingActionMaxTime()
    {
        $config     =   self::getManagerActionValidConfig();

        return isset( $config['MANAGER_NOTHING_ACTION_TIME'] ) ? $config['MANAGER_NOTHING_ACTION_TIME'] : 10 ;
    }
    /**
     * @desc 获取连续登录失败的时间,单位分钟
     */
    public static function getManagerLoginErrorTime()
    {
        $config     =   self::getManagerActionValidConfig();

        return isset( $config['MANAGER_LOGIN_ERROR_TIME'] ) ? $config['MANAGER_LOGIN_ERROR_TIME'] : 30 ;
    }
    /**
     * @desc 允许manager登录错误的最大次数
     */
    public static function getManagerLoginErrorMaxLimit()
    {
        $config     =   self::getManagerActionValidConfig();

        return isset( $config['MANAGER_LOGIN_ERROR_LIMIT'] ) ? $config['MANAGER_LOGIN_ERROR_LIMIT'] : 5 ;
    }

    /**
     * @desc 后台账号被锁的时间,单位分钟
     */
    public static function getManagerLockTime()
    {
        $config     =   self::getManagerActionValidConfig();

        return  isset( $config['LOCK_MANAGER_TIME'] ) ? $config['LOCK_MANAGER_TIME'] : 120 ;
    }

    /**
     * @desc 强制重置登录密码的时间，单位天
     */
    public static function getResetPasswordTime()
    {
        $config     =   self::getManagerActionValidConfig();

        return isset( $config['FORCING_RESET_PASSWORD_TIME'] ) ? $config['FORCING_RESET_PASSWORD_TIME'] : 30 ;
    }

    /**
     * @return $config | array
     * @desc manager 操作的时效配置
     */
    protected static function getManagerActionValidConfig()
    {
        return SystemConfigLogic::getConfig( 'MANAGER_ACTION_VALID_CONFIG' );
    }

}
