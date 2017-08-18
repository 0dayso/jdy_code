<?php
/**
 * Created by sublime text.
 * User: xialili <xia.lili@9douyu.com>
 * Date: 16/10/18
 * Time: 
 */

namespace App\Http\Controllers\Weixin\Activity;


use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\HalloweenLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;


class HalloweenController extends WeixinController
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 页面数据展示
     */
    public function index( Request $request)
    {
        $client         =   RequestSourceLogic::getSource();

        $token          =   $request->input('token','');

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/halloween');

        $activityTime   =   HalloweenLogic::getActivityCycle();

        $projectList    =   HalloweenLogic::getActivityProject();

        $lotteryList    =   HalloweenLogic::getLotteryRecord();

        $timesList      =   HalloweenLogic::getFormatEveryDayPrize();

        $everyDayPrize  =   HalloweenLogic::setEveryDayPrize();

        $activityStatus =   HalloweenLogic::getActivityStatus();

        $viewData       =   [
            'client'        =>  $client,
            'token_status'  =>  $token ? $token : false,
            'userStatus'    =>  $this->getUserId() ? true : false,
            'activityTime'  =>  $activityTime,
            "projectList"   =>  $projectList,
            'lotteryList'   =>  $lotteryList,
            'timeList'      =>  $timesList,
            'everyDayPrize' =>  $everyDayPrize,
            'activityStatus'=>  $activityStatus,
        ];

        return view('wap.activity.halloween.index',$viewData);
    }

    /**
     * @param Request $request
     * @desc 执行抽奖的程序
     */
    public function doLuckDraw( Request $request)
    {
        $userId     =   $this->getUserId();
        
        //时间判断
        $timeStatus =   HalloweenLogic::isCheckLotteryStatus($userId);

        if( $timeStatus['status'] ==false ){

            return $timeStatus;
        }

        //条件判断
        $lotteryStatus  =   HalloweenLogic::isCheckLotteryInvestStatus($userId);

        if( $lotteryStatus['status'] == false){

            return $lotteryStatus;
        }

        $lotteryParam   =   [
            'user_id'   =>  $userId,
            'group_id'  =>  2,
        ];
        $return     =   HalloweenLogic::doLuckDraw( $lotteryParam );

        return $return;
    }
    

    
}