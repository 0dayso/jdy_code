<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/26
 * Time: 上午11:29
 */

namespace App\Http\Models\Common;

use App\Http\Dbs\InvestDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\ProjectRefundPlanDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Models\Model;
use App\Http\Models\Refund\ProjectModel;
use App\Lang\LangModel;
use App\Tools\ToolTime;
use Ares333\CurlMulti\Exception;
use Illuminate\Support\Facades\Log;

class IncomeModel extends Model
{

    const BASE_YEAR_DAY   = 365,
          BASE_YEAR_MONTH = 12,
          BASE_PERCENTAGE = 100,
          BASE_MONTH_DAY  = 30,

          MIN_INTEREST    = 0.005;

    /**
     * @param $projectId integer 项目id
     * @param $cash      integer 以分为单位 投资金额 即本金
     * @param string $investTime 投资时间
     * @return array     principal:本金,interest:利息,cash:总回款金额,times:回款时间
     * @throws \Exception
     * @desc 生成回款记录列表
     */
    public function getIncome($projectId, $cash, $investTime='')
    {

        $investTime   = $investTime ? ToolTime::getDate($investTime) : ToolTime::dbDate();

        ValidateModel::isProjectId($projectId);

        ValidateModel::isDecimalCash($cash);

        $projectDb    = new ProjectDb();

        $projectInfo  = $projectDb->getObj($projectId);

        $record = [];

        $fullAt = $this->getFullAt($projectInfo);

        if($projectInfo->new){

            if($investTime < $fullAt){

                $investTime = $fullAt;

            }

        }

        $endAt = $this->getEndAt( $projectInfo );

        switch($projectInfo->refund_type){
            /*------------到期还本期--------------*/
            case ProjectDb::REFUND_TYPE_BASE_INTEREST:

                $record = self::getBaseInterestRecord($cash, $projectInfo->profit_percentage, $investTime, $endAt);

                break;
            /*------------先息后本--------------*/
            case ProjectDb::REFUND_TYPE_ONLY_INTEREST:

                $record = self::getOnlyInterestRecord($cash, $projectInfo->profit_percentage, $investTime, $projectId, $projectInfo->invest_time, $fullAt);

                break;
            /*------------投资当日付息,到期还本--------------*/
            case ProjectDb::REFUND_TYPE_FIRST_INTEREST:

                $record = self::getFirstInterestRecord($cash, $projectInfo->profit_percentage, $investTime, $endAt);

                break;
            /*------------等额本息--------------*/
            case ProjectDb::REFUND_TYPE_EQUAL_INTEREST:

                $record = self::getEqualInterestRecord($cash, $projectInfo->profit_percentage, $investTime, $projectId, $projectInfo->invest_time, $fullAt);

                break;
            default:
                break;
        }

        return $record;

    }

    /**
     * @param $cash
     * @param $percentage
     * @param $diffTime
     * @return float
     * @desc 根据投资天数，返回利息金额
     */
    public function getInterestByDay($cash,$percentage,$diffTime)
    {

        return round(($cash * ($percentage / self::BASE_PERCENTAGE) * $diffTime / self::BASE_YEAR_DAY), 2);

    }

    /**
     * @param $cash
     * @param $percentage
     * @return float
     * @desc 根据投资月数,返回利息金额
     */
    public function getInterestByMonth($cash,$percentage)
    {

        return round(($cash * ($percentage / self::BASE_PERCENTAGE) / self::BASE_YEAR_MONTH), 2);

    }

    /**
     * @param $cash
     * @param $percentage
     * @param $times
     * @return float
     * @desc 根据期数计算每月还款的利息
     */
    public function getEqualInterestByMonth($cash, $percentage, $times)
    {

        return round(((($times + 1) / 2) * $cash * ($percentage / self::BASE_PERCENTAGE) / self::BASE_YEAR_MONTH) , 2);

    }

    /**
     * @param $cash
     * @param $times
     * @return float
     * @desc 根据期数计算每月还款的本金
     */
    public function getEqualPrincipalByMonth($cash, $times)
    {

        return round(($cash/$times) , 2);

    }

    /**
     * @param $percentage
     * @return float
     * @desc 月利率
     */
    public function getMonthPercentage($percentage)
    {

        return (($percentage / self::BASE_PERCENTAGE) / self::BASE_YEAR_MONTH);

    }

