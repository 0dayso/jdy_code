<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/26
 * Time: 18:21
 */

namespace App\Http\Controllers\Weixin\User;

use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Logics\Current\CreditLogic;

class CurrentController extends UserController{

    /**
     * 我的零钱计划
     */
    public function index(){

        $from   = RequestSourceLogic::getSourceKey('wap');

        $logic  = new UserLogic();

        $currentLogic = new CurrentLogic();

        $userId = $this->getUserId();

        $result = $logic->getCurrentFund($userId);

        $result['data']['bonusInfo'] = $currentLogic->getUserCurrentBonusList($userId, $from);

        return view('wap.user.current.index',$result['data']);
    }


    /**
     * 微信端查看零钱计划债权
     */
    public function viewCredit(){

        $userId = $this->getUserId();
        
        $result = CreditLogic::viewCredit($userId);

        return view('wap.user.current.credit',$result);
        
        
    }
}