<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:56
 */

namespace App\Http\Logics;

use App\Http\Models\User\UserModel;
use App\Tools\AdminUser;

class Logic extends BaseLogic
{
    /**
     * @todo 不同的业务场景，可以定义不同code
     */
    const
        CODE_SUCCESS        = 200,  //成功
        CODE_ERROR          = 500;  //失败


    /**
     * @param array $data
     * @param string $msg
     * @return array
     * @desc 统一返回成功数据
     */
    public static function callSuccess($data = [], $msg = '成功')
    {

        return [
            'status'    => true,
            'code'      => self::CODE_SUCCESS,
            'msg'       => $msg,
            'data'      => empty($data) ? '' : $data
        ];

    }

    /**
     * @param string $msg
     * @param int $code
     * @param array $data
     * @return array
     * @desc 统一返回失败数据
     */
    public static function callError($msg = '', $code = self::CODE_ERROR, $data = [])
    {

        return [
            'status'    => false,
            'code'      => $code,
            'msg'       => $msg,
            'data'      => empty($data) ? '' : $data
        ];

    }


    /**
     * 获取用户信息
     * @param $userId
     * @return array
     */
    public function getUser($userId){

        if(empty($userId)){
            return '';
        }

        return UserModel::getCoreApiUserInfo($userId);
        
    }

    /**
     * @return mixed
     */
    public function getAdminUserId(){

        $userId = AdminUser::getAdminUserId();

        return empty($userId)?1:$userId;

    }
}