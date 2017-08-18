<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date: 17/01/23
 * Time: 17:55Pm
 * Desc: 猜灯谜活动事件监听
 */
namespace App\Listeners\Award\Activity;


use App\Events\Activity\GuessRiddlesEvent;

use Log;

class GuessRiddlesLister
{

    public function handle(GuessRiddlesEvent $event){

        $userId = $event->getUserId();

        $configAward = $event->getAwardConfig();

        //发送奖励的加息券或者红包
        $event->sendBonus($userId, $configAward);

    }
}
