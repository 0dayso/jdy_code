<?php

/**  *********************************** 秒杀活动PC端***********************************************
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/13
 * Time: 下午2:06
 */

namespace App\Http\Controllers\Pc\Activity;

use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\LaborDayLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class LabourDayController extends PcController
{


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 春节活动
     */
    public function index()
    {
        $userId         =   $this->getUserId();
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/LabourDay');

        $logic          =   new LaborDayLogic();

        $projectList    =   $logic->getActivityProject();

        $activityTime   =   $logic->getActivityTime();

        $signTime       =   $logic->setSignTime();

        $lotteryStatus  =   $logic->setLotteryStatus($userId);

        $signStatus     =   $logic->setSignStatus($userId);

        $signDayList    =   $logic->getSignStatistics($userId);

        $exchangeStatus =   $logic->setExchangeStatus($userId);

        $minInvest      =   $logic->getMinInvest();
        $viewData   =   [
            'minInvest'     =>  $minInvest,
            'projectList'   =>  $projectList,       //活动项目
            'activityTime'  =>  $activityTime,      //活动时间
            'signTime'      =>  $signTime,          //签到时间
            'lotteryStatus' =>  $lotteryStatus,     //活动状态
            'signStatus'    =>  $signStatus,        //签到状态
            'signDayList'   =>  $signDayList ,      //签到文件
            'exchangeStatus'=>  $exchangeStatus,    //兑换红包的状态
        ];

        //加载数据
        return view('pc.activity.LabourDay.index',$viewData);
    }
    /**
     * @param Request $request
     * @return array
     * @desc 签到
     */
    public function doSignIn(Request $request)
    {
        $userId     =   $this->getUserId();

        $logic      =   new LaborDayLogic();

        $signStatus =   $logic->setSignStatus($userId);

        if($signStatus['status'] == false){

            return $signStatus;
        }

        return $logic->doSignIn($userId);

    }

    /**
     * @param Request $request
     * @return array
     * @desc 领取红包
     */
    public function doExchange(Request $request)
    {
        $userId         =   $this->getUserId();

        $logic          =   new LaborDayLogic();

        $exchangeStatus =   $logic->setExchangeStatus($userId);

        if($exchangeStatus['status'] == false){

            return $exchangeStatus;
        }

        return $logic->doExchange($userId);
    }
    /**
     * @param Request $request
     * @return array|bool
     * @desc 执行抽奖的过程
     */
    public function doLottery(Request $request)
    {
        $userId     =   $this->getUserId();

        $logic      =   new LaborDayLogic();

        //条件判断
        $lotteryStatus  =   $logic->setLotteryStatus($userId);

        if( $lotteryStatus['status'] == false){

            return $lotteryStatus;
        }

        return $logic->doLuckDraw( $userId );
    }

}
