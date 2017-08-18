<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午5:48
 */

namespace App\Http\Logics\Article;

use App\Http\Dbs\Article\ArticleDb;
use App\Http\Dbs\Article\CategoryDb;
use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Oss\OssLogic;
use App\Http\Models\Article\ArticleModel;
use App\Http\Models\Article\CategoryModel;
use App\Http\Models\Common\CoreApi\RefundModel;
use App\Http\Models\Picture\PictureModel;
use App\Tools\AdminUser;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use App\Tools\ToolUrl;
use Illuminate\Support\Facades\Redirect;
use Log;

class ArticleLogic extends Logic
{

    public static function _filterParams($data){

        $data = [
            'title'         => trim($data['title']),
            'img'           => !empty($data['img']) ? $data['img'] : '',
            'intro'         => !empty($data['intro']) ? htmlspecialchars(trim($data['intro'])) : '',
            'keywords'      => !empty(trim($data['keywords'])) ? trim($data['keywords']):'',
            'description'   => !empty(trim($data['description'])) ? trim($data['description']) : '',
            'category_id'   => (int)$data['category_id'],
            'layout'        => trim($data['layout']),
            'content'       => htmlspecialchars(trim($data['content'])),
            'sort_num'      => !empty((int)$data['sort_num']) ? (int)$data['sort_num'] : 0,
            'is_top'        => !empty($data['is_top']) ? (int)$data['is_top']:0,
            'type_id'       => !empty($data['type_id']) ? (int)$data['type_id'] : 0,
            'is_push'       => !empty($data['is_push']) ? (int)$data['is_push'] : 0,
            'status'        => (int)$data['status'],
            'create_by'     => AdminUser::getAdminUserId(),
            'publish_time'  => !empty($data['publish_time']) ? $data['publish_time'] : ToolTime::dbNow(),
            'file'          => !empty($data['file']['tmp_name']) ? $data['file'] : '',
        ];

        return $data;

    }

