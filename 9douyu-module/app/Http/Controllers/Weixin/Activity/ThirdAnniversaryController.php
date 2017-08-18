<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午8:14
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\ActivityBaseController;
//use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\AnniversarySecondLogic;
use App\Http\Logics\Activity\GradeLotteryLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\Activity\AnniversaryLogic;
use App\Http\Logics\Activity\AnniversaryThirdLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class ThirdAnniversaryController extends ActivityBaseController
{

    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 多奖池抽奖活动
     */
    public function firstPart(Request $request)
    {
        ToolJump::setLoginUrl('/thirdAnniversary/firstPart');

        $userId         =   $this->getUserId();

        $logic          =   new GradeLotteryLogic();

        $activityTime   =   $logic->getActivityTime();

        $anniversaryLogic   = new AnniversaryLogic();

        $viewData   =   [
            'activityTime'  =>  $activityTime,
            //'lotteryList'   =>  $lotteryList,
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
            'client'        =>  RequestSourceLogic::getSource() ,
            'version'       =>  $request->input('version'),
            'actToken'      =>  $anniversaryLogic->getActToken(),
            'package'       =>  $this->setChannelInfo()['package'],
        ];

        return view('wap.activity.thirdanniversary.firstPart',$viewData);
    }
    /*
    * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    * @desc 周年庆活动第二趴
    */
    public function secondPart(Request $request)
    {
        ToolJump::setLoginUrl('/thirdAnniversary/secondPart');

        $logic = new AnniversarySecondLogic();

        $anniversaryLogic   = new AnniversaryLogic();

        $userId         =   $this->getUserId();

        $viewData = [
            'activityTime' =>   $logic->setTime(),
            'client'       =>   RequestSourceLogic::getSource(),
            'version'      =>   $request->input('version'),
            'actToken'     =>   $anniversaryLogic->getActToken(),
            'package'       =>  $this->setChannelInfo()['package'],
            'jrttChanleValue'=> $anniversaryLogic->getJrttStatisitis($request->input('channel')),
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
        ];
        return view('wap.activity.thirdanniversary.secondPart', $viewData);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 周年庆第三趴
     */
    public function thirdPart( Request $request)
    {
        ToolJump::setLoginUrl('/thirdAnniversary/thirdPart');

        $logic      =   new AnniversaryThirdLogic();

        $anniversaryLogic   = new AnniversaryLogic();

        $userId         =   $this->getUserId();

        $viewData   =   [
            'activityTime'   =>   $logic->getActivityTime(),
            'client'         =>   RequestSourceLogic::getSource(),
            'version'        =>   $request->input('version'),
            'actToken'       =>   $anniversaryLogic->getActToken(),
            'package'       =>  $this->setChannelInfo()['package'],
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
        ];

        return view('wap.activity.thirdanniversary.thirdPart',$viewData);
    }
    /**
     * @param Request $request
     * @desc 执行抽奖的程序
     */
    public function doLuckDraw( Request $request)
    {
        $userId     =   $this->getUserId();

        $grade      =   $request->input('grade',1);

        $logic     =   new GradeLotteryLogic();

        $timeCondition = $logic->getTimeCondition($userId);

        if($timeCondition['status'] == false) {

            return $timeCondition;
        }

        $lotteryCondition=  $logic->getUserCondition($userId , $grade);

        if($lotteryCondition['status'] == false){

            return $lotteryCondition;
        }

        return $logic->doLuckDraw($userId,$lotteryCondition['data']['lottery_group']);
    }

    /**
     * @return array
     * @desc 获取用户抽奖的信息
     */
    public function getLotteryConfig()
    {
        $userId     =   $this->getUserId() ;

        $logic      =   new GradeLotteryLogic();

        return$logic->setLotteryMessage($userId);
    }

    /*
     * @param Request $request
     * @return investSumTotal
     */
    public function getInvestPercentage(Request $request)
    {
        $logic  =   new AnniversaryLogic();

        return $logic->getInvestPercentage();
    }

    /**
     * @return array | lottery && record
     * @desc 伴手礼的数据
     */
    public function getLottery(Request $request)
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
     * @param Request $request
     * @return array
     * @desc 红包雨的抽奖状态
     */
    public function getBonusRainStatus(Request $request)
    {
        $userId         =   $this -> getUserId() ;

        $logic          =   new AnniversaryThirdLogic();

        $timeCondition  =   $logic -> getTimeCondition( $userId );

        if( $timeCondition['status'] == false ) {

            return $timeCondition;
        }

        $condition      =  $logic -> getUserCondition( $userId );

        if( $condition['status'] == false){

            return $condition;
        }

        return  $condition;
    }
    /**
     * @param Request $request
     * @return array
     * @desc 抽取红包雨,第三趴的红包雨
     */
    public function doLottery(Request $request)
    {

        $userId         =   $this -> getUserId() ;

        $logic          =   new AnniversaryThirdLogic();

        $timeCondition  =   $logic -> getTimeCondition( $userId );

        if( $timeCondition['status'] == false ) {

            return $timeCondition;
        }

        $condition      =  $logic -> getUserCondition( $userId );

        if( $condition['status'] == false){

            return $condition;
        }

        return $logic -> doLuckDraw( $userId );
    }

    /**
     * @return array lottery && reocrd
     * @desc 返货中奖的数据和每天展示的奖品
     */
    public function getThirdLottery( Request $request )
    {
        $logic          =   new AnniversaryThirdLogic();

        return [
            'lottery'   =>  $logic -> getEveryDayLottery(),
            'record'    =>  $logic -> getCouponWinningList(),
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
