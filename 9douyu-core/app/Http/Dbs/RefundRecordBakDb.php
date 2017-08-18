<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/26
 * Time: 16:52
 */

namespace App\Http\Dbs;

class RefundRecordBakDb extends JdyDb{

    protected $table = 'refund_record_bak';


    /**
     * @param $data
     * @return mixed
     * 备份原还款计划
     */
    public function addRecord($data){

        return self::insert($data);
    }
}