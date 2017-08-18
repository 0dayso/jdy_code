<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/23
 * Time: 上午10:26
 */

namespace App\Http\Logics\Refund;

use App\Http\Dbs\CurrentAccountDb;
use App\Http\Dbs\CurrentInterestHistoryDb;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\RefundLogic;
use App\Http\Logics\Warning\WarningLogic;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Current\AccountModel;
use App\Http\Models\Current\InterestHistoryModel;
use App\Jobs\Refund\CurrentJob;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use App\Tools\ToolMoney;
use Queue;
use Log;

class CurrentLogic extends Logic
{

    /**
     * 零钱计划计算收益
     */
    public function doRefund($data=[])
    {

        $log = self::callError();

        if( empty($data) ){

            $log['msg'] = LangModel::getLang('ERROR_CURRENT_EMPTY_INTEREST_USER');

            Log::Error('currentDoRefundError', $log);

            return false;

        }

        $accountModel = new AccountModel();

        $interestHistoryModel = new InterestHistoryModel();

        $interestHistoryData = [];

        $rateTime = ToolTime::getDateBeforeCurrent();

        try{

            self::beginTransaction();

            foreach( $data as $user ){

                //ValidateModel::isCash($user['cash']);

                //ValidateModel::isUserId($user['user_id']);

                $interest = IncomeModel::getCurrentInterest($user['cash'],$user['rate']);

                $interestHistoryData[] = [
                    'user_id'       => $user['user_id'],
                    'rate'          => $user['rate'],
                    'interest'      => $interest,
                    'interest_date' => $rateTime,
                    'principal'     => $user['cash']
                ];

                $accountModel->updateInterest($user['user_id'], $interest);

            }

            if( empty($interestHistoryData) ){

                $log['data'] = $data;

                Log::Error('currentDoRefundEmptyInterestUser', [$log]);

                return '';

            }

            $interestHistoryModel->addInfo($interestHistoryData);

            self::commit();

            $log = self::callSuccess($data);

            Log::info('doCurrentRefundSuccess', [$log]);

        }catch (\Exception $e){

            self::rollback();

            $log['msg'] = $e->getMessage();

            $log['code'] = $e->getCode();

            $log['data'] = $data;

            Log::Error('doCurrentRefundError', [$log]);

            RefundLogic::doRefundCurrentWarning($log);

        }

        return $log;

    }


    /**
     * 拆分计息用户
     */
    public function splitRefund($rate)
    {

        $return = self::callError();

        //获取零钱计划账户总数
        $currentAccountDb = new CurrentAccountDb();

        $total = $currentAccountDb->getTotal();

        $size = 200;

        $pageNum = ceil($total/$size);

        if( empty($rate) ){

            //短信报警
            $log['msg'] = LangModel::getLang('ERROR_CURRENT_RATE');

            Log::Error('currentDoRefundRateError', $log);

            return '';

        }

        //根据当日利率，计算最小金额
        $minCash = IncomeModel::getCurrentInterestMinCash($rate);

        //查询今日零钱计划资金变动的用户
        $fundHistoryDb = new FundHistoryDb();

        $interestTime = ToolTime::dbDate();

        for( $page = 1; $page <= $pageNum; $page++ ){

            //获取用户列表
            $accountList = $currentAccountDb->getList($page, $size);

            $userIds = ToolArray::arrayToIds($accountList, 'user_id');

            if( empty($userIds) ){

                continue;

            }

            $fundList = $fundHistoryDb->getCurrentEventAssemble($userIds);

            $fundList = ToolArray::arrayToKey($fundList, 'user_id');

            $interestData = [];

            //当日投资的，从总金额减去，当日转出的，加入总金额
            foreach( $accountList as $accountKey => $account ){

                //如果已经计息，直接continue；
                if( $account['interested_at'] >= $interestTime ){

                    continue;

                }

                $accountCash = $account['cash'];

                if( isset($fundList[$account['user_id']]) ){

                    $accountCash = $accountCash + $fundList[$account['user_id']]['balance_change'];

                }

                //如果计息金额小于最小计息金额，直接跳过
                if( $accountCash < $minCash ){

                    continue;

                }

                $interestData[] = [
                    'user_id'       => $account['user_id'],
                    'cash'          => round($accountCash, 2),
                    'rate'          => $rate
                ];

            }

            $res = true;

            if( !empty( $interestData ) ){

                $data = [
                    'type'  => CurrentJob::TYPE_DO_REFUND,
                    'data'  => $interestData
                ];

                $res = Queue::pushOn('doRefundCurrent', new CurrentJob($data));

            }

            if( !$res ){

                Log::info('splitCurrentRefundErr',$interestData);

                $return['msg'] = LangModel::getLang('ERROR_CURRENT_REFUND_SPILIT');

                RefundLogic::splitRefundCurrentWarning($interestData);

                return $return;

            }

        }

        return self::callSuccess();

    }


    /**
     * @param $rate
     * @return array
     * @desc 加入队列任务
     */
    public function doRefundJob($rate)
    {

        if( empty($rate) ){

            return self::callError('零钱计划项目利率为空');

        }

        $data = [
            'rate'  => $rate,
            'type'  => CurrentJob::TYPE_SPLIT_REFUND
        ];

        $res = Queue::pushOn('doSplitRefund', new CurrentJob($data));

        if( $res ){

            return self::callSuccess($res, '加入队列任务成功');

        }else{

            RefundLogic::doRefundCurrentJobWarning($data);

            return self::callError('加入队列任务失败');

        }

    }

    /**
     * @return array
     * 获取零钱计划用户总收益
     */
    public function getTotalInterest(){
        
        $db             = new CurrentAccountDb();
        $totalInterest  = $db->getTotalInterest();

        $totalInterest  = ToolMoney::formatDbCashDelete($totalInterest);

        return self::callSuccess(['totalInterest' => $totalInterest]);
    }

    /**
     * desc 清除昨日未计息用户的昨日收益
     */
    public function clearUserYesterdayInterest()
    {

        $db = new CurrentAccountDb();

        $res = $db->clearUserYesterdayInterest();

        if( !$res ){

            $data = ToolTime::dbNow();

            \App\Http\Logics\Warning\CurrentLogic::doClearYesterdayInterestWarning($data);

            Log::Error('clearUserYesterdayInterestError', $data);

        }

    }

    /**
     * @return array
     * 获取用户昨日零钱计划总收益
     */
    public function getYesterdayInterest(){
        
        $db = new CurrentInterestHistoryDb();

        $date = ToolTime::getDateBeforeCurrent();
        $list = $db->getYesterdayInterest($date);

        return self::callSuccess($list);
    }

}