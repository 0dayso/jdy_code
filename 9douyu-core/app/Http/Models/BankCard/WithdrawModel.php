<?php
/**
 * User: zhangshuang
 * Date: 16/4/23
 * Time: 11:47
 * Desc: 提现卡相关model层
 */

namespace App\Http\Models\BankCard;

use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Dbs\BankCardDb;

class WithdrawModel extends CardModel{

    public static $codeArr = [

        'bindCardBeforBind'             => 1,
        'bindCardGetListByCardNo'       => 2,
        'deleteCardBeforBind'           => 3,
        'deleteCardDel'                 => 4,
        'getWithdrawCard'               => 5,

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_WITHDRAW_CARD;

    /**
     * @param $userId
     * @param $cardNo
     * 绑定提现银行卡
     */
    public static function bindCard($userId,$bankId,$cardNo){

        $authCard = self::beforBind($userId);

        if($authCard){
            throw new \Exception(LangModel::getLang('ERROR_WITHDRAW_BANK_CARD_CAN_NOT_REPEAT_ADD'),self::getFinalCode('bindCardBeforBind'));
        }

        //判断该银行卡是否已存在
        $db = new BankCardDb();
        $cardList = $db->getListByCardNo($cardNo);

        if($cardList){
            throw new \Exception(LangModel::getLang('ERROR_WITHDRAW_BANK_CARD_IS_BINDED'),self::getFinalCode('bindCardGetListByCardNo'));

        }

        return $db->add($userId,$bankId,$cardNo);
    }


    /**
     * @param $userId
     * @param $cardNo
     * @throws \Exception
     * 删除提现银行卡
     */
    public static function deleteCard($userId,$cardNo){

        $authCard = self::beforBind($userId);

        if($authCard){
            throw new \Exception(LangModel::getLang('ERROR_WITHDRAW_BANK_CARD_CAN_NOT_DELETE'),self::getFinalCode('deleteCardBeforBind'));
        }

        //判断该银行卡是否已存在
        $db = new BankCardDb();

        //判断提现卡是否存在
        $withdrawCard = $db->getUserCardByCardNumber($userId,$cardNo);

        if(!$withdrawCard){

            throw new \Exception(LangModel::getLang('ERROR_WITHDRAW_BANK_CARD_IS_NOT_EXISTS'),self::getFinalCode('deleteCardGetUserCardByCardNumber'));

        }

        $result = $db->del($userId,$cardNo);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_WITHDRAW_BANK_CARD_DELETE_FAILED'),self::getFinalCode('deleteCardDel'));

        }
    }


    /**
     * @param $userId
     * 获取用户提现银行卡列表
     */
    public static function getWithdrawCard($userId){

        $withdrawCardList = [];

        //获取所有可用的提现银行卡列表，一个用户可能有多个提现卡
        $bankCardDb = new BankCardDb();
        $bankCard = $bankCardDb->getUserCardList($userId);

        if(!empty($bankCard)){

            //获取用户绑定卡信息，同卡进出
            $authCard = self::getUserAuthCard($userId);
            
            $tmpList = [];
            if(empty($authCard)){
                $tmpList = $bankCard;
            }else{
                //绑定银行卡号
                $authCardNumber = $authCard['card_number'];
                //从提现卡中选中绑卡定，同卡进出
                foreach($bankCard as $list){
                    if($authCardNumber === $list['card_number']){
                        $tmpList[] = $list;
                    }
                }

            }
            //返回结果处理
            foreach($tmpList as $bank){
                $withdrawCardList[] = [
                    'id'      => $bank['id'],
                    'bank_id' => $bank['bank_id'],
                    'card_no' => $bank['card_number']
                ];
            }

        }
        //判断是否有可用的提现银行卡
        if(empty($withdrawCardList)){

            throw new \Exception(LangModel::getLang('ERROR_WITHDRAW_BANK_CARD_IS_NOT_EXISTS'),self::getFinalCode('getWithdrawCard'));

        }

        return $withdrawCardList;
    }
}