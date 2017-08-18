<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/7/11
 * Time: 下午4:47
 * Desc: 银行卡管理
 */

namespace App\Http\Controllers\Weixin\User;

use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\Pay\WithdrawLogic;

class CardController extends UserController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 用户提现银行卡列表
     */
    public function index()
    {

        $userId = $this->getUserId();

        $withDrawLogic = new WithdrawLogic();

        $result = $withDrawLogic->getWithdrawCardForApp($userId);

        $result = array_filter($result['data']['list']);
        $data['cards']     = isset($result) ? $result : '';
        $data['real_name'] = isset($result['data']['user_name']) ? $result['data']['user_name'] : '';

        return view('wap.user.BankCard/mybankcard', $data);

    }

}