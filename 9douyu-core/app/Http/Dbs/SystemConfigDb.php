<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/5/10
 * Time: 下午7:07
 * Desc：系统配置
 */

namespace App\Http\Dbs;

use App\Tools\ToolArray;

class SystemConfigDb extends JdyDb
{

    protected $table = 'system_config';


    /**
     * @return mixed
     * @desc 获取列表
     */
    public function getList()
    {

        return $this->orderBy('id','desc')->get()->toArray();

    }

    /**
     * @param $id
     * @return mixed
     * @desc 通过id获取信息
     */
    public function getInfoById($id)
    {

        $res = $this->where('id', $id)->get()->toArray();

        return ToolArray::arrayToSimple($res);

    }

    /**
     * @param $key
     * @return mixed
     * @desc 通过key获取配置信息
     */
    public function getInfoByKey($key)
    {

        $res = $this->where('key', $key)->get()->toArray();

        return ToolArray::arrayToSimple($res);

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 编辑信息
     */
    public function editInfo($id, $data)
    {

        return self::where('id', $id)
            ->update([
                'name'          => $data['name'],
                'key'           => $data['key'],
                'value'         => $data['value'],
                'user_id'       => $data['user_id'],
                'status'        => $data['status'],
                'second_des'    => $data['second_des']
            ]);

    }


    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 编辑信息(通过key)
     */
    public function editByKey($key, $data)
    {

        return self::where('key', $key)
            ->update([
                'name'          => $data['name'],
                'value'         => $data['value'],
                'user_id'       => $data['user_id'],
                'status'        => $data['status'],
                'second_des'    => $data['second_des']
            ]);

    }

    /**
     * @param array $data
     * @return mixed
     * @desc 创建配置信息
     */
    public function addInfo($data=[])
    {

        $this->name = $data['name'];

        $this->key = $data['key'];

        $this->value = $data['value'];

        $this->user_id = $data['user_id'];

        $this->status = $data['status'];

        $this->second_des = $data['second_des'];

        $this->save();

        return $this->id;

    }

}