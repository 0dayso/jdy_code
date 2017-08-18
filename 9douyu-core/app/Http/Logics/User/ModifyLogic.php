<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/18
 * Time: 上午10:37
 */

namespace App\Http\Logics\User;

use App\Http\Logics\Logic;

use App\Http\Models\Common\UserModel;

use Log;

/**
 * 用户变更逻辑
 *
 * Class ModifyLogic
 * @package App\Http\Logics\User
 */
class ModifyLogic extends Logic
{
    /**
     * 变更手机号
     *
     * @param int $phone
     * @param int $new_phone
     * @return array
     */
    public function modifyPhone($phone = 0, $new_phone = 0){

        try {
            UserModel::phoneLength($phone);

            UserModel::phoneLength($new_phone);

            $is = UserModel::modifyPhone($phone, $new_phone);

            Log::info(__METHOD__ . ' SUCCESS  # '. $phone . '->' . $new_phone);

        }catch (\Exception $e){
            $data['phone']       = $phone;
            $data['new_phone']   = $new_phone;
            $data['msg']         = $e->getMessage();
            $data['code']        = $e->getCode();

            Log::error(__METHOD__.'Error', $data);

            return self::callError($e->getMessage());
        }

        return self::callSuccess($is);
    }

    /**
     * @desc 锁定用户账户
     * @author lgh
     * @param $userId
     * @param $status
     * @return array
     */
    public function doModifyStatusBlock($userId, $status){

        $userModel = new UserModel();

        try{

            $res = $userModel->doModifyStatusBlock($userId, $status);

            $return = self::callSuccess($res);

        }catch(\Exception $e){
            $log = [
                'msg'   => $e->getMessage(),
                'code'  => $e->getCode(),
                'data'  => [
                    'user_id'   => $userId,
                ]
            ];

            Log::Error('doModifyStatusBlock', $log);

            $return = self::callError($log['msg']);
        }
        return $return;
    }
}