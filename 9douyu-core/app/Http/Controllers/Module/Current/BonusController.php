<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/13
 * Time: 17:03
 * Desc: 零钱计划加息券相关
 */
namespace App\Http\Controllers\Module\Current;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Logics\Module\Current\BonusLogic;

class BonusController extends Controller{

    /**
     * @param Request $request
     * 零钱计划加息券计息接收数据入口
     * interest_list 格式为以下数据的json形式
     *  array(
     *      array('user_id' => 82692,
     *            'rate'    => 2.0
     *      ),
     *      array('user_id' => 82691,
     *            'rate'    => 2.0
     *      )
     * )
     */
    public function interestAccrual(Request $request){

        $interestList = $request->input('interest_list','');
        //数据入队列
        $logic = new BonusLogic();
        $result = $logic->saveInterestUserList($interestList);
        
        return self::returnJson($result);
        
        
    }
}