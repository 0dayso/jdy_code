<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/11/10
 * Time: 10:41
 */

namespace App\Http\Dbs;

use App\Tools\ToolTime;

class TermPrincipalDb extends JdyDb{

    protected $table = 'term_principal';


    /**
     * @return mixed
     * 获取表的数据总数
     */
    public function getTotal(){

        return self::count();
    }

    /**
     * @return mixed
     * 生成数据
     */
    public function createData(){

        $dbPrefix = env('DB_PREFIX');
        $today = ToolTime::dbDate();

        $sql = "insert into ".$dbPrefix.$this->table."(user_id,principal) 
                select user_id,sum(principal) as cash from ".$dbPrefix."refund_record
                where times >='".$today."' 
                and created_at < '".$today."'
                group by user_id";
        return \DB::statement($sql);
    }


    /**
     * @return mixed
     * 清除表中所有的数据
     */
    public function clearData(){

        $sql = "truncate table ".env('DB_PREFIX').$this->table;
        return \DB::statement($sql);

    }


}