<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/13
 * Time: 上午10:42
 */
namespace App\Http\Logics\User;

use App\Http\Logics\Logic;

use App\Http\Models\Common\UserModel;

use Event;

use Log;

use App\Events\Api\User\RegisterSuccessEvent;

use App\Events\Api\User\DoActivateSuccessEvent;

/**
 * 注册逻辑
 * Class RegisterLogic
 * @package App\Http\Logics\User
 */
class RegisterLogic extends Logic
{
    /**
     * 创建用户
     * @param array $data
     * @return array
     */
    public function create($data = []){
        Log::info('user create '. json_encode($data));
        try {
            $data      = UserModel::beforeRegister($data);
            $UserModel = new UserModel();
            $userID = $UserModel->create($data);
        }catch (\Exception $e){
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }

        //创建成功
        Event::fire(new RegisterSuccessEvent(
            ['phone' => $data['phone']]
        ));
        return self::callSuccess(['id' => $userID], '创建用户成功');
    }

    /**
     * 激活用户
     * @param int $userId
     * @return array
     */
    public function doActivate($userId = 0){
        try {
            UserModel::isUserId($userId);
            $UserModel = new UserModel();
            $UserModel->doActivate($userId);
        }catch (\Exception $e){
            $data['user_id'] = $userId;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }
        //创建成功
        Event::fire(new DoActivateSuccessEvent(
            ['userId' => $userId]
        ));
        return self::callSuccess(['id' => $userId], '激活用户成功');
    }



}