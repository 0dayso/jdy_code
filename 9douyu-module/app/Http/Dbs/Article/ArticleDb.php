<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午4:33
 */
namespace App\Http\Dbs\Article;

use App\Http\Dbs\JdyDb;

class ArticleDb extends JdyDb
{

    const

        STATUS_PUBLISH_FALSE = 100, //未发布

        STATUS_PUBLISH_TRUE  = 200, //已发布

        REGISTER_AGREEMENT_ID   = 5,//注册协议文章主键ID

        END = TRUE;

    /**
     * @param $id
     * @return array
     * @desc 通过id获取文章
     */
    public function getById( $id ){

        $result = self::find( $id );

        return $this -> dbToArray($result);

    }

    /**
     * @param $data
     * @return mixed
     * @desc 添加文章
     */
    public function add( $data ){

        return \DB::table('article')->insertGetId($data);

        //return self::insert($data);

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 编辑文章
     */
    public function edit( $id, $data){

        return $this->where('id', $id)->update($data);

    }

    /**
     * @return mixed
     * @desc 获取取所有文章列表
     */
    public function getAllList(){

        return self::get() -> toArray();

    }

    /**
     * @return mixed
     * @desc 获取取所有文章列表[状态为200]
     */
    public static function getAllPublishList(){
        return self::where('status', self::STATUS_PUBLISH_TRUE)->get(['id','title','keywords','hits','publish_time']) -> toArray();
    }

    /**
     * @param $id
     * @return mixed
     * @desc 删除文章
     */
    public function del( $id ){

        return self::where('id', $id) -> delete();

    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc 分页列表数据
     */
    public function getList( $page, $size ){

        $start = $this->getLimitStart($page, $size);

        $total = $this->count('id');

        $list = $this->orderBy('id', 'desc')
            ->skip($start)
            ->take($size)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];

    }

    /**
     * @param $categoryIds [1,2]
     * @param  int $limit 条数 5
     * @param string $order
     * @return mixed
     * @desc 通过类别获取$limit条数据
     */
    public function getArticleList($categoryIds, $limit=5, $order='desc'){

        return $this->whereIn('category_id',$categoryIds)
            ->where('status', self::STATUS_PUBLISH_TRUE)
            ->select('title','id','type_id','picture_id','publish_time')
            ->take($limit)
            ->orderBy('is_push','desc')
            ->orderBy('sort_num')
            ->orderBy('publish_time', $order)
            ->orderBy('id', $order)
            ->get()
            ->toArray();

    }

    /**
     * @desc 获取头条置顶文章
     * @param $categoryIds [1,2]
     * @param  int $limit 条数 5
     * @param string $order
     * @return mixed
     */
    public function getArticleByTop( $categoryIds, $limit = 5, $order = 'desc'  )
    {
        return $this->whereIn( 'category_id', $categoryIds )
            ->where('status', self::STATUS_PUBLISH_TRUE)
            ->select('title','id','type_id','picture_id','publish_time')
            ->take($limit)
            ->orderBy( 'is_top', $order )
            ->orderBy( 'id', $order )
            ->get()
            ->toArray();

    }

    /**
     * @param $page
     * @param $size
     * @param $category
     * @param $order
     * @return array
     * @desc 分页列表数据
     */
    public function getPageList( $page, $size, $category, $order='desc' ){

        $start = $this->getLimitStart($page, $size);

        $total = $this->where('category_id', $category)
            ->where('status', self::STATUS_PUBLISH_TRUE)
            ->count('id');

        $list = $this->where('category_id', $category)
            ->where('status', self::STATUS_PUBLISH_TRUE)
            ->skip($start)
            ->take($size)
            ->orderBy('is_top',$order)
            ->orderBy('sort_num')
            ->orderBy('publish_time', $order)
            ->orderBy('id', $order)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];

    }

    /**
     * @param $category
     * @param $order
     * @return array
     * @desc 根据类别获取文章标题和内容
     */
    public function getArticleByCategory($category, $order='desc' ){

        return $this->where('category_id', $category)
            ->where('status', self::STATUS_PUBLISH_TRUE)
            ->select('id','title','content')
            ->orderBy('is_top',$order)
            ->orderBy('sort_num')
            ->orderBy('publish_time', $order)
            ->orderBy('id', $order)
            ->get()
            ->toArray();

    }

    /**
     * @param $ids
     * @param $order
     * @return array
     * @desc 根据ids获取文章信息
     */
    public function getArticleByCategoryIds($ids, $order='desc'){

        return $this->select('id','title','category_id')
            ->where('status', self::STATUS_PUBLISH_TRUE)
            ->whereIn('category_id',$ids)
            ->orderBy('is_top',$order)
            ->orderBy('sort_num')
            ->orderBy('publish_time', $order)
            ->orderBy('id', $order)
            ->get()
            ->toArray();
    }

   /**
    * @param $page
    * @param $size
    * @param $categoryIds
    * @param $order
    * @return array
    * @desc APP4.0公告分页列表数据
    **/
   public function getNoticePageList( $page, $size, $categoryIds, $order='desc' ){

      $start = $this->getLimitStart($page, $size);

      $list = $this->whereIn('category_id', $categoryIds)
          ->where('status', self::STATUS_PUBLISH_TRUE)
          ->skip($start)
          ->take($size)
          ->orderBy('is_top',$order)
          ->orderBy('sort_num')
          ->orderBy('publish_time', $order)
          ->orderBy('id', $order)
          ->select('id','title','publish_time')
          ->get()
          ->toArray();
      return $list;
    }

    /**
     * @param $id
     * @return mixed
     * @desc 文章点击量
     */
    public function hitArticle( $id ){

        $info = $this->getById($id);

        $hits = rand(2,8);

        return self::where('id', $id)
            ->update(['hits'=> $info['hits']+$hits]);

    }

    /**
     * @return array
     * @desc 查询内容中有picture的文章
     */
    public function getContent(){
        return self::select('id','content')
            ->where('content','like','%picture%')
            ->get()
            ->toArray();
    }
}
