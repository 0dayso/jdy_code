<?php

namespace App\Listeners\Invest\CurrentBefore;

use App\Events\Invest\CurrentBeforeEvent;
use App\Http\Models\Bonus\UserBonusModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckBonusListener
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
     * @param  ProjectBeforeEvent  $event
     * @return void
     */
    public function handle(CurrentBeforeEvent $event)
    {
        $data = $event->data;

        $bonusId = $data['bonus_id'];   //零钱计划加息券ID
        $userId  = $data['user_id'];    //用户ID
        $cash    = $data['cash'];       //转入金额
        $from    = $data['from'];       //三端来源

        if($bonusId > 0){
            try{
                
                $bonusModel = new UserBonusModel();

                //判断用户是否正在使用零钱计划加息券
                $bonusModel->checkCurrentBonusUsed($userId);
                //判断零钱计划加息券使用条件
                $bonusModel->checkUserBonus($userId,$bonusId,$from,$cash);


                UserBonusModel::addLock($bonusId);
                
            }catch (\Exception $e){

                throw new \Exception($e->getMessage());
            }
        }
    }
}
