<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/7
 * Time: 下午8:58
 * Desc: App3.0 首页接口
 */

namespace App\Http\Controllers\App\V3;

use App\Http\Controllers\App\AppController;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\Article\ArticleLogic;
use App\Http\Logics\Bonus\BonusLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Statistics\StatLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\Bonus\UserBonusModel;
use App\Http\Models\Current\RateModel;
use App\Http\Models\Project\CurrentModel;

class HomeController extends AppController
{

    /**
     * @SWG\Post(
     *   path="/home_v3",
     *   tags={"APP-3.0"},
     *   summary="首页 [V3\HomeController@index]",
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
     *      default="3.0",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取信息成功。",
     *   ),
     * )
     */
    public function index()
    {

        $client = $this->getClient();

        $userId = $this->getUserId();

        //获取banner
        $banner = AdLogic::getUseAbleListByPositionId(5);

        $banner = $this->formatAdData($banner);

        //首页文章
        $articleLogic = new ArticleLogic();

        //获取1条
        $articleList = $articleLogic->getArticleList([18], 1);

        $articleList = $this->formatArticleData($articleList);

        //appButton
        $infoButton = AdLogic::getUseAbleListByPositionId(6);

        $infoButton = $this->formatAdData($infoButton);

        //获取零钱计划项目
        $currentModel = new CurrentModel();

        $currentProject = $currentModel->getProject();

        //是否显示新手专享,0标示不显示;1标示显示
        $userNewBonus = 1;

        $userBonusModel = new UserBonusModel();

        $userBonusLogic = new UserBonusLogic();

        if ($userId) {

            $currentRateBonus = $userBonusModel->getCurrentAbleUserBonusList($userId, $client);

            $userNewBonus = $userBonusLogic->getAppNewUserCurrentBonus($currentRateBonus);

            $bonusRate = isset($currentRateBonus[0]['rate']) ? $currentRateBonus[0]['rate'] : 0;

        }else{

            $configBonusId = SystemConfigLogic::getConfig('CURRENT_BONUS_RATE_ID');

            $bonusRateInfo = BonusLogic::findById($configBonusId);

            $bonusRate = isset($bonusRateInfo['data']['obj']['rate']) ? $bonusRateInfo['data']['obj']['rate'] : 0;

        }

        //零钱计划利率
        $currentRateModel = new RateModel();

        $rate = $currentRateModel->getRate();

        $currentProject['is_new_user_show'] = $userNewBonus;

        $currentProject['bonus_rate'] = $bonusRate;

        $currentProject['rate'] = $rate['rate'];

        $currentProject = $this->formatCurrentProject($currentProject);

        //首页底部广告
        $downAd = AdLogic::getUseAbleListByPositionId(20);

        $downAd = $this->formatAdData($downAd, true);

        //首页数据显示
        $logic = new StatLogic();

        $statData = $logic->getAppV3HomeData();

        $result = [
            'banner'                => $banner,
            'article_list'          => $articleList,
            'button_list'           => empty($infoButton) ? [[]] : $infoButton,
            'current_project'       => $currentProject,
            'down_ad_list'          => $downAd ? $downAd : [[]],
            'data_statistics'       => $statData
        ];

        return self::appReturnJson(Logic::callSuccess($result));

    }

    /**
     * @param array $data
     * @return array
     * @desc 格式化广告
     */
    private function formatAdData($data = [], $isDown = false)
    {

        if (empty($data)) {

            return [];

        }

        $result = [];

        foreach ($data as $key => $value) {

            $param = $value['param'];

            if ($isDown) {

                $param['word'] = explode('|', $param['word']);

            }

            $result[] = [
                'id'    => $value['id'],
                'word'  => $param['word'],
                'url'   => $param['url'],
                'file'  => $param['file'],
                'title' => $value['title'],
                'shareInfo' => [
                    'share_title' => empty($param['share_title']) ? $value['title'] : $param['share_title'],
                    'share_desc'  => empty($param['share_desc']) ? '' : $param['share_desc'],
                    'share_url'   => empty($param['share_url']) ? $param['url'] : $param['share_url'],
                    'share_image' => empty($param['share_image']) ? $param['file'] : $param['share_image'],
                ],
            ];

        }

        return $result;

    }

    /**
     * @param array $data
     * @return array
     * @desc 简化文章列表
     */
    private function formatArticleData($data = [])
    {

        if (empty($data)) {

            return [];

        }


        foreach ($data as $value) {

            $url     = env('APP_URL_WX')."/Article/index/".$value['id'].'?from=app';
            $moreUrl = env('APP_URL_WX')."/Article/getArticleList?from=app";
            $shareImg = env('APP_URL_WX')."/static/weixin/images/partner-share2.png";

            $result = [
                'title'         => $value['title'],
                'picture_id'    => $value['picture_id'],
                'url'           => $url,
                'url_title'     => $value['title'],
                'more_url'      => $moreUrl,
                'more_title'    => '资讯中心',
                'shareInfo'     => [
                    'share_title' => $value['title'],
                    'share_desc'  => '',
                    'share_url'   => $url,
                    'share_image' => '',
                ],
            ];

            return $result;

        }

    }

    /**
     * @param array $data
     * @return array
     * @desc 格式化零钱计划项目
     */
    private function formatCurrentProject($data = [])
    {

        if( empty($data) ){

            return [];

        }

        return [
            'id'                => $data['id'],
            'name'              => $data['name'],
            'rate'              => $data['bonus_rate'] ? (float)$data['rate'] : (float)$data['rate'],
            'rate_note'         => '今日年化收益%',
            'invest_note'       => '灵活存取',
            'money_note'        => '1元可投',
            'is_new_user_show'  => $data['is_new_user_show'],
            'bonus_rate'        => number_format($data['bonus_rate'], 1)
        ];

    }

}