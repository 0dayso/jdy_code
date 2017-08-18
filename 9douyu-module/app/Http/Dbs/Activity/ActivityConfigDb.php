<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/1/19
 * Time: 下午3:04
 */

namespace App\Http\Dbs\Activity;


use App\Http\Dbs\JdyDb;

class ActivityConfigDb extends JdyDb
{
    protected $table = "activity_config";

    const
        STATUS_OPEN 	= 1,
        STATUS_CLOSE 	= 0,
        CONFIG_CACHE_PREFIX =   'ACTIVITY_',

        END = true;
    /**
     * @param $data
     * @return mixed
     * @desc 添加配置
     */
    public function add( $data ){

        $this->name     = $data['name'];

        $this->key      = $data['key'];

        $this->value    = $data['value'];

        $this->admin_id = $data['admin_id'];

        $this->status   = $data['status'];

        $this->second_desc = $data['second_desc'];

        $this->save();

        return $this->id;

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 编辑配置
     */
    public function edit( $id, $data){

        return self::where('id', $id)
            ->update([
                'name'          => $data['name'],
                'key'           => $data['key'],
                'value'         => $data['value'],
                'admin_id'      => $data['admin_id'],
                'status'        => $data['status'],
                'second_desc'   => $data['second_desc']
            ]);

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 通过id更新Value
     */
    public function editValueById( $id, $data){

        return self::where('id', $id)
            ->update([
                'value'         => $data['value'],
            ]);

    }
    /**
     * @desc 开启获取系统配置
     * @param $key
     * @return array
     */
    public function getConfig($key){

        return $this->dbToArray(self::where('key',$key)
                    ->where('status',self::STATUS_OPEN)
                    ->first()
                );
    }

    /**
     * @param $id
     * @return array
     * @desc 通过Id获取配置
     */
    public function getById( $id ){

        $return = self::find($id);

        return $this->dbToArray($return);

    }

    /**
     * @param $key
     * @param $id
     * @return array
     * @desc 通过Key获取配置
     */
    public function getByKey( $key, $id=0 ){

        $return = self::where('key' , $key)
                    ->where('id', '!=', $id)
                    ->first();

        return $this->dbToArray($return);

    }

    /**
     * @param $key
     * @return array
     * @desc 验证键名的唯一性（暂时取消同一级别下，同一个父级栏目）
     */
    public function getConfigByKey($key) {

        $res = $this->where('key',$key)->find();

        return $this->dbToArray($res);
    }


    /**
     * @desc 删除配置
     * @param $id
     * @return mixed
     */
    public function del($id) {

        return $this->where('id', $id)->delete();
    }

    /**
     * @param $keyWord
     * @param $page
     * @param $size
     * @return array
     * @desc 获取配置列表
     */
    public function getAllList($page, $size, $keyWord = '')
    {
        if( $keyWord ){

            return $this->getByKeyWord($keyWord, $page, $size);
        }

        return $this->getList($page, $size);

    }

    /**
     * @param $keyWord
     * @param $page
     * @param $size
     * @return array
     * @desc 通过关键字查询
     */
    private function getByKeyWord( $keyWord, $page, $size )
    {

        $start = $this->getLimitStart($page, $size);

        $total = $this->where('name', 'like', "%$keyWord%")->orWhere('key', 'like', "%$keyWord%" )->count('id');

        $list  = $this->where('name', 'like', "%$keyWord%")
                    ->orWhere('key', 'like', "%$keyWord%" )
                    ->orderBy('id', 'desc')
                    ->skip($start)
                    ->take($size)
                    ->get()
                    ->toArray();

        return [ 'total' => $total, 'list' => $list];
    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc 获取配置列表
     */
    private function getList($page, $size)
    {

        $start = $this->getLimitStart($page, $size);

        $total = $this->count('id');

        $list  = $this->orderBy('id', 'desc')
                ->skip($start)
                ->take($size)
                ->get()
                ->toArray();

        return [ 'total' => $total, 'list' => $list];

    }
}