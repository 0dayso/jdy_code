<?php

namespace App\Listeners\Invest\ProjectSuccess;

use App\Events\CommonEvent;
use App\Events\Invest\ProjectSuccessEvent;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Models\Bonus\UserBonusModel;
use DB;

class UpdateBonusListener
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
     * @param  ProjectSuccessEvent  $event
     * @return void
     */
    public function handle(CommonEvent $event)
    {
        $data = $event->getDataByKey('bonus');

        $userBonusId = $data['bonus_id'];
        $investId    = $data['invest_id'];
        if($userBonusId>0 && $investId>0) {
            DB::beginTransaction();
            try {
                $userBonusLogic = new UserBonusLogic();
                $userBonusLogic->doRegularUsedBonus($userBonusId,$investId);
                UserBonusModel::delLock($userBonusId);
                DB::commit();
            }catch (\Exception $e){

                DB::rollback();

                \Log::Error(__CLASS__.__METHOD__.'Error', ['code' => $e->getCode(), 'msg' => $e->getMessage()]);

                throw new \Exception($e->getMessage());

            }
        }
    }
}
