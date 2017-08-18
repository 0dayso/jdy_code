<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/15
 * Time: 下午4:37
 * Desc: Model 操作提示信息
 */

namespace App\Lang;

class LangModel
{

    const

            SUCCESS_COMMON                              = '操作成功',
            SUCCESS_INVEST                              = '投资成功',
            SUCCESS_PROJECT_CREATE                      = '项目创建成功',
            SUCCESS_PROJECT_EDIT                        = '项目更新成功',
            SUCCESS_PROJECT_DELETE                      = '项目删除成功',

        /******************************************这里是分割线************************************************/
        //Common
            ERROR_COMMON                                = '操作失败',


        //Invest
            ERROR_INVEST_RECORD                         = '操作投资记录失败',
            ERROR_INVEST_EXTEND_RECORD                  = '操作投资扩展记录失败',
            ERROR_EMPTY_RECORD                          = '投资记录不存在',

        //Project
            ERROR_UPDATE_PROJECT                        = '操作项目失败',
            ERROR_PROJECT_EXIST                         = '项目不存在',
            ERROR_PROJECT_LEFT_AMOUNT                   = '剩余可投金额不足',
            ERROR_PROJECT_STATUS                        = '项目状态异常',
            ERROR_PROJECT_END_AT                        = '项目完结日异常',
            ERROR_PROJECT_INVEST_DAYS                   = '项目融资时间异常',

        //CreditAssignProject
            ERROR_PROJECT_INVESTING                     = '项目已被投资',
            ERROR_ORIGIN_PROJECT_FINISHED               = '原项目已完结',
            ERROR_PROJECT_SELL_OUT                      = '项目已售罄,请选择其他项目',
            ERROR_PROJECT_CANCELED                      = '项目已被取消',
            ERROR_ASSIGN_PROJECT_NOT_EXIST              = '债转项目不存在',
            ERROR_ASSIGN_PROJECT_EXIST                  = '债转项目已存在',
            ERROR_ASSIGN_PROJECT_FREE_AMOUNT_NOT_ENOUGH = '债转项目可投金额不足',
            ERROR_CAN_NOT_INVEST_SAM_USER_PROJECT       = '无法购买自己发起的债权项目',
            ERROR_ASSIGN_PROJECT_INVEST_TIME            = '投资当天不允许债转',
            ERROR_SDF_PRODUCT_CAN_NOT_CREDIT_ASSIGN     = '闪电付息项目不允许债转',
            ERROR_PROJECT_END_DATE_CAN_NOT_CREDIT_ASSIGN= '项目完结日不允许债转',
            ERROR_ASSIGN_PROJECT_CASH                   = '投资金额不等于可投金额',
            ERROR_NEXT_REFUND_NOT_EXIST                 = '用户不存在还款的回款计划',
            ERROR_REFUND_NOT_EXIST                      = '还款计划不存在',
            ERROR_BEFORE_CANCEL_FAIL                    = '提前还款取消正在债转的项目失败',
            ERROR_BEFORE_UPDATE_END_AT                  = '提前还款更新债转项目完结日失败',

        //Fundhistory
            ERROR_FUND_HISTORY                          = '操作资金记录失败',

        //User
            ERROR_USER_BALANCE                          = '账户余额不足',
            ERROR_USER                                  = '操作用户信息失败',
            ERROR_USER_NOT_EXIST                        = '用户不存在',

            ERROR_USER_PHONE_LENGTH                     = '不是一个有效的手机号长度',
            ERROR_USER_PASSWORD_LENGTH                  = '不是一个有效的密码长度',
            ERROR_USER_PHONE_REPEAT                     = '不允许重复注册',
            ERROR_USER_PHONE_ACTIVE                     = '注册的用户未激活',
            ERROR_USER_CREATE                           = '创建用户失败',
            ERROR_USER_DO_ACTIVE                        = '激活用户失败',
            ERROR_USER_UPDATE_PHONE                     = '修改手机号失败',
            ERROR_USER_UPDATE_PHONE_EXIST               = '手机号已存在',
            ERROR_USER_VERIFIED                         = '您已实名过,请勿重复操作',
            ERROR_ID_CARD_VERIFIED                      = '身份证已被实名',
            ERROR_USER_TRADING_PASSWORD                 = '交易密码错误',
            ERROR_USER_TRADING_PASSWORD_NO_SET          = '未设置交易密码',
            ERROR_USER_STATUS_FROZEN                    = '用户账户未冻结',
            ERROR_USER_FROZEN                           = '用户信息冻结失败',
            ERROR_USER_UNFROZEN                         = '用户信息解冻失败',
            ERROR_CARD_FROZEN                           = '用户银行卡信息冻结失败',
            ERROR_CARD_UNFROZEN                         = '用户银行卡信息解冻失败',

