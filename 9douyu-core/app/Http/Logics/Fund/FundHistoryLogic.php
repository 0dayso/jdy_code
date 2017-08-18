<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 下午2:14
 */

namespace App\Http\Logics\Fund;

use App\Http\Dbs\OrderDb;
use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;

use App\Http\Logics\Order\OrderLogic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Fund\FundHistoryModel;

use App\Http\Dbs\FundHistoryDb;
use App\Http\Models\Order\OrderModel;
use App\Tools\ToolArray;
use Illuminate\Support\Facades\Lang;
use App\Http\Models\Common\UserModel;
use Log;

use App\Tools\ToolMoney;

/**
 * 资金历史
 * Class FundHistoryLogic
 * @package App\Http\Logics\Fund
 */
class FundHistoryLogic extends Logic
{

    /**
     * 获取资金历史
     * 
     * @param array $data
     * @return array
     */
    public function getLists($data = []){

        $data = $this->filterAttribute($data);

        try {
            $fundHistoryModel = new FundHistoryModel;

            $return           = $fundHistoryModel->getLists($data);
            $return           =  self::formatOutput($return);
            Log::Info('FundHistoryLogic - getLists - success',$data);

            $return = self::callSuccess($return);

        }catch (\Exception $e){

            $data['data'] = $data;

            $data['code'] = $e->getCode();
            $data['msg']  = $e->getMessage();

            Log::Info('FundHistoryLogic - getLists - error', $data);

            $return = self::callError($e->getMessage());
        }
        return $return;
    }

    /**
     * 获取长期未投资用户ids
     *
     * @param array $data
     * @return array
     */
    public function getNoInvestUserId($data){

        try {
            $fundHistoryModel = new FundHistoryModel;

            $return = $fundHistoryModel->getNoInvestIds($data);

            Log::Info('FundHistoryLogic - getNoInvestUserIds - success',$data);

            $return = self::callSuccess($return);

        }catch (\Exception $e){

            $data['data'] = $data;

            $data['code'] = $e->getCode();
            $data['msg']  = $e->getMessage();

            Log::Info('FundHistoryLogic - getNoInvestUserIds - error', $data);

            $return = self::callError($e->getMessage());
        }
        return $return;
    }

    /**
     * 格式化输出  【金额从分到元】
     *
     * @param array $return
     * @return array
     */
    protected static function formatOutput($return = []){
        if(isset($return['data']) && !empty($return['data'])){

            //获取用户信息
            $userIds = ToolArray::arrayToIds($return['data'],'user_id');
            $db = new UserDb();
            $userList = $db->getUserListByUserIds($userIds);
            $userList = Toolarray::arrayToKey($userList,'id');

            $currentEventIds   = [
                FundHistoryDb::INVEST_CURRENT_AUTO,
                FundHistoryDb::INVEST_CURRENT,
                FundHistoryDb::INVEST_OUT_CURRENT,
                FundHistoryDb::INVEST_CURRENT_NEW,
                FundHistoryDb::INVEST_OUT_CURRENT_NEW,
            ];
            //组装用户提现资金记录的数据的数据
            $orderIds = [];
            foreach($return['data'] as $key => $value){
                if($value['event_id'] == FundHistoryDb::WITHDRAW_ORDER && !empty($value['note'])){
                    $orderIds[] = $value['note'];
                }
            }
            $orders = ToolArray::arrayToKey(OrderModel::getOrderByIds($orderIds),'order_id');

            foreach($return['data'] as $key => $value){

                //提现标识
                if($value['event_id'] == FundHistoryDb::WITHDRAW_ORDER){
                    if($value['note']){
                        //$orderInfo = OrderModel::getOrder($value['note']);
                        $orderInfo   = $orders[$value['note']];
                        $value['note'] = OrderModel::$withdrawCoderArr[$orderInfo['status']];
                    }else{
                        $value['note'] = Lang::get('messages.FUND_HISTORY.EVENT_ID_'.$value['event_id']);
                    }
                }
                //交易流水类型标示
                $value['event_id_label'] = Lang::get('messages.FUND_HISTORY.EVENT_ID_'.$value['event_id']);

                if($value['event_id'] == FundHistoryDb::WITHDRAW_ORDER_CANCEL){

                    if($value['note']){
                        $value['note'] = OrderDb::WITHDRAW_CANCLE_NOTE;
                    }else{
                        $value['note'] = Lang::get('messages.FUND_HISTORY.EVENT_ID_'.$value['event_id']);
                    }

                }

                $userId = $value['user_id'];
                $value['balance_before'] = ToolMoney::formatDbCashDelete($value['balance_before']);
                $value['balance_change'] = ToolMoney::formatDbCashDelete($value['balance_change']);
                $value['balance']        = ToolMoney::formatDbCashDelete($value['balance']);

                if( in_array($value['event_id'], $currentEventIds) ){

                    $value['note'] = Lang::get('messages.FUND_HISTORY.EVENT_ID_'.$value['event_id']);

                }elseif ( $value['event_id'] == FundHistoryDb::RECHARGE_ORDER ){

                    $value['note'] = Lang::get('messages.FUND_HISTORY.EVENT_ID_'.$value['event_id']);

                }

                $value['phone'] = $userList[$userId]['phone'];
                
                $value['name'] = $userList[$userId]['real_name'];

                $return['data'][$key] = $value;
            }
        }
        return $return;
    }

