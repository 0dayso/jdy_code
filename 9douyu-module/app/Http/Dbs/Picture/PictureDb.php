<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/16
 * Time: 下午4:50
 */

namespace App\Http\Dbs\Picture;


use App\Http\Dbs\JdyDb;

class PictureDb extends JdyDb
{

    protected $table = 'picture';
    /**
     * @param $id
     * @return array
     * @desc 通过id获取图片
     */
    public function getById( $id ){

        $result = self::find( $id );

        return $this -> dbToArray($result);

    }

    /**
     * @param $path
     * 通过$path 获取 id
     * @param $path
     * @return array
     */
    public function getByPath( $path ){
        $obj = self::select(['id'])->where(['path' => $path])->first();

        return $this -> dbToArray($obj);
    }

    /**
     * @param $imgPath
     * @return mixed
     * @desc 添加图片
     */
    public function add( $imgPath ){

        $this -> path = $imgPath;

        $this->save();

        return $this->id;

    }

    /**
     * @param $id
     * @param $imgPath
     * @return mixed
     * @desc 编辑图片
     */
    public function edit( $id, $imgPath){

        return self::where('id', $id) -> update(['path'=>$imgPath]);

    }

    /**
     * @return mixed
     * @desc 获取取所有图片列表
     */
    public function getAllList(){

        return self::get() -> toArray();

    }

    //通过id获取图片资源路径
    public function getPicture($id = 0) {
        $result = self::find( $id );
        $res    =  $this -> dbToArray($result);
        if(empty($res)) return false;

        return $res['path'];
    }

    //通过id获取图片资源路径
    public function getPicturePath($id = 0) {
        $result = self::find( $id );
        $res    =  $this -> dbToArray($result);
        if(empty($res)) return false;

        return '/resources/'.$res['path'];
    }

    /**
     * @desc 多个ID获取信息图片信息
     * @param array $ids
     * @return array|bool
     */
    public function getPicturePaths($ids = []){

        $res = self::whereIn('id', $ids)->get()->toArray();
        if(empty($res)) return false;

        return $res;
    }

}