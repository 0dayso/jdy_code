<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 下午4:59
 */

namespace App\Http\Dbs\Fund;


class FundHistoryDb
{

    const
        PROJECT_REFUND          = 200,  //回款
        INVEST_PROJECT          = 300,  //定期
        INVEST_CURRENT          = 400,  //零钱计划
        INVEST_OUT_CURRENT      = 401,  //零钱计划转出
        INVEST_CURRENT_AUTO     = 402,  //回款自动转零钱计划
        INVEST_CREDIT_PROJECT   = 500,  //债转
        RECHARGE_ORDER          = 600,  //充值
        WITHDRAW_ORDER          = 700,  //提现
        WITHDRAW_ORDER_FAILED   = 701,  //提现失败
        WITHDRAW_ORDER_CANCLE   = 702,  //取消提现
        ACTIVITY_AWARD          = 800,  //活动奖励
        CREDIT_ASSIGN_PROJECT   = 900,  //出让
        INVEST_CREDIT_ASSIGN    = 901,  //投资
        SYSTEM_DEDUCT           = 1000,  //系统扣除
        INVEST_CURRENT_NEW      = 1100,  //新版零钱计划
        INVEST_OUT_CURRENT_NEW  = 1101,  //新版零钱计划转出

        OLD_CURRENT_INVEST      = 1,//原系统零钱计划转入类型标识
        OLD_CURRENT_INVEST_OUT  = 2,//原系统零钱计划转出类型标识

    END = true;

}