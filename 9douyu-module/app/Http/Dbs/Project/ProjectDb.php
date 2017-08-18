<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/23
 * Time: 上午11:25
 * 项目数据类
 */
namespace App\Http\Dbs\Project;

use App\Http\Dbs\JdyDb;

class ProjectDb extends JdyDb{


    const
        //产品线
        PRODUCT_LINE_ONE_MONTH                  = 101,  //九省心一月期
        PRODUCT_LINE_THREE_MONTH                = 103,  //九省心三月期
        PRODUCT_LINE_SIX_MONTH                  = 106,  //九省心六月期
        PRODUCT_LINE_TWELVE_MONTH               = 112,  //九省心十二月期
        PRODUCT_LINE_FACTORING                  = 200,  //保理
        PRODUCT_LINE_LIGHTNING_SIX_MONTH        = 306,  //闪电付息六月期
        PRODUCT_LINE_LIGHTNING_TWELVE_MONTH     = 312,  //闪电付息十二月期


        //项目产品线
        PROJECT_PRODUCT_LINE_JSX    = 100,      //九省心
        PROJECT_PRODUCT_LINE_JAX    = 200,      //九安心
        PROJECT_PRODUCT_LINE_SDF    = 300,      //闪电付息
        //项目期限
        INVEST_TIME_MONTH_THREE     = 3,      //3月期
        INVEST_TIME_MONTH_SIX       = 6,      //6月期
        INVEST_TIME_MONTH_TWELVE    = 12,     //12月期
        INVEST_TIME_DAY_ONE         = 1,      //1月期
        INVEST_TIME_DAY             = 0,      //天

        PROJECT_INVEST_TYPE_CREDIT  = 1,       //定期产品
        PROJECT_INVEST_TYPE_CURRENT = 2,       //零钱计划产品

        REFUND_TYPE_BASE_INTEREST   = 10,       //到期还本息
        REFUND_TYPE_ONLY_INTEREST   = 20,       //按月付息，到期还本
        REFUND_TYPE_FIRST_INTEREST  = 30,       //投资当日付息，到期还本
        REFUND_TYPE_EQUAL_INTEREST  = 40,       //等额本息


        //项目状态
        STATUS_UNAUDITED            = 100,  //未审核
        STATUS_AUDITE_FAIL          = 110,  //未通过
        STATUS_UNPUBLISH            = 120,  //审核通过 未发布

        STATUS_INVESTING            = 130,  //投资中
        STATUS_REFUNDING            = 150,  //还款中
        STATUS_FINISHED             = 160,   //已完结
        BEFORE_REFUND               = 1,    //提前还款标志

        INTEREST_RATE_NOTE          = '借款利率',
        INTEREST_RATE_NOTE_APP413   = '预期年化收益',

        END=true;

}