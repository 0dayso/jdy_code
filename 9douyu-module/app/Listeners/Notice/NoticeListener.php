<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/3/2
 * Time: 下午5:44
 */

namespace App\Listeners\Notice;

use App\Events\CommonEvent;
use App\Http\Logics\Notice\NoticeLogic;

class NoticeListener{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Invest/ProjectSuccessEvent  $event
     * @return void
     * @desc data 发送站内信,二维数组中的 notice
     */
    public function handle(CommonEvent $event)
    {

        $data = $event->getDataByKey('notice');
        
        NoticeLogic::sendNoticeByType($data['title'], $data['user_id'], $data['message'], $data['type']);

    }

}