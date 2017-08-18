<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/7
 * Time: 下午4:48
 */

namespace App\Http\Controllers\Weixin\User;


use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\Project\RefundRecordLogic;
use Illuminate\Http\Request;

class RefundPlanController extends UserController
{



    /**
     * @param Request $request
     * @return array
     * @desc 回款计划
     */
    public function refundPlan(Request $request){

        $userId = $this -> getUserId();

        $logic = new RefundRecordLogic();

        $result = $logic -> getRefundPlanByMonthByUserId( $userId );

        $list = [];
        if(!empty($result['data']['data'])){
            $lists = $logic->getRefundPlanFormatYear($result['data']['data']);
            $list = ['lists'=> $lists];
        }

        return view('wap.user.RefundPlan/index', $list);

    }

    /**
     * @param Request $request
     * @return array
     * @desc 当月回款具体信息
     */
    public function refundPlanByDate($date, $total, $num){

        $userId = $this -> getUserId();

        $logic = new RefundRecordLogic();

        $result = $logic -> refundPlanByDate( $userId, $date );

        $list['lists'] = isset($result['data']) ? $result['data'] : [];

        $list['total'] = $total;

        $list['num'] = $num;

        return view('wap.user.RefundPlan/bydate', $list);

    }


}