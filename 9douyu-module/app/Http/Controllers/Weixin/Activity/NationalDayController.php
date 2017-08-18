<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/26
 * Time: 下午4:11
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Activity\NationalDayLogic;

use App\Http\Logics\Activity\ActivitySignLogic;

use App\Http\Logics\RequestSourceLogic;
use App\Lang\LangModel;
use App\Tools\ToolJump;
use Illuminate\Http\Request;
use Cache;

class NationalDayController extends WeixinController
{
    public function __construct()
    {
        parent::__construct();
        $this->checkLogin(false);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 国庆活动的页面
     */

    public function index(Request $request)
    {

        $logic      =   new NationalDayLogic();

        //$client     =   strtolower($request ->input('client'));
        $client     =   RequestSourceLogic::getSource();

        $token      =   $request->input('token','');

        $project    =   $logic->getActivityProject();

        $activitySignLogic = new ActivitySignLogic();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/national');

        $userId     =   $this->getUserId();

        //获取连续签到次数
        $signNum    =   $activitySignLogic->getContinueSignNum($userId, ActivityFundHistoryDb::SOURCE_ACTIVITY_NATIONAL);

        $timeStatus =   $logic->getNationDayStatus();
        
        $lottery    =   ['status'=>false];

        if( $timeStatus==NationalDayLogic::NATIONAL_ING && $userId){

            $lottery=   $logic->isCheckLotteryInvestStatus($userId);

        }

        $viewData   =   [
            'projectList'  => $project,
            'client'       => $client,
            'user_id'      => (!empty($userId)||$userId!=0) ? true : false,
            'token_status' => $token,
            'sign_num'     => $signNum,
            'start_time'   => $logic->setStartTime(),
            'end_time'     => $logic->setEndTime(),
            'time_status'  => $timeStatus,
            'lottery'      => $lottery,

        ];

        return view('wap.activity.national.index', $viewData);
    }

    /**
     * @return array
     * @desc 执行抽奖的程序
     */
    public function doLuckDraw( Request $request)
    {

        $userId     =   $this->getUserId();

        $logic      =   new NationalDayLogic();

        //时间判断
        $timeStatus =   $logic->isCheckLotteryStatus($userId);

        if( $timeStatus['status'] ==false ){

            return $timeStatus;
        }

        //条件判断
        $lotteryStatus  =   $logic->isCheckLotteryInvestStatus($userId);

        if( $lotteryStatus['status'] == false){

            return $lotteryStatus;
        }

        $lotteryParam   =   [
            'user_id'   =>  $userId,
            'group_id'  =>  1,
            'activity_id'=> ActivityFundHistoryDb::SOURCE_ACTIVITY_NATIONAL,
        ];
        $return     =   $logic->doLuckDraw( $lotteryParam );

        return $return;

    }

    /**
     * @desc 十一签到Ajax
     * @param Request $request
     */
    public function nationSignAjax(Request $request){

        $userId         = $this->getUserId();

        $activitySignLogic = new ActivitySignLogic();

        $nationalLogic     = new NationalDayLogic();

        $data = [
            'user_id' => $userId,
            'type' => ActivityFundHistoryDb::SOURCE_ACTIVITY_NATIONAL,
            'note' => LangModel::NATIONAL_SIGN_NOTE,
            'activity_status' => $nationalLogic->getNationDayStatus(),
        ];

        $return = $activitySignLogic->doSign($data);

        $return['sign_num'] = $activitySignLogic->getContinueSignNum($userId, ActivityFundHistoryDb::SOURCE_ACTIVITY_NATIONAL);

        return self::returnJson($return);
    }
}