<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/22
 * Time: 下午4:07
 */

namespace App\Http\Dbs\CurrentNew;


use App\Http\Dbs\JdyDb;
use App\Tools\ToolArray;
use App\Tools\ToolTime;

class UserCurrentNewFundHistoryDb extends JdyDb
{

    protected $table = 'user_current_new_fund_history';

    const
        STATUS_INVEST       = 400,  //转入
        STATUS_INVEST_OUT   = 401,  //转出
        STATUS_FROZEN       = 500,  //冻结
        STATUS_INTEREST     = 402,  //计息

    END=1;

    /**
     * @param $userId
     * @param $cash
     * @param $afterBalance
     * @param $eventId
     * @param $note
     * 添加数据
     */
    public function add($userId, $cash, $afterBalance, $eventId, $note){

        $this->user_id          = $userId;
        $this->change_balance   = $cash;
        $this->after_balance    = $afterBalance;
        $this->event_id         = $eventId;
        $this->times            = ToolTime::dbDate();
        $this->note             = $note;

        $this->save();

        return $this->id;
    }

    /**
     * @param $userId
     * @return int
     * 用户数据
     */
    public function getUserAmount($userId){

        $result = $this->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->first();

        if(empty($result)){
            return 0;
        }

        return $result['after_balance'];

    }

    /**
     * @param $userIds
     * @param $date
     * @return mixed
     */
    public function getUsersAmountByUserIds( $userIds, $date='' ){

        $date = empty($date) ? ToolTime::dbDate() : $date;

        $userIds = implode(',', $userIds);

        $sql = "select * from (
                    select * from module_user_current_new_fund_history
                    where user_id in ({$userIds}) AND times<'{$date}'
                    order by id desc
                ) user group by user_id";

        $result       =   app('db')->select($sql);

        $result       =   ToolArray::objectToArray($result);

        return $result;

    }

    /**
     * @desc 分组获取活期用户的ID
     * @return array
     */
    public function getAllUserIds()
    {
        return $this->select('user_id')
            ->groupBy('user_id')
            ->get()
            ->toArray();

    }

    /**
     * @param $date
     * @param $size
     * @param $page
     * @return mixed
     * 转出
     */
    public function getUserInvestOutList( $date, $page, $size ){

        $date = empty($date)?ToolTime::getDateBeforeCurrent():$date;

        $result = $this->where('times', $date)
            ->where('event_id', self::STATUS_FROZEN)
            ->skip($page * $size)
            ->take($size)
            ->get()
            ->toArray();

        return $result;

    }

    /**
     * @param $date
     * @return mixed
     * 转出总条数
     */
    public function getUserInvestOutCount( $date ){

        $date = empty($date)?ToolTime::getDateBeforeCurrent():$date;

        $result = $this->where('times', $date)
            ->where('event_id', self::STATUS_FROZEN)
            ->count();

        return $result;

    }

    /**
     * @param $id
     * @return mixed
     * 更新转出成功状态
     */
    public function updateInvestOutStatus( $id ){

        return self::where('id', $id)
            ->update(array(
                'event_id'         => self::STATUS_INVEST_OUT,
            ));

    }

}
