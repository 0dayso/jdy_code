<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/13
 * Time: 下午1:38
 * Desc: 错误码
 */

namespace App\Http\Models\Common;

class ExceptionCodeModel
{

    /**
     * @todo 逐步完善model错误码，定义2——4——3规则
     */

    const

        //错误码切分：2_4_3

        //BASE 100
        EXP_MODEL_BASE                                      = 101000100,

        //INVEST
        EXP_MODEL_INVEST                                    = 101001100,


        //CURRENT
        EXP_MODEL_INVEST_CURRENT                            = 101002100,

        //PROJECT
        EXP_MODEL_INVEST_PROJECT                            = 101003100,

        //CREDIT
        EXP_MODEL_INVEST_CREDIT                             = 101004100,

        //EXTEND
        EXP_MODEL_INVEST_EXTEND                             = 101029100,


        /***************//***************INVEST-END***************//***************/

        //COMMON-公共

        //USER_FUND
        EXP_MODEL_COMMON_USER_FUND                          = 101005100,

        //USER
        EXP_MODEL_COMMON_USER                               = 101006100,

        //VALIDATE
        EXP_MODEL_COMMON_VALIDATE                           = 101007100,

        /***************//****************COMMON-END**************//***************/


        EXP_MODEL_CURRENT_ACCOUNT                           = 101008100,

        /***************//****************CURRENT-END**************//***************/

        //PROJECT
        EXP_MODEL_REFUND_PROJECT                            = 101009100,

        //CURRENT
        EXP_MODEL_REFUND_CURRENT                            = 101010100,

        //CREDIT
        EXP_MODEL_REFUND_CREDIT                             = 101011100,

        /***************//****************REFUND-END**************//***************/

        //事件通知
        EXP_MODEL_EVENT_NOTIFY                              = 101012100,

        //银行卡相关
        EXP_MODEL_BANK_CARD                                 = 101013100,

        //充值银行卡相关
        EXP_MODEL_RECHARGE_CARD                             = 101014100,

        //提现银行卡相关
        EXP_MODEL_WITHDRAW_CARD                             = 101015100,

        //订单相关
        EXP_MODEL_ORDER                                     = 101016100,

        //充值订单相关
        EXP_MODEL_RECHARGE_ORDER                            = 101017100,

        //提现订单相关
        EXP_MODEL_WITHDRAW_ORDER                            = 101018100,

        //充值回调相关的
        EXP_MODEL_RECHARGE                                  = 101019100,

        //零钱计划利息记录
        EXP_MODEL_CURRENT_INTEREST_HISTORY                  = 101020100,

        //系统配置中心
        EXP_MODEL_SYSTEM_CONFIG                             = 101021100,


        //用户限额
        EXP_MODEL_LIMIT                                     = 101022100,

        //支付路由
        EXP_MODEL_ROUTE                                     = 101023100,

        //邮件
        EXP_MODEL_EMAIL                                     = 101024100,

        //提现订单操作Model
        EXP_MODEL_WITH_DRAW_OPERATE                         = 101025100,

        /***************//****************PROJECT**************//***************/

        //项目
        EXP_MODEL_PROJECT                                   = 101026100,

        /***************//****************PROJECT_END**************//***************/

        //用户账户加钱票据,防止重复加钱
        EXP_FUND_TICKET                                     = 201027100,


        EXP_CREDIT_ASSIGN_PROJECT                           = 201028100,

        //最后一个，新增请在这个上面添加
        EXP_LAST_ITEM                                       = 100000000;



}
