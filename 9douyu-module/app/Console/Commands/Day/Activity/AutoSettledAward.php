<?php

/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/8/30
 * Time: 下午3:10
 */
namespace App\Console\Commands\Day\Activity;


use App\Http\Logics\Activity\AwardRecordLogic;
use App\Http\Logics\Project\ProjectDetailLogic;
use Illuminate\Console\Command;
use Log;

class AutoSettledAward extends Command
{
    //计划任务唯一标识
    protected $signature = 'jdy:AutoSettledActivityAward';

    //计划任务描述
    protected $description = 'Everyday 8:00 auto settled Activity Award.';
    /**
     *
     * Handle the event.
     * @param  $event
     * @throws \Exception
     */
    public function handle(){
        //当前日期
        $currentDate       = date("Y-m-d");

        //获取还未结算的奖励
        
        $awardLogic        =   new AwardRecordLogic();

        $projectIds        =   $awardLogic->getRefundProjectByTime($currentDate);

        if( empty($projectIds) ){

            return false;
        }
        $awardRecordData   =   $awardLogic->getPendingList($projectIds);

        //没有可奖励的记录
        if(!$awardRecordData){

            $message = "活动加息奖励转入账户余额－没有可奖励的用户记录";
            Log::info('refundFatherAwardToBalance',['day'=>$currentDate,'msg'=>$message]);

            return false;
        }
        $projectLogic   =   new ProjectDetailLogic();

        foreach($awardRecordData as $key=>$val){

            if( !empty($val['project_id'])){

                $lastTime   = $projectLogic->getLastRefundPlanTime($val['project_id']);

                //如果是项目最后一期的回款
                if($currentDate >= $lastTime){

                    $awardLogic->refundAwardToBalance($val['user_id'], $val['cash'], $val['id'],$val['event_type']);
                }
            }

        }
    }

}