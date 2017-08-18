<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/11/10
 * Time: 下午1:59
 * Desc: 邀请收益率
 */

namespace App\Http\Dbs\Invite;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class InviteRatesDb extends JdyDb{

    protected $table = 'invite_rates';

    CONST   STATUS_CAN_USE  = 100,  //可使用
            STATUS_USED     = 200;  //已使用

    public function scopeId($query, $id)
    {

        return $query->where('id', $id);

    }

    public function scopeUserId($query, $userId)
    {

        return $query->where('user_id', $userId);

    }

    public function scopeUserIds($query, $userIds){

        return $query->whereIn('user_id', $userIds);

    }

    public function scopeStatus($query, $status)
    {

        return $query->where('status', $status);

    }

    public function scopeEltRateEndTime($query, $time)
    {

        return $query->where('rate_end_time', '>=', $time);

    }

    public function scopeEgtRateStartTime($query, $time)
    {

        return $query->where('rate_start_time', '<=', $time);

    }

    public function scopeEltExpireTime($query, $time){

        return $query->where('use_expire_time', '>=', $time);

    }

    public function getObjById($id){

        return $this->find($id);

    }

    /**
     * @param $data
     * @return mixed
     * @desc 添加信息
     */
    public function doAdd($data){

        $this->user_id = $data['user_id'];

        $this->days = $data['days'];

        $this->admin_id = $data['admin_id'];

        $this->rate = $data['rate'];

        $this->use_expire_time = $data['use_expire_time'];

        $this->save();

        return $this->id;

    }

    /**
     * @param $id
     * @return mixed
     * @desc 更新使用
     */
    public function doUseRate($id){

        $obj = $this->getObjById($id);

        $obj->status = self::STATUS_USED;

        $obj->rate_start_time = ToolTime::getDateAfterCurrent();

        $obj->rate_end_time = date('Y-m-d H:i:s', ToolTime::getUnixTime(ToolTime::getDateAfterCurrent($obj->days), 'end'));

        return $obj->save();

    }

}