<?php
/**
 * Created by PhpStorm.
 * @desc    账户统计
 * @date    2016年11月24日
 * @author  @llper
 *
 */
namespace App\Http\Logics\User;

use App\Http\Dbs\CurrentAccountDb;
use App\Http\Dbs\InvestDb;
use App\Http\Dbs\OrderDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;
use App\Tools\ToolMoney;
use App\User;
use Log;

class FundStatisticsLogic extends Logic
{

    /**
     * @desc    账户资金统计
     * @date    2016年11月23日
     * @author  @llper
     *
     * 账户总金额- 活期投资+账户余额+定期再投金额
     * 再投本金 - 活期投资 + 定期投资本金
     * 累计收益 - 活期收益+定期收益
     * 留存资金 - 总充值 - 总体现
     * 今日投资 - core_invest
     * 今日充值   core_order
     * 今日体现   core_order
     * 今日回款   core_refund_record
     *
     */
    public function getFundStatisticsWithDay(){
        $resData            = [];
        $startTime          = date('Y-m-d',strtotime(" -1 day"));
        $endTime            = date("Y-m-d");
        #用户余额总计
        $userDb             = new UserDb();
        $orderDb            = new OrderDb();
        $refundRecordDb     = new RefundRecordDb();
        $investDb           = new InvestDb();
        $currentAccountDb   = new CurrentAccountDb();

        $totalBalance       = $userDb->getFundStatisticsTotalBalance();
        #活期统计
        $currentData        = $currentAccountDb->getCurrentFundStatistics();
        #活期金额
        $currentCash        = empty($currentData["current_cash"])? "0.00" : $currentData["current_cash"];
        #活期收益
        $currentInterest    = empty($currentData["current_interest"])?  "0.00" : $currentData["current_interest"];
        //昨天活期收益
        $currentYesterdayInterest=   empty($currentData["yesterday_interest"])?  "0.00" : $currentData["yesterday_interest"];
        #定期累计手机
        $investRefundInterest= $refundRecordDb->getTotalInterest();
        #定期-在投中项目
        $investRefundData   = $refundRecordDb->getFundStatisticsRefund();
        #定期-在投金额
        $regularCash        = empty($investRefundData["principal"])?'0.00' : $investRefundData["principal"];
        #定期-在投利息
        $regularInterest    = empty($investRefundData["interest"])? '0.00' : $investRefundData["interest"];

        #账户总资产 - 活期资金 + 账户余额 + 在投中定期投资+在投中利息
        $totalUserCash       = $currentCash + $totalBalance + $regularCash + $regularInterest;
        #累计收益     活期收益 + 已完结项目收益
        $totalUserInterest   = $currentInterest + $investRefundInterest;
        #在投本金   活期金额 + 定期在投金额
        $investingCash       = $currentCash + $regularCash;
        $orderFund           = $orderDb->getOrderFundStatistics();

        $total_recharge      = empty($orderFund["totalRecharge"])?'0.00':$orderFund["totalRecharge"];
        $total_withdraw      = empty($orderFund["totalWithdraw"])?'0.00':$orderFund["totalWithdraw"];
        #昨日日充值金额
        $orderTodayFund      = $orderDb->getOrderFundStatistics($startTime, $endTime);
        $today_recharge      = empty($orderTodayFund["totalRecharge"])?'0.00':$orderTodayFund["totalRecharge"];
        $today_withdraw      = empty($orderTodayFund["totalWithdraw"])?'0.00':$orderTodayFund["totalWithdraw"];
        #今日定期投资金额
        $invertToday         = $investDb->getInvestTermTotal($startTime , $endTime);
        $invertToday         = empty($invertToday['cash'])?'0.00':$invertToday['cash'];
        //今日回款总额
        $refundRecord        =   $refundRecordDb->getTodayRefundSuccessByTimes($startTime);
        $fullCaleProjectCash =  self::getFullCaleProjectTotalCash();
        $resData             =   [
            "stat_date"       => $startTime,       //统计时间
            "total_cash"      => $totalUserCash,    //账号资产
            "total_interest"  => $totalUserInterest,//累计收益
            "investing_cash"  => $investingCash,    //再投本金(定期+零钱计划)
            "total_balance"   => $totalBalance,     //账号余额
            "current_cash"    => $currentCash,      //零钱计划余额
            "current_interest"=> $currentInterest,  //零钱计划累计收益
            "yesterday_interest"=> $currentYesterdayInterest,   //零钱计划昨日收益
            'full_scale_cash' => $fullCaleProjectCash,         //满标总额
            "regular_cash"    => $regularCash,          //再投本金(定期)
            "regular_interest"=> $regularInterest,      //再投收益(定期)
            "investRefundInterest"=> $investRefundInterest,//定期-已完结项目收益
            "total_recharge"  => $total_recharge,       //总充值
            "total_withdraw"  => $total_withdraw,       //总提醒
            "today_recharge"  => $today_recharge,       //今日提现
            "today_withdraw"  => $today_withdraw,       //今日提现
            "invert_today"    => $invertToday,          //今日投资(定期)
            "refund_today"    => isset($refundRecord[0]['cash']) ?$refundRecord[0]['cash'] : "0.00",
        ];
        return self::callSuccess($resData);

    }

