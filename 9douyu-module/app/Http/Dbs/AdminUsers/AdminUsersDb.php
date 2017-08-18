<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/29
 * Time: 下午2:10
 */

namespace App\Http\Dbs\AdminUsers;

use App\Http\Dbs\JdyDb;

class AdminUsersDb extends JdyDb
{
    protected $table='admin_users';

    const   LOCK_KEY = 'LOCK_KEY_';

    /**
     * @param $id
     * @return array
     * @desc 通过Id获取管理员信息
     */
    public function getUserInfoById($id){

        $obj =  $this->where('id',$id)
            ->first();

        return $this->dbToArray($obj);

    }

    /**
     * @param $email
     * @return array
     * @desc 通过email获取管理员信息
     */
    public function getUserInfoByEmail($email){

        $obj =  $this->where('email',$email)
            ->first();

        return $this->dbToArray($obj);

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 通过Id修改管理员信息
     */
    public function edit($id, $data){

        return $this->where('id', $id)->update($data);

    }

    /**
     * @desc    获取最大工号
     **/
    public function getMaxVerify(){
        return  $this->max("verify");
    }

}