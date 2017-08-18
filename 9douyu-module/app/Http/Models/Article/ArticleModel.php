<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午4:53
 */

namespace App\Http\Models\Article;

use App\Http\Dbs\Article\ArticleDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class ArticleModel extends Model
{

    public static $codeArr            = [
        'doCreate' => 1,
        'doUpdate' => 2,
        'findById' => 3,
    ];



    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_ARTICLE;

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 创建文章
     */
    public function doCreate($data){

        $db = new ArticleDb();

        $result = $db -> add($data);
        
        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ARTICLE_ADD'), self::getFinalCode('doCreate'));
        }

        return $result;

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 更新文章
     */
    public function doUpdate($id, $data){

        $db = new ArticleDb();

        $result = $db -> edit($id, $data);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ARTICLE_EDIT'), self::getFinalCode('doUpdate'));
        }

        return $result;

    }

    /**
     * @param $id
     * @return array
     * @desc 通过id获取数据
     */
    public function getById( $id ){

        $db = new ArticleDb();

        $result = $db -> getById($id);

        if(!$result) return [];

        return $result;

    }

    /**
     * @return array|mixed
     * @desc 获取文章列表
     */
    public function getAllList(){

        $db = new ArticleDb();

        $result = $db -> getAllList();

        if(!$result) return [];

        return $result;

    }


    /**
     * @return array|mixed
     * @desc 获取文章列表[已经发布]
     */
    public function getAllPublishList(){

        $cacheKey = 'articleModel_getAllPublishList_v101';
        $minutes  = 24 * 60;

        $list     = \Cache::store('file')->get($cacheKey);
        if(!empty($list)){
            $result     = json_decode($list, true);
            if(!empty($result)){
                return $result;
            }
        }

        $db = new ArticleDb();

        $result = $db -> getAllPublishList();

        if(!$result) return [];

        \Cache::store('file')->put($cacheKey, json_encode($result), $minutes);

        return $result;

    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc 文章获取文章列表
     */
    public function getList( $page, $size ){

        $db = new ArticleDb();

        $result = $db -> getList($page, $size);

        if(!$result) return [];

        return $result;

    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * @desc 删除文章
     */
    public function doDelete( $id ){

        $db = new ArticleDb();

        $result = $db -> del($id);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ARTICLE_DEL'), self::getFinalCode('doDelete'));
        }

        return $result;

    }

    /**
     * @param $categoryIds [1,2]
     * @param  int $limit 条数 5
     * @param string $order
     * @return mixed
     * @desc 通过类别获取$limit条数据
     */
    public function getArticleList($categoryIds, $limit, $order='desc')
    {

        $db = new ArticleDb();

        $result = $db -> getArticleList($categoryIds, $limit, $order);

        return $result;

    }

    /**
     * @param $page
     * @param $size
     * @param $categoryId
     * @return array
     * @desc 分页列表数据
     */
    public function getPageList( $page, $size, $categoryId){

        $db = new ArticleDb();

        $result = $db -> getPageList($page, $size, $categoryId);

        return $result;

    }

    /**
     * @param $categoryId
     * @return array
     * @desc 分页列表数据
     */
    public function getArticleByCategory($categoryId){

        $db = new ArticleDb();

        $result = $db -> getArticleByCategory($categoryId);

        return $result;

    }

    /**
     *  @param $page
     *  @param $size
     *  @param $categoryIds
     *  @return array
     *  @desc APP4.0分页列表数据
     */
    public function getNoticePageList( $page, $size, $categoryIds)
    {
        $db = new ArticleDb();
        $result = $db -> getNoticePageList($page, $size, $categoryIds);
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * @desc 文章点击量
     */
    public function hitArticle( $id ){

        $db =  new ArticleDb();

        $result = $db -> hitArticle( $id );

        return $result;

    }

    /**
     * @param $ids
     * @return mixed
     * @desc 根据ids获取文章信息
     */
    public function getArticleByCategoryIds($ids){

        $db =  new ArticleDb();

        $result = $db -> getArticleByCategoryIds( $ids );

        return $result;

    }

}
