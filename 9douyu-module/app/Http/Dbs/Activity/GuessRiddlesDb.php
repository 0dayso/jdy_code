<?php
/**
 * Created by PhpStorm.
 * User:  linguanghui
 * * Date: 17/1/22
 * Time: 下午18:04
 */

namespace App\Http\Dbs\Activity;

use App\Http\Dbs\JdyDb;

class GuessRiddlesDb extends JdyDb{

    protected $table = 'lantern_riddles';

    /**
     * @desc 添加用户成功猜灯谜的纪录
     * @param $data
     * @return mixed
     */
    public function addLantern($data){

        $this->riddles_id = $data['riddles_id'];
        $this->user_id = $data['user_id'];
        $this->type = $data['type'];
        $this->save();
        return $this->id;
    }
    /**
     * @desc  获取用户猜灯谜成功纪录
     * @param $userId
     * @param $riddlesId
     * @param $type
     */
    public function getUserLanttern($userId, $riddlesId, $type){

        $return = $this->where('user_id',$userId)
            ->where('riddles_id',$riddlesId)
            ->where('type',$type)
            ->first();

        return $this->dbToArray($return);
    }

    /**
     * @desc 获取用户活动猜过的灯谜
     * @param $userId
     * @param $type
     * @return mixed
     */
    public function getUserGuessRiddles($userId,$type){

        return  $this->where('user_id',$userId)
            ->where('type',$type)
            ->get()
            ->toArray();

    }



}
