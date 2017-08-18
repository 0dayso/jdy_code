<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/3/2
 * Time: 下午5:44
 */

namespace App\Listeners\Notice;

use App\Events\CommonEvent;
use App\Http\Dbs\Article\CategoryDb;
use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\Notice\NoticeLogic;

class SiteNoticeListener{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param CommonEvent $event
     * @desc 发送站内公告
     */
    public function handle(CommonEvent $event)
    {

        $data = $event->getDataByKey('notice');

        if( $data['type'] == CategoryDb::NOTICE ){

            NoticeLogic::sendSystemNotice($data['title'], $data['message'], NoticeDb::TYPE_SITE_NOTICE);

        }

    }

}