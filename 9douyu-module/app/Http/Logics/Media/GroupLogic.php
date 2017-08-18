<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/30
 * Time: 14:56
 */

namespace App\Http\Logics\Media;

use App\Http\Dbs\Media\ChannelDb;
use App\Http\Dbs\Media\GroupDb;
use App\Http\Logics\Logic;

class GroupLogic extends Logic{

    /**
     * 获取自媒体列表页
     */
    public function getList(){
        
        $db = new GroupDb();

        $list = $db->getList();

        return $list;
    }


    /**
     * @param $id
     * 根据id获取分组数据
     */
    public function getById($id){

        $db = new GroupDb();
        
        return $db->getById($id);
    }

    /**
     * @param $id
     * @param $name
     * @param $desc
     * 保存编辑
     */
    public function doEdit($id,$name,$desc){

        $data = [
            'name'  => $name,
            'desc'  => $desc
        ];

        $db = new GroupDb();

        $result = $db->doEdit($id,$data);
        
        if($result){

            return self::callSuccess();
        }else{

            return self::callError('编辑分组失败');
        }
    }

    /**
     * @param $name
     * @param $desc
     * @return array
     * 添加分组
     */
    public function create($name,$desc){

        $data = [
            'name'  => $name,
            'desc'  => $desc
        ];

        $db = new GroupDb();

        $result = $db->addRecord($data);

        if($result){

            return self::callSuccess();
        }else{

            return self::callError('添加分组失败');
        }
    }


    /**
     * @param $id
     * @return array
     * 删除指定分组
     */
    public function delete($id){
        
        $db = new ChannelDb();

        $num = $db->getNumByGroupId($id);

        if($num > 0){

            return self::callError('该分组下存在渠道,不能删除');
        }else{

            $db = new GroupDb();

            $result = $db->deleteRecord($id);
            if($result){

                return self::callSuccess();
            }else{

                return self::callError('删除失败');
            }
        }
    }
}