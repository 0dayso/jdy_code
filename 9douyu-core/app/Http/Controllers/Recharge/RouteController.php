<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 19:26
 * Desc: 充值路由选择控制器
 */

namespace App\Http\Controllers\Recharge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Logics\Recharge\RouteLogic;
use App\Tools\ToolMoney;

class RouteController extends Controller{


    /**
     * @param Request $request
     * @return array
     * 支付路由选择 该接口已废弃
     */
    public function userRechargeChannel(Request $request){

        $userId = $request->get('user_id',0);
        $cash   = $request->get('cash',0);
        $bankId = $request->get('bank_id',0);

        $cash = ToolMoney::formatDbCashAdd($cash);//金额统一在controller处理
        
        $logic = new RouteLogic();
        $result = $logic->choiceRouteChannel($userId,$cash,$bankId);

        return self::returnJson($result);
    }


}