    /**
     * @desc 计算每个月还款额格式化保留两位小数
     * @param $cash
     * @param $monthPercentage
     * @param $timeLimit
     * @return float
     */
    public function getEqualRefundCash($cash, $monthPercentage,$timeLimit){

       /* [本金×月利率×（1+月利率）^期限月数]
        $a = ($cash*$monthPercentage * pow((1+$monthPercentage),$timeLimit));

        $b =(pow((1+$monthPercentage),$timeLimit)-1);*/
        //每月还款金额  [本金×月利率×（1+月利率）^期限月数]/[（1+月利率）^还款月数－1]

        $equalCash = round((($cash*$monthPercentage * pow((1+$monthPercentage),$timeLimit)) / (pow((1+$monthPercentage),$timeLimit)-1)),2);

        return $equalCash;

    }

    /**
     * @param $cash
     * @param $percentage
     * @param $investTime
     * @param $endTime
     * @return array
     * @desc 获取[到期还本期]回款记录
     */
    private function getBaseInterestRecord( $cash, $percentage, $investTime, $endTime){

        //按照天计算
        $diffTime = ToolTime::getDayDiff($investTime, $endTime);

        $interest = $this->getInterestByDay($cash, $percentage, $diffTime);

        Log::info("createRecordBaseInterest",[$cash, $percentage, $diffTime, $interest]);

        $record[] = [
            'principal'         => $cash,
            'interest'          => $interest,
            'cash'              => $cash + $interest,
            'times'             => $endTime,
        ];

        return $record;

    }

    /**
     * @param $cash
     * @param $percentage
     * @param $investTime
     * @param $projectId
     * @param $timeLimit
     * @param $publishTime
     * @return array
     * @desc 获取[先息后本]回款记录
     */
    private function getOnlyInterestRecord( $cash, $percentage, $investTime, $projectId, $timeLimit, $publishTime){

        //按月计算
        //项目回款计划
        //$refundPlanDb   = new ProjectRefundPlanDb();

        //$refundPlanInfo = $refundPlanDb->getObjByProjectId($projectId);
        $refundPlanInfo = self::getRefundPlan($projectId);

        $interest       = $this->getInterestByMonth($cash, $percentage); //每月所需利息

        Log::info("createRecordOnlyInterest",[$cash, $percentage]);

        $record = [];

        $i = 0;

        foreach($refundPlanInfo as $key => $times){

            if($investTime >= $times['refund_time']){
                continue;
            }

            $i++;

            $record[$key] = [
                'principal'         => 0,
                'interest'          => $interest,
                'cash'              => $interest,
                'times'             => $times['refund_time'],
            ];

            if($i == 1){ //首月利息
                $firstDays  = ToolTime::getDayDiff($investTime, $times['refund_time']);
                $beforeTimes = $publishTime;
                if($key != 0){
                    $beforeTimes = $refundPlanInfo[$key-1]['refund_time'];
                }
                $normalDays = ToolTime::getDayDiff($beforeTimes, $times['refund_time']);
                $record[$key]['interest']   = round(($interest * ($firstDays/$normalDays)), 2);
                $record[$key]['cash']       = round(($interest * ($firstDays/$normalDays)), 2);
            }

            if($key == ($timeLimit-1)){
                $record[$key]['principal']  = $cash;
                $record[$key]['cash']       = $cash + $interest;
            }

        }

        return $record;

    }

