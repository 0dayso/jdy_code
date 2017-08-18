<?php

namespace App\Http\Logics\User\Setting;

use App\Http\Logics\Logic;

use App\Http\Logics\User\SessionLogic;

use App\Http\Models\Common\TradingPasswordModel;

use Log;
/**
 * 用户中心 用户设置
 *
 * Class UserLogic
 * @package App\Http\Logics\User\Setting
 *
 */
class UserCheckLogic extends Logic
{
    /**
     * 验证交易密码
     *
     * @param array $data
     * @return array
     */
    public static function verifyTransactionPassword($data = []){
        try{

            TradingPasswordModel::checkPassword($data['password'], $data['trading_password']);

        }catch (\Exception $e){
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            $data['data']    = $data;

            Log::error(__METHOD__ . 'Error', $data);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();

    }


    /**
     * 验证重置手机号 token
     * @param $token
     * @param array $user
     * @return bool
     */
    public static function verifyTransactionPasswordToken($token, $user = []){
        $userToken = md5($user['id'] . $user['trading_password'] . $user['phone']);
        if($userToken != base64_decode($token)){
            return false;
        }
        return true;
    }
}