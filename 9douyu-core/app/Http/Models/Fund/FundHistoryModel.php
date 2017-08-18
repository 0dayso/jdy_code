<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 下午2:17
 */

namespace App\Http\Models\Fund;

use App\Http\Dbs\OrderDb;
use App\Http\Models\Model;

use App\Http\Dbs\FundHistoryDb;

use App\Http\Models\Order\OrderModel;
//use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\Paginator;
use Log;

/**
 * 资金历史
 * Class FundHistoryModel
 * @package App\Http\Models\Fund
 */
class FundHistoryModel extends Model
{
    /**
     * 获取资金历史记录
     *
     * @param array $data
     * @return array
     */
    public function getLists($data = []){
        $page       = $data['page'];
        $size       = $data['size'];
        $method     = $data['typeCode'];
        $userId     = $data['userId'];

        $start_time = $data['start_time'];
        $ent_time   = $data['end_time'];

        $eventId    = false;
        if(method_exists($this, $method)){
            $eventId = $this->$method();
        }

        $obj = new FundHistoryDb();

        // 用户ID
        if($userId) {
            $obj = FundHistoryDb::where('user_id', '=', $userId);
        }

        // 事件类型 支持数组
        if($eventId){
            if(is_array($eventId)) {
                Log::info(json_encode($eventId));
                $obj = $obj->whereIn('event_id', $eventId);
            } else {
                $obj = $obj->where('event_id', '=', $eventId);
            }
        }
        // 时间范围
        if($start_time && $ent_time){
            $obj = $obj->where('created_at', '>=', $start_time);
            $obj = $obj->where('created_at', '<=', $ent_time);
        }elseif($start_time && !$start_time){
            $obj = $obj->where('created_at', '>=', $start_time);
        }elseif(!$start_time && $start_time){
            $obj = $obj->where('created_at', '<=', $ent_time);
        }

        /*
        // 分页
        $data =  $obj->paginate(
            $size,
            ['id', 'user_id', 'balance_before', 'balance_change', 'balance', 'event_id', 'note', 'created_at'],
            'page',
            $page)->toArray();

        */

        $list = [
            'total' => 0,
            'data'  => [],
            'totalCash' => 0,
            'Summary'=> ['recharge_summary'=>0.00 , 'withdraw_summary'=> 0.00,'balance_change_summary'=>0.00],
        ];

        //分页方法进行改造
        $total = $obj->count();
        $totalCash = $obj->sum('balance_change');
        if($total > 0){

            $totalChange  =   $obj->sum('balance_change');

            $start = ( max(0, $page -1) ) * $size;

            $data = $obj->skip($start)
                ->take($size)
                //->orderBy('id','desc')
                ->orderBy('created_at','desc')
                ->orderBy('id','desc')
                ->get()
                ->toArray();

            $list['total']  = $total;
            $list['data']   = $data;
            $list['totalCash'] = $totalCash;
            if($userId){
                $summary = OrderModel::getRechargeWithdrawSummary($userId);
                if(!empty($summary)){
                    $summary = array_column($summary, 'total_cash', 'type');
                }
                \Log::info('summary', $summary);
            }
            $list['Summary']['balance_change_summary'] = $totalChange;
            $list['Summary']['recharge_summary'] = isset($summary[OrderDb::TYPE_RECHARGE]) ? $summary[OrderDb::TYPE_RECHARGE] : $list['Summary']['recharge_summary'];
            $list['Summary']['withdraw_summary'] = isset($summary[OrderDb::TYPE_WITHDRAW]) ? $summary[OrderDb::TYPE_WITHDRAW] : $list['Summary']['withdraw_summary'];
        }

        return $list;
    }

    /**
     * 获取长期未投资用户ids
     *
     * @param array $data
     * @return array
     */
    public function getNoInvestIds($data){

        $balance  = $data['balance'];
        $days     = $data['days'];
        $page     = $data['page'];
        $size     = $data['size'];

        $start    = ( max(0, $page -1) ) * $size;

        $obj      = new FundHistoryDb();

        $totalArr = $obj->getNoInvestIds($balance, $days);

        $result['list'] = $obj->getNoInvestIds($balance, $days, $start, $size);

        $result['total']= count($totalArr);

        return $result;
    }

    /**
     * 获取 【充值事件ID数组 + 提现事件ID数组】
     */
    public static function getWithdrawAndRechargeEventId(){
        return array_merge(self::getRechargeEventId(), self::getWithdrawEventId());
    }


    /**
     * 获取充值事件ID数组
     */
    public static function getRechargeEventId(){
        return [
            FundHistoryDb::RECHARGE_ORDER,
        ];
    }

    /**
     * 获取提现事件ID数组
     */
    public static function getWithdrawEventId(){
        return [
            FundHistoryDb::WITHDRAW_ORDER,
            FundHistoryDb::WITHDRAW_ORDER_CANCEL,
            FundHistoryDb::WITHDRAW_ORDER_FAILED,
        ];
    }

    /**
     * 获取投资事件ID数组
     */
    public static function getInvestEventId(){
        return [
            FundHistoryDb::INVEST_CREDIT_PROJECT,
            FundHistoryDb::INVEST_PROJECT,
        ];
    }

    /**
     * 获取本息回款资事件ID
     */
    public static function getRefundEventId(){
        return [
            FundHistoryDb::PROJECT_REFUND,
        ];
    }

    /**
     * 获取零钱计划事件ID数组
     */
    public static function getCurrentEventId(){
        return [
            FundHistoryDb::INVEST_CURRENT,
            FundHistoryDb::INVEST_OUT_CURRENT,
            FundHistoryDb::INVEST_CURRENT_AUTO,
        ];
    }

    /**
     * 获取零钱转入ID数组
     */
    public static function getInvestCurrentId(){
        return [
            FundHistoryDb::INVEST_CURRENT,
            FundHistoryDb::INVEST_CURRENT_AUTO,
        ];
    }

    /**
     * 获取零钱转出ID数组
     */
    public static function getOutCurrentId(){
        return [
            FundHistoryDb::INVEST_OUT_CURRENT,
        ];
    }

    /**
     * 获取活动奖励事件ID数组
     */
    public static function getRewardEventId(){
        //todo
        return [
            FundHistoryDb::ACTIVITY_AWARD,      //活动奖励标示
        ];
    }

    /**
     * 所有事件ID
     * @return array
     */
    public static function getAllEventId(){
        return [];
    }

}