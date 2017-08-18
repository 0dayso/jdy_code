<?php
/**
 * @desc    资金每日统计
 */

namespace App\Http\Dbs\DbKvdb;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class DbKvdbDb extends JdyDb
{

    protected $table = 'db_kvdb';

    const   RAWKEY_FUND     = "FUND_STATISTICS",        //账户资金统计
            RAWKEY_RECHARGE = "RECHARGE_STATISTICS";    //账户充值统计


    /**
     * @param   $data
     * @return  bool
     * @desc    添加数据
     */
    public function doDbKvdbAdd($data)
    {

        return self::insert($data);

    }

    /**
     * @param   $date
     * @return  array
     * @desc    根据健值获取数据
     */

    public function getDbKvdbByRawkey( $rawKey='' ){

        return $this->dbToArray(
                $this->where('rawkey', $rawKey)->get()
            );

    }


    /**
     * @param   $page
     * @param   $size
     * @return  array
     * @desc    分页列表数据
     */
    public function getDbKvdbList( $rawkey, $page, $size , $startTime, $endTime){

        $start = $this->getLimitStart($page, $size);

        $dbobj  = self::select("id","rawkey","val","created_at");
        
        $dbobj->where('rawkey', $rawkey);

        // 时间范围
        if($startTime && $endTime){

             $startTime = date("Y-m-d", strtotime($startTime)+86400);
             $endTime   = date("Y-m-d", strtotime($endTime)+86400*2);
             $dbobj->where('created_at', '>', $startTime);
             $dbobj->where('created_at', '<=', $endTime);


        }elseif($startTime && !$endTime){

            $startTime = date("Y-m-d", strtotime($startTime)+86400);
            $dbobj->where('created_at', '>', $startTime);

        }elseif(!$startTime && $endTime){

            $endTime   = date("Y-m-d", strtotime($endTime)+86400*2);
            $dbobj->where('created_at', '<=', $endTime);

        }

        $total = $dbobj->count('id');

        $list = $dbobj->orderBy('id', 'desc')
            ->skip($start)
            ->take($size)
            ->get()
            ->toArray();


        return [ 'total' => $total, 'list' => $list];

    }


}