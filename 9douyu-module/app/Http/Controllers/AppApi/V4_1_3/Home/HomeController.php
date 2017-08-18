<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/1/20
 * Time: 下午16:03
 */

namespace App\Http\Controllers\AppApi\V4_1_3\Home;

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
     *   path="/home?v=4.1.3",
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

        $userId = $this->getUserId();

        //App返回结果初始化
        $appReturn = [];

        //首页可用的Banner广告
        $banner = AdLogic::getUseAbleListByPositionId(5);
        $formatBannerData = AdLogic::formatAppV4AdData($banner);

        //appButton首页广告图片按钮列表
        $buttonList = AdLogic::getUseAbleListByPositionId(6);
        $buttonListData = AdLogic::formatAppV4AdData($buttonList,false,$userId);

        //首页新手广告
        $logic = new AdLogic();
        $noviceAdList = $logic->getUseAbleListByPositionId(31);
        $noviceAd = AdLogic::formatAppNoviceAdData($noviceAdList,$userId);

        //首页文章
        $articleLogic = new ArticleLogic();
        $article = $articleLogic->getAppV4Article([18],1);//获取1条记录
        $article = $articleLogic->formatAppV4HomeArticle($article);//格式化app首页头条数据

        //平台数据统计
        $statLogic = new StatLogic();
        $statisticsData = $statLogic->getV4HomeStatistics();

        //App4.0首页定期项目列表
        $projectLogic   = new ProjectLogic();
        $projectArr     = $projectLogic->getProjectPackAppV413();
        //格式化定期项目
        $projectArr     = $projectLogic->getProjectRecordAppV413Format($projectArr);

        #新手项目
        $projectNovice[]  = $projectArr["novice"];

        unset($projectArr["novice"]);
        $projectArrNew  = [];
        foreach ($projectArr as $val){
            $projectArrNew[]    = $val;
        }


        $appReturn = [
            'banner'           => $formatBannerData,
            'article_list'     => $article,
            'button_list'      => $buttonListData,//首页按钮广告
            'data_statistics'  => $statisticsData,
            'novice_ad'        => $noviceAd,        //新手广告图
            'novice_project'   => $projectNovice,   //新手项目
            'invest_project'   => $projectArrNew,   //项目列表
            'set_cookie'       => $this->setCookieInfo($this->client),
        ];

        return AppLogic::callSuccess($appReturn);
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
            "path"          => "/",
            "domain"        => ToolDomainCookie::getDomain(),
        ];

    }
}
