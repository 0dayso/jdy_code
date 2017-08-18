<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/13
 * Time: 上午11:29
 * Desc: 应用配置中心
 */

namespace App\Http\Dbs;


class SecurityAuthDb extends JdyDb
{


    protected $table = 'security_auth';

    const   STATUS_NORMAL = 200,    //正常
            STATUS_LOCKED = 500;    //关闭锁定

    /**
     * @param $appId
     * @return mixed
     * @desc 通过appid获取信息
     */
    public function getInfoByName($name)
    {

        return self::where('name',$name)->first();

    }

    /**
     * @param $id
     * @return mixed
     * @desc 通过id获取信息
     */
    public function getInfoById($id)
    {

        return $this->byId($id)->first();

    }





}