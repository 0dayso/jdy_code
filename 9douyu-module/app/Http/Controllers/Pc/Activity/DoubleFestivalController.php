<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/28
 * Time: 下午7:24
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\DoubleFestivalLogic;
use App\Tools\ToolJump;
use Redirect;

class DoubleFestivalController extends PcController
{

    public function festival()
    {
        $planLine           =   DoubleFestivalLogic::setActivityPlanType();

        if( $planLine == false ){

            return Redirect::to("/activity/festivalTwo");
        }
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/festival');

        $activityProject    =   DoubleFestivalLogic::getActivityProject();

        $activityTime       =   DoubleFestivalLogic::getActivityTime();

        $projectStatus      =   DoubleFestivalLogic::getProjectStatus($activityProject);

        $viewData   =   [
            'projectInfo'   =>  $activityProject,
            'activityTime'  =>  $activityTime,
            'projectStatus' =>  $projectStatus,
        ];

        return view('pc.activity.festival.index',$viewData);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 方案二
     */
    public function festivalTwo()
    {
        $planLine           =   DoubleFestivalLogic::setActivityPlanType();

        if( $planLine == true ){

            return Redirect::to("/activity/festival");
        }
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/festivalTwo');

        $activityProject    =   DoubleFestivalLogic::getActivityProject(false);

        $activityTime       =   DoubleFestivalLogic::getActivityTime();

        $lotteryInfo        =   DoubleFestivalLogic::getLotteryInfo();

        $lotteryList        =   DoubleFestivalLogic::getYesterdayLotteryInfo();
        
        $viewData   =   [
            'projectInfo'   =>  $activityProject,
            'activityTime'  =>  $activityTime,
            'lotteryInfo'   =>  $lotteryInfo,
            'lotteryList'   =>  $lotteryList,
        ];

        return view('pc.activity.festival.schemeTwo',$viewData);
    }
}