        //Validate
            ERROR_INVALID_USER_ID                       = '无效的用户ID',
            ERROR_INVALID_CASH                          = '无效的金额(必须是大于0的整数)',
            ERROR_INVALID_BANK_ID                       = '无效的银行ID',
            ERROR_INVALID_ORDER_ID                      = '无效的订单号',
            ERROR_INVALID_PROJECT_ID                    = '无效的项目ID',
            ERROR_INVALID_BANK_CARD                     = '无效的银行卡号',
            ERROR_INVALID_NAME                          = '无效的姓名',
            ERROR_INVALID_ID_CARD                       = '无效的身份证号',
            ERROR_AGE_IS_LESS_EIGHTEEN                  = '未满十八岁，不能进行实名认证',
            ERROR_INVALID_ORDER_TYPE                    = '无效的充值或提现类型',
            ERROR_INVALID_ORDER_FROM                    = '无效的订单来源平台',
            ERROR_INVALID_CANCLE_REASON                 = '取消提现原因不能为空',
            ERROR_INVALID_DECIMAL_CASH                  = '无效的金额(必须为大于0.01元的小数或整数金额)',
            ERROR_INVALID_INVEST_ID                     = '无效的投资ID',


        //Order
            ERROR_ORDER_NOT_EXIST                       = '订单号不存在',
            ERROR_ORDER_INFO_NOT_MATCH                  = '订单信息不匹配',
            ERROR_ORDER_EXTEND_NOT_EXIST                = '订单明细不存在',
            ERROR_ORDER_LOCK_FAILED                     = '订单锁定失败',
            ERROR_ORDER_UPDATE_FAILED                   = '订单更新失败',
            ERROR_ORDER_HAVE_DEALED                     = '该订单已处理',
            ERROR_ORDER_IS_EXIST                        = '订单号已存在',
            ERROR_ORDER_CANCLE_FAILED                   = '取消提现失败',
            ERROR_ORDER_TYPE_NOT_MATCH                  = '订单类型与调用接口不匹配',
            ERROR_ORDER_STATUS_IS_NOT_DEALING           = '订单状态不可处理',
            ERROR_ORDER_EXTEND_ADD_FAILED               = '创建充值订单扩展信息失败',
            ERROR_ORDER_EXTEND_UPDATE_FAILED            = '信息扩展信息更新失败',
            ERROR_UNING_ORDER_CAN_NOT_CANCEL            = '只能取消待处理的提现订单',
            ERROR_UNDEAING_ORDER_CAN_NOT_SUCCESS        = '只能标识处理中的订单为成功',
            ERROR_UNDEAING_ORDER_CAN_NOT_FAILED         = '只能标识处理中的订单为失败',


        //BankCard
            ERROR_BIND_CARD_FAILED                      = '绑定银行卡失败',
            ERROR_USER_UNBIND_CARD                      = '用户未绑卡',
            ERROR_WITHDRAW_BANK_CARD_IS_BINDED          = '该卡已被绑定',
            ERROR_WITHDRAW_BANK_CARD_CAN_NOT_REPEAT_ADD = '您已存在绑定银行卡,无法再次绑定提现银行卡',
            ERROR_WITHDRAW_BANK_CARD_DELETE_FAILED      = '提现银行卡删除失败',
            ERROR_WITHDRAW_BANK_CARD_CAN_NOT_DELETE     = '您存在绑定卡，无法删除提现银行卡',
            ERROR_WITHDRAW_BANK_CARD_IS_NOT_EXISTS      = '提现银行卡不存在',
            ERROR_BIND_CARD_IS_NOT_MATCH                = '绑卡信息不匹配',
            ERROR_CHANGE_CARD_IS_SAME_AS_AUTH_CARD      = '更换的卡号与绑定卡号一致',
            ERROR_BIND_CARD_UPDATE_FAILED               = '更新充值银行卡失败',
            ERROR_WITH_DRAW_CARD_UPDATE_FAILED          = '更新提现银行卡失败',
            ERROR_CHANGE_BIND_CARD_ADD_FAILED           = '添加换卡记录失败',
            ERROR_USER_BIND_CARD                        = '用户存在绑定卡,无法调用此接口',
            ERROR_USER_BIND_CARD_IS_EXIST               = '您已存在绑定银行卡,请勿重复绑定',
            ERROR_BANK_CARD_IS_BINDED                   = '银行卡已经被绑定,请使用其他银行卡',

        //Recharge
            ERROR_RECHARGE_ORDER_IS_EXIST               = '充值订单号已存在',
            ERROR_RECHARGE_ORDER_ADD_FAILED             = '创建充值订单失败',
            ERROR_RECHARGE_UPDATE_RECORD_FAILED         = '更新成功充值记录失败',
            ERROR_RECHARGE_MISS_ORDER_STATUS_CHECK_FAILED= '订单状态检测错误(不能处理成功状态)',
            ERROR_RECHARGE_MISS_ORDER_TYPE_CHECK_FAILED = '订单类型检测错误(不是充值订单)',

