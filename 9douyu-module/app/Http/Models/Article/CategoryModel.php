<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午4:53
 */

namespace App\Http\Models\Article;

use App\Http\Dbs\Article\CategoryDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class CategoryModel extends Model
{

    public static $codeArr            = [
        'doCreate' => 1,
        'doUpdate' => 2,
        'findById' => 3,
    ];



    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_CATEGORY;

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 创建分类
     */
    public function doCreate($data){

        $db = new CategoryDb();

        $result = $db -> add($data);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_CATEGORY_ADD'), self::getFinalCode('doCreate'));
        }

        return $result;

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 更新分类
     */
    public function doUpdate($id, $data){

        $db = new CategoryDb();

        $result = $db -> edit($id, $data);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_CATEGORY_EDIT'), self::getFinalCode('doUpdate'));
        }

        return $result;

    }

    /**
     * @param $id
     * @return array
     * @desc 通过id获取数据
     */
    public function getById( $id ){

        $db = new CategoryDb();

        $result = $db -> getById($id);

        if(!$result) return [];

        return $result;

    }

    /**
     * @return array|mixed
     * @desc 获取分类列表
     */
    public function getAllList(){

        $db = new CategoryDb();

        $result = $db -> getAllList();

        if(!$result) return [];

        return $result;

    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc 分页获取分类列表
     */
    public function getList($page, $size){

        $db = new CategoryDb();

        $result = $db -> getList($page, $size);

        if(!$result) return [];

        return $result;

    }

    /**
     * @param $parentId
     * @return array
     * @desc 根据父id获取子分类信息
     */
    public function getNameByPid($parentId){

        $db = new CategoryDb();

        $result = $db -> getNameByPid($parentId);

        if(!$result) return [];

        return $result;

    }

}