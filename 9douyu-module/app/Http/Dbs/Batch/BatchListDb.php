<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/18
 * Time: 下午1:44
 * Desc: 推送列表
 */

namespace App\Http\Dbs\Batch;

use App\Http\Dbs\JdyDb;

class BatchListDb extends JdyDb
{


    protected $table = 'batch_list';

    const   TYPE_PHONE  = 'phone',  //短信
            TYPE_WX     = 'wx',     //微信
            TYPE_APP    = 'app',    //app
            TYPE_BONUS  = 'bonus',  //红包加息券

            STATUS_WAIT     = 100,  //等待审核
            STATUS_SUCCESS  = 200,  //执行成功
            STATUS_AUDIT    = 300,  //审核通过




        END = '';

    /**
     * @return array
     * @desc 获取列表
     */
    public static function getTypeArr()
    {

        return [
            self::TYPE_APP,
            self::TYPE_PHONE,
            self::TYPE_BONUS,
            self::TYPE_WX
        ];

    }

    /**
     * @param $type
     * @param $page
     * @param $size
     * @return mixed
     * @desc 通过type获取列表信息
     */
    public function getListByType($type, $page, $size)
    {

        $offset = $this->getLimitStart($page, $size);

        return $this::where('type', $type)
            ->orderBy('id', 'desc')
            ->skip($offset)
            ->take($size)
            ->get()
            ->toArray();

    }

    /**
     * @param $page
     * @param $size
     * @return mixed
     * @desc 获取列表
     */
    public function getAllList($page, $size)
    {

        $offset = $this->getLimitStart($page, $size);

        return $this::orderBy('id', 'desc')
            ->skip($offset)
            ->take($size)
            ->get()
            ->toArray();

    }

    /**
     * @param $data
     * @return bool
     * @desc 执行添加
     */
    public function doAdd($data)
    {

        $this->type     = $data['type'];
        $this->admin_id = $data['admin_id'];
        $this->file_path= $data['file_path'];
        $this->note     = $data['note'];
        $this->content  = (isset($data['content']) && !empty($data['content'])) ? $data['content'] : '';
        $this->save();
        return $this->id;

    }

    /**
     * @param $id
     * @param $status
     * @return mixed
     * @desc 更新状态
     */
    public function doUpdateStatus($id, $status)
    {

        return $this::where('id', $id)
            ->update(['status' => $status]);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 获取信息
     */
    public function getInfoById($id)
    {

        return $this::where('id', $id)
            ->first();

    }

    /**
     * @param $id
     * @return mixed
     * @desc 删除
     */
    public function doDelById($id)
    {

        return $this::where('id', $id)
            ->where('status', self::STATUS_WAIT)
            ->delete();

    }



}