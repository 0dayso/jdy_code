<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/28
 * Time: 下午7:24
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Activity\ReceiveBonusLogic;
use App\Tools\ToolJump;

class GeeksController extends PcController
{
    const
        BONUS_ID        = 251;     //红包ID

    public function index()
    {
        //用户id
        $userId         = $this->getUserId();
        //是否已领取
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

        $viewData       =   [
            'userStatus'=> (!empty($userId)||$userId!=0) ? true : false,
            'isReceived'=> $isReceived,
            'repeatHit' => $repeatHit,
        ];


        return view("pc.activity.geeks.index", $viewData);
    }


    /**
     * @param Request $request
     * @return array
     * @desc 执行红包的领取
     */
    public function doReceiveBonus()
    {
        $userId     = $this->getUserId();

        $bonusId    = self::BONUS_ID;

        return  ReceiveBonusLogic::doReceiveBonusWithGeeks($userId,$bonusId);

    }


}