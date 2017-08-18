<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/12
 * Time: 下午4:13
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\DoubleFestivalLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;
use Redirect;


class DoubleFestivalController extends WeixinController{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 双诞活动方案一
     */
    public function festival( Request $request)
    {
        $planLine           =   DoubleFestivalLogic::setActivityPlanType();

        $token              =   strtolower($request->input('token'));

        $appVersion         =   $request->input('version');

        $client             =   RequestSourceLogic::getSource();

        if( $planLine == false ){

            return Redirect::to("/activity/festivalTwo?client=".$client."&token=".$token."&version=".$appVersion);
        }

        $userId             =   $this->getUserId();

        if( $client == 'android' && $userId ){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);

        }

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/festival');

        $activityProject    =   DoubleFestivalLogic::getActivityProject();

        $activityTime       =   DoubleFestivalLogic::getActivityTime();

        $projectStatus      =   DoubleFestivalLogic::getProjectStatus($activityProject);

        $lotteryStatus      =   DoubleFestivalLogic::isCheckLotteryStatus($userId);
        
        $isVersionTrue      =   DoubleFestivalLogic::isNotUserAppVersion($appVersion);

        $viewData   =   [
            'projectInfo'   =>  $activityProject,
            'client'        =>  $client,
            'token'         =>  $token,
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
            'activityTime'  =>  $activityTime,
            'projectStatus' =>  $projectStatus,
            'lotteryStatus' =>  $lotteryStatus,
            'version'       =>  $isVersionTrue,
        ];

        return view('wap.activity.festival.index',$viewData);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 双诞活动方案二
     */
    public function festivalTwo(Request $request)
    {
        $planLine           =   DoubleFestivalLogic::setActivityPlanType();

        $token              =   $request->input('token');

        $appVersion         =   $request->input('version');

        $client             =   RequestSourceLogic::getSource();

        if( $planLine == true ){

            return Redirect::to("/activity/festival?client=".$client."&token=".$token."&version=".$appVersion);
        }

        $userId             =   $this->getUserId();

        if( $client == 'android' && $userId ){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);

        }
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/festivalTwo');

        $activityProject    =   DoubleFestivalLogic::getActivityProject(false);

        $activityTime       =   DoubleFestivalLogic::getActivityTime();

        $lotteryInfo        =   DoubleFestivalLogic::getLotteryInfo();

        $lotteryStatus      =   DoubleFestivalLogic::isCheckLotteryStatus($userId);

        $isVersionTrue      =   DoubleFestivalLogic::isNotUserAppVersion($appVersion);

        $lotteryList        =   DoubleFestivalLogic::getYesterdayLotteryInfo();
        
        $viewData   =   [
            'projectInfo'   =>  $activityProject,
            'activityTime'  =>  $activityTime,
            'client'        =>  $client,
            'token'         =>  $token,
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
            'lotteryInfo'   =>  $lotteryInfo,
            'lotteryStatus' =>  $lotteryStatus,
            'version'       =>  $isVersionTrue,
            'lotteryList'   =>  $lotteryList,
        ];

        return view('wap.activity.festival.schemeTwo',$viewData);
    }

    /**
     * @return array
     * @desc 执行抽奖的程序
     */
    public function doLuckDraw()
    {
        $userId     =   $this->getUserId();

        $logic      =   new DoubleFestivalLogic();

        //时间判断
        $lotteryStatus =   $logic->isCheckLotteryStatus($userId);

        if( $lotteryStatus['status'] ==false ){

            return $lotteryStatus;
        }

        $lotteryParam   =   ['user_id'   =>  $userId,'group_id'  =>  3,];

        $return         =   $logic->doLuckDraw( $lotteryParam );

        return $return;

    }
}
