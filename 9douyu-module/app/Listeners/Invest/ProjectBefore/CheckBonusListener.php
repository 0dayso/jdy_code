<?php

namespace App\Listeners\Invest\ProjectBefore;

use App\Events\Invest\ProjectBeforeEvent;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Models\Bonus\UserBonusModel;
use DB;
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
    public function handle(ProjectBeforeEvent $event)
    {
        $data = $event->data;
        if($data['userBonusId']>0){
            DB::beginTransaction();
            try{
                $bounsLogic = new UserBonusModel();
                $bounsLogic->checkUserBonus($data['userId'],$data['userBonusId'],$data['source'],$data['cash'],$data['productLine'],$data['type'],$data['bonusType']);
                UserBonusModel::addLock($data['userBonusId']);
                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                throw new \Exception($e->getMessage());
            }
        }

        //投资前事件向借款人系统请求项目状态发布
        $logic = new ProjectLogic();
        $logic->doPublishCreditToLoanUser( $data['projectId'] );

    }
}
