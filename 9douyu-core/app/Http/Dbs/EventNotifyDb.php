<?php
/**
 * User: Zjmainstay
 * Date: 16/4/22
 */

namespace App\Http\Dbs;

use App\Tools\ToolTime;


class EventNotifyDb extends JdyDb{

    /**
     * @param $data
     * @return bool
     * @desc 创建资金记录
     */
    public function addEventNotice($authId, $eventName, $notifyUrl)
    {

        $this->auth_id      = $authId;
        $this->event_name   = $eventName;
        $this->notify_url   = $notifyUrl;

        $this->save();

        return $this->id;

    }

    /**
     * @param $id
     * @return mixed
     * @desc 获取user对象
     */
    public function getObj($id)
    {

        return $this->find($id);

    }

    /**
     * 通过事件名获取回调地址
     * @param $eventName
     * @return array
     */
    public function getNotifyListByEventName($eventName) {
        return $this->where('event_name', $eventName)->get()->toArray();
    }

}
