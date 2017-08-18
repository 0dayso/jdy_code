<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/17
 * Time: 下午3:08
 */

namespace App\Http\Logics\BankCard;

use App\Http\Logics\Logic;

use App\Http\Models\Common\CoreApi\BankCardModel;

use Log;
/**
 * 用户认证卡
 * Class UserAuthBankCard
 * @package App\Http\Logics\BankCard
 */
class UserAuthBankCardLogic extends Logic
{

    /**
     * 获取认证卡管理信息
     *
     * @param int $userId
     * @return array
     */
    public function getUserAuthBankCard($userId = 0){
        try {

            $return = BankCardModel::getUserBindCard($userId);

        }catch (\Exception $e){
            $attributes['userId']           = $userId;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$return]);
    }





}