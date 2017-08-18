<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 2017/2/21
 * Time: 下午2:37
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\InvitationLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;
use Session;

class InvitationController extends PcController
{
    public function index(Request $request)
    {
        ToolJump::setLoginUrl('/activity/tourism');

        $userId         =   $this->getUserId();

        $viewData = [
            'ranking_total' =>  InvitationLogic::getInvestTotalRanking(),
            'lottery_list'  =>  InvitationLogic::getPrizeList(),
            'ranking_list'  =>  InvitationLogic::getPartnerInvestmentRanking(),
            'userStatus'    =>  (!empty($userId)||$userId!=0) ? true : false,
            'activityTime'  =>  InvitationLogic::setTime(),
            ];

        return view("pc.activity.invitation.invitation", $viewData);
    }
}
