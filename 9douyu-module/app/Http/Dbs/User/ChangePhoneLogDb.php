<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/28
 * Time: 上午10:45
 */

namespace App\Http\Dbs\User;


use App\Http\Dbs\JdyDb;

class ChangePhoneLogDb extends JdyDb
{

    protected $table = "change_phone_log";

    /**
     * @param $data
     * @return bool
     * @desc 添加日志
     */
    public function doAdd( $data )
    {
        $this->user_id      =   $data['user_id'];

        $this->phone        =   $data['phone'];

        $this->old_phone    =   $data['old_phone'];

        $this->comment      =   $data['comment'];

        $this->admin_id     =   $data['admin_id'];

        return $this->save();
    }
    /**
     * @param $page
     * @param $pageSize
     * @return mixed
     * @desc 返回列表
     */
    public function getList( $page, $pageSize)
    {
        $start  = $this->getLimitStart($page, $pageSize);

        $total  = $this->count('id');

        $list   = $this->orderBy('id', 'desc')
            ->skip($start)
            ->take($pageSize)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];

    }
}