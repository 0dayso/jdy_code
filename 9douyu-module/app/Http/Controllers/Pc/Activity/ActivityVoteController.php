<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午8:14
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Activity\ActivityVoteLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class ActivityVoteController extends PcController
{

    public function index()
    {
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/challenge');

        return view('pc.activity.challenge.challenge');
        
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 投票页面
     */
    public function detail()
    {
        ToolJump::setLoginUrl('/activity/challenge/detail');
        //投票时间
        $pollingTime    =   ActivityVoteLogic::getVoteTime();

        //用户id
        $userId         =   $this->getUserId();

        $loginStatus    =   ActivityVoteLogic::isLogin($userId);

        $isCanVote['status']=   false;

        if( $userId ){

            $isCanVote  =   ActivityVoteLogic::isCanVote($userId,ActivityFundHistoryDb::SOURCE_ACTIVITY_VOTE);
        }

        $viewData       =   [
            'userStatus'    =>  $loginStatus,
            'pollingTime'   =>  $pollingTime,
            'isCanVote'     =>  $isCanVote,

        ];
        
        return view('pc.activity.challenge.detail',$viewData);
    }
    /**
     * @param Request $request
     * @return string
     * @desc 执行投票
     */
    public function doVote( Request $request )
    {
        $choices    =   $request->input("choices");

        $userId     =   $this->getUserId() ? $this->getUserId() : 0;

        $activityId =   ActivityFundHistoryDb::SOURCE_ACTIVITY_VOTE;

        $voteStatus =   ActivityVoteLogic::isCanVote($userId ,$activityId );

        if( $voteStatus['status'] ==false ){

            return self::returnJson($voteStatus);
        }
        
        $isVoteTeam = ActivityVoteLogic::isInVoteTeam($choices);

        if( $isVoteTeam['status'] ==false ){

            return self::returnJson($isVoteTeam);
        }
        
        $return     = ActivityVoteLogic::doAddVoteTimes( $userId ,$choices ,$activityId ,"高校挑战赛");

        return self::returnJson($return);
    }
}