    /**
     * @param $cash
     * @param $percentage
     * @param $investTime
     * @param $projectId
     * @param $timeLimit
     * @param $publishTime
     * @param $investTime
     * @desc  获取[等额本息]回款记录
     * @return array
     */
    private function getEqualInterestRecord( $cash, $percentage, $investTime, $projectId, $timeLimit, $publishTime)
    {

        $refundPlanInfo = self::getRefundPlan($projectId);

        Log::info("createRecordEqualInterest",[$cash, $percentage]);

        $record = [];

        $i = $interest = $principal = $monthPercentage = $totalPrincipal =  $equalCash = $noFormatEqualCash = 0;

        foreach($refundPlanInfo as $key => $times) {

            if ($investTime >= $times['refund_time']) {

                $timeLimit--;

                continue;

            }

            if((float)$percentage > 0){
                //月利率
                $monthPercentage = self::getMonthPercentage($percentage);
                //每月还款额保留两位小数
                $equalCash = self::getEqualRefundCash($cash, $monthPercentage, $timeLimit);
                //每月还款额未格式化用于辅助计算每月应还利息
                $noFormatEqualCash = (($cash*$monthPercentage * pow((1+$monthPercentage),$timeLimit)) / (pow((1+$monthPercentage),$timeLimit)-1));
            }

            break;

        }

        //回款数组最后值
        $lastRefund = end($refundPlanInfo);

        foreach($refundPlanInfo as $key => $times){

            if($investTime >= $times['refund_time']){
                continue;
            }

            $i++;

            //等额本息每月还款利息保留两位小数
            $monthInterest = round((($cash * $monthPercentage - $noFormatEqualCash) * pow((1+$monthPercentage),$i-1) + $noFormatEqualCash),2);
            //等额本息每月还款利息未格式化
            $noFormalMonthInterest = (($cash * $monthPercentage - $noFormatEqualCash) * pow((1+$monthPercentage),$i-1) + $noFormatEqualCash);
            //每月应还本金金额
            $monthPrincipal = round(($equalCash-$monthInterest),2);



            $record[$key] = [
                'principal'         => $monthPrincipal,
                'interest'          => $monthInterest,
                'cash'              => $equalCash,
                'times'             => $times['refund_time'],
            ];

            //首月利息
            if($i == 1){
                $firstDays  = ToolTime::getDayDiff($investTime, $times['refund_time']);
                $beforeTimes = $publishTime;
                if($key != 0){
                    $beforeTimes = $refundPlanInfo[$key-1]['refund_time'];
                }

                $normalDays = ToolTime::getDayDiff($beforeTimes, $times['refund_time']);

                $interest = round($noFormalMonthInterest*($firstDays/$normalDays),2);
                //首月应还利息金额未格式化
                $noFormatInterest = $noFormalMonthInterest*($firstDays/$normalDays);
                $record[$key]['interest'] = $interest;
                $record[$key]['principal'] = $monthPrincipal;
                $record[$key]['cash']  = $interest + $monthPrincipal;
                //先计算加法在四舍五入
                //$record[$key]['cash']  = round(($noFormatInterest + ($noFormatEqualCash-$noFormalMonthInterest)),2);

            }

            //除最后以此循环外每个月本金之和
            if($times !== $lastRefund){
                $totalPrincipal += $monthPrincipal;
            }

            //最后一次回款，本金等于投资金额减去前几个月还款本金之和，避免项目回款本金之和有分钱误差
            if($times === $lastRefund){
                
                $record[$key]['principal'] = $cash - $totalPrincipal;
                $lastPrincipal = $equalCash - $record[$key]['principal'];
                if($lastPrincipal  > -0.05){
                    $record[$key]['interest']  = $equalCash - $record[$key]['principal'];
                }

            }

            Log::info("createRecordEqualInterest",[$i, $record]);

        }

        return $record;

    }

    /**
     * @param $cash
     * @param $percentage
     * @param $investTime
     * @param $endTime
     * @return array
     * @desc  获取[投资当日付息,到期还本]回款记录
     */
    private function getFirstInterestRecord( $cash, $percentage, $investTime, $endTime)
    {

        //按照天计算
        $diffTime = ToolTime::getDayDiff($investTime, $endTime);

        $interest = $this->getInterestByDay($cash, $percentage, $diffTime);

        Log::info("createRecordFirstInterest", [$cash, $percentage, $diffTime]);

        //利息
        $record[] = [
            'principal' => 0,
            'interest' => $interest,
            'cash' => $interest,
            'times' => $investTime,
            //'status' => RefundRecordDb::STATUS_SUCCESS,
        ];
        //本金
        $record[] = [
            'principal' => $cash,
            'interest' => 0,
            'cash' => $cash,
            'times' => $endTime,
        ];

        return $record;
    }

    /**
     * @param $cash
     * @param $rate
     * @return float
     * @desc 获取零钱计划收益
     */
    public static function getCurrentInterest($cash, $rate)
    {
        return round( ($cash * ($rate / self::BASE_PERCENTAGE) / self::BASE_YEAR_DAY), 2 );
    }

    /**
     * @param $rate
     * @return float
     * @desc 获取零钱计划计息的最小金额
     */
    public static function getCurrentInterestMinCash($rate)
    {

        return round( (self::MIN_INTEREST * self::BASE_YEAR_DAY / ($rate / self::BASE_PERCENTAGE)), 2 );

    }

