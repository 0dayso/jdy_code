<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/13
 * Time: 上午11:02
 */

namespace App\Http\Dbs;

/**
 * 用户数据
 * Class UserDb
 * @package App\Http\Dbs
 */
class UserRegisterDb extends UserDb
{

    public $timestamps = true;

    /**
     * 通过手机号获取一条用户记录
     * @param $phone
     */
    public static function getBaseUserInfoByPhone($phone = null){
        return static::select(['id','phone','password_hash as password','status_code as status', 'trading_password', 'balance', 'real_name', 'identity_card', 'note','created_at','updated_at'])->where(['phone'=>$phone])->first();
    }

    /**
     * 激活用户
     * @param int $userId
     * * @return mixed
     */
    public static function doActivate($userId = 0){
        $data      = ['status_code' => self::STATUS_ACTIVE];
        $condition = ['status_code'=>self::STATUS_INACTIVE, 'id' => $userId];
        return static::where($condition)->update($data);
    }

    /**
     * 创建用户
     * @param array $data
     * @return mixed
     */
    public static function create(array $data = []){
        $fillable = parent::setFillable('create');
        $obj      = new static($data, $fillable);
        $obj->save();
        return $obj;
    }


}