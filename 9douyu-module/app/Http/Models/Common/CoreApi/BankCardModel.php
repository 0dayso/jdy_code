<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 11:41
 * Desc: 核心银行卡调用Model
 */



namespace App\Http\Models\Common\CoreApi;

use App\Http\Models\Common\CoreApiModel;
use Config;
use App\Http\Models\Common\HttpQuery;

class BankCardModel extends CoreApiModel{

    /**
     * @param $userId       用户ID    必填
     * @return array
     * 获取用户绑定银行卡信息
     */
    public static function getUserBindCard($userId){

        $api  = Config::get('coreApi.moduleBankCard.getUserBindCard');

        $params = [
            'user_id'   => $userId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }

    }

    /**
     * @param $userId       用户ID    必填
     * @return array
     * 获取用户提现银行卡列表
     */
    public static function getUserWithdrawCard($userId){

        $api  = Config::get('coreApi.moduleBankCard.getUserWithdrawCard');

        $params = [
            'user_id'   => $userId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }

    }

    /**
     * @param $bankCardId       银行卡主键ID    必填
     * @return array
     * 根据ID获取提现银行卡列表
     */
    public static function getWithdrawCardById($bankCardId){

        $api  = Config::get('coreApi.moduleBankCard.getWithdrawCardById');

        $params = [
            'bank_card_id'   => $bankCardId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }

    }


    /**
     * @param $userId       用户ID    必填
     * @param $bankId       银行ID    必填
     * @param $cardNo       银行卡号    必填
     * @return array
     * 添加提现银行卡号
     */
    public static function doCreateWithdrawCard($userId,$bankId,$cardNo){

        $api  = Config::get('coreApi.moduleBankCard.doCreateWithdrawCard');

        $params = [
            'user_id'   => $userId,
            'bank_id'   => $bankId,
            'card_no'   => $cardNo,
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }

    /**
     * @param $userId       用户ID    必填
     * @param $cardNo       银行卡号    必填
     * @return array
     * 删除提现银行卡
     */
    public static function doDeleteWithdrawCard($userId,$bankId,$cardNo){

        $api  = Config::get('coreApi.moduleBankCard.doDeleteWithdrawCard');

        $params = [
            'user_id'   => $userId,
            'card_no'   => $cardNo,
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param $userId       用户ID    必填
     * @param $bankId       新卡银行ID    必填
     * @param $oldCardNo    旧卡号
     * @param $newCardNo    新卡号
     * @return array
     * 更新绑定银行卡
     */
    public static function doChangeBindCard($userId,$bankId,$oldCardNo,$newCardNo){

        $api  = Config::get('coreApi.moduleBankCard.doChangeBindCard');

        $params = [
            'user_id'       => $userId,
            'bank_id'       => $bankId,
            'old_card_no'   => $oldCardNo,
            'new_card_no'   => $newCardNo
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


}