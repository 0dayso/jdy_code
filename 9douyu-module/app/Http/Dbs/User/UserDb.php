<?php
/**
 * User: caelyn
 * Date: 16/5/24
 * Time: 16:26
 * Desc: 用户db
 */

namespace App\Http\Dbs;


class UserDb extends JdyDb{

    protected $table = "users";

    /**
     * 获取用户信息
     * @param $userId
     * @return obj
     */
    public function getUser($userId){
        return self::where('id',$userId)
            ->where('status',200)
            ->first();
    }




}