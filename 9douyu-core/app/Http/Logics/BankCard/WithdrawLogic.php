<?php
/**
 * User: zhangshuang
 * Date: 16/4/15
 * Time: 13:13
 * Desc: 提现银行卡相关逻辑层
 */

namespace App\Http\Logics\BankCard;
use App\Http\Logics\Logic;
use App\Http\Dbs\BankCardDb;
use App\Http\Dbs\AuthCardDb;
use App\Http\Models\Common\BankCardModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\BankCard\WithdrawModel;


class WithdrawLogic extends Logic{


    /**
     * @param int $userId
     * @return array
     * 根据用户获取提现银行卡列表
     */
    public function getWithdrawCardByUserId($userId = 0){

        try{
            //验证用户ID是否正确
            //WithdrawModel::isUserId($userId);
            ValidateModel::isUserId($userId);

            /*
            //获取所有可用的提现银行卡列表，一个用户可能有多个提现卡
            $bankCardDb = new BankCardDb();
            $bankCard = $bankCardDb->getUserCardList($userId);

            if(!empty($bankCard)){
                //获取用户绑定卡信息，同卡进出

                $authCard = BankCardModel::getUserAuthCard($userId);

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
                        'bank_id' => $bank['bank_id'],
                        'card_no' => $bank['card_number']
                    ];
                }

            }
            */

            $withdrawCardList = WithdrawModel::getWithdrawCard($userId);

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess($withdrawCardList);
    }


    /**
     * @param $userId
     * @param $bankId
     * @param $cardNo
     * 绑定提现银行卡
     */
    public function bindCard($userId,$bankId,$cardNo){

        try{

            //数据验证
            ValidateModel::isUserId($userId);
            ValidateModel::isBankId($bankId);
            ValidateModel::isBankCard($cardNo);

            //绑定提现银行卡
            $id = WithdrawModel::bindCard($userId,$bankId,$cardNo);

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        $data = [
            'user_id'       => $userId,
            'bank_id'       => $bankId,
            'card_no'       => $cardNo,
            'bank_card_id'  => $id,
        ];

        return self::callSuccess($data);
    }

    /**
     * @param $userId
     * @param $cardNo
     * @return array
     * 删除提现银行卡
     */
    public function deleteCard($userId,$cardNo){

        try{

            //数据验证
            ValidateModel::isUserId($userId);
            ValidateModel::isBankCard($cardNo);

            //绑定提现银行卡
            WithdrawModel::deleteCard($userId,$cardNo);

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        $data = [
            'user_id' => $userId,
            'card_no' => $cardNo
        ];

        return self::callSuccess($data);
    }

    /**
     * @param $id
     * @return array
     * 根据提现卡主键获取相应的信息
     */
    public function getWithdrawCardById($id){
        
        $cardDb = new BankCardDb();
        $object = $cardDb->getWithdrawCardById($id);

        if(!empty($object)){

            $cardInfo = $object->toArray();

        }else{
            $cardInfo = [];
        }

        return self::callSuccess($cardInfo);

    }

}