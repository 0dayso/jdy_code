<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/11/10
 * Time: 10:41
 */

namespace App\Http\Dbs;

class CurrentPrincipalDb extends JdyDb{

    protected $table = 'current_principal';


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

        $sql = "insert into ".$dbPrefix.$this->table."(user_id,principal) select user_id,cash from ".$dbPrefix."current_account";

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


    /**
     * @param $userId
     * @param $cash
     * @return mixed
     * 更新活期本金
     */
    public function updateRecord($userId,$cash){

        return self::where('user_id',$userId)
            ->update(['principal' => \DB::raw(sprintf('`principal`+%.2f', $cash))]);

    }
}