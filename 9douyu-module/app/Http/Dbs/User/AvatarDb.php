<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/20
 * Time: 上午11:11
 */

namespace App\Http\Dbs\User;

use App\Http\Dbs\JdyDb;
/**
 * 头像 表
 * Class AvatarDb
 * @package App\Http\Dbs
 */
class AvatarDb extends JdyDb
{

    protected $table = 'avatar';

    public $timestamps = false;

    /**
     * 获取用户头像信息
     *
     * @param int $userId
     * @return mixed
     */
    public static function getUserAvatarByUserId($userId = 0){
        $obj = static::select(['id', 'user_id', 'avatar_url', 'status', 'app_request', 'version', 'created_at', 'updated_at'])
            ->where(['user_id' => $userId])
            ->orderBy('updated_at','desc')
            ->first();
        return is_null($obj) ? $obj : $obj->toArray();
    }

    /**
     * 头像上传成功添加信息
     *
     * @param array $param
     * @return mixed
     */
    public function add($param){
        $this -> user_id     = $param['user_id'];
        $this -> avatar_url  = $param['avatar_url'];
        $this -> app_request = $param['app_request'];
        $this -> status      = $param['status'];
        $this -> version     = $param['version'];
        $this -> save();
        return $this->id;
    }
}