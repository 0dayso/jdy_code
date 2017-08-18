<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 16:26
 * Desc: 用户绑卡Db
 */

namespace App\Http\Dbs;

use App\Tools\ToolTime;


class AuthCardDb extends JdyDb{

    //表名
    protected $table = 'auth_card';

    /**
     * @param $userId
     * @return mixed
     * 根据用户ID获取绑卡信息
     */
    public function getAuthCardByUserId($userId){

        return self::where('user_id',$userId)->first();

    }


    /**
     * @param $userId
     * @param $bankId
     * @param $cardNumber
     * 新增绑定卡
     */
    public function add($userId,$bankId,$cardNumber){

        $this->user_id = $userId;
        $this->bank_id = $bankId;
        $this->card_number = $cardNumber;

        $this->save();
    }

    /**
     * @param $userId
     * @param $oldCardNo
     * @param $newCardNo
     * @param $bankId
     * @return mixed
     * 更换银行卡号
     */
    public function updateCard($userId,$oldCardNo,$newCardNo,$bankId){

        $data = [
            'card_number'   => $newCardNo,
            'bank_id'       => $bankId
        ];
        return self::where('user_id',$userId)
            ->where('card_number',$oldCardNo)
            ->update($data);
    }

    /**
     * @param $cardNo
     * @return mixed
     * 根据卡号获取绑卡信息
     */
    public function getAuthCardByCardNo($cardNo){

        return self::where('card_number',$cardNo)
            ->first();
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 冻结银行卡
     */
    public function frozenByUserId( $userId ){

        return self::where('user_id', $userId)
            ->update( ['card_number'=>\DB::raw("concat('".UserDb::FROZEN_STR."', card_number, '.', '".rand(100,999)."')")] );

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 解冻银行卡
     */
    public function unFrozenByUserId( $userId ){

        return self::where('user_id', $userId)
            ->update( ['card_number'=>\DB::raw("SUBSTRING_INDEX(REPLACE(card_number, '".UserDb::FROZEN_STR."' ,'') ,'.', 1)")] );

    }

}