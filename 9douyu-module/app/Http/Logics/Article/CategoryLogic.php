<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午5:48
 */

namespace App\Http\Logics\Article;

use App\Http\Logics\Logic;
use App\Http\Models\Article\CategoryModel;

class CategoryLogic extends Logic
{

    public static function _filterParams($data){

        $data = [
            'parent_id' => (int)$data['parent_id'],
            'name'      => trim($data['name']),
            'alias'     => trim($data['alias']),
            'sort_num'  => (int)$data['sort_num'],
            'status'    => (int)$data['status'],
        ];

        return $data;

    }

    /**
     * @param $data
     * @return array
     * @desc 添加分类
     */
    public function doCreate($data){

        $data = self::_filterParams($data);

        try{

            $model = new CategoryModel();

            $result = $model->doCreate($data);

        }catch (\Exception $e){

            \Log::error(__METHOD__,[$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);

    }

    /**
     * @param $data
     * @return array
     * @desc 更新分类
     */
    public function doUpdate($data){

        $id = (int)$data['id'];

        $data = self::_filterParams($data);

        try{

            $model = new CategoryModel();

            $result = $model->doUpdate($id, $data);

        }catch (\Exception $e){

            \Log::error(__METHOD__,[$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);

    }

    /**
     * @param $id
     * @return array
     * @desc 通过id获取分类信息
     */
    public function getById( $id ){

        $model = new CategoryModel();

        $result = $model -> getById($id);

        return $result;

    }

    /**
     * @return array|mixed
     * @desc 获取分类
     */
    public function getAllList(){

        $model = new CategoryModel();

        $result = $model -> getAllList();

        return $result;

    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc 分页获取分类
     */
    public function getList($page, $size){

        $model = new CategoryModel();

        $result = $model -> getList($page, $size);

        return $result;

    }

}