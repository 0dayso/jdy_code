<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 20:24
 */

namespace App\Http\Logics\Module\Invest;

use App\Http\Dbs\CurrentAccountDb;
use App\Http\Dbs\InvestDb;
use App\Http\Dbs\OrderDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;
use App\Http\Logics\User\GetLogic;
use App\Http\Models\Invest\ProjectModel;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;

class StatisticsLogic extends Logic{


    /**
     * @return array
     * 首页统计页面
     */
    public function getStatistics(){

        $model = new ProjectModel();

        $data = $model->getData();

        $list['totalInterest']        = ToolMoney::formatDbCashDelete($data['totalInterest']);        //总收益
        $list['currentInvestAmount']  = ToolMoney::formatDbCashDelete($data['currentInvestAmount']);    //零钱计划转入总金额
        $list['projectInvestAmount']  = ToolMoney::formatDbCashDelete($data['projectInvestAmount']);     //定期投资总额
        $list['creditAssignInvestAmount']  = ToolMoney::formatDbCashDelete($data['creditAssignInvestAmount']);     //债转投资总额
        $list['currentCashTotal']  = ToolMoney::formatDbCashDelete($data['currentCashTotal']);     //零钱计划的总体量

        $db = new RefundRecordDb();
        $refundAmount   = $db->getRefundAmount();   //已回款本息
        $list['refundAmount']   = ToolMoney::formatDbCashDelete($refundAmount);
        $getLogic = new GetLogic();
        $list['userCount'] = $getLogic->getUserTotal(); //注册总额

        //待收的数据
        $list['collect']   = $db->getFundStatisticsRefund();

        //项目占比数
        $list['projectTotalHundredPercent']   = $model->getProjectTotalHundredPercent();
        
        $list['projectTotal']   =   $data['projectTotal'];

        return self::callSuccess($list);
    }

    /**
     * @desc    后台数据统计
     * 统计信息
     *  用户注册数
     *  昨日注册
     *  今日注册
     *
     *  今日充值金额
     *  今日提现金额
     *
     *  今日定期投资金额
     *  用户活期投资金额
     *
     *  今日回款金额
     *  明日回款金额
     *
     *  三天内回款金额
     *
     *  近15日投资数/回款数
     *  投资来源四端比例
     *  用户注册四端比例
     */
    public function getHomeStat(){
        #昨天
        $yesterday  = ToolTime::getDateBeforeCurrent();
        $yesterday1 = date('Ymd',   strtotime(" -1 day"));
        #今天
        $today      = ToolTime::dbDate();
        $today1     = date('Ymd');
        #明天
        $tomorrow   = ToolTime::getDateAfterCurrent();

        #用户注册总数
        $userDb         = new UserDb();
        $userTotalNum   = $userDb->getUserTotal();

        #昨天、今天注册数
        $userStatArr    = $userDb->getUserAmountByDate($yesterday,  $today);
        $userYesterDay  = 0;
        $userToday      = 0;
        if($userStatArr){
            foreach ($userStatArr as $ukey=>$uval){
                if($uval['date'] == $yesterday1){
                    $userYesterDay  = $uval['total'];
                }elseif($uval['date'] == $today1){
                    $userToday      = $uval['total'];
                }
            }
        }

        #昨日日充值金额
        $orderDb            = new OrderDb();
        $orderTodayFund     = $orderDb->getOrderFundStatistics($today, $tomorrow);
        $todayRecharge      = empty($orderTodayFund["totalRecharge"])?'0': round($orderTodayFund["totalRecharge"]);

        $todayWithdraw      = $orderDb->getWithdrawOrderStat($today, $tomorrow);
        $todayWithdraw      = empty($todayWithdraw["totalCash"])? '0' : round($todayWithdraw["totalCash"]);

        #今日定期投资金额
        $investDb           = new InvestDb();
        $invertToday        = $investDb->getInvestTermTotal($today , $today);
        $invertToday        = empty($invertToday['cash'])?'0': round($invertToday['cash']);

        #活期统计
        $currentAccountDb   = new CurrentAccountDb();
        $currentData        = $currentAccountDb->getCurrentFundStatistics();
        #活期金额
        $currentCash        = empty($currentData["current_cash"])? "0" : round($currentData["current_cash"]);

        $refundRecordDb     = new RefundRecordDb();
        #今日回款总额
        $todayRefundCash    = 0;
        #明天回款金额
        $tomorrowRefundCash = 0;
        #未来三天回款
        $threeDayRefundCash = 0;

        $endTime            = ToolTime::getDateAfterCurrent(4);
        $fiveDayRefund      = $refundRecordDb->getRefundTotalGroupByTime($today, $endTime);
        if($fiveDayRefund){
             foreach ($fiveDayRefund as $rkey=>$rval){
                 if($rval['times']  == $today){
                     $todayRefundCash   = $rval["cash"];
                 }elseif($rval['times'] == $tomorrow){
                     $tomorrowRefundCash= $rval['cash'];
                     $threeDayRefundCash += $rval['cash'];
                 }else{
                     $threeDayRefundCash += $rval['cash'];
                 }
             }
        }

        #近15日投资数
        $fiftenDayTime      = ToolTime::getDateBeforeCurrent(14);
        $fiftenDayInvest    = $investDb->getInvestAmountByDate($fiftenDayTime, $tomorrow);
        #近15日回款
        $fiftenDayRefund    = $refundRecordDb->getRefundTotalGroupByTime($fiftenDayTime,$tomorrow);

        #近15日充值、提现
        $fiftenDayOrder     = $orderDb->getOrderAmountByDate($fiftenDayTime, $today);


        $resData    = [
            "userTotalNum"   => $userTotalNum,
            "userYesterDay"  => $userYesterDay,
            "userToday"      => $userToday,
            "todayRecharge"  => $todayRecharge,
            "todayWithdraw"  => $todayWithdraw,
            "invertToday"    => $invertToday,
            "currentCash"    => $currentCash,
            "todayRefundCash"    => $todayRefundCash,
            "tomorrowRefundCash" => $tomorrowRefundCash,
            "threeDayRefundCash" => $threeDayRefundCash,
            "fiftenDayInvest"    => $fiftenDayInvest,
            "fiftenDayRefund"    => $fiftenDayRefund,
            "fiftenDayOrder"     => $fiftenDayOrder,
        ];
        return self::callSuccess($resData);
    }

}