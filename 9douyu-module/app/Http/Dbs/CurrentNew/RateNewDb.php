<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/27
 * Time: 下午3:02
 * Desc: 零钱计划利率
 */

namespace App\Http\Dbs\CurrentNew;

use App\Tools\ToolTime;
use App\Http\Dbs\JdyDb;

class RateNewDb extends JdyDb
{

    protected $table = 'current_new_rate';
    /**
     * @param string $date
     * @return mixed
     * @desc 根据时间获取信息
     */
    public function getInfoByDate($date='')
    {

        $date = $date ? $date : ToolTime::dbDate();

        return self::where('rate_date',$date)
                ->first();

    }

    /**
     * 获取今日前端显示的零钱计划利率
     */
    public function getShowRate(){

        $date = ToolTime::dbDate();

        $result =  self::select('rate','profit_percentage')
            ->where('rate_date','<=',$date)
            ->orderBy('rate_date','desc')
            ->first();

        return $this -> dbToArray($result);

    }

    /**
     * @param $date
     * @param $rate
     * @param $profile
     * @return bool
     * 添加零钱计划利率
     */
    public function add($date,$rate,$profile){

        $this->rate_date            = $date;
        $this->rate                 = $rate;

        $this->profit_percentage    = $profile;

        return $this->save();
    }

    /**
     * @return mixed
     * 获取昨日零钱计划利率
     */
    public function getYesterdayRate(){

        $date = date('Y-m-d',strtotime('-1 day'));

        $result = self::select('rate','profit_percentage')
            ->where('rate_date',$date)
            ->first();

        return $this->dbToArray($result);

    }

    /**
     * @param $id
     * @return mixed
     * 根据主键ID获取利率信息
     */
    public function getById($id){

        return self::find($id);
    }

    /**
     * @param $id
     * @param $date
     * @param $rate
     * @param $profit
     * @return mixed
     * 编辑零钱计划利率
     */
    public function edit($id,$date,$rate,$profit){

        $data = [
            'rate'                  => $rate,
            'rate_date'             => $date,
            'profit_percentage'     => $profit
        ];
        return self::where('id',$id)
            ->update($data);
    }

    /**
     * @desc 获取零钱计划利率列表
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getRateList($page, $pageSize){
        $start = $this->getLimitStart($page, $pageSize);
        $rateList = self::orderBy('rate_date', 'desc')
            ->skip($start)
            ->paginate($pageSize)
            ->toArray();
        return $rateList;
    }


}