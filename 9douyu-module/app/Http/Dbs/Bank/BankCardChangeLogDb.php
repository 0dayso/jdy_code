<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 16:26
 * Desc: 用户绑卡Db
 */

namespace App\Http\Dbs\Bank;

use App\Http\Dbs\JdyDb;

class BankCardChangeLogDb extends JdyDb{


    /**
     * @param $userId
     * @param $oldCardNo
     * @param $newCardNo
     * 添加换卡记录
     */
    public function add($userId,$oldCardNo,$newCardNo){

        $this->user_id = $userId;
        $this->old_card = $oldCardNo;
        $this->new_card = $newCardNo;

        return $this->save();
    }


}