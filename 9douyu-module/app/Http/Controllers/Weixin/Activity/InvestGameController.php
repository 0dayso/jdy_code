<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/12
 * Time: 下午4:13
 */

namespace App\Http\Controllers\Weixin\Activity;


use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\InvestGameForthLogic;
use App\Http\Logics\Activity\InvestGameLogic;
use App\Http\Logics\Activity\InvestMatchLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use App\Tools\ToolTime;
use Illuminate\Http\Request;
use Redirect;

class InvestGameController extends WeixinController
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 投资PK第一期
     */
    public function firstPhase( Request $request)
    {
        $client         =   RequestSourceLogic::getSource();

        $version        =   $request->input('version','');

        $token          =   $request->input('token','');

        $ruleStatus     =   InvestGameLogic::getInvestGameRule();

        if( $ruleStatus == true ){
            //跳转到规则一的页面
            return Redirect::to("/activity/investment/secondPhase?client=".$client."&token=".$token."&version=".$version);
        }
        //活动时间
        $activityTime   =   InvestGameLogic::getActivityTime();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/investpk/firstphase');

        //活动项目
        $activityProject=   InvestGameLogic::getActivityProject();

        //当天中奖数据
        $nowDayLottery  =   InvestGameLogic::getActivityStatisticsNowDate(5);

        //每一天中奖数据
        $everyDayLottery=   InvestGameLogic::getEveryDayLotteryStatistics(3);

        //活动状态
        $activityStatus =   InvestGameLogic::setActivityStatus();

        $activityLottery=   InvestGameLogic::getActivityLottery();

        //跳秒
        $lastSecond     =   InvestGameLogic::doMatTimeToSecond();

        $viewData       =   [
            'activityTime'  =>  $activityTime,
            'projectList'   =>  $activityProject,
            'nowDay'        =>  $nowDayLottery,
            'everyDay'      =>  $everyDayLottery,
            'client'        =>  $client,
            'lotteryList'   =>  $activityLottery,
            'activityStatus'=>  $activityStatus,
            'lastSecond'    =>  $lastSecond,
            'lastTime'      =>  ToolTime::getUnixTime(date("Y-m-d"),'end'),
        ];

        return view('wap.activity.investGame.firstPhase',$viewData);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 投资PK第二期
     */
    public function secondPhase( Request $request)
    {
        //请求来源
        $client         =   RequestSourceLogic::getSource();

        $version        =   $request->input('version','');

        $token          =   $request->input('token','');

        $ruleStatus     =   InvestGameLogic::getInvestGameRule();

        $userId         =   $this->getUserId();

        if( $ruleStatus == false ){
            //跳转到规则一的页面
            return Redirect::to("/activity/investpk/firstphase?client=".$client."&token=".$token."&version=".$version);
        }

        if( $client == 'android' && $userId ){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);
        }
        //活动时间
        $activityTime   =   InvestGameLogic::getActivityTime();
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/investment/secondPhase');
        //活动项目
        $activityProject=   InvestGameLogic::getActivityProject();
        //当天排名
        $rankingList    =   InvestGameLogic::getRealTimeRanking();
        //活动状态
        $activityLottery=   InvestGameLogic::getLotteryMessage();
        //模板数据
        $viewData       =   [
            'activityTime'  =>  $activityTime,
            'projectList'   =>  $activityProject,
            'ranking'       =>  $rankingList,
            'client'        =>  $client,
            'lotteryList'   =>  $activityLottery,
            'userStatus'    =>  (!empty($userId)||$userId!=0) ? true : false,
            'version'       =>  $version,
            'actToken'      =>  InvestGameLogic::getActToken(),
        ];

        return view('wap.activity.investGame.secondPhase',$viewData);
    }

    /**
     * 投资pk 第三季
     * 投资活动
     */
    public function thirdPhase( Request $request)
    {
        $client         =   RequestSourceLogic::getSource();

        $token          =   $request->input('token','');

        //活动时间
        $activityTime   =   InvestMatchLogic::getActivityTime();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/investment/thirdPhase');

        //活动项目
        $activityProject=   InvestMatchLogic::getActivityProject();

        //当天中奖数据
        $nowDayLottery  =   InvestMatchLogic::getActivityStatisticsNowDate();

        //每一天中奖数据
        $everyDayLottery=   InvestMatchLogic::getEveryDayLotteryStatistics();

        //活动状态
        $activityStatus =   InvestMatchLogic::setActivityStatus();

        $activityLottery=   InvestMatchLogic::getLotteryMessage();

        //跳秒
        $lastSecond     =   InvestMatchLogic::doMatTimeToSecond();

        $viewData       =   [
            'activityTime'  =>  $activityTime,
            'projectList'   =>  $activityProject,
            'nowDay'        =>  $nowDayLottery,
            'everyDay'      =>  $everyDayLottery,
            'client'        =>  $client,
            'lotteryList'   =>  $activityLottery,
            'activityStatus'=>  $activityStatus,
            'lastSecond'    =>  $lastSecond,
            'lastTime'      =>  ToolTime::getUnixTime(date("Y-m-d"),'end'),
            'version'       =>  $request->input('version'),
            'actToken'      =>  InvestMatchLogic::setActToken(),
        ];

        return view('wap.activity.investGame.fivePhase',$viewData);
    }
    /**
     * 投资pk 第四季
     * 投资活动
     */
    public function forthPhase(Request $request)
    {
        $client         =   RequestSourceLogic::getSource();

        $token          =   $request->input('token','');
        //活动时间
        $activityTime   =   InvestGameForthLogic::getActivityTime();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/investMatch');

        $userId         =   $this->getUserId();

        if( $client == 'android' && $userId ){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);
        }

        //活动项目
        $activityProject=   InvestGameForthLogic::getActivityProject();

        //当天中奖数据
        $nowDayLottery  =   InvestGameForthLogic::getActivityStatisticsNowDate();

        //活动状态
        $activityStatus =   InvestGameForthLogic::setActivityStatus();

        $activityLottery=   InvestGameForthLogic::getLotteryMessage();

        //跳秒
        $lastSecond     =   InvestGameForthLogic::doMatTimeToSecond();

        $viewData       =   [
            'activityTime'  =>  $activityTime,
            'projectList'   =>  $activityProject,
            'nowDay'        =>  $nowDayLottery,
            'lotteryList'   =>  $activityLottery,
            'activityStatus'=>  $activityStatus,
            'lastSecond'    =>  $lastSecond,
            'client'        =>  $client,
            'lineToNumber'  =>  InvestGameForthLogic::getProjectNote(),
            'lastTime'      =>  ToolTime::getUnixTime(date("Y-m-d"),'end'),
        ];

        return view('wap.activity.investGame.forthPhase',$viewData);
    }
}
