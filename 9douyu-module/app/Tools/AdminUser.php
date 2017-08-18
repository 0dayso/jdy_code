<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/10/17
 * Time: 下午4:17
 */

namespace App\Tools;


class AdminUser
{
    const
        LOG_PRE           = '__LOG_ADMIN__',//后台log 前缀
        LOGIN_STATUS      = 'ADMIN_LOGIN_STATUS_',//后台用户登录缓存标示
        UPDATE_PWD_STATUS = 'UPDATE_PWD_STATUS_', //后台管理员未修改密码标识
        MANAGER_LOCK_INFO = 'MANAGER_LOCK_INFO_', //后台管理员账号被锁信息
        ACTION_OUTTIME    = 'ADMIN_ACTION_TIME_',

        END     = true;
    /**
     * 获取后端登陆用户数据
     *
     * @return array
     */
    public static function getAdminLoginUser(){
        $userInfo = [];
        try {
            if (\Auth::guard('admin')->user()) {
                $user     = \Auth::guard('admin')->user();
                $userInfo = [$user->id, $user->name, $user->verify,$user->remember_token];
            }
        }catch (\Exception $e){
            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);
        }
        return $userInfo;
    }

    /**
     * 用户操作日志暂时只写入文件
     *
     * @param string $namespace
     * @param array $data
     */
    public static function userLog($namespace ='',$data = []){
        try{
            $user = self::getAdminLoginUser();
            \Log::info(self::LOG_PRE . $namespace, [$user, $data]);
        }catch (\Exception $e){
            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);
        }
    }

    /**
     * @return mixed
     * @desc 后台登录用户ID
     */
    public static function getAdminUserId(){

        $userId = 0;

        try{
            $user = self::getAdminLoginUser();
            $userId = !empty($user[0]) ? $user[0] : 0;
        }catch (\Exception $e){
            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);
        }

        return $userId;

    }
}
