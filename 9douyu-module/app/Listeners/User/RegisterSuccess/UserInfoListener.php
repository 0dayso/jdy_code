<?php

namespace App\Listeners\User\RegisterSuccess;

use Log;

use App\Events\User\RegisterSuccessEvent;

use App\Http\Logics\User\RegisterLogic;

use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 用户扩展详情记录
 * Class UserInfoListener
 * @package App\Listeners\User\RegisterSuccess
 */
class UserInfoListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * 用户注册扩展信息添加
     * Handle the event.
     *
     * @param  RegisterSuccessEvent  $event
     * @return void
     */
    public function handle(RegisterSuccessEvent $event)
    {
        $data = [
            'userId'     => $event->getUserId(),
            'ip'         => $event->getIp(),
            'source_code' => $event->getSource(),
        ];

        $registerLogic = new RegisterLogic();

        $registerLogic->createUserInfo($data);
    }
}
