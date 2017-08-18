<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/1/20
 * Time: 下午16:03
 */

namespace App\Http\Controllers\AppApi\V4_0\Home;

use App\Http\Dbs\Ad\AdPositionDb;
use App\Tools\ToolDomainCookie;
use App\Tools\ToolTime;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\Article\ArticleLogic;
use App\Http\Logics\Statistics\StatLogic;
use App\Http\Logics\Project\CurrentLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\Version\VersionLogic;

/**
 * class HomeController
 * @package App\Http\Controllers\AppApi\V4
 */
class HomeController extends AppController
{

    /**
     * @SWG\Post(
     *   path="/home",
     *   tags={"APP-Home"},
     *   summary="首页 [Home\HomeController@index]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取信息成功。",
     *   ),
     * )
     */
    public function index(Request $request){

        $client = $this->client;

        $userId = $this->getUserId();

        //App返回结果初始化
        $appReturn = [];

        //App4.0首页可用的Banner广告
        $banner = AdLogic::getUseAbleListByPositionId(5);
        $formatBannerData = AdLogic::formatAppV4AdData($banner);

        //appButton首页广告图片按钮列表
        $buttonList = AdLogic::getUseAbleListByPositionId(6);
        $buttonListData = AdLogic::formatAppV4AdData($buttonList,false,$userId);

        //App4.0首页图标文章
        //首页文章
        $articleLogic = new ArticleLogic();
        $article = $articleLogic->getAppV4Article([18],1);//获取1条记录
        $article = $articleLogic->formatAppV4HomeArticle($article);//格式化app首页头条数据

        //平台数据统计
        $statLogic = new StatLogic();
        $statisticsData = $statLogic->getV4HomeStatistics();

        //App4.0零钱计划数据
        $currentLogic = new CurrentLogic();
        $currentProject = $currentLogic->getAppHomeV4Current($userId,$client);
        //格式化appV4首页零钱计划数据
        $currentProject = $currentLogic->formatAppHomeV4CurrentData($currentProject);

        //App4.0首页定期项目列表
        $projectLogic = new ProjectLogic();
        $projectArr   = $projectLogic->getIndexProjectPack();
        $projectArr   = $projectLogic->getAppV4HomeProject($projectArr);

        //格式化定期项目
        $projectArr = $projectLogic->formatAppV4HomeProjectDetail($projectArr);

        $appReturn = [
            'banner'           => $formatBannerData,
            'article_list'     => $article,
            'button_list'      => $buttonListData,//首页按钮广告
            'data_statistics'  => $statisticsData,
            'current_project'  => $currentProject,
            'invest_project'   => $projectArr,
            'set_cookie'       => $this->setCookieInfo($this->client),
            ];

        return AppLogic::callSuccess($appReturn);
    }

    /**
     * @SWG\Post(
     *   path="/home_pop",
     *   tags={"APP-Home"},
     *   summary="首页弹窗 [Home\HomeController@indexPop]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取信息成功。",
     *   ),
     * )
     */
    public function indexPop(Request $request){

        $client       = strtolower($request->input('client'));
        $version      = strtolower($request->input('version'));
        $userId       = $this->getUserId();

        $indexPopData = $formatSystemInfo = $formatIndexPopAd = $versionUpdate = [];

        //系统维护
        $articleLogic = new ArticleLogic();

        $formatSystemInfo =  $articleLogic->getIndexSystemArticle();
        if(!empty($formatSystemInfo)){

            $formatSystemInfo['type'] = 1;
            $formatSystemInfo['content'] =  $articleLogic->getIndexSystemArticle();
        }

        //首页广告弹窗
        $indexPopAdList = AdLogic::getUseAbleListByPositionId(26);

        if(!empty($indexPopAdList)){
            $formatIndexPopAd['type'] = 2;
            $formatIndexPopAd['content'] = AdLogic::formatAppV4AdData($indexPopAdList);

        }

        //未登录广告弹窗
        $noLoginPopAd = [];
        if(empty($userId)){
            $noLoginPopAdList = AdLogic::getUseAbleListByPositionId(29);
            if(!empty($noLoginPopAdList[0])){
                $noLoginPopAdArr[] = $noLoginPopAdList[0];
                $noLoginPopAd['type'] = 2;
                $noLoginPopAd['content'] = AdLogic::formatAppV4AdData($noLoginPopAdArr);

            }
        }

        //更新提示
        $versionLogic = new VersionLogic;
        $version       = $versionLogic->checkVersion($client, $version, $userId);
        if(!empty($version)){
            $versionUpdate['type']   = 4;
            $versionUpdate['content'] = $version['data'];
            $versionUpdate['content']['no_update_btn'] = '暂不更新';
            $versionUpdate['content']['now_update_btn'] = '立即更新';
        }

        $indexPopData = [
              'system' => $formatSystemInfo,
              'ad'     => $formatIndexPopAd,
              'noLoginAd' => $noLoginPopAd,
              'update' => $versionUpdate,
            ];

        return AppLogic::callSuccess($indexPopData);
    }

    /**
     * @SWG\Post(
     *   path="/ad_show",
     *   tags={"APP-Home"},
     *   summary="广告 [Home\HomeController@adShow]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=false,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="position_type",
     *      in="formData",
     *      description="广告类型",
     *      required=true,
     *      type="string",
     *      default="start_page",
     *   ),
     *   @SWG\Parameter(
     *      name="uuid",
     *      in="formData",
     *      description="uuid",
     *      required=true,
     *      type="string",
     *      default="4234",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取信息成功。",
     *   ),
     * )
     */
    /**
     * @param Request $request
     */
    public function adShow(Request $request){


        $position = $request->input('position_type', 'start_page');

        switch($position){
            case 'start_page':
                $positionId = 3;
                break;
            case 'realName':
                $positionId = AdPositionDb::P_REAL_NAME;
                break;
            case 'realNameOk':
                $positionId = 12;
                break;
            case 'realNameNo':
                $positionId = AdPositionDb::P_REAL_NAME_NO;
                break;
            case 'login':
                $positionId = 24;
                break;
            default:
                $positionId = 3;
                break;

        }

        $result = AdLogic::getUseAbleListByPositionId($positionId);
        $formatBannerData = AdLogic::formatAppV4AdData($result);

        return AppLogic::callSuccess($formatBannerData);

    }

    /**
     * @param $client
     * @return array
     */
    private function setCookieInfo($client){

        return [
                "name"          => "JDY_CLIENT_COOKIES",
                "value"         => $client,
                'expire_at'     =>  date("Y-m-d H:i:s",strtotime("+1 day")),
                //"expire_at"     => ToolTime::getDateAfterCurrent(),
                "path"          => "/",
                "domain"        => ToolDomainCookie::getDomain(),
            ];

    }

}