        //WithDraw
            ERROR_WITHDRAW_ORDER_IS_EXIST               = '提现订单号已存在',
            ERROR_WITHDRAW_ORDER_ADD_FAILED             = '创建提现订单失败',
            ERROR_INVALID_WITHDRAW_HANDING_FEE          = '无效提现手续费金额',
            PHONE_VERIFY_CODE_WITHDRAW_DEALING          = '亲爱的%s，您于%s申请%s元提现已提交银行处理，到账时间以银行处理时间为准。客服4006686568',
            ERROR_WITH_DRAW_UNDEAL_HAVE_NOT_DATA        = '不存在未处理的提现订单,请核查',
            WITH_DRAW_ORDER_BATCH_HANDLE_FAILED_LIST    = '提现自动对账处理失败订单列表:%s,请及时处理',


        //Refund
            ERROR_EMPTY_REFUND_RECORD                   = '回款记录为空',
            ERROR_UPDATE_REFUND_STATUS                  = '更新回款记录状态失败',
            ERROR_INSERT_REFUND_STATUS                  = '创建回款记录状态失败',

            ERROR_BAK_REFUND_RECORD_CREATE_FAILED       = '还款计划备份失败',
            ERROR_REFUND_RECORD_CHANGE_FAILED           = '原还款计划更新失败',
            ERROR_BAK_REFUND_RECORD_DELETE_FAILED       = '原还款计划删除失败',
            ERROR_BAK_REFUND_NOT_EXIST                  = '还款计划不存在',

        //Current
            ERROR_CURRENT_ACCOUNT_ADD                   = '创建零钱计划账户出错',
            ERROR_CURRENT_ACCOUNT_UPDATE                = '更新零钱计划账户出错',
            ERROR_CURRENT_INTEREST_HISTORY              = '零钱计划利息错误',
            ERROR_CURRENT_RATE                          = '零钱计划利率有误',
            ERROR_CURRENT_EMPTY_INTEREST_USER           = '零钱计划计息用户为空',
            ERROR_CURRENT_REFUND_SPILIT                 = '零钱计划计息拆分加入队列失败',
            ERROR_CURRENT_ACCOUNT_NOT_EXIST             = '用户零钱计划账户不存在',
            ERROR_CURRENT_ACCOUNT_BALANCE_NOT_ENOUTH    = '零钱计划账户余额不足',
            ERROR_CURRENT_INVEST_OUT_CASH               = '零钱计划转出金额错误(最少转出一分钱)',


        //Limit
            ERROR_LIMIT_EXCEED                          = '今日已达限额,请明日再试',

        //Route
            ERROR_BANK_ID_MISMATCH                      = '传递的银行ID与绑定卡所属银行ID不匹配',

        //SystemConfig
            ERROR_SYSTEM_CONFIG                         = '创建配置错误',

        //Project
            ERROR_PROJECT_INVALID_TOTAL_AMOUNT          = '无效的项目融资总额',
            ERROR_PROJECT_RECORD_CREATE                 = '创建项目失败',
            ERROR_PROJECT_RECORD_UPDATE                 = '更新项目失败',
            ERROR_PROJECT_UNINVESTED_ALL                = '项目未投满',
            ERROR_PROJECT_NOT_FUND                      = '项目信息不存在',
            ERROR_PROJECT_RECORD_DELETE                 = '删除项目失败',

        //后台添加项目
            ERROR_INVEST_TIME_NOT_FIND                  = '项目期限类型未定义',
            ERROR_PROJECT_PRODUCT_LINE_UNDEFINED        = '项目产品线不合法',
            ERROR_PROJECT_REFUND_TYPE_UNDEFINED         = '项目还款方式不存在',
            ERROR_PROJECT_INVALID_RATE                  = '项目利率不合法',
            ERROR_PROJECT_EMPTY_NAME                    = '项目名称为空',
            ERROR_PROJECT_INVALID_INVEST_DAYS           = '融资天数不合法',
            ERROR_PROJECT_INVALID_INVEST_TIME           = '投资期限不合法',
            ERROR_PROJECT_PUBLISH_TIME                  = '发布日期格式不对',
            ERROR_PROJECT_DELETE_FAIL                   = '项目删除操作错误',
            ERROR_PROJECT_RECORD_NOT_FIND               = '项目不存在',


        //邮件
            ERROR_INVALID_EMAIL                         = '邮箱地址不合法',

            ERROR_FUND_TICKET_ADD_FAILED                = '添加票据失败'





        ;


    /**
     * @param $name
     * @return string
     */
    public static function getLang($name)
    {

        $className = __CLASS__;

        $lang = defined("$className::$name") ? constant("$className::$name") : $name;

        return $lang;

    }

}
