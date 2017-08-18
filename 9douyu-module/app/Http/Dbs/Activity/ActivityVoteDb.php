<?php
/**
 * #######################################投票的模块的数据层#######################################
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午2:49
 */

namespace App\Http\Dbs\Activity;


use App\Http\Dbs\JdyDb;

class ActivityVoteDb extends JdyDb
{
    protected $table = 'activity_vote'; //投票记录的表名


    /**
     * @param $data
     * @return bool
     * @desc 记录数据
     */
    public function doAdd( $data )
    {
        $this->user_id      =   $data['user_id'];

        $this->phone        =   $data['phone'];

        $this->activity_id  =   $data['activity_id'];

        $this->choices      =   $data['choices'];

        $this->note         =   $data['note'];

        return $this->save();
    }

    /**
     * @param $activityId
     * @param $userId
     * @param string $startTime
     * @param string $endTime
     * @return mixed
     * @desc 某一投票活动的次数
     */
    public static function getActivityVoteByTime( $userId ,$activityId ,$startTime = '',$endTime = '')
    {

        $obj    =   self::where('activity_id',$activityId)
                        ->where('user_id',$userId );

        if( !empty($startTime) ){

            $obj    =   $obj->where('created_at','>=',$startTime);
        }

        if( !empty( $endTime) ){

            $obj    =   $obj->where('created_at' ,"<=",$endTime );
        }

        $result     =   $obj->count('id');

        return $result;

    }

}