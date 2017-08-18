<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2016/12/5
 * Time: 下午6:26
 * Desc: 回款自动进活期
 */

namespace App\Listeners\Invest;

use App\Http\Dbs\FundHistoryDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Logics\Invest\CurrentLogic;
use App\Tools\ToolArray;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cache;

class CurrentAutoListener implements ShouldQueue{


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {



    }


    /**
     * 接收参数，自动投资零钱计划
     */
    public function handle($data)
    {

        $sendRes = Cache::get('A_I_C_S_'.$data['end_time']);

        if( $sendRes ){

            return '';

        }

        $db = new RefundRecordDb();

        //获取未回款总数
        $count = $db->getRefundCountByTimes($data['end_time']);

        //如果还有未回款的,则直接跳过
        if( $count > 0 ){

            return '';

        }

        //获取今日用户已还款列表
        $list = $db->getTodayRefundList();

        if(empty($list)){

            $msg = "回款自动转入零钱计划-当日无用户回款";

            Log::info(__METHOD__.'RefundToCurrentError',['msg' => $msg]);

            return false;

        }

        $userList = ToolArray::arrayToKey($list,'user_id');

        $userFund = [];

        $fundDb     = new FundHistoryDb();

        //用户有充值/提现/交易过不动当前用户的这笔回款
        $userFund   = $fundDb->getTodayFundList();

        $userIds    = ToolArray::arrayToIds($userFund,'user_id');

        $currentLogic = new CurrentLogic();

        $refundList = [];

        foreach ($userList as $userId => $val){

            //判断当前的用户有没有充值,提现,投资过,如果有,则略过
            if(in_array($userId,$userIds)){

                continue;

            }

            $cash = (int)$val['total_cash'];

            if($cash < 1){

                continue;

            }

            $result = $currentLogic->invest($userId, $cash, true);

            if($result['status']){

                $refundList[] = [
                    'user_id' => $userId,
                    'cash' => $cash
                ];
            }

        }

        //回款自动进零钱计划资金列表
        if(!empty($refundList)){

            $params = [
                'event_name'            => 'App\Events\Api\Current\RefundAutoInvestEvent',
                'auto_invest_list'      => json_encode($refundList),
            ];
            \Event::fire('App\Events\Current\RefundAutoInvestEvent',[$params]);

        }

        Cache::put('A_I_C_S_'.$data['end_time'], 100);

    }


}