    /**
     *
     * 过滤传入的参数
     *
     * @param array $data 请求的数据
     * @return array
     */
    protected function filterAttribute($data){

        $data                          = isset($data['data']) ? $data['data'] : $data;

        Log::Info('FundHistoryLogic - filterAttribute - input data: ', $data);

        $attribute                     = [];
        $attribute['page']             = isset($data['page']) ? $data['page'] : 0;
        $attribute['size']             = isset($data['size']) ? $data['size'] : 20;

        $attribute['typeCode']         = isset($data['typeCode']) ? $data['typeCode'] : 0;
        $attribute['userId']           = isset($data['userId']) ? $data['userId'] : 0;

        $attribute['start_time']       = isset($data['start_time']) ? $data['start_time'] : null;
        $attribute['end_time']         = isset($data['end_time']) ? $data['end_time'] : null;

        return $attribute;
    }


    /**
     * @param $userId
     * @param $page
     * @param $size
     * 分页获取零钱计划转入转出记录
     */
    public function getCurrentLists($userId,$page,$size){

        $result = [
            'total' => 0,
            'data'  => []
        ];

        $db = new FundHistoryDb();

        $total = $db->getCurrentRecordNum($userId);

        if($total > 0){

            $return = $db->getCurrentListByPage($userId,$page,$size);

            foreach($return as $k => $val){
                $return[$k]['note'] =  Lang::get('messages.FUND_HISTORY.EVENT_ID_'.$val['event_id']);

                $return[$k]['balance_change'] = ToolMoney::formatDbCashDelete($val['balance_change']);
                $return[$k]['balance_before'] = ToolMoney::formatDbCashDelete($val['balance_before']);
                $return[$k]['balance']        = ToolMoney::formatDbCashDelete($val['balance']);
            }

            $result['total']    = $total;
            $result['data']     = $return;
        }

        return $result;
    }

    
    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * 获取零钱计划转入转出记录
     */
    public function getCurrentInvestList($userId,$page,$size){

        try{

            ValidateModel::isUserId($userId);

            //判断用户是否存在
            $userModel = new UserModel();
            $userModel->checkUserExitsByUserId($userId);

            $result = $this->getCurrentLists($userId,$page,$size);

            return self::callSuccess($result);

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }
    }


    /**
     * 获取零钱计划昨日转入转出资金记录
     */
    public function getYesterdayCurrentFundData(){

        $db = new FundHistoryDb();

        $return = $db->getYesterdayCurrentFundData();

        if(!empty($return)){

            foreach($return as $k => $val){
                $return[$k]['note'] =  Lang::get('messages.FUND_HISTORY.EVENT_ID_'.$val['event_id']);
                $return[$k]['balance_change']    = abs($val['balance_change']);
            }

            return self::callSuccess($return);

        }else{

            return self::callError('数据不存在');
        }

    }

    /**
     * @return array
     * 获取今日零钱计划用户总转出金额
     */
    public function getTodayCurrentInvestOutAmount(){

        $db = new FundHistoryDb();
        $amount = $db->getTodayCurrentInvestOutAmount();

        return self::callSuccess(['amount' => abs($amount)]);
    }


    /**
     * @return array
     * 获取今日零钱计划用户总转入金额
     */
    public function getTodayCurrentInvestAmount(){
        
        $db = new FundHistoryDb();
        $amount = $db->getTodayCurrentInvestAmount();

        return self::callSuccess(['amount' => abs($amount)]);
    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户当日自动转入零钱计划的金额总和
     */
    public function getTodayAutoInvestCurrentTotalByUserId($userId){

        $amount = FundHistoryDb::getTodayAutoInvestCurrentTotalByUserId($userId);

        return self::callSuccess(['amount' => abs($amount)]);

    }

    /**
     * @param string $date
     * @return array
     * @desc 根据时间获取平台自动转入零钱计划金额总数
     */
    public function getTodayAutoInvestCurrentTotal($date=''){

        $amount = FundHistoryDb::getTodayAutoInvestCurrentTotal($date);

        return self::callSuccess(['amount' => abs($amount)]);

    }


    /**
     * @param string $date
     * @return array
     * @desc 根据事件类型分组进行数据统计
     */
    public static function getChangeCashGroupByEventId( $date = '')
    {
        $result     =   FundHistoryDb::getChangeCashGroupByEventId($date);

        $result     =   ToolArray::arrayToKey($result,'type');

        //$result[FundHistoryDb::WITHDRAW_ORDER_FAILED]   =   OrderDb::getWithDrawFailedTotal($date)[0];

        return self::callSuccess($result);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     * @desc 根据时间获取用户自动投资活期的列表信息
     */
    public static function getAutoInvestCurrentListByDate($startDate, $endDate){

        if( empty($startDate) || empty($endDate) || $endDate < $startDate ){

            return self::callError('时间参数不正确,请修改后重试!');

        }

        $result = FundHistoryDb::getAutoInvestCurrentListByDate($startDate, $endDate);

        return self::callSuccess($result);

    }

}