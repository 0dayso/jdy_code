<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每天04:00回款自动转零钱计划
 */
namespace App\Console\Commands\Day\Current;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Logics\Invest\CurrentLogic;
use App\Tools\ToolArray;
use Illuminate\Console\Command;
use Log;

class AutoRefundToCurrent extends Command{

    //计划任务唯一标识
    protected $signature = 'AutoRefundToCurrent';

    //计划任务描述
    protected $description = '每天1点,回款自动进零钱计划';


    public function handle(){

        $refundDb  = new RefundRecordDb();

        //1.获取今日用户的回款列表
        $list = $refundDb->getTodayRefundList();

        if(empty($list)){

            $msg = "回款自动转入零钱计划-当日无用户回款";
            Log::info(__METHOD__.'RefundToCurrentError',['msg' => $msg]);
            return false;
        }
        
        $userList = ToolArray::arrayToKey($list,'user_id');

        //回款的金额在进入零钱计划的这个时刻如果用户有充值/提现/交易过不动当前用户的这笔回款
        //2.判断当前的用户有没有充值,提现,投资过
        $userFund = [];

        $fundDb     = new FundHistoryDb();

        $userFund   = $fundDb->getTodayFundList();

        $userIds    = ToolArray::arrayToIds($userFund,'user_id');

        $currentLogic = new CurrentLogic();

        $refundList = [];

        foreach ($userList as $userId => $val){

            if(in_array($userId,$userIds)){
                continue;
            }

            $cash = (int)$val['total_cash'];

            if($cash < 1){
                continue;
            }
            $result = $currentLogic->invest($userId,$cash,true);

            if($result['status']){

                $refundList[] = [
                    'user_id' => $userId,
                    'cash' => $cash
                ];
            }

        }
        //回款自动进零钱计划资金列表
        if($refundList){

            $params = [
                'event_name'            => 'App\Events\Api\Current\RefundAutoInvestEvent',
                'auto_invest_list'      => json_encode($refundList),
            ];
            \Event::fire('App\Events\Api\Current\RefundAutoInvestEvent',[$params]);

        }
    }
}