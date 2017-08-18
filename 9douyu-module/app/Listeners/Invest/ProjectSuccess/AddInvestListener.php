<?php

namespace App\Listeners\Invest\ProjectSuccess;

use App\Events\CommonEvent;
use App\Events\Invest\ProjectSuccessEvent;
use App\Http\Models\Bonus\UserBonusModel;
use App\Http\Models\Invest\InvestModel;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddInvestListener
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
        //add ***
        $event = $event->getDataByKey('invest');

        try {

            $investModel = new InvestModel();

            if( $event['bonus_id'] ){

                $bonusModel = new UserBonusModel();

                $bonusInfo = $bonusModel->checkIsExits($event['bonus_id']);

                $event['bonus_id'] = $bonusInfo['bonus_id'];

            }

            $investModel->addRecord($event);

        }catch (\Exception $e){

            \Log::Error(__CLASS__.__METHOD__.'Error', ['code' => $e->getCode(), 'msg' => $e->getMessage()]);

            throw new \Exception($e->getMessage());

        }
    }
}
