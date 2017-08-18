<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/30
 * Time: 15:00
 * 自媒体分组DB
 */

namespace App\Http\Dbs\Media;

use App\Http\Dbs\JdyDb;

class GroupDb extends JdyDb{

    protected $table = "media_group";

    /**
     * @param $condition
     * @return mixed
     * 根据条件获取债权列表
     */
    public function getList($condition = array()){

        return self::where($condition)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->toArray();
    }


    /**
     * @param $id
     * @return mixed
     * 根据主键获取分组信息
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
     * 保存编辑的分组数据
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
     * 删除指定分组
     */
    public function deleteRecord($id){

        return self::where('id',$id)
            ->delete();
    }


    /**
     * @return mixed
     * 获取所有分组的列表
     */
    public function getAll(){

        return self::select('id','name')
            ->get()
            ->toArray();
    }
}