    /**
     * @param $projectId
     * @param $cash
     * @param $profit
     * @param string $investTime
     * @return array|string
     * @throws \Exception
     * @desc 获取加息券所产生的利息
     */
    public function getRateRecord($investId, $profit)
    {

        ValidateModel::isProfit((float)$profit);

        $investDb = new InvestDb();

        $investInfo = $investDb->getInfoById($investId);

        $projectDb    = new ProjectDb();

        $projectInfo  = $projectDb->getObj($investInfo['project_id']);

        $projectInfo->end_at = ToolTime::getDate($projectInfo->end_at); ###

        $record = [];

        $investTime = ToolTime::getDate($investInfo['created_at']);

        $fullAt = $this->getFullAt( $projectInfo );

        if($projectInfo->new){

            if($investTime < $fullAt){

                $investTime = $fullAt;

            }

        }

        $endAt  = $this->getEndAt( $projectInfo );

        switch($projectInfo->refund_type){
            /*------------到期还本期--------------*/
            case ProjectDb::REFUND_TYPE_BASE_INTEREST:

                $record = self::getBaseInterestRecord($investInfo['cash'], $profit, $investTime, $endAt);

                break;
            /*------------先息后本--------------*/
            case ProjectDb::REFUND_TYPE_ONLY_INTEREST:

                $record = self::getOnlyInterestRecord($investInfo['cash'], $profit, $investTime, $investInfo['project_id'], $projectInfo->invest_time, $fullAt);

                break;
            /*------------等额本息--------------*/
            case ProjectDb::REFUND_TYPE_EQUAL_INTEREST:

                $record = self::getEqualInterestRecord($investInfo['cash'], $profit, $investTime, $investInfo['project_id'], $projectInfo->invest_time, $fullAt);

                break;
            default:
                break;
        }

        //$record = end($record);

        $interest = 0;

        foreach($record as $item){

            if( empty($item) || $item['interest'] <= 0 ){

                continue;

            }

            $interest += $item['interest'];

        }

        if( $interest == 0 ){

            return [];

        }

        return [
            'project_id'        => $investInfo['project_id'],
            'invest_id'         => $investId,
            'user_id'           => $investInfo['user_id'],
            'principal'         => 0,
            'interest'          => $interest,
            'cash'              => $interest,
            'times'             => $projectInfo->end_at,
            'type'              => RefundRecordDb::TYPE_BONUS_RATE
        ];

    }

    /**
     * @param $projectId
     * @param $cash
     * @param $profit
     * @return array
     * @throws \Exception
     * @desc 获取用户投资使用加息券的回款计划
     */
    public function getPlanInterest($projectId, $cash, $profit)
    {

        $investTime = ToolTime::dbDate();

        ValidateModel::isProjectId($projectId);

        ValidateModel::isCash($cash);

        if( $profit ){

            ValidateModel::isProfit($profit);

        }

        $projectDb    = new ProjectDb();

        $projectInfo  = $projectDb->getObj($projectId);

        $cashRecord = [];

        $fullAt = $this->getFullAt( $projectInfo );

        if($projectInfo->new){

            if($investTime < $fullAt){

                $investTime = $fullAt;

            }

        }

        $endAt  = $this->getEndAt( $projectInfo );

        switch($projectInfo->refund_type){
            /*------------到期还本期--------------*/
            case ProjectDb::REFUND_TYPE_BASE_INTEREST:

                $cashRecord = self::getBaseInterestRecord($cash, $projectInfo->profit_percentage, $investTime, $endAt);

                $rateRecord = self::getBaseInterestRecord($cash, $profit, $investTime, $endAt);

                break;
            /*------------先息后本--------------*/
            case ProjectDb::REFUND_TYPE_ONLY_INTEREST:

                $cashRecord = self::getOnlyInterestRecord($cash, $projectInfo->profit_percentage, $investTime, $projectId, $projectInfo->invest_time, $fullAt);

                $rateRecord = self::getOnlyInterestRecord($cash, $profit, $investTime, $projectId, $projectInfo->invest_time, $projectInfo->publish_at);

                break;

            /*------------等额本息--------------*/
            case ProjectDb::REFUND_TYPE_EQUAL_INTEREST:

                $cashRecord = self::getEqualInterestRecord($cash, $projectInfo->profit_percentage, $investTime, $projectId, $projectInfo->invest_time, $fullAt);

                $rateRecord = self::getEqualInterestRecord($cash, $profit, $investTime, $projectId, $projectInfo->invest_time, $fullAt);

                break;

            default:
                break;
        }

        //$rateRecord = end($rateRecord);

        if( empty($rateRecord) ){

            $rateRecord = [];

        }else{

            $rateRecord = $this->formatRateInterestRecord($rateRecord);
            $rateRecord['project_id']   = $projectId;

        }

        return [
            'cash_record'   => $cashRecord,
            'rate_record'   => $rateRecord
        ];

    }

