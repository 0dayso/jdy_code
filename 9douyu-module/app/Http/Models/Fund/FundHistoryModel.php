<?php

namespace App\Http\Models\Fund;

use App\Http\Models\Model;

use App\Tools\ToolCurl;

use Config;

use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use Log;

use App\Http\Dbs\Fund\FundHistoryDb;

use App\Tools\ToolMoney;
/*
 * 资金历史记录
 */
class FundHistoryModel extends Model{

    public static $codeArr            = [
        'getCoreHistoryData' => 1,

    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_FUND_HISTORY;

    /**
     * 获取核心历史数据列表
     *
     * @param array $data
     * @throws \Exception
     */
    public function getCoreHistoryData($data = []){
        try {

            $data = \App\Http\Models\Common\CoreApi\FundHistoryModel::getFundList($data);

        }catch (\Exception $e){
            
            $data['msg']            = $e->getMessage();
            $data['code']           = $e->getCode();

            Log::error(__METHOD__.'curl-Error', $data);

            throw new \Exception(LangModel::getLang('ERROR_FUND_HISTORY_LIST_GET_FAIL'), self::getFinalCode('getCoreHistoryData'));
        }

        return $data;
    }

    /**
     * 金额转化【元 转 分】
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getCoreHistoryFormData($data = []){
        $data = $this->getCoreHistoryData($data);
        return $data;
    }
//
//    /**
//     *
//     * @param int $event_id
//     * @return mixed
//     */
//    public static function getLable($event_id = 0){
//
//        $label = [
//            FundHistoryDb::PROJECT_REFUND          => '投资回款',
//            FundHistoryDb::INVEST_PROJECT          => '定期',
//            FundHistoryDb::INVEST_CURRENT          => '零钱计划',
//            FundHistoryDb::WITHDRAW_ORDER_FAILED   => '提现失败',
//            FundHistoryDb::INVEST_OUT_CURRENT      => '零钱计划转出',
//            FundHistoryDb::INVEST_CURRENT_AUTO     => '回款自动转零钱计划',
//            FundHistoryDb::INVEST_CREDIT_PROJECT   => '债权转让',
//            FundHistoryDb::RECHARGE_ORDER          => '充值',
//            FundHistoryDb::WITHDRAW_ORDER          => '提现',
//            FundHistoryDb::WITHDRAW_ORDER_CANCLE   => '取消提现',
//        ];
//        if(isset($label[$event_id]))
//            return $label[$event_id];
//
//        if($event_id){
//            return null;
//        }
//        return $label;
//    }




    /**
     * 获取资金变动前金额
     * @param int $balance
     * @return mixed
     */
    public static function getBalanceBeforeAdd($balance = 0){
        return ToolMoney::formatDbCashAdd($balance);
    }

    /**
     * 获取资金变动金额
     * @param int $balance
     * @return mixed
     */
    public static function getBalanceChangeAdd($balance = 0){
        return ToolMoney::formatDbCashAdd($balance);
    }


    /**
     * @param $eventId
     * @return array
     * 新系统零钱计划转入转出事件ID转化
     */
    public static function formatCurrentEventId($eventId){

        $list = [
            FundHistoryDb::INVEST_CURRENT       => FundHistoryDb::OLD_CURRENT_INVEST,
            FundHistoryDb::INVEST_OUT_CURRENT   => FundHistoryDb::OLD_CURRENT_INVEST_OUT,
            FundHistoryDb::INVEST_CURRENT_AUTO  => FundHistoryDb::OLD_CURRENT_INVEST,
        ];

        return $list[$eventId];
    }

    /**
     * @return array
     * @desc fundHistory的事件解释
     */
    public static function getEventIdToExplain()
    {
        return [
            FundHistoryDb::PROJECT_REFUND          => '定期回款',  //回款
            FundHistoryDb::INVEST_PROJECT          => '定期投资',  //定期
            FundHistoryDb::INVEST_CURRENT          => '零钱转入',  //零钱计划
            FundHistoryDb::INVEST_OUT_CURRENT      => '零钱转出',  //零钱计划转出
            FundHistoryDb::INVEST_CURRENT_AUTO     => '回款转零钱',  //回款自动转零钱计划
            FundHistoryDb::INVEST_CREDIT_PROJECT   => '投资债券',  //债转
            FundHistoryDb::RECHARGE_ORDER          => '充值',  //充值
            FundHistoryDb::WITHDRAW_ORDER          => '提现',  //提现
            FundHistoryDb::WITHDRAW_ORDER_FAILED   => '提现失败',  //提现失败
            FundHistoryDb::WITHDRAW_ORDER_CANCLE   => '取消提现',  //取消提现
            FundHistoryDb::ACTIVITY_AWARD          => '活动奖励',  //活动奖励
            FundHistoryDb::CREDIT_ASSIGN_PROJECT   => '债券转让',  //出让
            FundHistoryDb::INVEST_CREDIT_ASSIGN    => '投资债券',  //投资
            FundHistoryDb::SYSTEM_DEDUCT           => '系统扣除',  //系统扣除
            FundHistoryDb::INVEST_CURRENT_NEW      => '新版零钱计划转入',  //新版零钱计划
            FundHistoryDb::INVEST_OUT_CURRENT_NEW  => '新版零钱计划转出',  //新版零钱计划转出
        ];
    }

    /**
     * @param $eventId
     * @return mixed|string
     * @desc 获取eventNote
     */
    public static function getEventNoteByEvent($eventId){

        $eventArr = self::getEventIdToExplain();

        return isset($eventArr[$eventId]) ? $eventArr[$eventId] : '';

    }
}