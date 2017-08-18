<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date: 16/09/25
 * Time: 18:14Pm
 * Desc: 十一国庆活动活动签到事件监听
 */
namespace App\Listeners\Award\Activity;


use App\Events\Activity\SignEvent;
use App\Http\Dbs\Activity\ActivitySignDb;
use App\Http\Logics\Activity\ActivitySignLogic;
use App\Http\Logics\Activity\NationalDayLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class NationalSignListener
{

    public function handle(SignEvent $event){

        $userId = $event->data['user_id'];
        $type = $event->data['type'];

        $activitySignDb = new ActivitySignDb();
        $activitySignLogic = new ActivitySignLogic();
        $nationalDayLogic  = new NationalDayLogic();

        //获取十一活动的配置
        $nationalConfig = SystemConfigModel::getConfig('NATIONAL_ACTIVITY');

        $signNum = $activitySignLogic->getContinueSignNum($userId,$type);
        //连续三天签到
        if($event->checkSignContinueThree($signNum)){
            //发送3%零钱计划加息券
            $event->sendBonus($userId, [$nationalConfig['CURRENT_BONUS_ID']]);
        }
        //连续五天签到
        if($event->checkSignContinueFive($signNum)){
            //10元现金红包
            $event->sendBonus($userId, [$nationalConfig['TEN_BONUS_ID']]);
        }
        //连续七天签到
        if($event->checkSignContinueSeven($signNum)){
            //2%定期加息
            $event->sendBonus($userId, [$nationalConfig['PROJECT_BONUS_ID']]);
        }

    }

}