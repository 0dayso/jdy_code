<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/12/15
 * Time: 上午11:01
 */

namespace App\Http\Controllers\App\V3;


use App\Http\Controllers\App\AppController;
use App\Http\Logics\Invite\InviteLogic;
use App\Http\Logics\Micro\MicroJournalLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Models\User\InviteModel;

/**
 * 活动
 *
 * Class ActiveController
 * @package App\Http\Controllers\App\Active
 */
class ActiveController extends AppController
{
    /**
     * @SWG\Post(
     *   path="/more_v3",
     *   tags={"APP-3.0"},
     *   summary="更多 [V3\ActiveController@index]",
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

    /**
     * @return array
     * @desc V3版本新的more接口
     */
    public function index(){

        $faqInfo        =   $this->setFaqInfo();

        $teamInfo       =   $this->setTeamInfo();

        $appDownInfo    =   $this->setAppDownLoadInfo();

        $newsPaperInfo  =   $this->setNewsPaperInfo();

        $appArticleInfo =   $this->setAppArticleInfo();

        $partnerInfo    =   $this->setPartnerInfo();

        $items = [
            "faqInfo"               =>  $faqInfo,
            'teamInfo'              =>  $teamInfo,
            "appDownShareInfo"      =>  $appDownInfo,
            'newsPaperInfo'         =>  $newsPaperInfo,
            "appArticleInfo"        =>  $appArticleInfo,
            'partnerInfo'           =>  $partnerInfo,
        ];

        $result = self::callSuccess($items);

        return $this->appReturnJson($result);
    }

    /**
     * @return array
     * @desc 制作团队
     */
    private function setTeamInfo()
    {
        $teamInfo    =   [
            'team_title'    =>  '策划制作团队',
            'team_url'      =>  env('APP_URL_WX') . "/app/team"
        ];

        return $teamInfo;
    }
    /**
     * @return array
     * @desc 常见问题
     */
    private function setFaqInfo()
    {
        $faqInfo    =   [
            'faq_title' =>  '常见问题',
            'faq_url'   =>  env('APP_URL_WX') . "/app/faq"
        ];

        return $faqInfo;
    }

    private function setAppArticleInfo()
    {
        $maxArticleInfo = [
            'app_news_title'    =>  '资讯中心',
            'min_picture_id'    =>  '',
            'max_news_id'       =>  '',
            'app_news_url'      =>  env("APP_URL_WX")."/Article/getArticleList",
        ];

        return $maxArticleInfo;
    }
    /**
     * @return array
     * @desc 设置App下载分享的信息
     */
    private function setAppDownLoadInfo()
    {
        return  [
            'app_down_title'=>  '推荐好友下载',
            'shareInfo'     =>[
                'share_title' => "吆~吆~九斗鱼，理财收益就是高，安心理财睡好觉！" ,
                'share_desc'  => "下载九斗鱼App，快来一起赚钱吧！",
                'share_url'   => env("WEIXIN_URL")."/zt/appguide.html?from=singlemessage&isappinstalled=1",
                'share_image' => env('APP_URL_IOS')."/resources/weixin/share.png",
            ],
        ];
    }
    /**
     * @return array
     * @desc 合伙人的信息
     */
    private function setPartnerInfo()
    {
        $userId = $this->getUserId();

        $phone = '';

        if( $userId ){

            $inviteLogic    = new InviteLogic();

            $inviteInfo     = $inviteLogic->getInfoByOtherUserId($userId);

            if( !empty($inviteInfo) && isset($inviteInfo['user_id']) ){

                $user = UserModel::getCoreApiUserInfo($inviteInfo['user_id']);

                $phone = isset($user['phone']) ? $user['phone'] : '';
            }

        }
        $returnPartnerInfo  =   [
            'partner_titie'         => '设置合伙人',
            'partner_phone'         => $phone,
            'tip_list'              => [
                '填写邀请人的手机号，只能填写一次，不可更改。',
                '新用户可在注册之日起14天内填写添加邀请您加入九斗鱼的用户手机号。',
                '过期填写邀请手机号，不能形成绑定关系。'
            ]
        ];

        return $returnPartnerInfo;
    }
    /**
     * @return array
     * @desc 九斗鱼月刊数据
     */
    private function setNewsPaperInfo()
    {
        //todo 月刊url  表sf_newspaper_config http://wx.9douyu.com/zt/newspaper1602.html

        $defaultLink        =   env('WEIXIN_URL_HTTPS')."/zt/newspaper1609";

        $shareImage         =   env('WEIXIN_URL_HTTPS')."/static/images/9yue.png";

        $microLogic         =   new MicroJournalLogic();

        $microInfo          =   $microLogic->getLastMicroByDate();

        if( isset($microInfo['picture_id']) && $microInfo['picture_id'] ){

            $shareImage     =   $microLogic->getPictureById($microInfo['picture_id']);
        }
        $defaultInfo        =   [
            'news_paper_url'=>  isset($microInfo['link']) && $microInfo['link'] ? $microInfo['link'] : $defaultLink,
            'shareInfo'     =>  [
                'share_title' => isset($microInfo['title']) && $microInfo['title'] ? $microInfo['title']: "九斗鱼鱼乐微刊9月" ,
                'share_desc'  => isset($microInfo['content']) && $microInfo['content'] ? $microInfo['content'] :"金秋九月 受益满满",
                'share_url'   => isset($microInfo['link']) && $microInfo['link'] ? $microInfo['link'] : $defaultLink,
                'share_image' => $shareImage,
            ],
        ];

        return $defaultInfo;
    }
}
