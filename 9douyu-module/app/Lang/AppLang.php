<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/27
 * Time: 下午4:37
 * Desc: app api 提示信息
 */

namespace App\Lang;

class AppLang
{

    const
        CODE_SUCCESS                = 2000,  //服务端返回正常数据
        CODE_ERROR                  = 4000,  //服务端异常
        CODE_TRADING_PASSWORD       = 4009,  //交易密码输入错误
        CODE_LOGIN_EXPIRE           = 4010,  //登录超时
        CODE_PHONE_NOT_ACTIVATION   = 6001,  //手机号未激活
        CODE_PHONE_CAN_REGISTE      = 6000,  //手机号可注册

        APP_RECHARGE_BANK_LIMIT     = '单笔限额%s元',  //银行单笔限额
        APP_MIN_RECHARGE_CASH       = '快捷充值最低金额为%s元',  //最小充值金额
        APP_RECHARGE_NOTE           = "●为了您的资金安全，客户端仅支持使用一张快捷充值卡进行充值，绑定后只能通过快捷充值卡进行提现。\r\n●绑定银行卡时需充值才能绑定成功●\r\n首次充值不限金额",
        APP_BANK_CARD_NOTICE        = "•为了您的资金安全,客户端仅支持使用一张快捷充值卡进行充值,绑定后只能通过快捷充值卡进行提现.\r\n•绑定银行卡时需充值才能绑定成功\r\n•100元起充\r\n•登录九斗鱼官网(www.9douyu.com)可使用更多银行卡进行充值.\r\n•更换绑定银行卡请联系九斗鱼客服.",//我的银行卡页面文字说明

        APP_WITHDRAW_MESSAGE		= "1、100元起提\n2、每位用户每自然月有4次免费提现机会，超过4次后的每笔提现将收5元手续费\n3、用户发起提现申请并在平台审核之后，可在T+1 即第二个工作日到达用户指定银行卡账户（节假日顺延）。",

        APP_ORDER_NO_BANK           = '请选择所属银行',
        APP_ORDER_NO_PAYTYPE        = '无可用支付通道',
        APP_ORDER_ERROR_CREATE      = '提交充值订单失败',
        APP_NO_WITHDRAW_CARDS       = '无提现银行卡',

        APP_USER_TERM_MESSAGE       = '定期资产=待收本金+待收收益',
        APP_PLAN_REFUNDED_TIME      = '到期日',
        APP_PLAN_REFUND_TIME        = '起息日',
        APP_INTEREST_TEXT           = '预期收益',
        APP_END_INTEREST_TEXT       = '实际收益',

        APP_INVEST_PARAM_ERROR      = '参数错误',
        APP_INVEST_INFO_ERROR       = '投资信息不存在',
        APP_INVEST_NOPLAN_ERROR     = '项目回款信息不存在',
        APP_INVEST_AWARD_RATE       = '%s%%加息奖励',
        APP_INVEST_AWARD_COUPON     = '%加息奖励',
        APP_INVEST_AWARD_CASH       = '额外加息奖励%s',
        APP_INVEST_OWNED            = '收益到账',
        APP_SET_TRAD_PASSWORD       = '您未设置过交易密码',

        APP_PROJECT_INFO_ERROR      = '未查询到项目信息',
        APP_REFUND_DATE             = '回款时间',
        APP_FIRST_REFUND_DATE       = '首次回款时间',
        APP_END_REFUND_DATE         = '回款完结时间',

        END                         = TRUE;


}