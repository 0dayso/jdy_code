<?php

namespace App\Listeners\Api\Common;

use App\Events\Api\ApiEvent;
use App\Http\Logics\Event\EventNotifyLogic;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ares333\CurlMulti\Core as CurlMulti;
use Illuminate\Support\Facades\Log;

class AsyncListener implements ShouldQueue
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
     * @param  $data
     * @return void
     */
    public function handle($apiEvent)
    {
        if(!($apiEvent instanceof ApiEvent)) {
            $className = $apiEvent['event_name'];
            $apiEvent = new $className($apiEvent);
        }
        $eventName = get_class($apiEvent);
        $notifyLogic = new EventNotifyLogic();
        
        $notifyList = $notifyLogic->getNotifyListByEventName($eventName);
        
        $curl = new CurlMulti();
        
        foreach($notifyList as $notify) {
            try {
                $curl->add([
                    'url'   => $notify['notify_url'],
                    'args'  => [
                        'event_name'    => $eventName,
                        'auth_id'       => $notify['auth_id'],
                        'notify_url'    => $notify['notify_url'],
                    ],
                    'opt'   => [
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $apiEvent->getData(),
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ]
                ], function($res, $args){
                    Log::Notice(sprintf('Success: eventName:%s, authId:%s, notifyUrl:%s, respone:%s', $args['event_name'], $args['auth_id'], $args['notify_url'], json_encode($res['content'])));
                }, function($err, $args){
                    Log::Notice(sprintf('Error:%s, eventName:%s, authId:%s, notifyUrl:%s', $err['error'], $args['event_name'], $args['auth_id'], $args['notify_url']));
                });
            } catch (\Exception $e) {
                var_dump($e->getMessage(), $e->getCode());
            }
            
        }

        try {
            $curl->start();
        } catch (\Exception $e) {
            var_dump($e->getMessage(), $e->getCode());
        }
    }
}
