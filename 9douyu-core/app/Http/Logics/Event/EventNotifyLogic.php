<?php
/**
 * Created by PhpStorm.
 * User: Zjmainstay
 * Date: 16/4/21
 */
namespace App\Http\Logics\Event;

use App\Http\Dbs\EventNotifyDb;
use App\Http\Logics\Logic;
use App\Http\Models\Event\EventNotifyModel;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Application;

/**
 * 事件通知逻辑
 * Class EventLogic
 * @package App\Http\Logics\Event
 */
class EventNotifyLogic extends Logic
{
    /**
     * 获取事件列表
     * @return array
     */
    public function getEventList()
    {
        $app = new Application();
        $app->configure('event');
        $eventList = $app['config']['event'];

        return self::callSuccess($eventList);
        //return (array)$eventList;
    }
    
    public function getNotifyListByEventName($eventName) {
        $db = new EventNotifyDb();
        
        return $db->getNotifyListByEventName($eventName);
    }

    /**
     * 事件通知注册
     * 
     * 当核心对应事件触发是，会给相应的通知接收地址发送事件相关信息
     * @param $eventName
     * @param $notifyUrl
     */
    public function register($authId, $eventName, $notifyUrl)
    {
        $return  = self::callSuccess();
        $eventModel = new EventNotifyModel();
        
        try {
            self::beginTransaction();
            
            $eventModel->addEventNotice($authId, $eventName, $notifyUrl);
            
            self::commit();
        } catch (\Exception $e) {
            self::rollback();

            $return['status'] = false;
            $return['msg'] = $e->getMessage();

            $log = [
                'event_name'    => $eventName,
                'notify_url'    => $notifyUrl,
                'code'          => $e->getCode(),
                'msg'           => $e->getMessage(),
            ];

            Log::Error('EventRegisterError', $log);
        }
        
        return $return;
    }
}
