<?php

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/3
 * Time: 上午10:42
 *
 */

namespace App\Http\Controllers\Pc\Project;

use App\Http\Controllers\Pc\PcController;

use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Logics\User\UserInfoLogic;
use App\Http\Models\Invest\InvestModel;
use App\Http\Logics\Invest\CurrentLogic;
use \App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Article\ArticleLogic;
use App\Tools\ToolJump;
use Illuminate\Support\Facades\Redirect;

/**
 * 项目详情
 * Class ProjectDetailController
 * @package App\Http\Controllers\Pc\Project
 */
class ProjectDetailController extends PcController
{
    //零钱计划详情ID
    const SMALL_CHANGE_PLAN_ARTICLE_DETAIL = 1422;

    //零钱计划问题ID
    const SMALL_CHANGE_PLAN_ARTICLE_QUES   = 1423;
    /**
     * 项目详情逻辑类
     * @var ProjectDetailLogic|null
     */
    protected $ProjectDetailLogic = null;


    public function appendConstruct(){
        $this->ProjectDetailLogic = new ProjectDetailLogic;
    }

    /**
     * 项目详情页面
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get( $id ){

        //项目信息
        $project         = $this->ProjectDetailLogic->get($id);
        if(empty($project) || $project['status'] < ProjectDb::STATUS_UNPUBLISH)
            return Redirect::to("/project/index");

        //用户信息
        $userId          = $this->getUserId();
        $user            = $this->ProjectDetailLogic->getUser($userId);
        //红包信息
        $userBonusLogic  = new UserBonusLogic();
        $bonus           = $userBonusLogic->getAppUserUsableBonus($userId,$id,'pc');
        //投资列表
        $investList      = $this->ProjectDetailLogic->getInvestList($id);
        //投资概况
        $investBrief     = $this->ProjectDetailLogic->getInvestBrief($id);
        //单比投资排行榜
        $maxInvestTop    = $this->ProjectDetailLogic->getMaxInvestTop($id);
        //投资动态
        $investNew       = InvestModel::getInvestNew();
        //项目还款计划
        $refundPlan      = $this->ProjectDetailLogic->getRefundPlan($id);
        //当前项目可投状态
        $status          = ProjectLogic::getProjectStatus($user,$project);

        $refundType      = [
            'baseInterest'  =>  ProjectDb::REFUND_TYPE_BASE_INTEREST,
            'onlyInterest'  =>  ProjectDb::REFUND_TYPE_ONLY_INTEREST,
            'firstInterest'  =>  ProjectDb::REFUND_TYPE_FIRST_INTEREST,
            'equalInterest'  =>  ProjectDb::REFUND_TYPE_EQUAL_INTEREST,
        ];

        // 债权
        $creditDetail = $this->ProjectDetailLogic->getCreditBrowserShowData($id, $this->getUserId());
        //解析债券中存在的人或者公司的信息
        $creditDetail['companyView']   =   $this->ProjectDetailLogic->doFormatCreditLoadUser($creditDetail['companyView']);
        \Log::info(__METHOD__, [$creditDetail]);

        //详情广告图
        $ad = AdLogic::getUseAbleListByPositionId(17);

        $jsxOneMaxId = SystemConfigLogic::getConfig('JSX_ONE_MAX_ID');

        $user['assessment'] =   '';
        if( $userId !=0 || !empty($userId) ) {
            //检测用户是否进行了风险评估
            $userInfoLogic   = new UserInfoLogic();
            $user['assessment']=  $userInfoLogic->getAssessmentType($userId);
        };
        $assign          = [
            'project'      => $project,
            'user'         => $user,
            'bonus'        => $bonus['data']['list'],
            'investList'   => $investList,
            'investBrief'  => $investBrief,
            'maxInvestTop' => $maxInvestTop,
            'investNew'    => $investNew,
            'refundPlan'   => $refundPlan,
            'refundType'   => $refundType,
            'status'       => $status,
            'msg'          => session('msg') ? session('msg') : '',
            'creditDetail' => $creditDetail,
            'ad'           => $ad,
            'jsxOneMaxId'  => $jsxOneMaxId?$jsxOneMaxId:0,
        ];
        if(!$userId)
            ToolJump::setLoginUrl('/project/detail/' . $id);

        return view('pc.invest.project.detail', $assign);
    }


    /**
     * 零钱计划项目投资详情页
     */
    public function getCurrent(){

        $isLogin        = $this->checkLogin();

        $from           = RequestSourceLogic::getSource();
        $logic          = new CurrentLogic();
        $userId = $this->getUserId();

        $viewData       = $logic->projectDetail($userId,$from);

        $userInfo       = $isLogin ? $this->getUser() : [];

        //获取用户状态
        $userStatus     = UserLogic::getUserAuthStatus($userInfo);

        $assign             = $viewData['data'];
        $assign['showStatus'] = $userStatus;
        //dd($assign);
        if(!$userId)
            ToolJump::setLoginUrl('/project/current/detail');

        //零钱计划详情 & 常见问题
        $articleLogic    = new ArticleLogic();
        $articlePlan     = $articleLogic->getById(self::SMALL_CHANGE_PLAN_ARTICLE_DETAIL);
        $articleQues     = $articleLogic->getById(self::SMALL_CHANGE_PLAN_ARTICLE_QUES);
        $footerAtr = array();
        if(isset($articlePlan) && !empty($articlePlan['content'])){
            $plan  = html_entity_decode($articlePlan['content']);
            $footerAtr['plan']   = $plan;
        }
        if(isset($articleQues) && !empty($articleQues['content'])){
            $ques  = html_entity_decode($articleQues['content']);
            $footerAtr['ques']   = $ques;
        }
        $assign['assessment']=  '';
        if( $isLogin ) {
            $userInfoLogic  =   new UserInfoLogic();
            $assign['assessment'] =   $userInfoLogic -> getAssessmentType($userId);
        }
        $assign['footerAtr'] = $footerAtr;
        return view('pc.invest.current.detail',$assign);

    }

}
