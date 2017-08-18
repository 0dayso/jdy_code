<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 16:31
 * Desc: 零钱计划债权db
 */

namespace App\Http\Dbs\Current;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class InvestDb extends JdyDb{

    protected $table = 'current_invest';


    const
        INVEST_CURRENT          = 400,  //零钱计划
        INVEST_OUT_CURRENT      = 401,  //零钱计划转出
        INVEST_CURRENT_AUTO     = 402;  //回款自动转零钱计划


    /**
     * @param $userId
     * @param $cash
     * @param $appRequest
     * @param int $bonusId
     * 零钱计划转入添加记录
     */
    public function doInvest($userId,$cash,$appRequest){

        $this->user_id      = $userId;
        $this->cash         = $cash;
        $this->app_request  = $appRequest;
        $this->type         = self::INVEST_CURRENT;

        $this->save();
    }


    /**
     * @param $userId
     * @param $cash
     * @param $appRequest
     * 零钱计划转出添加记录
     */
    public function doInvestOut($userId,$cash,$appRequest){

        $this->user_id      = $userId;
        $this->cash         = $cash;
        $this->app_request  = $appRequest;
        $this->type         = self::INVEST_OUT_CURRENT;

        $this->save();
    }

    /**
     * @param $list
     * @return mixed
     * 自动回款转零钱计划批量写入
     */
    public function autoInvest($list){

        return self::insert($list);
    }

    /**
     * @desc 零钱计划转入
     * @return mixed
     */
    public function currentInvestIn(){

        return $this->whereIn('type', [self::INVEST_CURRENT,self::INVEST_CURRENT_AUTO]);
    }
   /**
     * @desc 零钱计划主动转入
     * @return mixed
     */
    public function currentInvestManualIn(){

        return $this->where('type', '=', self::INVEST_CURRENT);
    }

    /**
     * @desc 零钱计划自动转入[回款进活期]
     * @return mixed
     */
    public function currentInvestAutoIn(){

        return $this->where('type', '=', self::INVEST_CURRENT_AUTO);
    }


     /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 根据开始结束日期获取投资记录总额
     */
    public function getCurrentAmountByDate($start = '',$end = ''){

        $obj = $this->select(\DB::raw('sum(cash) as cash'), \DB::raw('count(id) as total') ,\DB::raw('DATE_FORMAT(created_at,\'%Y%m%d\') as date'));

        if(!empty($start) && !empty($end)){

            $obj   = $obj->whereBetween('created_at',[$start,$end]);
        }

        $res = $obj->groupBy('date')
            ->orderBy('date','desc')
            ->get()
            ->toArray();

        return $res;

    }
    /**
     * @desc 零钱计划转出
     * @return mixed
     */
    public function currentInvestOut()
    {
        return $this->where('type', self::INVEST_OUT_CURRENT);
    }
    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 获取零钱计划投资总额
     */
    public function getCurrentAmountTotal($start = '', $end=''){

        $obj = $this->select(\DB::raw('sum(cash) as cash'));

        if(!empty($start) && !empty($end)){

            $obj   = $obj->whereBetween('created_at',[$start,$end]);
        }

        $res = $obj->first();

        return $res;
    }

    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 获取用户投资获取的数据
     */
    public function getInvestCurrentListGroupByUserId($start = '', $end=''){

        $obj = $this->where('type',"<>" ,self::INVEST_OUT_CURRENT);

        if(!empty($start) && !empty($end)){

            $obj   = $obj->whereBetween('created_at',[$start,$end]);
        }

        $result    =  $obj->get()->toArray();

        return $result;
    }

    /**
     * @param string $start
     * @param string $end
     * @return array
     * @desc  每天零钱计划的动态,转入,转出
     */
    public  function getInvestCurrentTotalCashByTime($start = '',$end = '')
    {
        $obj = self::select(\DB::raw('sum(cash) as cash'),"type");

        if( !empty($start) ){

            $obj    =   $obj->where('created_at',">=",$start);
        }
        if( !empty($end) ){

            $obj    =   $obj->where('created_at',"<=",$end);
        }

        $result    =  $obj->groupBy('type')->get()->toArray();

        return $result;
    }


    /**
     * @return mixed
     * @零钱计划的投资笔数
     */
    public function getInvestTotalNoAuto()
    {
        return $this->currentInvestManualIn()
                    ->count('id');

    }

    /**
     * @return mixed
     * @desc 投资定期,活期的投资人数,去重
     */
    public function getInvestNumber()
    {

        $dbPrefix = env('DB_PREFIX');

        $investSql  =   'select count(distinct user_id) as total from ( select distinct user_id from '.$dbPrefix.'invest
                        union
                        select distinct user_id from '.$dbPrefix.'current_invest where type='.self::INVEST_CURRENT.') as t1 limit 1';

        $return     =   app('db')->select($investSql);

        return (array)$return[0];
    }
}


