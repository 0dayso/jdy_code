<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 2017/2/21
 * Time: 下午2:37
 */

namespace App\Http\Controllers\Weixin\Activity;


use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\InvitationLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;
use Session;

class InvitationController extends WeixinController
{
    public function index(Request $request)
    {
        $token          =   $request->input('token');

        $client         =   RequestSourceLogic::getSource();

        ToolJump::setLoginUrl('/activity/invitation');

        $userId         =   $this->getUserId();

        if( $client == 'android' && $userId ){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);
        }

        $viewData = [
            'ranking_total' =>  InvitationLogic::getInvestTotalRanking(),
            'lottery_list'  =>  InvitationLogic::getPrizeList(),
            'ranking_word'  =>  InvitationLogic::getFormatNumberToWord(),
            'ranking_list'  =>  InvitationLogic::getPartnerInvestmentRanking(),
            'userStatus'    =>  (!empty($userId)||$userId!=0) ? true : false,
            'activityTime'  =>  InvitationLogic::setTime(),
            'client'        =>  $client,
        ];

        return view('wap.activity.invitation.index', $viewData);
    }

}
