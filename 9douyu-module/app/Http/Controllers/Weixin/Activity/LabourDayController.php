<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 2017/2/21
 * Time: 下午2:37
 */

namespace App\Http\Controllers\WeiXin\Activity;


use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\CouponLogic;
use App\Http\Logics\Activity\LaborDayLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class LabourDayController extends WeixinController
{

    public function index(Request $request)
    {
        $token              =   $request->input('token');

        $appVersion         =   $request->input('version');

        $client             =   RequestSourceLogic::getSource();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/LabourDay');

        $userId         =   $this->getUserId();

        if( $client == 'android' && $userId ){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);
        }

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
            'lotteryStatus' =>  $lotteryStatus,      //活动状态
            'signStatus'    =>  $signStatus,        //签到状态
            'signDayList'   =>  $signDayList ,      //签到文件
            'exchangeStatus'=>  $exchangeStatus,    //兑换红包的状态
            'client'        =>  $client,
            'token'         =>  $token,
            'appVersion'    =>  $appVersion,
        ];
        //加载数据
        return view('wap.activity.laborDay.index',$viewData);

    }
    /**
     * @param Request $request
     * @return array|bool
     * @desc 执行抽奖的过程
     */
    public function doLottery(Request $request)
    {
        $userId         =   $this->getUserId();

        $logic          =   new LaborDayLogic();

        //条件判断
        $lotteryStatus  =   $logic->setLotteryStatus($userId);

        if( $lotteryStatus['status'] == false){

            return $lotteryStatus;
        }

        return $logic->doLuckDraw( $userId );
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

        $logic          =   new LaborDayLogic();

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

}
