<?php
/**
 * Created by PhpStorm.
 * User: Zjmainstay 
 * Date: 16/4/21
 */

namespace App\Http\Models\Event;
use App\Http\Dbs\EventNotifyDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use Laravel\Lumen\Application;

class EventNotifyModel extends Model
{
    public function addEventNotice ($authId, $eventName, $notifyUrl)
    {
        $db = new EventNotifyDb();
        
        $this->checkEventExists($eventName);
        
        $res = $db->addEventNotice($authId, $eventName, $notifyUrl);
        
        if(empty($res)) {
            throw new \Exception("注册事件通知[$eventName]失败", ExceptionCodeModel::EXP_MODEL_EVENT_NOTIFY);
        }
        
        return true;
    }
    
    public function checkEventExists($eventName) {
        $app = new Application();
        $app->configure('event');
        
        $eventList = $app['config']['event.list'];
        
        if(!isset($eventList[$eventName])) {
            throw new \Exception("事件[{$eventName}]不存在");
        }
        
        return true;
    }
}
