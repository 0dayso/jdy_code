<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/15
 * Time: 11:10
 */

namespace App\Http\Dbs\Current;

use App\Http\Dbs\JdyDb;

class FundStatisticsDb extends JdyDb{

    protected $table = 'current_fund_statistics';

    /**
     * @param $date
     * @return mixed
     * 获取提定日期的统计数据
     */
    public function getByDate($date){

        $obj = self::where('date',$date)
            ->first();

        if($obj){
            return $obj->toArray();
        }else{
            return [];
        }
    }

    public function addRecord($data){

        return self::insert(
            $data
        );
    }

    /**
     * @param $date
     * @param $data
     * @return mixed
     * 更新统计数据
     */
    public function updateRecord($date,$data){

        return self::where('date', '=', $date)
            ->where('interest','0.00')
            ->update($data);
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @desc 根据时间获取列表信息
     */
    public function getListByTimesParam($startTime, $endTime){

        return $this->select('date', 'invest_in', 'invest_out', 'cash', 'day_interest', 'interest', 'cost', 'rate')
            ->whereBetween('date', [$startTime, $endTime])
            ->get()
            ->toArray();

    }
}