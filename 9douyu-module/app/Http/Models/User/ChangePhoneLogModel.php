<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/28
 * Time: 上午11:00
 */

namespace App\Http\Models\User;


use App\Http\Dbs\User\ChangePhoneLogDb;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;

class ChangePhoneLogModel extends Model
{

    public static $codeArr = [
        'doAddChangePhoneLog'               => 90,
        'doModifyPhone'                     => 91,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_USER;

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 记录日志
     */
    public function doAdd( $data )
    {
        $db     =   new ChangePhoneLogDb();

        $return =   $db->doAdd($data);

        if( empty($return) ){
            
            throw new \Exception("记录失败", self::getFinalCode('doAddChangePhoneLog'));
        }

        return $return;
    }

    /**
     * @param $phone
     * @param $secondPhone
     * @throws \Exception
     * @desc 请求核心更换手机号码
     */
    public function doModifyPhone( $phone,$secondPhone)
    {
        $return =   UserModel::doModifyPhone($phone, $secondPhone);
        
        if( $return['status'] != true ){

            throw new \Exception("更换手机号码失败",self::getFinalCode("doModifyPhone"));
        }

        return $return;
    }

}