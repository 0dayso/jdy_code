<?php

/**
 * User: zhangshuang
 * Date: 16/4/20
 * Time: 10:54
 * Desc: 零钱计划投资使用加息券
 */

namespace App\Listeners\Invest\CurrentSuccess;

use App\Events\Invest\CurrentSuccessEvent;
use App\Http\Logics\Bonus\UserBonusLogic;
use Illuminate\Foundation\Auth\User;


class DoUseCurrentBonusListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  $data
     * @return void
     */
    public function handle(CurrentSuccessEvent $event)
    {


        $data = $event->getBonusData();

        $userId     = $data['user_id'];    //用户ID
        $bonusId    = $data['bonus_id'];    //加息券ID

        //零钱计划转入使用加息券
        if($bonusId){
            
            $logic      = new UserBonusLogic();
            $logic->doCurrentUsedBonus($userId,$bonusId);
        }


    }
}
