<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/17
 * Time: 下午5:27
 */

namespace App\Http\Controllers\Pc\Article;


use App\Http\Controllers\Pc\PcController;
use App\Http\Dbs\Article\CategoryDb;
use App\Http\Logics\Article\ArticleLogic;
use App\Http\Logics\Article\CategoryLogic;
use App\Http\Models\Common\CoreApi\SystemConfigModel;
use Redirect;
use Illuminate\Http\Request;

class ArticleController extends PcController
{

    public function help($id = 0){

        $size = 15;

        $logic = new ArticleLogic();
        $cLogic = new CategoryLogic();

        $helpArticles = $logic->getArticleList([CategoryDb::HELP_TWO], $size, 'asc');

        if( empty($id) ){

            $id = $helpArticles[0]['id'];

        }

        $info               = $logic -> getById($id);
        $info['content']    = stripslashes(htmlspecialchars_decode($info['content']));
        $info['category']   = $cLogic->getById($info['category_id']);

        $viewData = [

            'helpList'      => $helpArticles,   //帮助中心
            'current'       => $info,           //详情

        ];

        return view('pc.article.help', $viewData);

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 文章详情
     */
    public function detail($id){

        $id = (int)$id;
        $size = 15;

        $logic  = new ArticleLogic();
        $cLogic = new CategoryLogic();

        //点击量
        $logic -> hitArticle($id);

        $info = $logic -> getById($id);
        $info['content'] = stripslashes(htmlspecialchars_decode($info['content']));
        $info['category']= $cLogic->getById($info['category_id']);

        if($id==1){
            return Redirect::to('/about/index');
        }elseif($id == 162){
            return Redirect::to('/about/sunholding');
        }

        $url = '';
        if(!empty($info['category']['id'])) {
            if ($info['category']['id'] == 15) {
                $url = "/about/media";
            } else if ($info['category']['id'] == 16) {
                $url = "/about/notice?q=monthly";
            } else if ($info['category']['id'] == 7) {
                $url = "/about/notice?q=records";
            } else if ($info['category']['id'] == 5) {
                $url = "/about/notice";
            }
        }

        switch($id){
            case 81:
                return Redirect::to('/about/index');
                break;
            case 82:
                return Redirect::to('/about/index');
                break;
            case 87:
                return Redirect::to('/about/index');
                break;
            case 446:
                return Redirect::to('/content/article/insurance');
                break;
            case 15:
                return Redirect::to('/about/media');
                break;
            case 93:
                return Redirect::to('/about/notice');
                break;
            case 33:
                return Redirect::to('/about/index');
                break;
            case 125:
                return Redirect::to('/about/index');
                break;
            case 2:
                return Redirect::to('/about/joinus');
                break;
            case 6:
                return Redirect::to('/about/index');
                break;
            default:
        }

        $layout     = empty($info['layout']) ? 'default' : $info['layout'];
        if( $layout != "notice" && $layout != 'index' && $layout != 'registerAgreement' ){
            $layout = 'default';
        }

        $articleList = $logic->getArticleList([$info['category_id']], $size, 'desc');

        $shareJS = assetUrlByCdn('/static/static/api/js/share.js');
        $viewData = [
            'info'                => $info,  //详情
            'articleList'         => $articleList,
            'url'                 => $url,
            'share_js'            => $shareJS,
        ];

        return view('pc.article.'.$layout, $viewData);

    }


    /**
     * @return mixed
     * 客户端下载页面
     */
    public function download(){

        $config = \App\Http\Models\SystemConfig\SystemConfigModel::getConfig("APP_DOWNLOAD");
        /*
        $this->iosUrl       = $config["IOS_IPA"];
        $this->androidUrl   = $config["ANDROID_APK"];
        $this->title = L('CONTENT_ARTICLE_DOWNLOAD_TITLE');
        $keywords=L('CONTENT_ARTICLE_INDEX_KEYWORDS_INDEX');
        $description=L('CONTENT_ARTICLE_INDEX_DESCRIPTION_INDEX');
        */
        $viewData = [
            'iosUrl' => $config["IOS_IPA"],
            'androidUrl'    => env('ALIYUN_OSS_PUBLIC','http://9douyu.oss-cn-beijing.aliyuncs.com').$config['ANDROID_APK']
        ];

        return view('pc.article.download',$viewData);

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