    /**
     * @param $projectId
     * @return array|string
     * @desc 获取项目的回款计划
     */
    public function getRefundPlan($projectId){

        $projectDb  = new ProjectDb();

        $info       = $projectDb->getObj($projectId);

        $investTime = ToolTime::dbDate();

        if( !$info ) return '';

        $refundPlan = [];

        $fullAt = $this->getFullAt($info);

        $endAt  = $this->getEndAt($info);

        switch($info->refund_type){
            /*------------到期还本期--------------*/
            case ProjectDb::REFUND_TYPE_BASE_INTEREST:

                $record = self::getBaseInterestRecord($info->total_amount, $info->profit_percentage, $fullAt, $endAt);

                $refundPlan[] = [
                    'refund_cash'       => $record[0]['cash'],
                    'refund_time'       => $record[0]['times'],
                    'project_id'        => $projectId,
                ];

                break;

            /*------------先息后本--------------*/
            case ProjectDb::REFUND_TYPE_ONLY_INTEREST:

                $refundInterest = self::getInterestByMonth($info->total_amount,$info->profit_percentage);

                for($i=0; $i<$info->invest_time; $i++){

                    $endTime = date('Y-m-d', strtotime("-$i months",strtotime($endAt)));

                    if($endTime  == $endAt){
                        $refundCash = $info->total_amount + $refundInterest;
                    }else{
                        $refundCash = $refundInterest;
                    }

                    $refundPlan[] = [
                        'refund_time'   => ToolTime::getNextMonthDate($endAt, $endTime),
                        'refund_cash'   => $refundCash,
                        'project_id'    => $projectId,

                    ];

                    $times[] = ToolTime::getNextMonthDate($endAt, $endTime);

                }

                array_multisort($times, SORT_ASC, $refundPlan);

                break;

            /*------------投资当日付息,到期还本--------------*/
            case ProjectDb::REFUND_TYPE_FIRST_INTEREST:

                $refundPlan[] = [
                    'project_id'    => $projectId,
                    'refund_time'   => $endAt,
                    'refund_cash'   => $info->total_amount,
                ];

                break;

            /*------------等额本息--------------*/
            case ProjectDb::REFUND_TYPE_EQUAL_INTEREST:
                
                if($info->profit_percentage > 0){
                    //月利率
                    $monthPercentage = self::getMonthPercentage($info->profit_percentage);
                    //每月还款额
                    $equalCash = self::getEqualRefundCash($info->total_amount, $monthPercentage, $info->invest_time);
                }

                for($i=0; $i<$info->invest_time; $i++){

                    $endTime = date('Y-m-d', strtotime("-$i months",strtotime($endAt)));


                    $refundPlan[] = [
                        'refund_time'   => ToolTime::getNextMonthDate($endAt, $endTime),
                        'refund_cash'   => $equalCash,
                        'project_id'    => $projectId,

                    ];

                    $times[] = ToolTime::getNextMonthDate($endAt, $endTime);

                }

                array_multisort($times, SORT_ASC, $refundPlan);

                break;

            default:
                break;
        }

        return $refundPlan;

    }

    /**
     * @param $record
     * @return array
     * @desc 格式化加息券回款记录
     */
    protected function formatRateInterestRecord($record){

        if(empty($record)){return array();}

        $endDate  = '';

        $interest = 0;

        foreach ($record as $v) {

            $interest += $v['interest'];

            $endDate   = $v['times'];
        }

        $rateRecord = [
            'principal' => 0,
            'interest'  => $interest,
            'cash'      => $interest,
            'times'     => $endDate,
            'type'      => RefundRecordDb::TYPE_BONUS_RATE
        ];

        return $rateRecord;

    }

    /**
     * @param $projectInfo
     * @return bool|string
     * 满标时间
     */
    public function getFullAt( $projectInfo ){

        if($projectInfo->new){

            return ($projectInfo->full_at == '0000-00-00 00:00:00') ? ToolTime::dbDate() : $projectInfo->full_at;

        }else{

            return $projectInfo->publish_at;

        }

    }

    /**
     * @param $projectInfo
     * @return bool|string
     * 项目完结时间
     */
    public function getEndAt( $projectInfo ){

        $fullAt = $this->getFullAt( $projectInfo );
        $investTime = $projectInfo->invest_time;

        if($projectInfo->end_at == '0000-00-00' && $projectInfo->new){

            if($projectInfo->refund_type== ProjectDb::REFUND_TYPE_BASE_INTEREST || ProjectDb::REFUND_TYPE_FIRST_INTEREST){

                $endAt = date('Y-m-d', strtotime("+$investTime days",strtotime($fullAt)));

            }else{

                $endAt = date('Y-m-d', strtotime("+$investTime months",strtotime($fullAt)));

                $endAt = ToolTime::getNextMonthDate($fullAt, $endAt);

            }

            return $endAt;

        }else{

            return $projectInfo->end_at;

        }

    }



}
