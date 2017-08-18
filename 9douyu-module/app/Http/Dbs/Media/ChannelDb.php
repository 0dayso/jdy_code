<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/30
 * Time: 15:00
 * 自媒体渠道DB
 */

namespace App\Http\Dbs\Media;


use App\Http\Dbs\JdyDb;

class ChannelDb extends JdyDb{

    protected $table = "media_channel";


    public function getNumByGroupId($id){

        return self::where('group_id',$id)
            ->count();
    }


    /**
     * @param $condition
     * @return mixed
     * 根据条件获取债权列表
     */
    public function getList($condition = array()){
        $obj    =   self::select('*');

        if( isset($condition['group_id']) && !empty($condition['group_id'])){

            $obj    =  $obj->where('group_id',$condition['group_id']);
        }
        if(isset($condition['name']) && !empty($condition['name'])){
            $obj    =  $obj->where('name', 'like', "%{$condition['name']}%")
                           ->orwhere('desc','like', "%{$condition['name']}%");
        }
        unset($condition['name']);
        unset($condition['group_id']);
        return $obj->where($condition)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->toArray();
    }


    /**git
     * @param $id
     * @return mixed
     * 根据主键获取渠道信息
     */
    public function getById($id){

        $object = self::where('id',$id)
            ->first();

        return $this->dbToArray($object);
    }


    /**
     * @param $id
     * @param $data
     * @return mixed
     * 保存编辑的渠道数据
     */
    public function doEdit($id,$data){

        return self::where('id',$id)
            ->update($data);
    }


    /**
     * @param $data
     * @return mixed
     * 添加数据
     */
    public function addRecord($data){

        return self::insert($data);
    }


    /**
     * @param $id
     * @return mixed
     * 根据主键删除记录
     */
    public function deleteRecord($id){

        return self::where('id',$id)
            ->delete();
    }


    /**
     * @param $name
     * @return mixed
     * 根据渠道名称获取渠道信息
     */
    public function getByName($name){

        $object = self::where('name',$name)
            ->first();

        return $this->dbToArray($object);

    }
}