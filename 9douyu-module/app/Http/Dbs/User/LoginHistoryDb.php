<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/10/18
 * Time: 19:20
 */

namespace App\Http\Dbs\User;

use App\Http\Dbs\JdyDb;

class LoginHistoryDb extends JdyDb{

    protected $table = 'login_history';

    /**
     * @desc 添加登录成功的历史记录
     * @param $data
     * @return mixed
     */
    public function add($data){

        return $this->insert($data);
    }

    /**
     * @desc  通过用户id获取用户登录的次数
     * @param $userId
     * @return mixed
     */
    public function getLoginNumByUserId($userId){

        return self::where('user_id', $userId)->count();
    }

    /**
     * @desc 获取多个用户的登录次数信息
     * @param $userIds
     * @return mixed
     */
    public function getLoginNumByUserIds($userIds){

        return self::select('user_id', \DB::raw('count(id) as num'))
            ->whereIn('user_id',$userIds)
            ->groupBy('user_id')
            ->get()
            ->toArray();
    }

    /**
     * @desc  通过用户id获取用户登录的列表
     * @param $userId
     * @return mixed
     */
    public function getLoginListByUserId($userId,$pageSize){

        return self::where('user_id',$userId)
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();

    }
}