    /**
     * @param $data
     * @return array
     * @desc 添加文章
     */
    public function doCreate($data){

        $data = self::_filterParams($data);

        try{

            self::beginTransaction();

            $picModel = new PictureModel();

            $ossLogic = new OssLogic();

            if(!empty($data['file'])){

                $upload = $ossLogic->putFile($data['file'],'resources/images');

                $imgPath = substr($upload['data']['path'],strpos($upload['data']['path'],'/')+1).'/'.$upload['data']['name'];

                $result = $picModel -> doCreate($imgPath);

                $data['picture_id'] = $result;

            }else{

                $data['picture_id'] = 0;

            }

            unset($data['img']);

            unset($data['file']);

            //添加文章默认生成一个初始化浏览量
            $data['hits'] = rand(1000,1200);

            $model = new ArticleModel();

            $insertId = $model->doCreate($data);

            self::commit();

            //文章发布成功事件,公告要发送站内信
            $param['notice'] = [
                'title'     => $insertId,
                'message'   => $data['title'],
                'type'      => $data['category_id']
            ];

            \Event::fire(new \App\Events\Article\CreateArticleSuccessEvent($param));

        }catch (\Exception $e){

            self::rollback();

            \Log::error(__METHOD__,[$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess();

    }

    /**
     * @param $data
     * @return array
     * @desc 更新文章
     */
    public function doUpdate($data){

        $id = (int)$data['id'];

        $picture_id = $data['picture_id'];

        $data = self::_filterParams($data);

        try{

            self::beginTransaction();

            $picModel = new PictureModel();

            $ossLogic = new OssLogic();

            if(isset($data['file']) && !empty($data['file'])){

                $upload = $ossLogic->putFile($data['file'],'resources/images');

                $imgPath = substr($upload['data']['path'],strpos($upload['data']['path'],'/')+1).'/'.$upload['data']['name'];

                $result = $picModel -> doCreate($imgPath);

                $data['picture_id'] = $result;

            }else{

                $data['picture_id'] = $picture_id;

            }

            unset($data['img']);

            unset($data['file']);

            $model = new ArticleModel();

            $result = $model->doUpdate($id, $data);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            \Log::error(__METHOD__,[$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);

    }

    /**
     * @param $id
     * @return array
     * @desc 删除文章
     */
    public function doDelete($id){

        $id = (int)$id;

        try{

            $model = new ArticleModel();

            $result = $model->doDelete($id);

        }catch (\Exception $e){

            \Log::error(__METHOD__,[$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);

    }

    /**
     * @param $id
     * @return array
     * @desc 通过id获取文章信息
     */
    public function getById( $id ){

        $model = new ArticleModel();

        $result = $model -> getById($id);

        if(isset($result['picture_id']) && $result['picture_id']){

            $pmodel = new PictureModel();

            $pInfo  = $pmodel -> getById($result['picture_id']);

            $result['pic_url'] = isset($pInfo['path']) ? $pInfo['path'] : '';

        }

        return $result;

    }

    /**
     * @return array|mixed
     * @desc 获取文章
     */
    public function getList($page, $size){

        $model = new ArticleModel();

        $result = $model -> getList($page, $size);

        return $result;

    }

    /**
     * @return array
     * @desc 获取布局
     */
    public function getArticleLayouts() {
        $appPath = realpath(dirname(__FILE__) ."/../../../../") ;
        $articleTplDir = $appPath . '/resources/views/article/layout';

        $layouts        = array(
            array(
                'value' => 'index',
                'text'  => '默认',
            ),
        );
        foreach(glob($articleTplDir . '/*.blade.php') as $tpl) {
            if(preg_match('#/([^/]+)\.blade\.php#is', $tpl, $match)) {
                if($match[1] == 'index') continue;

                $layouts[] = array(
                    'value' => $match[1],
                    'text'  => $match[1],
                );
            }
        }

        return $layouts;
    }

    /**
     * @param $path
     * @desc 创建文件夹
     */
    public function makeFolder($path){

        if(!is_readable($path)){

            $this->makeFolder( dirname($path) );

            if(!is_file($path)) mkdir($path,0777);

        }

    }

    /**
     * @param array $categoryIds [1,2]
     * @param  int $limit 条数 5
     * @param string $order
     * @return mixed
     * @desc 通过类别获取$limit条数据
     */
    public function getArticleList($categoryIds, $limit=5, $order = 'desc'){

        $model = new ArticleModel();

        $result = $model -> getArticleList( $categoryIds, $limit, $order );

        return $result;

    }


    /**
     * @return array
     * @desc pc首页数据
     */
    public function getHomeList()
    {

        return [
            'media'     => $this->getMediaList(),   //媒体报道
            'notice'    => $this->getNoticeList(),  //平台公告
            'refund'    => $this->getRecordsList(), //还款公告
            'newest'    => $this->getNewestList()   //最新动态
        ];

    }

    /**
     * @param int $page
     * @param int $size
     * @return array
     * @desc App4.0公告数据
     **/
    public function getAllNoticeList($page,$size)
    {
        return $this->getNoticePageList($page, $size, [CategoryDb::NOTICE,CategoryDb::RECORDS]);
    }

    /**
     * @param $page
     * @param $size
     * @param $categoryIds
     * @return array
     * @desc App4.0分页列表数据
     */
    public function getNoticePageList($page, $size, $categoryIds)
    {
        $model = new ArticleModel();
        $result = $model->getNoticePageList($page, $size, $categoryIds);
        return $result;
    }

    /**
     * @param int $size
     * @return mixed
     * @desc 媒体报道
     */
    public function getMediaList($size=5)
    {

        return $this->getArticleList([CategoryDb::MEDIA], $size);

    }

    /**
     * @param int $size
     * @return mixed
     * @desc 网站公告
     */
    public function getNoticeList($size=5)
    {

        return $this->getArticleList([CategoryDb::NOTICE], $size);

    }

    /**
     * @param int $size
     * @return mixed
     * @desc 还款公告
     */
    public function getRecordsList($size=5)
    {

        $info = $this->getPageList(1, $size, CategoryDb::RECORDS);

        return $info['list'];

    }

    /**
     * @param int $size
     * @return mixed
     * @desc 首页最新动态
     */
    public function getNewestList($size=6)
    {

        return $this->getArticleList([CategoryDb::MEDIA, CategoryDb::NOTICE, CategoryDb::RECORDS], $size);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 根据分类ID,获取其下所有子分类及其文章
     */
    public function getArticleByCategoryId($id)
    {

        $categoryModel = new CategoryModel();

        $hotQuestionCategory = $categoryModel->getNameByPid($id);

        $hotQuestionIds = [];

        foreach($hotQuestionCategory as $key=>$value){

            $hotQuestionIds[] = $value['id'];

        }

        $model = new ArticleModel();

        $info = $model->getArticleByCategoryIds($hotQuestionIds);

        $data = [];

        foreach($hotQuestionCategory as $key=>$value){

            foreach($info as $k=>$v){

                if($v['category_id'] == $value['id']){
                    $data[$value['name']][] = [
                        'url' => env('APP_URL_WX')."/Article/question/".$v['id'],
                        'title' =>  $v['title'],
                    ];
                }

            }
        }

        return $data;

    }

    /**
     * @param $page
     * @param $size
     * @param $categoryId
     * @return array
     * @desc 分页列表数据
     */
    public function getPageList($page, $size, $categoryId){

        $model = new ArticleModel();

        $result = $model -> getPageList($page, $size, $categoryId);

        return $result;

    }

    /**
     * @param $id
     * @return mixed
     * @desc 文章点击量
     */
    public function hitArticle( $id )
    {

        $model = new ArticleModel();

        $result = $model -> hitArticle( $id );

        return $result;

    }


    /**
     * @return array
     * 获取注册协议内容
     */
    public function getRegisterAgreement(){

        $id = ArticleDb::REGISTER_AGREEMENT_ID;
        $article = $this->getById($id);

        //不存在或未发布
        if(empty($article) || $article['status'] != ArticleDb::STATUS_PUBLISH_TRUE) {

            return Redirect::to('/')->send();
        }

        //点击计数
        $hits = $this->hitArticle($id);

        $article['content'] = stripslashes(htmlspecialchars_decode($article['content']));

        return $article;


    }

    public function getRegisterAgreementHtml(){

        $registerInfo = $this->getRegisterAgreement();

        return self::callSuccess(['info'=>$registerInfo['content']]);

    }

    public function doAddRefundSuccessNotice($times)
    {

        $result = RefundModel::getArticleNoticeByTimes($times);

        if( !empty($result) ){

            $data = $this->packagingData($times, $result);

            $model = new ArticleModel();

            try{

                $model->doCreate($data);

            }catch (\Exception $e){

                Log::Error(__METHOD__.'doAddRefundSuccessNoticeError', ['data' => $data, 'msg' => $e->getMessage()]);

            }

        }

    }

    private function packagingData($times, $data)
    {

        $date            = date("y年m月d日",strtotime($times));

        //$projectIds      = ToolArray::arrayToIds($data, 'project_id');

        //$idStr = implode('、', $projectIds);

        $projectStr      = '';

        $str             = $this->getRefundTpl();

        foreach($data as $project) {

            $projectId   = $project['project_id'];

            $title       = $project['product_line_note'] .' '. $project['invest_time_note'] .' '. $project['project_id'];

            $q           = $project['q'];

            $n           = $project['n'];

            $typeNote    = $project['refund_type_note'];

            $refundCash   = $project['cash_total'];

            $detailUrl   = ("/project/".$projectId);

            $projectStr.="<tr>
            <th class='tc'><a target='__blank' href='{$detailUrl}'>{$title}</a></th>
            <th class='tc'>{$q}/{$n}</th>
            <th class='tc'>{$typeNote}</th>
            <th class='tc'>{$refundCash}</th>
            </tr>";

        }

        $str = str_replace('{dateStr}', $date, $str);

        // $str = str_replace('{idStr}', $idStr, $str);

        $str = str_replace('{projectStr}', $projectStr, $str);

        $title = date("Y-m-d",strtotime($times))." 项目还款公告";

        return array(
            'category_id'   => CategoryDb::REFUND,
            'layout'        => 'notice',
            'title'         => $title,
            'content'       => $str,
            'is_top'        => 1,
            'status'        => ArticleDb::STATUS_PUBLISH_TRUE,
        );

    }

    private function getRefundTpl() {
        $str = <<<Eof
        <p style="text-indent:2em;font-family:'Microsoft YaHei', 'Lucida Sans Unicode', 'Myriad Pro', 'Hiragino Sans GB', 'Heiti SC', Verdana, simsun;color:#555555;font-size:13px;background-color:#FFFFFF;">
                         尊敬的鱼客们：
        </p>
        <p class="mb10"></p>
        <p style="text-indent:2em;font-family:'Microsoft YaHei', 'Lucida Sans Unicode', 'Myriad Pro', 'Hiragino Sans GB', 'Heiti SC', Verdana, simsun;color:#555555;font-size:13px;background-color:#FFFFFF;">
                        {dateStr}还款详情如下，请点击查看，投资用户可登录账户查看回款详情；建议将回款资金再次出借，获取高额收益。
        </p>
        <p class="mb10"></p>
        <style type='text/css'>
        .table{border-collapse:collapse; width:100%;}
        .table th,.table td{border:1px solid #eee;line-height:36px; font-weight:normal;}
       .table a,.table a:visited {color: #555;}
        .table a:hover,.table a:active {color: #fe7822;}
        </style>
        <table class='table'>
        <tr>
        <th class='tc' width='38%'>项目名称</th>
        <th class='tc' width='18%'>还款期数</th>
        <th class='tc' width='18%'>还款方式</th>
        <th class='tc '>还款金额（元）</th>
        </tr>
        {projectStr}
        </table>
        <p class="mb10"></p>
        <p style="text-indent:2em;font-family:'Microsoft YaHei', 'Lucida Sans Unicode', 'Myriad Pro', 'Hiragino Sans GB', 'Heiti SC', Verdana, simsun;color:#555555;font-size:13px;background-color:#FFFFFF;">
            <strong>借款人均委托九斗鱼向所有投资人表示感谢，感谢各位投资人在借款人急需帮助的时候提供了资金支持。借款人表示一定会准时还款，回报投资者的信任。</strong>
        </p>
        <p class="mb10"></p>
        <p style="text-indent:2em;font-family:'Microsoft YaHei', 'Lucida Sans Unicode', 'Myriad Pro', 'Hiragino Sans GB', 'Heiti SC', Verdana, simsun;color:#555555;font-size:13px;background-color:#FFFFFF;">
            <strong>备注：以上项目均是当日计息。</strong>
        </p>
Eof;
        return $str;
    }

    /**
     * @param array $categoryIds [1,2]
     * @param  int $limit 条数 5
     * @param string $order
     * @return mixed
     * @desc 获取App4.0的文章列表
     */
    public function getAppV4Article($categoryIds, $limit=5, $order = 'desc'){

        $articleDb =  new ArticleDb( );

        $result = $articleDb->getArticleByTop( $categoryIds, $limit, $order );

        return $result;

    }

    /**
     * @desc App4.0首页文章头条格式化
     * @param $article array
     * @return array
     */
    public function formatAppV4HomeArticle($article){

        $result = [];

        if(!empty($article)){

        foreach ($article as $value) {

            //$url     = env('APP_URL_WX')."/Article/index/".$value['id'].'?from=app';
            $url     = env('APP_URL_WX')."/Article/getAppV4ArticleList?from=app";
            $moreUrl = env('APP_URL_WX')."/Article/getArticleList?from=app";
            $shareImg = env('APP_URL_WX')."/static/weixin/images/partner-share2.png";

            $result = [
                'title'         => $value['title'],
                'picture_id'    => $value['picture_id'],
                'url'           => $url,
                'url_title'     => '资讯中心',
                'more_url'      => $moreUrl,
                'more_title'    => '头条',
                'shareInfo'     => [
                    'share_title' => $value['title'],
                    'share_desc'  => '',
                    'share_url'   => $url,
                    'share_image' => '',
                ],
            ];


          }
        }
        return $result;
    }

    /**
     * @desc 格式化首页系统维护信息
     */
    public function getIndexSystemArticle(){

        $systemInfo = [];

        $article = $this->getAppV4Article([25],1);

        if(!empty($article)){
            $articleInfo = $this->getById($article[0]['id']);

            //print_r($articleInfo);exit;
            $systemInfo['title'] = $articleInfo['title'];
            $systemInfo['content'] = strip_tags( htmlspecialchars_decode( $articleInfo['content'] ) );
            $systemInfo['is_know_btn'] = '知道了';
        }

        return $systemInfo;
    }

    /**
     * @desc App4.0公告数据格式化
     * @param $notice array
     * @return array
     */
    public function formatAppNoticeData($notice=[]){

        $data = [];

        if(!empty($notice)){

            foreach($notice as $key=>$value){
                $data[$key]['url']          = env('APP_URL_WX')."/Article/index/".$value['id'].'?from=app';
                $data[$key]['title']        = $value['title'];
                $data[$key]['publish_time'] = $value['publish_time'];
            }

        }

        return $data;
    }



}
