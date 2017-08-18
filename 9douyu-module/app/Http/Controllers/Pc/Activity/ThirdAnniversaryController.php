<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午8:14
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\AnniversaryThirdLogic;
use App\Http\Logics\Activity\GradeLotteryLogic;
use App\Http\Logics\Activity\AnniversaryLogic;
use App\Http\Logics\Activity\AnniversarySecondLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class ThirdAnniversaryController extends PcController
{

    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 多奖池抽奖活动
     */
    public function firstPart()
    {
        ToolJump::setLoginUrl('/thirdAnniversary/firstPart');

        $userId         =   $this->getUserId() ;

        $logic          =   new GradeLotteryLogic();

        $activityTime   =   $logic->getActivityTime();

        $anniversaryLogic   = new AnniversaryLogic();

        $viewData   =   [
            'activityTime'  =>  $activityTime,
            //'lotteryList'   =>  $lotteryList,
            'userStatus'    => ( !empty($userId) || $userId != 0 ) ? true : false,
            'actToken'     =>   $anniversaryLogic->getActToken(),
        ];

        return view('pc.activity.thirdAnniversary.firstPart',$viewData);
    }
    /*
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 周年庆活动第二趴
     */
    public function secondPart()
    {
        ToolJump::setLoginUrl('/thirdAnniversary/secondPart');

        $logic      =   new AnniversarySecondLogic();

        $anniversaryLogic   = new AnniversaryLogic();

        $viewData   =   [
            'activityTime'   =>   $logic->setTime(),
            'actToken'     =>   $anniversaryLogic->getActToken(),
        ];

        return view('pc.activity.thirdAnniversary.secondPart',$viewData);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 周年第三趴
     */
    public function thirdPart()
    {
        ToolJump::setLoginUrl('/thirdAnniversary/thirdPart');

        $logic      =   new AnniversaryThirdLogic();

        $anniversaryLogic   = new AnniversaryLogic();

        $viewData   =   [
            'activityTime'   =>   $logic->getActivityTime(),
            'actToken'     =>   $anniversaryLogic->getActToken(),
        ];

        return view('pc.activity.thirdAnniversary.thirdPart',$viewData);
    }
    /**
     * @return array
     * @desc 获取用户抽奖的信息
     */
    public function getLotteryConfig()
    {
        $userId     =   $this->getUserId() ;

        $logic      =   new GradeLotteryLogic();

        return$logic->setLotteryMessage( $userId );
    }
    /**
     * @param Request $request
     * @desc 执行抽奖的程序 第一趴抽奖的的程序
     */
    public function doLuckDraw( Request $request )
    {
        $userId         =   $this->getUserId() ;

        $grade          =   $request->input('grade',1 );

        $logic          =   new GradeLotteryLogic();

        $timeCondition  =   $logic->getTimeCondition( $userId );

        if( $timeCondition['status'] == false ) {

            return $timeCondition;
        }

        $lotteryCondition=  $logic->getUserCondition( $userId , $grade );

        if( $lotteryCondition['status'] == false ) {

            return $lotteryCondition;
        }

       return $logic->doLuckDraw( $userId , $lotteryCondition['data']['lottery_group'] );
    }

    /*
     * @param Request $request
     * @return investSumTotal
     */
    public function getInvestPercentage( Request $request )
    {
        $logic  =   new AnniversaryLogic();

        return $logic->getInvestPercentage();
    }

    /**
     * @return array | lottery && record
     * @desc 伴手礼的数据
     */
    public function getLottery( Request $request )
    {
        $logic  =   new AnniversaryLogic();

        return [
            'lottery'   =>  $logic->getLottery(),
            'record'    =>  $logic->getWinnerList(),
            ];
    }

    /*
     * @param Request $request
     * @return array project
     * @desc 活动页展示的项目信息
     */
    public function getProject(Request $request)
    {
        $logic  =   new AnniversaryLogic();

        return $logic->getShowProject();
    }

    /**
     * @return array lottery && reocrd
     * @desc 返货中奖的数据和每天展示的奖品
     */
    public function getThirdLottery( Request $request )
    {
        $logic = new AnniversaryThirdLogic();

        return [
            'lottery'   => $logic->getEveryDayLottery(),
            'record'    => $logic->getCouponWinningList(),
        ];
    }
    /**
     * @return array
     * @desc 周年庆第二趴的排名数据
     */
    public function getSecondRanking()
    {
        $logic  =   new AnniversarySecondLogic();

        return [
            'inviteList'     =>  $logic->getInviteInvestList(),
            'partnerList'    =>  $logic->getPartnerInvestmentRanking()
        ];
    }
}
