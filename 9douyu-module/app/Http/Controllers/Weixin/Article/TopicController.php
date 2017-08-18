<?php
/**
 * wap底部链接文章
 * Create By Phpstorm
 * @author linguanghui
 * Date 16/07/29
 * Time 18:50 Pm
 */
namespace App\Http\Controllers\Weixin\Article;

use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Article\ArticleLogic;
use App\Http\Logics\Article\CategoryLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Models\Common\CoreApi\StatisticsModel;
use App\Http\Logics\User\UserInfoLogic;
use Illuminate\Http\Request;

class TopicController  extends WeixinController{

    /**
     * 了解九斗鱼页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function introduce(){
        return view('wap.article.introduce');
    }

    /**
     * 资产安全页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function safe(){
        //债转核心url指向了此固定连接 app端项目指向app端safe 以后只维护 app.topic.safe article 是错误的
        //return view('wap.article.safe');
        return view('app.topic.safe');
    }

    /**
     * 中国耀盛页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sunFund(){
        return view('wap.article.sunfund');
    }

    /**
     * 品牌介绍
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sunHolding(){
        return view('wap.article.sunholding');
    }

    /**
     * 关于我们
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about(){
        return view('wap.article.about');
    }

    /**
     * 安全保障
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function security(){
        return view('wap.article.security');
    }

    /**
     * push tutorial 微信推送教程
     *
     */
    public function pushandroid()
    {

        return view('wap.article.pushandroid');

    }
    public function pushios()
    {

        return view('wap.article.pushios');

    }
    public function withdrawIntro()
    {

        return view('wap.article.withdrawIntro');

    }
    public function rechargeIntro()
    {

        return view('wap.article.rechargeIntro');

    }

    /**
     * newbieguide 新手指引
     *
     */
    public function newbieguide()
    {

        return view('wap.article.newbieguide');

    }

    /**
     *
     */
    public function dataStatistics(){

        $data = StatisticsModel::getStatistics();

        return view('wap.article.statistics', $data);
    }

    /**
     * 资讯中心
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getArticleList(){

        $size       = 30;
        $articleLogic = new ArticleLogic();
        $res = $articleLogic->getArticleList([18],$size);

        $viewData = [
            'articleList'   => $res,
        ];

        return view('wap.article.information-center', $viewData);
    }

    /**
     * App4.0资讯中心列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAppV4ArticleList(){

        $size       = 30;
        $articleLogic = new ArticleLogic();
        $res = $articleLogic->getAppV4Article([18],$size);

        $viewData = [
            'articleList'   => $res,
        ];

        return view('wap.article.information-center', $viewData);
    }

    public function index($id){

        $id = (int)$id;

        $logic  = new ArticleLogic();
        $cLogic = new CategoryLogic();

        //点击量
        $logic -> hitArticle($id);

        $info = $logic -> getById($id);
        $info['content'] = stripslashes(htmlspecialchars_decode($info['content']));
        $info['category']= $cLogic->getById($info['category_id']);


        $viewData = [
           'currentArticle' => $info,
        ];

        return view('wap.article.information-info', $viewData);

    }

    public function questionArticle($id){

        $id = (int)$id;

        $logic  = new ArticleLogic();

        //点击量
        $logic -> hitArticle($id);

        $info = $logic -> getById($id);
        $info['content'] = stripslashes(htmlspecialchars_decode($info['content']));


        $viewData = [
            'questionArticle' => $info,
        ];

        return view('wap.article.hot-detail', $viewData);

    }

    /**
     * @desc 热门问题
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function question(){

        $articleLogic = new ArticleLogic();

        $hotQuestion = $articleLogic->getArticleByCategoryId(28);
        $allQuestion = $articleLogic->getArticleByCategoryId(29);

        $viewData = [
            'hotQuestion'   => $hotQuestion,
            'allQuestion'   => $allQuestion,
        ];

        return view('wap.article.hot-question',$viewData);

    }

    /**
     * questionnaire 风险评估问卷
     *
     */
    public function questionnaire()
    {

        if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
            $system = "ios";
        }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
            $system = "android";
        }else{
            $system = "wap";
        }

        return view('wap.article.questionnaire',['system'=>$system]);

    }

    public function doQuestionNaire(Request $request){
        $userId = $this->getUserId();

        $data = [];
        for($i=1;$i<=10;$i++){
            $data['question'.$i] = $request->input('question'.$i);
        };

        $logic = new UserInfoLogic();
        $result = $logic->doWapScore($userId,$data);

        return self::returnJson($result);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 注册协议
     */
    public function registerAgreement(){

        $id = 5;

        $logic  = new ArticleLogic();
        $cLogic = new CategoryLogic();

        $info = $logic -> getById($id);
        $info['content'] = stripslashes(htmlspecialchars_decode($info['content']));
        $info['category']= $cLogic->getById($info['category_id']);

        $viewData = [
            'info'                => $info,  //详情
        ];

        return view('article.layout.registerAgreement', $viewData);

    }
}