    /**
     * @return string
     * @desc 截止数据统计数据点满标的项目
     */
    protected static function getFullCaleProjectTotalCash()
    {
        $startTime      =   '2014-01-01 00:00:00';

        $endTime        =   date('Y-m-d 00:00:00',time());

        $projectDB      = new ProjectDb();

        $fullCaleProject=  $projectDB->getProjectWithTime($startTime,$endTime,1,100000);

        $cashTotalArr   =   array_column($fullCaleProject['list'],'total_amount');

        $cashTotal      =   array_sum($cashTotalArr);

        return $cashTotal?$cashTotal:0;
    }


    /**
     * @desc    获取借款投资相关统计信息
     *
     **/
    public function getCoreApiInvestStat(){

        $refundRecordDb     = new RefundRecordDb();
        $projectDB          = new ProjectDb();
        #定期-在投中项目
        $investRefundData   = $refundRecordDb->getFundStatisticsRefund();
        #定期-在投金额
        $surplusPrincipal   = empty($investRefundData["principal"])?'0.00' : $investRefundData["principal"];
        #定期-在投利息
        $surplusInterest    = empty($investRefundData["interest"])? '0.00' : $investRefundData["interest"];
        #投资还款中项目ID
        $projectIds         = $projectDB->getInvestProjectIds();

        $investUserNumArr   = $refundRecordDb->getInvestUserNum();
        \Log::info(__METHOD__ . ' : '. __LINE__.' INVEST-STAT ', $investUserNumArr);
        $investUserNum      = isset($investUserNumArr[0])?$investUserNumArr[0]->investUserNum:0;
        $refundProjectStat  = $refundRecordDb->getRefundProject();

        $oneMonthProjectIds = $projectDB->getProjectInvestOne();
        $projectInvestStat  = $projectDB->getProjectInvestStat();
        #平均借款利率
        $loanAvgRate        = isset($projectInvestStat["loanAvgRate"])?$projectInvestStat["loanAvgRate"]:"0";

        $twoMonth           = isset($projectInvestStat["twoMonth"]) ? $projectInvestStat["twoMonth"]: "0";
        $moreMonth          = isset($projectInvestStat["moreMonth"])? $projectInvestStat["moreMonth"]:"0";


        $resData    = [
            "surplusPrincipal"  => $surplusPrincipal,       //再投本金(定期)
            "surplusInterest"   => $surplusInterest,        //再投收益(定期)
            "investUserNum"     => $investUserNum,          //出借人数
            "loanAvgRate"       => $loanAvgRate,            //平均借款利率

            "projectIds"        => $projectIds,             //投资、还款中项目ID
            "oneMonthProjectIds"=> $oneMonthProjectIds,     //保理一月期-项目ID
            "twoMonth"          => $twoMonth,               //二月期-期限
            "moreMonth"         => $moreMonth,              //三、六、十二月期-期限
            "refundProjectStat" => $refundProjectStat,      //在投、还款中项目信息
        ];
        return self::callSuccess($resData);
    }

}
