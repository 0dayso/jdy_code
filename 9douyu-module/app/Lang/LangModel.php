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

        SUCCESS_COMMON                                        = '操作成功',

        /******************************************这里是分割线************************************************/
        //项目模块 债权
        ERROR_CREDIT_VALID_TYPE                                = '没有对应的债权表单',

        ERROR_CREDIT_CREATE_FACTORING                          = '创建保理债权失败',

        ERROR_CREDIT_CREATE_GROUP                              = '创建项目集债权失败',

        ERROR_CREDIT_CREATE_NINE                               = '创建九省心债权失败',

        ERROR_CREDIT_CREATE_LOAN                               = '创建耀盛信贷债权失败',

        ERROR_CREDIT_CREATE_HOUSING                            = '创建房产抵押债权失败',

        ERROR_CREDIT_CREATE_THIRD                              = '创建第三方债权失败',
        ERROR_CREDIT_CREATE_THIRD_DETAIL                       = '创建第三方债权人详情失败',

        ERROR_CREDIT_CREATE_DISPERSE                           = '新债权录入失败',

        ERROR_CREDIT_UPDATE_STATUE                             = '更新债权状态失败',

        ERROR_CREDIT_CREATE_FIND_BY_ID                         = '获取债权失败',

        ERROR_CREDIT_UPDATE                                    = '更新债权失败',

        //项目添加匹配债权
        ERROR_PROJECT_LINK_CREDIT_CREATE_FAIL                 = '创建项目添加债权失败',

        ERROR_PROJECT_LINK_CREDIT_EDIT_FAIL                   = '更新项目债权失败',

        ERROR_PROJECT_LINK_CREDIT                             = '获取项目债权失败',

        ERROR_PROJECT_CREATE                                  = '核心创建项目失败',

        ERROR_PROJECT_DELETE                                  = '核心删除项目失败',

        ERROR_PROJECT_DETAIL_GET_FAIL                         = '获取项目详情失败',

        ERROR_PROJECT_LINK_GET_FAIL                           = '获取项目债权失败',

        ERROR_PROJECT_UPDATE                                  = '核心更新项目失败',

        ERROR_PROJECT_TOTAL_AMOUNT_CREDIT                     = '项目金额跟债权金额不匹配',

        //充值支付模块
        ERROR_PAY_CREATE_ORDER                                = '创建订单失败',
        ERROR_PAY_NOUSE_CHANNEL                               = '无可支付通道',
        SMS_PAY_VERIFY_CODE                                   = '验证码：%s 【九斗鱼】',

        //提现管理模块
        SUCCESS_WITHDRAW_UPLOAD                               = '上传文件成功，等待系统自动处理',
        ERROR_WITHDRAW_UPLOAD                                 = '上传文件失败',
        SUCCESS_WITHDRAW                                      = '操作成功',
        ERROR_WITHDRAW                                        = '操作失败',


        //密码相关
        ERROR_USER_TRADING_PASSWORD_EMPTY                    = '您未设置过交易密码',
        ERROR_USER_TRADING_PASSWORD_IS_SAME_AS_PASSWORD      = '交易密码与登录密码不能相同',
        ERROR_USER_TRADING_PASSWORD_IS_SAME                  = '新旧交易密码不能相同',
        ERROR_USER_PASSWORD_IS_SAME                          = '新旧登录密码不能相同',
        ERROR_USER_TRADING_PASSWORD_SET                      = '交易密码已设置,请勿重复设置',


        //用户相关
        ERROR_USER_PHONE                                      = '不是一个有效的手机号',
        ERROR_INVITE_PHONE                                    = '邀请手机号码不是一个有效的手机号',
        ERROR_USER_NOT_EXIST                                  = '用户不存在',
        ERROR_PHONE_EXIST                                     = '手机号已存在',
        ERROR_USER_NAME_CHECKED                               = '您未实名认证,请先进行实名',
        ERROR_USER_PASSWORD_CHECKED                           = '您未设置交易密码,请先进行设置',

        //用户红包加息券
        ERROR_USER_BONUS_ID                                   = '红包/加息券Id无效',
        ERROR_USER_BONUS_USER_ID                              = '用户Id无效',
        ERROR_USER_BONUS_ADD                                  = '红包/加息券发入失败',
        ERROR_USER_BONUS_IS_LOCK                              = '此条记录已锁定',
        ERROR_USER_BONUS_NOT_FIND                             = '此条记录不存在',
        ERROR_USER_BONUS_ADD_LOCK                             = '此条记录锁定失败',
        ERROR_USER_BONUS_NU_LOCK                              = '此条记录未锁定',
        ERROR_USER_BONUS_DEL_LOCK                             = '此条记录解锁失败',
        ERROR_USER_BONUS_START                                = '优惠券未到可用日期',
        ERROR_USER_BONUS_EXPIRE                               = '优惠券已过期',
        ERROR_USER_BONUS_IS_USED                              = '优惠券已使用',
        ERROR_USER_BONUS_USED_FAIL                            = '优惠券使用失败',
        ERROR_USER_BONUS_CURRENT_USED                         = '零钱计划加息中,不能再次加息',
        ERROR_USE_BONUS_FAIL                                  = '使用优惠券失败',
        USER_BIRTHDAY_BONUS                                   = '生日福利',
        USER_REFUND_BONUS                                     = '回款福利',
        USER_REFUND_SMS                                       = '回款用户加息劵短信提醒',

        ERROR_USER_SUGGEST_EMPTY                              = '反馈意见不能为空',
        ERROT_USER_SUGGEST_TOO_LONG                           = '反馈意见必须在800字内',

        //注册
        USER_REGISTER_FIELD_PHONE                             = '手机号',
        USER_REGISTER_FIELD_PASSWORD                          = '密码',
        MODEL_USER_FIELD_PHONE_CODE                           = '验证码',
        USER_REGISTER_FIELD_REQUEST_FROM                      = '来源',
        USER_REGISTER_FIELD_AGGREEMENT                        = '注册协议',

        ERROR_EMPTY                                           = ' 不允许为空.',
        USER_REGISTER_AGREEMENT                               = '请勾选注册协议',

        MODEL_USER_PASSWORD_CONFIRM_NOT_MATCH                 = '两次密码不匹配',
        MODEL_USER_PASSWORD_TOO_SHORT                         = '密码过短，需要[%s]位以上',
        MODEL_USER_PASSWORD_TOO_LONG                          = '密码过长，最多[%s]位',
        MODEL_USER_PASSWORD_FORMAT_INVALID                    = '密码不能是纯数字或者纯字母',
        MODEL_USER_PASSWORD_CONFIRM_CANT_SAME                 = '新密码不能和旧密码一致',
        MODEL_USER_PASSWORD_TYPE_ERROR                        = '密码类型错误',


        ERROR_USER_REGISTER_FROM                                   = '注册验证失败',//注册来源验证失败
        ERROR_USER_GETINFO_FROM                                    = '获取用户信息失败',
        USER_REGISTER_STATUS_BLOCK                                 = '账户被锁定',
        USER_REGISTER_STATUS_ACTIVE                                = '不能重复注册',
        USER_REGISTER_DO_REGISTER                                  = '注册失败',
        USER_GET_FAILURE                                           = '获取用户失败',
        USER_REGISTER_INVITE_PHONE_ERROR                           = '邀请手机号还未注册',
        USER_REGISTER_INVITE_PHONE_NO_ERROR                        = '邀请手机号不可与注册手机号一致',
        USER_REGISTER_INVITE_NO_ERROR                              = '不可自己邀请自己',

        USER_REGISTER_SEND_REGISTER_SMS_ERROR                       = '发送短信验证码失败',


        PHONE_VERIFY_CODE_REGISTER                                 = '注册验证码：%s，30分钟内有效 【九斗鱼】',
        PHONE_REGISTERED_PUFUBAO                                   = '【九斗鱼】亲爱的用户您好！您在九斗鱼的初始登录密码为：%s，请尽快修改密码。',

        PHONE_VERIFY_CODE_MODIFY_PHONE                             = '修改手机号验证码：%s，30分钟内有效 【九斗鱼】',
        PHONE_VERIFY_CODE_REGISTER_ERROR                           = '注册验证码验证失败',

        PHONE_VERIFY_CODE_ACTIVATE                                  = '手机激活验证码：%s，30分钟内有效【九斗鱼】',
        PHONE_VERIFY_CODE_FORGET_PASSWORD                           = '找回密码验证码：%s，30分钟内有效【九斗鱼】',
        PHONE_VERIFY_CODE_COMMON                                    = '验证码：%s, 30分钟内有效【九斗鱼】',
        PHONE_VERIFY_CODE_NEW_PHONE                                 = '修改手机号码验证码：%s, 30分钟内有效【九斗鱼】',
        PHONE_VERIFY_CODE_RETURN_NOTICE                             = '验证码正在打飞机过来，这就放弃财富增值的机会了吗？',
        PHONE_REGISTERED_PASSWORD                                   = '【九斗鱼】亲爱的用户您好！您在九斗鱼的初始登录密码为：%s，请尽快修改密码。',

        ERROR_SMS_TYPE                                              = '发送类型错误',

        //交易密码
        ERROR_VERIFY_TRADING_PASSWORD                              = '交易密码错误',
        ERROR_EMPTY_TRADING_PASSWORD                               = '交易密码为空',
        ERROR_EDIT_VERIFY_USER_PASSWORD                            = '原登录密码错误',
        ERROR_EDIT_VERIFY_TRADING_PASSWORD                         = '原交易密码错误',

        //投资记录相关
        ERROR_INVEST_ADD                                           = '添加投资记录失败',
        ERROR_NOVICE_INVEST_USER                                   = '获取投资资格失败（非首次投资）',
        ERROR_NOVICE_INVEST_CASH_LIMIT                             = '超出新手首次投资可投金额上限',

        //零钱计划投资相关

        ERROR_CURRENT_INVEST_USER_BALANCE_NOT_ENOUGH               = '账户余额不足，请充值',
        ERROR_CURRENT_INVEST_BONUS_IS_USING                        = '已有加息券正在使用中',
        ERROR_CURRENT_INVEST_PROJECT_UPDATE_FAILED                 = '项目金额更新失败',
        ERROR_CURRENT_INVEST_PROJECT_NOT_EXIST                     = '投资项目不存在',
        ERROR_CURRENT_INVEST_LEFT_AMOUNT_NOT_ENOUGH                = '项目可投金额不足',
        ERROR_CURRENT_INVEST_FAILED                                = '零钱计划转入失败',
        ERROR_CURRENT_INVEST_OUT_FAILED                            = '零钱计划转出失败',
        ERROR_CURRENT_INVEST_MIN_AMOUNT                            = '零钱计划最小转入金额为%s元',
        ERROR_CURRENT_INVEST_MAX_AMOUNT                            = '单人加入零钱计划总额不超过%s元',
        ERROR_CURRENT_INVEST_FREE_AMOUNT                           = '您当前可加入额度为%s元（单人加入总额限%s元）',
        ERROR_CURRENT_INVEST_OUT_CASH_GREATER_THAN_ACCOUNT_FUND    = '转出金额不能超过零钱计划总资产',
        ERROR_CURRENT_INVEST_OUT_MIN_AMOUNT                        = '零钱计划最小转出金额为%s元',
        ERROR_CURRENT_INVEST_OUT_MAX_AMOUNT                        = '单日转出金额不超过%s元！',
        ERROR_CURRENT_INVEST_OUT_FREE_AMOUNT                       = '当前可转出金额为%s元！',
        ERROR_CURRENT_INVEST_OUT_CASH                              = '零钱计划转出金额错误(最少转出一分钱)',
        ERROR_CURRENT_CREDIT_IS_ASSIGNING                          = '零钱计划债权正在匹配中',
        ERROR_CURRENT_TODAY_INVEST_OUT_FREE_AMOUNT                 = '当日剩余可转出额度为%s元',
        ERROR_CURRENT_INVEST_PROJECT_UN_PUBLISH                    = '项目未发布暂不可投',

        //零钱计划相关
        ERROR_CURRENT_CREDIT_CREATE_FAILED                         = '零钱计划债权添加失败',
        ERROR_CURRENT_CREDIT_DETAIL_CREATE_FAILED                  = '零钱计划债权详情添加失败',
        ERROR_CURRENT_CREDIT_EDIT_FAILED                           = '零钱计划债权编辑失败',
        ERROR_CURRENT_CREDIT_DETAIL_DELETE_FAILED                  = '零钱计划债权详情删除失败',
        ERROR_RECOVERY_CURRENT_CREDIT_FAILED                       = '回收零钱计划债权失败',
        ERROR_RECOVERY_CURRENT_CREDIT_DETAIL_FAILED                = '回收零钱计划债权详情失败',
        ERROR_CREDIT_ASSIGNED_AMOUNT_IS_ZERO                       = '零钱计划账户债权匹配金额为零',
        ERROR_CREDIT_USABLE_AMOUNT_UPDATE_FAILED                   = '更新零钱计划债权可用金额失败',
        ERROR_CREDIT_DETAIL_UPDATE_FAILED                          = '更新零钱计划债权人可用金额失败',
        ERROR_CURRENT_BONUS_INTEREST_USER_NOT_EXIST                = '零钱计划计息用户不存在',
        ERROR_CURRENT_RATE_IS_EXIST                                = '当前日期零钱计划利率已存在',
        ERROR_CURRENT_RATE_DATE_LESS_TODAY                         = '零钱计划利率日期不能早于今天',
        ERROR_CURRENT_RATE_NOT_EXIST                               = '编辑的零钱计划利率记录不存在',
        ERROR_CURRENT_RATE_EDIT_FAILED                             = '编辑零钱计划利率失败',
        ERROR_CURRENT_PROJECT_CREATE_FAILED                        = '创建零钱计划项目失败',
        ERROR_CURRENT_PROJECT_EDIT_FAILED                          = '修改零钱计划项目失败',
        ERROR_CURRENT_USER_NOT_EXIST                               = '零钱计划账户不存在',
        CURRENT_INVEST_MAX_NOTE                                    = '每位用户最多可以投%s万元',
        CURRENT_MIN_INVEST_NOTE                                    = '%s元起，灵活存取',

        ERROR_INVALID_CURRENT_RATE                                 = '无效的零钱计划利率',
        ERROR_INVALID_PROFIT_RATE                                  = '无效的零钱计划加息利率',

        ERROR_CURRENT_FUND_STATISTICS_NOT_FOUND                    = '%s零钱计划资金汇总数据不存在',

        //红包
        ERROR_BONUS_CREATE                                         = '创建红包加息券失败',
        ERROR_BONUS_UPDATE                                         = '编辑红包加息券失败',
        ERROR_BONUS_GET                                            = '获取红包加息券失败',
        ERROR_BONUS_IS_CAN_USE                                     = '优惠券不可用',
        ERROR_BONUS_CLIENT                                         = '不是有效的客户端',
        ERROR_BONUS_PROJECT_TYPE                                   = '不是有效的项目类型',
        ERROR_BONUS_MIN_CAN_USE                                    = '使用此优惠券须最低投资%s元',

        //资金历史
        ERROR_FUND_HISTORY_LIST_GET_FAIL                            = '资金历史获取失败',
        ERROR_ORDER_LIST_GET_FAIL                                   = '订单数据获取失败',

        //后台配置
        ERROR_SYSTEM_CONFIG_KEY_UNIQUE                              = '后台配置键值不唯一',
        ERROR_SYSTEM_CONFIG_CREATE                                  = '后台配置创建失败',
        ERROR_SYSTEM_CONFIG_EDIT                                    = '后台配置更新失败',
        ERROR_SYSTEM_CONFIG_DELETE                                  = '后台配置删除失败',

        //微信
        ERROR_WECHAT_OPERATION                                     = '保存微信数据失败',
        ERROR_WECHAT_OPERATION_UNBIND                              = '解绑失败',

        //验证
        ERROR_INVALID_USER_ID                                      = '无效的用户ID',
        ERROR_INVALID_CASH                                         = '无效的金额(必须为大于0的整数金额)',
        ERROR_INVALID_DECIMAL_CASH                                 = '无效的金额(必须为大于0.01元的小数或整数金额)',
        ERROR_INVALID_BANK_ID                                      = '无效的银行ID',
        ERROR_INVALID_ORDER_ID                                     = '无效的订单号',
        ERROR_INVALID_PROJECT_ID                                   = '无效的项目ID',
        ERROR_INVALID_BANK_CARD                                    = '无效的银行卡号',
        ERROR_INVALID_NAME                                         = '无效的姓名',
        ERROR_INVALID_ID_CARD                                      = '无效的身份证号',
        ERROR_AGE_IS_LESS_EIGHTEEN                                 = '未满十八岁，不能进行实名认证',
        ERROR_INVALID_ORDER_TYPE                                   = '无效的充值或提现类型',
        ERROR_INVALID_ORDER_FROM                                   = '无效的订单来源平台',
        ERROR_INVALID_CANCLE_REASON                                = '取消提现原因不能为空',
        ERROR_PROJECT_EMPTY_NAME                                   = '项目名称为空',
        ERROR_INVALID_DATE                                         = '日期不合法',
        ERROR_INVALID_PHONE                                        = '无效的手机号',
        ERROR_INVALID_SMS_CODE                                     = '验证码必须是六位数字',

        //文章分类
        ERROR_CATEGORY_ADD                                         = '创建分类失败',
        ERROR_CATEGORY_EDIT                                        = '更新分类失败',
        ERROR_ARTICLE_ADD                                          = '创建文章失败',
        ERROR_ARTICLE_EDIT                                         = '更新文章失败',
        ERROR_ARTICLE_DEL                                          = '删除文章失败',

        //银行卡相关
        ERROR_USER_BANK_CARD_CHECK_FAILED                          = '银行卡鉴权失败',
        ERROR_CARD_IS_NOT_SUPPORT                                  = '当前不支持该卡所属的银行',
        ERROR_BANK_CARD_IS_NOT_EXIST                               = '银行卡不存在',

        //项目相关
        ERROR_PROJECT_EMPTY                                        = '项目信息为空',
        ERROR_PROJECT_STATUS                                       = '项目状态异常',
        ERROR_PROJECT_LEFT_AMOUNT                                  = '投资额不能超出项目剩余融资额',
        ERROR_PROJECT_MIN_AMOUNT                                   = '起投金额有误',
        ERROR_PROJECT_SDF_INVEST_CASH                              = '投资金额必须是起投金额整数倍',
        ERROR_PROJECT_EMPTY_CONFIG                                 = '项目限制配置信息不存在',
        ERROR_PROJECT_USE_BONUS                                    = '该项目不允许使用红包加息券',
        ERROR_PROJECT_CANNOT_USE_BONUS                             = '项目已加息不能再使用优惠券',
        ERROR_PROJECT_EXTEND_DATA                                  = '项目扩展信息有误',
        ERROR_PROJECT_EXTEND_ADD                                   = '项目扩展信息添加失败',

        //邀请
        ERROR_INVITE_CREATE                                        = '邀请关系创建失败',
        ERROR_INVITE_OTHER_USER_ID                                 = '被邀请人的邀请关系已存在',
        ERROR_INVITE_USER_TO_OTHER                                 = '邀请关系不能互相建立',

        //合伙人
        ERROR_PARTNER_CREATE                                       = '参加合伙人活动失败',
        ERROR_PARTNER_USER_ID                                      = '此合伙人已存在不能再次创建',
        ERROR_PARTNER_DATA                                         = '参数错误',
        ERROR_PARTNER_CASH                                         = '请输入正确金额！',
        ERROR_PARTNER_CASH_BIG                                     = '转出金额不能超过佣金总额！',
        ERROR_PARTNER_FAIL                                         = '合伙人佣金操作失败',
        PARTNER_OUT                                                = '合伙人佣金转出',

        //短信相关
        PHONE_VERIFY_CODE_FAMILY_ACCOUNT                           = '【九斗鱼】验证码：%s, 30分钟内有效。%s正向你申请家庭账户授权，授权后他可以帮助你操作理财账户。不想授权，请忽略本信息',
        PHONE_ADD_FAMILY_ACCOUNT_SUCCESS                           = '【九斗鱼】家庭账户已成功授权，如若取消，请联系客服 400-6686-568',

        //家庭账户相关
        ERROR_FAMILY_ADD_SELF                                      = '不能添加自己为家庭账户',
        ERROR_FAMILY_ADD_FAIL                                      = '授权关系添加失败，请确认信息后重试。',
        ERROR_FAMILY_LOGOUT                                        = '当前账号已退出，请重新登录再绑定家庭账户。',
        ERROR_FAMILY_ADD_SUCCESS                                   = '家庭账户已成功授权，如若取消，请联系客服 400-6686-568',
        ERROR_FAMILY_ADD_FAIL_INFO                                 = '添加授权关系失败，被授权人：%s，授权信息：%s，原因：%s，错误码：%s',
        ERROR_FAMILY_MORE_AUTH                                     = '该用户您已交叉授权，请确认后再操作',
        ERROR_FAMILY_ADD_REPEAT                                    = '不能重复添加同一个账户',
        ERROR_FAMILY_AUTH_MORE                                     = '该用户已经授权加入了家庭账户，不能再次授权',
        ERROR_FAMILY_PARAM_LESS                                    = '缺少必要参数',
        ERROR_FAMILY_NO_AUTH                                       = '家庭账户未绑卡',
        ERROR_FAMILY_AUTH_FAIL                                     = '授权无效',
        ERROR_FAMILY_LOGOUT_SUCCESS                                = '家庭账户退出成功',

        ERROR_ACTIVITY_FUND_HISTORY                                = '活动资金记录异常',

        //普付宝订单记录
        ERROR_PFB_ADD_INVEST_FAIL                                  = '申请质押订单添加失败',
        ERROR_PFB_UPDATE_INVEST_FAIL                               = '修改订单状态失败',
        ERROR_PFB_ADD_PARAM_EMPTY                                  = '申请质押订单缺少参数',
        ERROR_PFB_UPDATE_PARAM_EMPTY                               = '修改订单状态缺少参数',

        //活动相关
        ERROR_ACTIVITY_ADD_RECORD                                  = '活动数据记录异常',
        ERROR_ACTIVITY_UPDATING_RECORD                             = '活动数据更新异常',
        ERROR_ACTIVITY_ADD_SIGN                                    = '活动签到失败',
        ERROR_ACTIVITY_NOT_LOGIN                                   = '别急还没登陆呢!',
        ERROR_ACTIVITY_PARAM_NULL                                  = '活动参数为空',
        ERROR_ACTIVITY_SIGN_REPEAT                                 = '您今天已经签到过了哦!',
        NATIONAL_SIGN_NOTE                                         = '国庆节活动签到',
        NATIONAL_BONUS                                             = '%d元红包一张',
        ERROR_ACTIVITY_NO_START                                    = '活动未开始',
        ERROR_ACTIVITY_END                                         = '活动已结束',
        ERROR_SIGN_NOT_CONTINUITY                                  = '签到已经中断,谢谢参与',


        ERROR_INVALID_EMAIL                                        = '无效的邮箱地址',

        //个人零钱计划额度
        ERROR_CURRENT_CASH_LIMIT_ADD_FAILED                        =  '创建个人额度失败',
        ERROR_CURRENT_CASH_LIMIT_ADD_SUCCESS                       =  '创建个人额度成功',
        ERROR_CURRENT_CASH_LIMIT_EDIT_SUCCESS                      =  '修改个人额度成功',
        ERROR_CURRENT_CASH_LIMIT_EDIT_FAILED                       =  '修改个人额度失败',
        ERROR_CURRENT_CASH_LIMIT_DELETE_SUCCESS                    =  '删除个人额度成功',
        ERROR_CURRENT_CASH_LIMIT_DELETE_FAILED                     =  '删除个人额度失败',

        //抽奖系统
        ERROR_LOTTERY_CONFIG_ADD_FAILED                            =  '添加奖品设置失败',
        ERROR_LOTTERY_CONFIG_ADD_SUCCESS                           =  '添加奖品设置成功',
        ERROR_LOTTERY_CONFIG_EDIT_FAILED                           =  '修改奖品设置失败',
        ERROR_LOTTERY_CONFIG_EDIT_SUCCESS                          =  '修改奖品设置成功',

        ERROR_LOTTERY_RECORD_ADD_SUCCESS                            =  '记录添加成功',
        ERROR_LOTTERY_RECORD_ADD_FAILED                             =  '记录添加失败',
        ERROR_LOTTERY_RECORD_EDIT_SUCCESS                           =  '更新记录成功',
        ERROR_LOTTERY_RECORD_EDIT_FAILED                            =  '更新记录失败',
        ERROR_LOTTERY_DETAILS_IS_EMPTY                              =   '奖品信息不存在',
        LOTTERY_ENVELOPE_MESSAGE                                    = '【九斗鱼】恭喜您在抽奖活动中抽中了%s一张，请登陆个人账户查看您的优惠券!!',
        LOTTERY_ENTITY_MESSAGE                                      = '【九斗鱼】恭喜您在抽奖活动中抽中了%s礼品，九斗鱼客服会与您取得联系，确定礼品发放详情!!',

        //对账文件相关
        ERROR_CHECK_ORDER_BATCH_ADD_FAILED                          = '对账文件上传失败',
        ERROR_CHECK_ORDER_BATCH_ADD_SUCCESS                         = '对账文件上传成功',
        ERROR_CHECK_ORDER_BATCH_EDIT_FAILED                         = '对账更新失败',
        ERROR_CHECK_ORDER_BATCH_EDIT_SUCCESS                        = '对账更新成功',
        ERROR_CHECK_ORDER_BATCH_DELETE_FAILED                       = '对账删除成功',
        ERROR_CHECK_ORDER_BATCH_DELETE_SUCCESS                      = '对账删除成功',
        ERROR_CHECK_ORDER_BATCH_DELETE_EMPTY                        = '删除的数据不存在',
        ERROR_CHECK_ORDER_BATCH_DELETE_STATUS_FAILED                = '只有待审核的装可以删除',

        //充值对账相关
        ERROR_CHECK_ORDER_ADD_FAILED                                = '数据记录失败',
        ERROR_CHECK_ORDER_ADD_SUCCESS                               = '数据记录成功',
        ERROR_CHECK_ORDER_EDIT_FAILED                               = '数据更新失败',
        ERROR_CHECK_ORDER_EDIT_SUCCESS                              = '数据更新成功',

        ERROR_IS_EMPTY                                              = '不能为空',

        ERROR_CREDIT_ASSIGN_INVEST_FAILED                           = '投资债转项目失败',

        ERROR_ADD_MICRO_JOURNAL_FAILED                              =   '添加微刊信息失败',
        ERROR_EDIT_MICRO_JOURNAL_FAILED                             =   '修改微刊信息失败',
        ERROR_DELETE_MICRO_JOURNAL_FAILED                           =   '删除微刊信息失败',
        ERROR_EMPTY_MICRO_JOURNAL                                   =   '不存在的微刊信息',
        ERROR_CHANGE_PHONE_COMMENT                                  =   '用户ID:%s的手机号码：%s更换为%s,新手机号码为未使用的号码',
        ERROR_INVITE_RATE_INFO                                      = '信息有误',
        ERROR_INVITE_RATE_USING                                     = '已有正在使用的加息券',
        ERROR_INVITE_RATE_USE                                       = '使用失败',

        ERROR_ACTIVITY_VOTE_ADD_RECORD                              =   '投票失败',
        ERROR_ACTIVITY_VOTE_EVERY_DAY_TRAVEL                        =   '每天最多可投%s次,谢谢参与!!',
        ERROR_ACTIVITY_VOTE_ONLY_ONCE                               =   '每人最多可投%s票,谢谢参与!!',
        ERROR_ACTIVITY_TIME_NOT_OPEN                                =   '%s在%s准时开启!',
        ERROR_ACTIVITY_TIME_NOT_CLOSED                              =   '%s已经结束,谢谢参与!',
        ERROR_FUND_STAT_ADD_FIELD                                   =   '账户资金统计统计失败',

        ERROR_DBKV_ADD_FIELD                                        =    'DBKV添加数据失败',

        ERROR_RECORD_ADD_FAIL                                       =    '记录添加失败',
        ERROR_RECORD_UPDATE_FAIL                                    =    '记录更新失败',

        ERROR_ACTIVITY_LOTTERY_EVERY_DAY_TRAVEL                     =   '每天最多可抽%s次,谢谢参与!!',
        ERROR_ACTIVITY_LOTTERY_ONLY_ONCE                            =   '每人最多可抽%s次,谢谢参与!!',
        //活动配置
        ERROR_ACTIVITY_CONFIG_KEY_UNIQUE                            =   '活动配置键值不唯一',
        ERROR_ACTIVITY_CONFIG_CREATE                                =   '活动配置创建失败',
        ERROR_ACTIVITY_CONFIG_EDIT                                  =   '活动配置更新失败',
        ERROR_ACTIVITY_CONFIG_DELETE                                =   '活动配置删除失败',
        ERROR_ACTIVITY_CONFIG_NOT_FIND                              =    '活动配置不存在',
        ERROR_ACTIVITY_INVEST_EMPTY                                 =   '投资记录不存在',
        ERROR_ACTIVITY_INVEST_CASH                                  =   '投资金额不一致',
        ERROR_ACTIVITY_NOTE_EMPTY                                   =   '活动类型不存在',
        ERROR_ACTIVITY_INVEST_ERROR                                 =   '投资记录异常',

        ERROR_CURRENT_ACCOUNT_ADD                                   = '创建零钱计划账户出错',
        ERROR_CURRENT_ACCOUNT_UPDATE                                = '更新零钱计划账户出错',
        ERROR_CURRENT_INTEREST_HISTORY                              = '零钱计划利息错误',
        ERROR_CURRENT_RATE                                          = '零钱计划利率有误',
        ERROR_CURRENT_EMPTY_INTEREST_USER                           = '零钱计划计息用户为空',
        ERROR_CURRENT_REFUND_SPLIT                                  = '零钱计划计息拆分加入队列失败',
        ERROR_CURRENT_ACCOUNT_NOT_EXIST                             = '用户零钱计划账户不存在',
        ERROR_CURRENT_ACCOUNT_BALANCE_NOT_ENOUGH                    = '零钱计划账户余额不足',

        ERROR_CURRENT_NEW_INVEST_OUT                                = '新版零钱计划队列执行转出失败',


        ERROR_PARAMS                                                =   '参数错误',

        //站内信
        ERROR_NOTICE_COMMON                                         = '站内信异常',

        //checkLimit提示语
        ERROR_SEND_CODE_DAY_LIMIT                                   =   '超过24小时内最大次数',
        ERROR_SEND_CODE_TEN_MINUTE_LIMIT                            =   '超过十分钟内最大次数',
        ERROR_SEND_CODE_HOUR_LIMIT                                  =   '超过一小时内最大次数',
        ERROR_SEND_CODE_MINUTE_LIMIT                                =   '请在60秒后重新请求',

        ERROR_END                                             = null;


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
