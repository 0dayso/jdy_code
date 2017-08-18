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
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class CouponController extends WeixinController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 春风十里活动wap端
     */
    public function coupon(Request $request)
    {
        $token          =   $request->input('token');

        $version        =   $request->input('version');

        $client         =   RequestSourceLogic::getSource();

        ToolJump::setLoginUrl('/activity/coupon');

        $userId         =   $this->getUserId();

        if( $client == 'android' && $userId ){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);
        }

        $projectList    =   CouponLogic::getProject();

        $activityTime   =   CouponLogic::setTime();

        $lotteryInfo    =   CouponLogic::getCouponLottery();

        $couponBonus    =   CouponLogic::getBonusList();

        $versionStatus=  CouponLogic::isUnUseAppVersion($version);

        $viewData       =   [
            'projectList'   =>  $projectList,
            'activityTime'  =>  $activityTime,
            'lotteryInfo'   =>  $lotteryInfo,
            'couponBonus'   =>  $couponBonus,
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
            'versionStatus' =>  $versionStatus,
            'client'        =>  $client,
            'token'         =>  $token,
            'actToken'      =>  CouponLogic::setActToken(),
        ];

        return view('wap.activity.coupon.coupon',$viewData);
    }

    /**
     * @param Request $request
     * @return array
     * @desc 执行红包的领取
     */
    public function doReceiveBonus(Request $request)
    {
        $userId         =   $this->getUserId();

        $customValue    =   $request->input('custom_value','');
        //时间判断
        $receiveStatus  =   CouponLogic::isCanReceiveBonus($userId,$customValue);

        if( $receiveStatus['status'] ==false ){

            return $receiveStatus;
        }

        return  CouponLogic::doReceiveBonus($userId,$customValue);
    }
}
