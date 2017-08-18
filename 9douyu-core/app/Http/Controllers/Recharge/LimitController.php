<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 19:27
 * Desc: 限额列表控制器
 */

namespace App\Http\Controllers\Recharge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Logics\Recharge\LimitLogic;
use App\Tools\ToolMoney;

class LimitController extends Controller{


    private $logic = null;

    private $userId  = 0;

    public function __construct(Request $request){

        $this->userId = $request->input('user_id',0);

        parent::__construct($request);


        $this->logic = new LimitLogic();
    }

    /**
     * @return array
     * 获取绑卡用户限额
     * ***********该接口已迁移至模块***********
     */
    public function getUserBindCardLimit(){

        $result     = $this->logic->getBindCardLimitByUserId($this->userId);
        //金额处理成元
        if($result['status']){
            $result['data']['cash'] = ToolMoney::formatDbCashDelete($result['data']['cash']);
        }

        return self::returnJson($result);
    }


    /**
     * @return array
     * 获取未绑卡用户银行限额列表
     * ***********该接口已迁移至模块***********
     */
    public function getRechargeCardLimit(){

        $result     = $this->logic->getRechargeCardLimit($this->userId);
        //金额出口统一处理成元为单位
        if($result['status']){
            foreach($result['data'] as $key =>$val){
                $cash = $val['cash'];
                $result['data'][$key]['cash'] = ToolMoney::formatDbCashDelete($cash);
            }
        }

        return self::returnJson($result);
    }
}