<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/17
 * Time: 下午3:08
 */

namespace Tests\Http\Logics\BankCard;

use App\Http\Logics\BankCard\UserAuthBankCardLogic;

/**
 * 银行卡管理
 *
 * Class UserAuthBankCardLogicTest
 * @package Tests\Http\Logics\BankCard
 */
class UserAuthBankCardLogicTest extends \TestCase{


    /**
     * 获取认证卡管理信息
     *
     * @return array
     */
    public function testGetUserAuthBankCard(){
        $userAuthBankCardLogic = new UserAuthBankCardLogic;
        $data = $userAuthBankCardLogic->getUserAuthBankCard(82692);
        echo print_r($data, true);
    }




}