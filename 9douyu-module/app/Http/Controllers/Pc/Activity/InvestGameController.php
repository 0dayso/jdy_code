<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/12
 * Time: 下午4:17
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Activity\InvestGameForthLogic;
use App\Tools\ToolTime;
use Illuminate\Http\Request;
use App\Http\Logics\Activity\InvestGameLogic;
use App\Http\Logics\Activity\InvestMatchLogic;
use App\Tools\ToolJump;
use Redirect;

class InvestGameController extends PcController
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 投资PK第一期
     */
    public function firstPhase( Request $request)
    {

        $ruleStatus     =   InvestGameLogic::getInvestGameRule();

        if( $ruleStatus == true ){
            //跳转到规则一的页面
            return Redirect::to("/activity/investment/secondPhase");
        }

        //活动时间
        $activityTime   =   InvestGameLogic::getActivityTime();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/investpk/firstphase');

        //活动项目
        $activityProject=   InvestGameLogic::getActivityProject();

        //当天中奖数据
        $nowDayLottery  =   InvestGameLogic::getActivityStatisticsNowDate();

        //每一天中奖数据
        $everyDayLottery=   InvestGameLogic::getEveryDayLotteryStatistics();

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
            'lotteryList'   =>  $activityLottery,
            'activityStatus'=>  $activityStatus,
            'lastSecond'    =>  $lastSecond,
            'lastTime'      =>  ToolTime::getUnixTime(date("Y-m-d"),'end'),
        ];

        return view("pc.activity.investGame.firstPhase",$viewData);

    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 投资PK第一期
     */
    public function secondPhase( Request $request)
    {
        $ruleStatus     =   InvestGameLogic::getInvestGameRule();

        if( $ruleStatus == false ){
            //跳转到规则一的页面
            return Redirect::to("/activity/investpk/firstphase");
        }
        //活动时间
        $activityTime   =   InvestGameLogic::getActivityTime();
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/investment/secondPhase');
        //活动项目
        $activityProject=   InvestGameLogic::getActivityProject();
        //实时排名
        $rankingList    =   InvestGameLogic::getRealTimeRanking();
        //奖品的数据
        $activityLottery=   InvestGameLogic::getLotteryMessage();

        $userId         =   $this->getUserId();
        //模板数据
        $viewData       =   [
            'activityTime'  =>  $activityTime,
            'projectList'   =>  $activityProject,
            'ranking'       =>  $rankingList,
            'lotteryList'   =>  $activityLottery,
            'userStatus'    =>  (!empty($userId)||$userId!=0) ? true : false,
        ];

        return view("pc.activity.investGame.secondPhase",$viewData);
    }


    public function thirdPhase( Request $request)
    {
        //活动时间
        $activityTime   =   InvestMatchLogic::getActivityTime();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/investMatch');

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
            'lotteryList'   =>  $activityLottery,
            'activityStatus'=>  $activityStatus,
            'lastSecond'    =>  $lastSecond,
            'lastTime'      =>  ToolTime::getUnixTime(date("Y-m-d"),'end'),
            'actToken'      =>  InvestMatchLogic::setActToken(),
        ];

        return view("pc.activity.investGame.thirdPhase",$viewData);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 投资PK第四期
     */
    public function forthPhase()
    {
        //活动时间
        $activityTime   =   InvestGameForthLogic::getActivityTime();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/investMatch');

        //活动项目
        $activityProject=   InvestGameForthLogic::getActivityProject();

        //当天中奖数据
        $nowDayLottery  =   InvestGameForthLogic::getActivityStatisticsNowDate();

        //每一天中奖数据
        //$everyDayLottery=   InvestGameForthLogic::getEveryDayLotteryStatistics();

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
            'lineToNumber'  =>  InvestGameForthLogic::getProjectNote(),
            'lastTime'      =>  ToolTime::getUnixTime(date("Y-m-d"),'end'),
        ];

        //加载数据
        return view('pc.activity.investGame.fourPhase',$viewData);
    }
}
