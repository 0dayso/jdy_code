<?php
/**
 * User: zhangshuang
 * Date: 16/4/19
 * Time: 13:09
 * Desc: 银行信息Db
 */


namespace App\Http\Dbs;

use App\Http\Dbs\JdyDb;

class BankDb extends JdyDb{

    protected $table = "bank";

    /**
     * @return mixed
     * 获取所有的银行卡信息
     */
    public function getAllBank(){

        return self::select('id','name')
            ->get()
            ->toArray();
    }

    /**
     * 获取银行卡信息
     *
     * @param array $ids
     * @return array
     */
    public static function getBankList($ids = []){
        if($ids){
            return self::whereIn('id', $ids)->get()->toArray();
        }
        return [];
    }
}
