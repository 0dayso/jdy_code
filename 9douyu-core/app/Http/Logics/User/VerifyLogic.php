<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/4/23
 * Time: 16:43
 */

namespace App\Http\Logics\User;

use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;
use App\Http\Models\BankCard\RechargeModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Common\UserModel;

class VerifyLogic extends Logic{


    /**
     * @param $userId
     * @param $name
     * @param $cardNo
     * @param $bankId
     * @param $idCard
     * 实名 + 绑卡
     */
    public function verify($userId,$name,$cardNo,$bankId,$idCard){

        try{

            self::beginTransaction();
            //简单的数据校验
            ValidateModel::isUserId($userId);
            ValidateModel::isBankCard($cardNo);
            ValidateModel::isBankId($bankId);
            ValidateModel::isName($name);
            ValidateModel::isIdCard($idCard);

            //判断用户是否已实名过
            $userModel = new UserModel();
            $userModel->checkUserVerify($userId);

            //判断身份证是否已实名
            $userModel->checkIdCardUnique($idCard);

            //检查银行卡是否被绑定
            RechargeModel::checkCardCanBind($cardNo);

            //绑卡
            RechargeModel::bindCardByCard($userId,$bankId,$cardNo);

            //实名
            $userDb = new UserDb();
            $userDb->verify($userId,$name,$idCard);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());

        }

        $data = [
            'user_id'   => $userId,
            'name'      => $name,
            'card_no'   => $cardNo,
            'id_card'   => $idCard,
        ];
        return self::callSuccess($data);

    }

    /**
     * @param $userId
     * @param $name
     * @param $cardNo
     * @param $bankId
     * @param $idCard
     * @param $tradingPassword
     * 实名 + 绑卡 + 交易密码
     */
    public function verifyTradingPassword($userId,$name,$cardNo,$bankId,$idCard, $tradingPassword){

        try{

            self::beginTransaction();
            //简单的数据校验
            ValidateModel::isUserId($userId);
            ValidateModel::isBankCard($cardNo);
            ValidateModel::isBankId($bankId);
            ValidateModel::isName($name);
            ValidateModel::isIdCard($idCard);

            //判断用户是否已实名过
            $userModel   = new UserModel();
            $isVerify    = $userModel->checkUserVerify($userId, false);

            if(!$isVerify){
                //判断身份证是否已实名
                $userModel->checkIdCardUnique($idCard);

                //实名
                $userDb = new UserDb();
                $userDb->verify($userId,$name,$idCard);
            }

            //检查银行卡是否被绑定
            $isBind = RechargeModel::checkCardCanBind($cardNo, false);
            if(!$isBind) {
                //绑卡
                RechargeModel::bindCardByCard($userId, $bankId, $cardNo, false);
            }

            // 设置交易密码
            $userModel->doModifyTradingPassword($userId, $tradingPassword);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());

        }

        $data = [
            'user_id'   => $userId,
            'name'      => $name,
            'card_no'   => $cardNo,
            'id_card'   => $idCard,
        ];
        return self::callSuccess($data);

    }



    /**
     * @param $userId
     * @param $name
     * @param $idCard
     * 实名
     */
    public function realName($userId, $name, $idCard){

        try{

            self::beginTransaction();
            //简单的数据校验
            ValidateModel::isUserId($userId);
            ValidateModel::isName($name);
            ValidateModel::isIdCard($idCard);

            //判断用户是否已实名过
            $userModel = new UserModel();
            $userModel->checkUserVerify($userId);

            //判断身份证是否已实名
            $userModel->checkIdCardUnique($idCard);

            //实名
            $userDb = new UserDb();
            $userDb->verify($userId,$name,$idCard);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());

        }

        $data = [
            'user_id'   => $userId,
            'name'      => $name,
            'id_card'   => $idCard,
        ];
        return self::callSuccess($data);

    }

}