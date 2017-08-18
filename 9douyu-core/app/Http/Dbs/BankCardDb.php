<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 16:26
 * Desc: 用户提现卡Db
 */

namespace App\Http\Dbs;
use App\Tools\ToolTime;

class BankCardDb extends JdyDb{

    protected $table = 'bank_card';

    /**
     * @param $userId
     * @param string $status
     * 获取用户正常的提现卡列表
     */
    public function getUserCardList($userId){

        return self::where('user_id',$userId)
                   ->get()
                   ->toArray();

    }


    /**
     * @param $cardNo
     * @return mixed
     * 根据银行卡号获取所有的记录
     */
    public function getListByCardNo($cardNo){

        return self::where('card_number',$cardNo)
            ->get()
            ->toArray();
    }

    /**
     * @param $userId
     * @param $bankId
     * @param $cardNumber
     * @return  int
     * 添加提现银行卡
     */
    public function add($userId,$bankId,$cardNo){

        $this->user_id = $userId;
        $this->bank_id = $bankId;
        $this->card_number = $cardNo;

        $this->save();

        return $this->id;
    }

    /**
     * @param $userId
     * @param $cardNo
     * @return bool|null
     * 删除提现银行卡
     */
    public function del($userId,$cardNo){

        return self::where('user_id',$userId)
            ->where('card_number',$cardNo)
            ->delete();
    }


    /**
     * @param $userId
     * @param $cardNumber
     * @return mixed
     * 根据卡号获取用户提现卡信息
     */
    public function getUserCardByCardNumber($userId,$cardNo){

        return self::where('card_number',$cardNo)
                    ->where('user_id',$userId)
                    ->first();

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
     * @param $id
     * @return mixed
     * 根据主键ID获取提现银行卡信息
     */
    public function getWithdrawCardById($id){

        return self::find($id);
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 冻结银行卡
     */
    public function frozenByUserId( $userId ){



        return self::where('user_id', $userId)
            ->update( ['card_number'=>\DB::raw("concat('".UserDb::FROZEN_STR."',card_number, '.','".rand(100,999)."')")] );

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 解冻银行卡
     */
    public function unFrozenByUserId( $userId ){

        return self::where('user_id', $userId)
            ->update( ['card_number'=>\DB::raw("SUBSTRING_INDEX(REPLACE(card_number, '".UserDb::FROZEN_STR."' ,''), '.', 1)")] );

    }

}