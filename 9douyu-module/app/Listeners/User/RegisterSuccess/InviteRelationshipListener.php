<?php

namespace App\Listeners\User\RegisterSuccess;

use App\Events\User\RegisterSuccessEvent;

use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\Notice\NoticeLogic;
use App\Http\Models\User\InviteModel;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 用户邀请关系
 * Class InviteRelationshipListener
 * @package App\Listeners\User\RegisterSuccess
 */
class InviteRelationshipListener
{
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
     * @param  RegisterSuccessEvent  $event
     * @return void
     */
    public function handle(RegisterSuccessEvent $event)
    {

        if( $event->getInviteUserId() && $event->getUserId() ){

            $data = [
                'other_user_id'        => $event->getUserId(),
                'user_id'              => $event->getInviteUserId(),
                'type'                 => $event->getInviteType(),
                'source'               => $event->getInviteSource(),
                'user_type'            => $event->getInviteUserType(),
            ];

            $inviteModel = new InviteModel();

            $inviteModel->create($data);

            //给邀请人发送站内信
            $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_INVITE_SUCCESS);

            $msg = sprintf($msgTpl, $event->getUserPhone());

            NoticeLogic::sendNoticeByType(NoticeDb::TYPE_INVITE_SUCCESS, $event->getInviteUserId(), $msg, NoticeDb::TYPE_INVITE_SUCCESS);

        }



    }
}
