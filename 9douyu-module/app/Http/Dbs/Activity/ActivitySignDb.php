<?php
/**
 * create by Phpstorm
 * User: lgh-dev
 * Date: 16/09/25
 * Time: 13:23PM
 * Desc: 签到活动
 */

namespace App\Http\Dbs\Activity;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class ActivitySignDb extends JdyDb{

    protected $table = "activity_sign";

    //定义常量
    const
    
        ACTIVITY_CONTINUE_ONE  = 1,
        ACTIVITY_CONTINUE_THREE = 3,//连续3天签到
        ACTIVITY_CONTINUE_FIVE = 5,
        ACTIVITY_CONTINUE_SEVEN = 7
        ; //十一签到活动

    /**
     * @desc 添加签到记录
     * @param $data
     * @return bool
     */
    public function addSign($data){

        return $this->insert($data);
    }

    /**
     * @desc 更新签到记录
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateSign($where, $data){

        return $this->where($where)->update($data);

    }

    /**
     * @desc 获取用户本次活动的签到记录
     * @param $userId
     * @param $type
     * @return mixed
     */
    public function getUserSign($userId, $type){

        $return =    $this->where('user_id', $userId)
            ->where('type', $type)
            ->first();

        return $this->dbToArray($return);
    }

    /**
     * @desc 更新连续签到信息
     * @param $userId
     * @param $type
     * @return mixed
     */
    public function updateContinueSign($userId, $type){

        return $this->where('user_id', $userId)
            ->where('type', $type)
            ->update([
                'sign_continue_num' => \DB::raw('sign_continue_num+1'),
                'last_sign_day'     => ToolTime::dbDate(),
                'sign_record'       => \DB::raw('concat(`sign_record`,"'.ToolTime::dbDate().'|")'),
            ]);

    }

    /**
     * @desc 更新非连续签到信息
     * @param $userId
     * @param $type
     * @return mixed
     */
    public function updateNoContinueSign($userId, $type){

        return $this->where('user_id', $userId)
            ->where('type', $type)
            ->update([
                'sign_continue_num' => self::ACTIVITY_CONTINUE_ONE,
                'last_sign_day'     => ToolTime::dbDate(),
                'sign_record'       => \DB::raw('concat(`sign_record`,"'.ToolTime::dbDate().'|")'),
            ]);

    }

    /**
     * @desc 获取连续签到的次数
     * @param $userId
     * @param $type
     * @return mixed
     */
    public function getSignNum($userId, $type){
        return $this->select(\DB::raw('sign_continue_num as sign_num'))
            ->where('user_id', $userId)
            ->where('type', $type)
            ->first();
    }
}