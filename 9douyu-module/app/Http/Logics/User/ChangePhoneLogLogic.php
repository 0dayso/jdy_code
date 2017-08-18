<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/2
 * Time: 下午4:12
 */

namespace App\Http\Logics\User;


use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\User\ChangePhoneLogModel;
use App\Http\Models\User\UserModel;
//use App\Http\Models\Common\CoreApi\UserModel as CoreUserModel;
use App\Lang\LangModel;
use App\Tools\AdminUser;

class ChangePhoneLogLogic extends Logic
{

    /**
     * @param $phone
     * @param $secondPhone
     * @return array
     * @desc  检测用户提供的用户状态
     */
//    public static function doModifyPhone($phone, $secondPhone)
//    {
//        try{
//
//
//        }catch ( \Exception $e){
//
//            return self::callError($e->getMessage());
//        }
//
//        return self::callSuccess();
//    }

    /**
     * @param $phone
     * @param $secondPhone
     * @return array
     * @desc   执行手机号码的更换并记录数据库
     */
    public function doChangePhone( $phone, $secondPhone )
    {
        $model  =   new ChangePhoneLogModel();

        try{

            self::beginTransaction();

            //验证新手机号码
            UserModel::validationPhone($phone);
            //验证新手机号码
            UserModel::validationPhone($secondPhone);

            //获取用户信息
            $return =   UserModel::getCoreApiBaseUserInfo($phone);
            //格式化数据
            $data   =   self::setChangeUserPhoneNote($return['id'],$phone, $secondPhone);

            //添加更换的记录
            $model->doAdd($data);

            //更换手机号码
            $model->doModifyPhone($phone, $secondPhone);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @param $phone
     * @param $secondPhone
     * @param $userId
     * @return array
     * @desc  拼装数据
     */
    protected static function setChangeUserPhoneNote( $userId,$phone, $secondPhone)
    {
        return [
            'user_id'   =>  isset($userId) ? $userId : 0,
            'phone'     =>  isset($phone) ? $phone : 0,
            'old_phone' =>  isset($secondPhone) ? $secondPhone : 0,
            'admin_id'  =>  AdminUser::getAdminUserId(),
            'comment'   =>  sprintf(LangModel::getLang('ERROR_CHANGE_PHONE_COMMENT'),$userId,$phone,$secondPhone),
        ];
    }
}