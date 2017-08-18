<?php
/**
 * User: caelyn
 * Date: 16/6/27
 * Time: 13:09
 * Desc: 银行信息Db
 */
namespace App\Http\Dbs\Bank;

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

    public function getBankName($id){
        return $this->dbToArray(
            self::select('name')
            ->where('id',$id)
            ->first()
        );
    }
    

}
