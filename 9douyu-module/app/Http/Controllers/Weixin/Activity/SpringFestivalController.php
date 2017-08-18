<?php
/**
 * Created by PhpStorm.
 * User: tianxiaoyan
 * Date: 16/12/27
 * Time: 下午5:00
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\SpringFestivalLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class SpringFestivalController extends WeixinController{

    public function index( Request $request)
    {

        $token              =   $request->input('token');

        $appVersion         =   $request->input('version');

        $client             =   RequestSourceLogic::getSource();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/springFestival');

        $userId         =   $this->getUserId();

        $projectList    =   SpringFestivalLogic::getActivityProject();

        $activityTime   =   SpringFestivalLogic::getActivityTime();

        $signTime       =   SpringFestivalLogic::setSignTime();

        $lotteryStatus  =   SpringFestivalLogic::setLotteryStatus($userId);

        $signStatus     =   SpringFestivalLogic::setSignStatus($userId);

        $signDayList    =   SpringFestivalLogic::getSignStatistics($userId);

        $exchangeStatus =   SpringFestivalLogic::setExchangeStatus($userId);

        $canUsedBonus   =   SpringFestivalLogic::isCanUsedBonus();

        $viewData   =   [
            'projectList'   =>  $projectList,       //活动项目
            'activityTime'  =>  $activityTime,      //活动时间
            'signTime'      =>  $signTime,          //签到时间
            'lotteryStatus' =>  $lotteryStatus,      //活动状态
            'signStatus'    =>  $signStatus,        //签到状态
            'signDayList'   =>  $signDayList ,      //签到文件
            'exchangeStatus'=>  $exchangeStatus,    //兑换红包的状态
            'client'        =>  $client,
            'token'         =>  $token,
            'appVersion'    =>  $appVersion,
            'canUsedBonus'  =>  $canUsedBonus,
        ];
        //dump($viewData);
        return view('wap.activity.springFestival.springFestival',$viewData);
    }

    /**
     * @param Request $request
     * @return array|bool
     * @desc 执行抽奖的过程
     */
    public function doLotterySpring(Request $request)
    {
        $userId     =   $this->getUserId();

        //条件判断
        $lotteryStatus  =   SpringFestivalLogic::setLotteryStatus($userId);

        if( $lotteryStatus['status'] == false){

            return $lotteryStatus;
        }

        $lotteryParam   =   [
            'user_id'   =>  $userId,
            'group_id'  =>  4,
        ];
        $return     =   SpringFestivalLogic::doLuckDraw( $lotteryParam );

        return $return;
    }
    /**
     *
     * @param Request $request
     * @return array
     * @desc 签到
     */
    public function doSignIn(Request $request)
    {
        $userId     =   $this->getUserId();

        $signStatus =   SpringFestivalLogic::setSignStatus($userId);

        if($signStatus['status'] == false){

            return $signStatus;
        }

        return SpringFestivalLogic::doSignIn(['user_id'=>$userId]);

    }

    /**
     * @param Request $request
     * @return array
     * @desc 领取红包
     */
    public function doExchange(Request $request)
    {
        $userId         =   $this->getUserId();

        $exchangeStatus =   SpringFestivalLogic::setExchangeStatus($userId);

        if($exchangeStatus['status'] == false){

            return $exchangeStatus;
        }

        return SpringFestivalLogic::doExchange($userId);
    }
}