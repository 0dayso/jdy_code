<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/31
 * Time: 18:59
 */

namespace App\Listeners\User\RegisterSuccess;

use App\Events\User\RegisterSuccessEvent;

use App\Http\Logics\Media\InviteLogic;
use App\Http\Logics\RequestSourceLogic;
use Dingo\Api\Http\Middleware\Request;
use Illuminate\Queue\InteractsWithQueue;
use Log;

use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 推广渠道注册对应关系
 * Class InviteRelationshipListener
 * @package App\Listeners\User\RegisterSuccess
 */
class InviteMediaListener
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
        $userId     = $event->getUserId();
        $channel    = $event->getChannelInfo();

        if($channel){
            
            $channelId = $channel['id'];

            $logic = new InviteLogic();

            $result = $logic->addRecord($userId,$channelId,RequestSourceLogic::getSource());
            
            if(!$result['status']){

                Log::error(__METHOD__.'Error',$result);
            }

        }else{

        }
        
       
    }
}