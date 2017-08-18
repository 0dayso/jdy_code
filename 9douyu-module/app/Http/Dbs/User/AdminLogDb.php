<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/27
 * Time: 下午6:39
 */

namespace App\Http\Dbs\User;


use App\Http\Dbs\JdyDb;

class AdminLogDb extends JdyDb
{
    protected $table = "admin_log";

    /**
     * @param $param
     * @return mixed
     * @desc 添加操作记录
     */
    public function add( $param ){

        $this->user_id      = $param['user_id'];

        $this->url          = $param['url'];

        $this->http_referer = $param['http_referer'];

        $this->ip           = $param['ip'];

        $this->data         = $param['data'];

        $this->save();

        return $this->id;

    }


}