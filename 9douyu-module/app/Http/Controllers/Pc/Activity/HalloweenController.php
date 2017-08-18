<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/28
 * Time: 下午7:24
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\HalloweenLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class HalloweenController extends PcController
{
    
    public function index()
    {

        $client         =   RequestSourceLogic::getSource();

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
            'userStatus'    =>  $this->getUserId() ? true : false,
            'activityTime'  =>  $activityTime,
            "projectList"   =>  $projectList,
            'lotteryList'   =>  $lotteryList,
            'timeList'      =>  $timesList,
            'everyDayPrize' =>  $everyDayPrize,
            'activityStatus'=>  $activityStatus,
        ];

        return view('pc.activity.halloween.halloween',$viewData);
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