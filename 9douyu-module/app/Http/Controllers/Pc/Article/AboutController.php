<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/17
 * Time: 下午8:43
 */

namespace App\Http\Controllers\Pc\Article;


use App\Http\Controllers\Pc\PcController;
use App\Http\Dbs\Article\CategoryDb;
use App\Http\Logics\Article\ArticleLogic;
use App\Http\Logics\Project\CurrentLogic;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;
use Lang;

class AboutController extends PcController
{

    /**
     * @desc 公司介绍
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $viewData = [

            'keywords'      => Lang::get('article.CONTENT_ARTICLE_INDEX_KEYWORDS_1'),
            'description'   => Lang::get('article.CONTENT_ARTICLE_INDEX_DESCRIPTION_1'),
            'title'         => Lang::get('article.CONTENT_ARTICLE_INDEX_TITLE_1'),
            'class'         => 'index',

        ];

        return view('pc.about.index', $viewData);

    }

    /**
     * @desc 中国耀盛
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sunholding()
    {

        $viewData = [

            'keywords'      => Lang::get('article.CONTENT_ARTICLE_INDEX_KEYWORDS_162'),
            'description'   => Lang::get('article.CONTENT_ARTICLE_INDEX_DESCRIPTION_162'),
            'title'         => Lang::get('article.CONTENT_ARTICLE_INDEX_TITLE_162'),
            'class'         => 'sunholding',

        ];

        return view('pc.about.sunholding', $viewData);

    }

    /**
     * @desc 合作伙伴
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function partner(){

        $id      = 93;

        $logic   = new ArticleLogic();

        $article = $logic->getById($id);

        $viewData = [

            'keywords'      => Lang::get('article.CONTENT_ARTICLE_INDEX_KEYWORDS_INDEX'),
            'description'   => Lang::get('article.CONTENT_ARTICLE_INDEX_DESCRIPTION_INDEX'),
            'title'         => Lang::get('article.CONTENT_ARTICLE_INDEX_TITLE_93'),
            'article'       => $article,
            'class'         => 'partner',

        ];

        return view('pc.about.partner', $viewData);

    }

    /**
     * @desc 媒体报道
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function media(Request $request){

        $size              = 10;
        $categoryId        = CategoryDb::MEDIA;
        $page              = htmlspecialchars($request->input('page', 1));

        $logic = new ArticleLogic();

        $list  = $logic -> getPageList($page, $size, $categoryId);

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/about/media');

        $paginate = $toolPaginate->getPaginate();

        $viewData = [

            'keywords'      => Lang::get('article.CONTENT_CATEGORY_INDEX_TITLE_15'),
            'description'   => Lang::get('article.CONTENT_ARTICLE_INDEX_KEYWORDS_INDEX'),
            'title'         => Lang::get('article.CONTENT_ARTICLE_INDEX_DESCRIPTION_INDEX'),
            'list'          => $list,
            'paginate'      => $paginate,
            'class'         => 'media',

        ];

        return view('pc.about.media', $viewData);

    }

    /**
     * @desc 网站公告
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notice(Request $request){

        $size              = 6;
        $categoryId        = CategoryDb::NOTICE;
        $page              = htmlspecialchars($request->input('page', 1));
        $q                 = htmlspecialchars($request->input('q', ''));

        $params = $q ? '?q='.$q : '';

        if($q == 'records'){
            $categoryId        = CategoryDb::RECORDS;
        }
        if($q == 'monthly'){
            $categoryId        = CategoryDb::MONTHLY;
        }

        $logic = new ArticleLogic();

        $list  = $logic -> getPageList($page, $size, $categoryId);

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/about/notice'.$params);

        $paginate = $toolPaginate->getPaginate();

        if($q=='records'){
            $title = Lang::get('article.CONTENT_CATEGORY_INDEX_TITLE_25');
        }else{
            $title = Lang::get('article.CONTENT_CATEGORY_INDEX_TITLE_5');
        }

        $viewData = [

            'keywords'      => Lang::get('article.CONTENT_ARTICLE_INDEX_KEYWORDS_INDEX'),
            'description'   => Lang::get('article.CONTENT_ARTICLE_INDEX_DESCRIPTION_INDEX'),
            'title'         => $title,
            'list'          => $list,
            'q'             => $q,
            'countmax'      => count($list),
            'paginate'      => $paginate,
            'class'         => 'notice',

        ];

        return view('pc.about.notice', $viewData);

    }

    /**
     * @desc 加入我们
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function joinus(){

        $viewData = [

            'keywords'      => Lang::get('article.CONTENT_ARTICLE_INDEX_KEYWORDS_2'),
            'description'   => Lang::get('article.CONTENT_ARTICLE_INDEX_DESCRIPTION_2'),
            'title'         => Lang::get('article.CONTENT_ARTICLE_INDEX_TITLE_2'),
            'class'         => 'joinus',

        ];

        return view('pc.about.joinus', $viewData);

    }

    /**
     * @desc 分支机构
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function branch(){

        $viewData = [

            'keywords'      => Lang::get('article.CONTENT_ARTICLE_INDEX_KEYWORDS_37'),
            'description'   => Lang::get('article.CONTENT_ARTICLE_INDEX_DESCRIPTION_37'),
            'title'         => Lang::get('article.CONTENT_CATEGORY_INDEX_TITLE_37'),
            'class'         => 'branch',

        ];

        return view('pc.about.branch', $viewData);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 安全保障
     */
    public function insurance()
    {

        $viewData = [
            'keywords'      => Lang::get('article.CONTENT_ARTICLE_INSURANCE_KEYWORDS'),
            'description'   => Lang::get('article.CONTENT_ARTICLE_INSURANCE_DESCRIPTION'),
            'title'         => Lang::get('article.CONTENT_ARTICLE_INSURANCE_TITLE'),
            'class'         => 'insurance',
        ];
        return view('pc.about.insurance', $viewData);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 新手指引
     */
    public function newentrance()
    {

        //获取零钱计划
        $currentLogic = new CurrentLogic();
        $currentInfo = $currentLogic->getShowProject();

        $currentInfoUrl  = isset($currentInfo[0]['id']) ? '/current/id/' . $currentInfo[0]['id'] : 'javascript:void(0)';

        $viewData = [
            'keywords'       => Lang::get('article.CONTENT_ARTICLE_NEWENTRANCE_KEYWORDS'),
            'description'    => Lang::get('article.CONTENT_ARTICLE_NEWENTRANCE_DESCRIPTION'),
            'title'          => Lang::get('article.CONTENT_ARTICLE_NEWENTRANCE_TITLE'),
            'class'          => 'insurance',
            'currentInfoUrl' => $currentInfoUrl,
        ];
        return view('pc.article.newentrance', $viewData);

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 风险保证金
     */
    public function reservefund(Request $request){

        $id = htmlspecialchars($request->input('id', 0));

        $logic   = new ArticleLogic();

        $article        = $logic->getById($id);

        $viewData = [
            'keywords'       => Lang::get('article.CONTENT_ARTICLE_RESERVEFUND_KEYWORDS'),
            'description'    => Lang::get('article.CONTENT_ARTICLE_RESERVEFUND_DESCRIPTION'),
            'title'          => Lang::get('article.CONTENT_ARTICLE_RESERVEFUND_TITLE'),
            'currentArticle' => $article,
        ];
        return view('pc.article.reservefund', $viewData);

    }

}