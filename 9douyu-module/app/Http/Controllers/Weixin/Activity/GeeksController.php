<?php
/**
 * Created by PhpStorm.
 * User: tianxiaoyan
 * Date: 16/12/27
 * Time: 下午5:00
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Http\Request;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\Activity\ReceiveBonusLogic;
use App\Tools\ToolJump;

class GeeksController extends WeixinController{
    const
        BONUS_ID        = 251;     //红包ID

    public function geeks(Request $request)
    {

        $token          = strtolower($request->input('token',''));

        $client         = RequestSourceLogic::getSource();
        $userId         = $this->getUserId();
        $isReceived     = 0;
        $repeatHit      = "closed";

        if( !empty($userId)||$userId!=0 ){
            $bonusId    = self::BONUS_ID;
            $userBonusLogic =   new UserBonusLogic();
            $userBonus  = $userBonusLogic->getReceivedBonusWithUser($userId , $bonusId);
            if($userBonus){
                $isReceived = 1;
            }

            #限制重复点击
            $cacheKey   = "RECEIVED_GEEKS_".$userId;
            if(\Cache::has($cacheKey)){
                $repeatHit = "opened";
            }
        }
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/geeks');

        $viewsData      =   [
            'client'    =>  $client,
            'token'     =>  $token,
            'userStatus'=> (!empty($userId)||$userId!=0) ? true : false,
            'isReceived'=> $isReceived,
            'repeatHit' => $repeatHit,
        ];
        return view('wap.activity.geeks.geeks', $viewsData);
    }

    /**
     * @param Request $request
     * @return array
     * @desc 执行红包的领取
     */
    public function doReceiveBonus(Request $request)
    {
        $userId     = $this->getUserId();
        $bonusId    = self::BONUS_ID;
        return  ReceiveBonusLogic::doReceiveBonusWithGeeks($userId,$bonusId);

    }

}