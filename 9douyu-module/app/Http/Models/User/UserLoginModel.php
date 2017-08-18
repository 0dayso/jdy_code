<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/10
 * Time: 上午11:09
 */
namespace App\Http\Models\User;
use App\Http\Dbs\User\LoginHistoryDb;

/**
 * 注册模型
 * Class UserLoginModel
 * @package App\Http\Models\User
 */
class UserLoginModel extends UserModel{

    /**
     * @desc 添加用户登录的记录
     * @param $data
     * @return mixed
     */
    public function createUserLoginHistory($data){

        $loginHistoryDb = new LoginHistoryDb();

        return $loginHistoryDb->add($data);
    }

    /**
     * @desc 获取用户登录次数
     * @param $userId
     * @return mixed
     */
    public function getUserLoginNum($userId){

        $loginHistoryDb = new LoginHistoryDb();

        $userLoginNum  = $loginHistoryDb->getLoginNumByUserId($userId);

        return $userLoginNum;
    }

    /**
     * @desc 获取多个用户的登录次数信息
     * @param $userIds
     * @return mixed
     */
    public function getLoginNumByUserIds($userIds){

        $loginHistoryDb = new LoginHistoryDb();

        $userLoginNumArr = $loginHistoryDb->getLoginNumByUserIds($userIds);

        return $userLoginNumArr;
    }
}