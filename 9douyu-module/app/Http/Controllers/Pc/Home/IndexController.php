<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/17
 * Time: 下午2:33
 * Desc: 首页
 */

namespace App\Http\Controllers\Pc\Home;

use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\Agreement\AgreementLogic;
use App\Http\Logics\Article\ArticleLogic;
use App\Http\Logics\Project\CurrentLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Dbs\Article\CategoryDb;
use App\Http\Models\Picture\PictureModel;
use App\Tools\ToolTcPdf;
use Redirect;

class IndexController extends PcController
{

    public function index(){

        if($this->isMobile()){
            $wxLink = env("APP_URL_WX");
            return Redirect::to($wxLink);
        }

        $userId = $this->getUserId();

        $logic = new UserLogic();
        // 用户资产
        $userAccount     = $logic -> getUserInfoAccount($userId);
        // 累计收益
        $totalInterested = 0;
        if($userAccount){
            if(isset($userAccount['current']['interest'])){
                $totalInterested += $userAccount['current']['interest'];
            }
            if(isset($userAccount['project']['refund_interest'])) {
                $totalInterested += $userAccount['project']['refund_interest'];
            }
        }
        //零钱计划数据
        $currentLogic = new CurrentLogic();

        $current = $currentLogic->getShowProject();

        //项目数据包
        $projectLogic = new ProjectLogic();

        $projectArr = $projectLogic->getIndexProjectPack();

        //平台数据
        $stat = $projectArr['stat'];

        unset($projectArr['stat']);

        //九安心
        //$jaxProject = empty($projectArr['jax'])?[]:$projectArr['jax'];

        //unset($projectArr['jax']);

        //修改PC首页原九安心位置的项目
        $jaxProject = empty($projectArr[0])?[]:$projectArr[0];

        unset($projectArr[0]);

        //九省心
        $jsxProject = $projectArr;

        //文章相关
        $articleLogic = new ArticleLogic();
        $article = $articleLogic->getHomeList();


        //媒体报道
        $size        = 3;
        $categoryId  = CategoryDb::MEDIA;
        $retlist     = $articleLogic->getPageList(1, $size, $categoryId);
        $mediaList   = $picList =array();
        if(isset($retlist['list'])){
            $mediaList = $retlist['list'];
            $picModel  = new PictureModel();
            $ids       = array_column($mediaList, 'picture_id');
            $picList   = $picModel->getMutiPicturePathsByIds($ids);
        }

        $bannerList = AdLogic::getUseAbleListByPositionId(1);

        //首页注册按钮
        $indexButton    =   SystemConfigLogic::getConfig('INDEX_BUTTON');

        //设置弹窗的cookie
        setcookie('ad_already_pop',1);
        $tag = (isset($_COOKIE['ad_already_pop']) && !empty($_COOKIE['ad_already_pop'])) ? $_COOKIE['ad_already_pop'] : '';

        $data = [
            'current'       => $current,
            'stat'          => $stat,
            'article'       => $article,
            'jaxProject'    => $jaxProject,
            'jsxProject'    => $jsxProject,
            'mediaList'     => $mediaList,
            'picList'       => $picList,
            'bannerList'    => $bannerList,
            'totalInterested' => $totalInterested,
            'indexButton'   => $indexButton,
            'tag'           => $tag,
        ];

        return view('pc.home.index', $data);

    }

}
