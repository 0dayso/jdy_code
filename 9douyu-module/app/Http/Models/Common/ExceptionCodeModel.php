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
     * 错误码切分：2_4_3
     */

    const
        //BASE 100
        EXP_MODEL_BASE                                      = 101001000,

        //债权
        EXP_MODEL_CREDIT                                    = 101002000,
        EXP_MODEL_CREDIT_FACTORING                          = 101003000,
        EXP_MODEL_CREDIT_GROUT                              = 101004000,
        EXP_MODEL_CREDIT_NINE                               = 101005000,
        EXP_MODEL_CREDIT_LOAN                               = 101006000,
        EXP_MODEL_CREDIT_HOUSING                            = 101007000,
        EXP_MODEL_CREDIT_THIRD                              = 101008000,
        EXP_MODEL_CREDIT_DISPERSE                           = 101008001,

        EXP_MODEL_CREDIT_UPDATE_STATUE                      = 101009000,

        //项目
        EXP_MODEL_PROJECT                                   = 101010000,

        EXP_MODEL_PROJECT_LINK_CREDIT                       = 101011000,

        //用户注册
        EXP_MODEL_USER_REGIESTER                            = 101012000,

        //用户登陆
        EXP_MODEL_USER_LOGIN                                = 101013000,

        //用户详情扩展注册
        EXP_MODEL_USER_INFO                                 = 101014000,

        //用户核心用户相关接口
        EXP_MODEL_USER                                      = 101015000,

        //零钱计划债权
        EXP_MODEL_CREDIT_CURRENT                            = 101016000,


        //交易密码
        EXP_MODEL_TRADING_PASSWORD                          = 101017000,

        //投资记录
        EXP_MODEL_INVEST                                    = 101018000,

        //用户优惠券 (红包,加息券)
        EXP_MODEL_USER_BONUS                                = 101019000,

        //密码
        EXP_MODEL_PASSWORD                                  = 101020000,

        //历史记录
        EXP_MODEL_FUND_HISTORY                              = 101021000,

        //后台配置
        EXP_MODEL_SYSTEM_CONFIG                             = 101022000,

        //零钱计划加息券
        EXP_MODEL_BONUS_CURRENT                             = 101023000,

        //VALIDATE
        EXP_MODEL_COMMON_VALIDATE                           = 101024000,

        //零钱计划项目相关
        EXP_MODEL_CURRENT_PROJECT                           = 101025000,

        //微信信息
        EXP_MODEL_WECHAT                                    = 101026000,

        //微信关联表信息
        EXP_MODEL_USER_LINK_WECHAT                          = 101027000,

        //零钱计划投资相关
        EXP_MODEL_INVEST_CURRENT                            = 101028000,

        //文章分类
        EXP_MODEL_CATEGORY                                  = 101029000,

        //文章
        EXP_MODEL_ARTICLE                                   = 101030000,

        //零钱计划利率相关
        EXP_MODEL_CURRENT_RATE                              = 101031000,

        //图片
        EXP_MODEL_PICTURE                                   = 101032000,

        //支付限额
        EXP_MODEL_LIMIT                                     = 101030000,

        //订单
        EXP_MODEL_ORDER                                     = 101031000,

        //银行卡
        EXP_MODEL_BANK_CARD                                 = 101032000,

        //邀请
        EXP_MODEL_INVITE                                    = 101033000,

        //合伙人
        EXP_MODEL_PARTNER                                   = 101034000,

        //活动资金记录
        EXP_MODEL_ACTIVITY_FUND_HISTORY                     = 101035000,

        //零钱计划资金汇总
        EXP_MODEL_CURRENT_FUND_STATISTICS                   = 101036000,

        //普付宝记录
        EXP_MODEL_PFB_OPERATE                               = 101037000,


        EXP_MODEL_EMAIL                                     = 101038100,

        //零钱计划个人额度
        EXP_MODEL_CURRENT_LIMIT                             = 101039000,

        EXP_MODEL_LOTTERY_CONFIG                            = 101040000,

        //债转项目
        EXP_MODEL_CREDIT_ASSIGN_PROJECT                     = 101041000,

        //对账的文件列表
        EXP_MODEL_ORDER_BATCH                               = 101041000,

        EXP_MODEL_ORDER_CHECK                               = 101042000,

        //微刊
        EXP_MODEL_MICRO_JOURNAL                             = 101043000,

        //邀请加息券
        EXP_MODEL_INVITE_RATE                               = 101044000,

        //投票
        EXP_MODEL_ACTIVITY_VOTE                             = 101045000,

        EXP_MODEL_DBKV                                      = 101046000,

        //合同
        EXP_MODEL_CONTRACT                                  = 101047000,

        //站内信
        EXP_MODEL_NOTICE                                    = 101048000,
        //CheckLimitTimeCode
        EXP_MODEL_CHECK_LIMIT                               = 101049000,

        EXP_MODEL_CURRENT_USER                              = 101049000,

        EXP_MODEL_CURRENT_ACCOUNT                           = 101050000,

        EXP_MODEL_ACTIVITY_ACCOUNT                          = 101060000,

        //最后一个，新增请在这个上面添加
        EXP_LAST_ITEM                                       = 100000000;



}
