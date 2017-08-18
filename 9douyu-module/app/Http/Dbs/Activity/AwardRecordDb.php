<?php
/** ****************************** 额外加息的DB层 ******************************
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/8/29
 * Time: 上午11:21
 */

namespace App\Http\Dbs\Activity;

use App\Http\Dbs\Family\FamilyDb;
use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class AwardRecordDb extends JdyDb
{
    //定义数据表
    protected $table = "award_record";
    const

        DEFAULT_PERCENTAGE  = '0.00',     //主账账户活动加息0.00%
        MAIN_PERCENTAGE     = '2.00',     //主账账户活动加息2%
        FAMILY_PERCENTAGE   = '4.00',     //家庭账户活动加息4%

        STATUS_FOR_GIVE     = 100,        //活动奖励带发放
        STATUS_GIVE         = 200,        //活动奖励已随回款发放
        STATUS_CANCEL       = 300;        //活动奖励取消发放

    /**
     * @desc 添加活动奖励纪录
     * @param array $data
     * @return bool
     */
    public function addAwardRecord($data){
       return $this->insert($data);
    }

    /**
     * 更新奖励纪录内容
     * @param array $data
     * @return mixed
     */
    public function updateRecord($satisfied,$filed,$data = []){

        return static::whereIn($filed, $satisfied)->Update($data);
    }
    /**
     * 获取主体账户累计加息折现金额奖励收益
     * @param $userId
     * @return float
     */
    public function getAddInterestProfit($userId =''){
        $collectCash = $this->where('user_id',$userId)
                            ->where('status',self::STATUS_FOR_GIVE)
                            ->sum('cash');
        return $collectCash;
    }
    /**
     * 按用户ID的条件筛选
     * @param  $userId
     * @return where
     */
    public function getWhereByUserId($userId){
        return $this->whereIn(['user_id' , $userId])
                    ->orderBy('created_at desc')
                    ->get()
                    ->toArray();
    }

    /*
     * 通过活动类型
     * @param int $eventId
     * @return array()
     */
    public function getByEventType( $eventId ){
        return $this->where('event_type',$eventId)->get()->toArray();
    }

    /*
     * 获取待结算的列表
     */
    public function getPendingList($projectIds)
    {
        return $this->where('status',self::STATUS_FOR_GIVE)
                    ->whereIn('project_id',$projectIds)
//                    ->orderBy("project_id desc")
                    ->get()
                    ->toArray();
    }
}