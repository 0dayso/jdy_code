<?php

namespace App\Listeners\Invest\ProjectError;

use App\Http\Models\Bonus\UserBonusModel;
use App\Events\Invest\ProjectUnLockBonusEvent;
use DB;

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
     * @param  CheckBonusListener  $event
     * @return void
     */
    public function handle(ProjectUnLockBonusEvent $event)
    {
        $userBonusId = $event->data['bonus'];
        if($userBonusId>0){
            try{
                UserBonusModel::delLock($userBonusId);
            }catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }
    }
}
