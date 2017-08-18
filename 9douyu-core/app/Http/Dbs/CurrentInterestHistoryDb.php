<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/27
 * Time: 下午4:08
 * Desc: 零钱计划利息记录
 */

namespace App\Http\Dbs;

use App\Tools\ToolTime;

class CurrentInterestHistoryDb extends JdyDb
{

    const INTEREST_TYPE_BASE    = 1,    //零钱计划基准利息
          INTEREST_TYPE_BONUS   = 2;    //加息券利息

    protected $fillable = ['user_id', 'rate', 'interest', 'type','interest_date', 'principal'];

    /**
     * @param $data
     * @return static
     * @desc 批量插入记录
     */
    public function addInfo($data)
    {

        return \DB::table('current_interest_history')
                ->insert($data);

    }

    /**
     * @param $userId
     * @param int $days
     * @return mixed
     * @desc 获取用户的7天利息记录
     */
    public function getFundHistoryList( $userId, $days = 7 )
    {

        $day = ToolTime::getDateBeforeCurrent($days);

        return $this
            ->select('interest_date', \DB::raw('sum(interest) as interest'),\DB::raw('sum(rate) as total_rate'), 'rate', 'principal')
            ->where('user_id', $userId)
            ->where('interest_date', '>=', $day)
            ->groupBy('interest_date')
            ->orderBy('interest_date','desc')
            ->get()->toArray();
    }

    /**
     * @param $userIds
     * @return mixed
     * 获了多个用户今日零钱计划加息券利息数据
     */
    public function getBonusListByUserIds($userIds){

        $date = date('Y-m-d',strtotime('-1 day'));

        return self::select('user_id','rate','interest','interest_date')
            ->where('type',self::INTEREST_TYPE_BONUS)
            ->whereIn('user_id',$userIds)
            ->where('interest_date',$date)
            ->get()
            ->toArray();
    }

    /**
     * @param $date
     * @return mixed
     * 获取指定日期基准利息跟加息券利息
     */
    public function getYesterdayInterest($date){

        return $this->select('type',\DB::raw('sum(interest) as total_interest'))
            ->where('interest_date',$date)
            ->groupBy('type')
            ->get()->toArray();
    }

    /**
     * @desc  活期所有的活期计息记录
     * @param $pageSize
     * @param $attributes array
     * @return mixed
     */
    public function getAdminFundHistoryListAll($pageSize, $attributes){

        return $this->where($attributes)
            ->orderBy('created_at', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

}