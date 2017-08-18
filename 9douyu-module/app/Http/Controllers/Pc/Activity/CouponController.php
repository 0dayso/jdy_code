<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 2017/2/21
 * Time: 下午2:37
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\CouponLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class CouponController extends PcController
{
    public function coupon(Request $request)
    {
        ToolJump::setLoginUrl('/activity/coupon');

        $projectList    =   CouponLogic::getProject();

        $activityTime   =   CouponLogic::setTime();

        $lotteryInfo    =   CouponLogic::getCouponLottery();

        $couponBonus    =   CouponLogic::getBonusList();

        $userId         =   $this->getUserId();

        $viewData       =   [
            'projectList'   =>  $projectList,
            'activityTime'  =>  $activityTime,
            'lotteryInfo'   =>  $lotteryInfo,
            'couponBonus'   =>  $couponBonus,
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
            'actToken'      =>  CouponLogic::setActToken(),
        ];

        return view('pc.activity.coupon.index',$viewData);
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
