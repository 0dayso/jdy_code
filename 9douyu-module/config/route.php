<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/11
 * Time: 14:14
 */


return [
    /* 上传头像 */
    'up_avatar'=> 'User\AvatarController@upAvatar',

    /* 资讯的分享数据 */
    'get_news_share'=> 'Active\ActiveController@getNewsShare',

    /*安卓推广版的启动页的的活动数据接口*/
    'novice_info'=> 'Active\ActiveController@noviceInfo',

    'invite_user_down'=> 'Download\DownLoadController@getDown',

    /*更多*/
    'get_active_url' =>'Active\ActiveController@getUrl',

    'app/topic/safe'=> 'Topic\AppTopicController@safe',
    /* [项目详情] 理财介绍*/
    'app/topic/financing/desc'=> 'Topic\AppTopicController@financingDesc',
    
    /*项目详情*/
    'project_detail'=> 'Project\DetailController@get',
    
    /*项目详情 - 投资记录*/
    'project_invest_records'=> 'Project\DetailController@getInvestRecord',
    
    /*用户检测*/
    'check_phone'=> 'User\LoginController@checkPhone',

    
    /*用户登录*/
    'login'=> 'User\LoginController@doLogin',
    
    /*用户登出*/
    'logout'=> 'User\LoginController@doLogout',
    
    /*用户注册 - 注册手机号 - 发送短信 */
    'user_sendSms'=> 'User\RegisterController@sendSms',
    
    /*注册手机号 - 设置密码*/
    'register'=> 'User\RegisterController@doRegister',
    
    /* 实名状态 */
    'get_user_verify_status'=> 'User\VerifyController@verifyStatus',
    
    /* app内 h5 登陆 */
    'app_sign_login'=> 'AppController@loginWapPage',
    
    /* 投资页面-输入框数据显示 */
    'get_invest_percent'=> 'Project\DetailController@getInvestPercent',
    
    /* 获取头像 */
    'get_avatar_url'=> 'User\AvatarController@getAvatar',

    /* 获取头像状态 */
    'get_avatar_info'=> 'User\AvatarController@getAvatarInfo',
    
    /* 检测版本 */
    'check_version'=> 'Version\VersionController@checkVersions',
    
    /*我的资产*/
    'user_info'=> 'User\UserController@index',
    
    /*用户优惠券列表*/
    'user_bonus'=> 'User\BonusController@index',
    
    /*定期投资页面-获取优惠劵接口*/
    'user_usable_bonus'=> 'User\BonusController@userProjectAbleBonusList',
    
    /*零钱计划加息券信息接口*/
    'user_current_bonus_list'=> 'User\BonusController@userCurrentAbleBonusList',
    
    /*请求域名配置*/
    'get_server_list'=> 'Server\ServerController@getServerList',
    
    
    /*用户充值银行卡*/
    'bank_rechargeCards'=> 'BankCard\RechargeController@rechargeCards',
    /*充值银行卡列表*/
    'bank_rechargeBanks'=> 'BankCard\RechargeController@rechargeBanks',
    /*用户提现银行卡*/
    'bank_withdrawCards'=> 'BankCard\WithdrawController@withdrawCards',
    /*提现银行卡列表*/
    'bank_withdrawBanks'=> 'BankCard\WithdrawController@withdrawBanks',

    'bank_withDrawBanks' => 'BankCard\WithdrawController@withdrawBanks',
    /*绑定提现银行卡*/
    'bank_addwithDrawCard'=> 'BankCard\WithdrawController@bindWithdrawCard',


    'bank_addWithDrawCard' => 'BankCard\WithdrawController@bindWithdrawCard',
    /*用户充值*/
    'user_recharge'=> 'Order\RechargeController@makeOrder',

    'user_recharge_v3'=> 'Order\RechargeController@makeOrderV3',

    /*定期投资记录*/
    'user_invest_record'=> 'User\TermController@investRecord',
    /*定期记录详情*/
    'user_invest_detail'=> 'User\TermController@recordDetail',
    /*用户余额*/
    'user_balance'=> 'User\UserController@getBalance',
    /*预期收益*/
    'invest_profit'=> 'Project\InvestController@getInvestProfit',
    /*项目可投金额*/
    'project_left_amount'=> 'Project\InvestController@projectLeftAmount',
    /*投资定期*/
    'invest_project'=> 'Project\InvestController@termInvest',

    
    /*零钱计划投资页面*/
    'current_invest'=> 'Current\InvestController@investDetail',
    
    /*零钱计划项目详情页*/
    'current_project_detail'=> 'Current\DetailController@get',
    
    /*零钱计划转入*/
    'current_doinvest'=> 'Current\InvestController@doInvest',
    
    /*零钱计划转出前的详情页面*/
    'current_invest_out'=> 'Current\InvestController@investOutDetail',
    
    /*零钱计划投资协议*/
    'invest_current_agreement'=> 'Current\InvestController@getAgreement',
    
    /*零钱计划介绍*/
    'app/topic/current'=> 'Current\DetailController@getIntroduction',
    
    
    /*零钱计划转出*/
    'current_doinvest_out'=> 'Current\InvestController@doInvestOut',
    
    /*使用零钱计划加息券*/
    'user_do_current_bonus'=> 'User\BonusController@doUserCurrentBonus',
    
    /*交易密码弹出框 公共方法*/
    'verify_trade'=> 'Password\TradingController@verifyTrade',
    
    /*零钱计划用户近一周收益*/
    'current_interest_history'=> 'Refund\CurrentController@interestList',
    
    //获取零钱计划用户转入转出明细
    'current_invest_history'=> 'Fund\FundHistoryController@getCurrentInvestList',
    
    /*交易明细*/
    'fund_history'=> 'Fund\FundHistoryController@getList',
    
    /*提现预览*/
    'pre_withdraw'=> 'Order\WithdrawController@preDoWithdraw',
    
    /*创建提现订单*/
    'user_withdraw'=> 'Order\WithdrawController@doWithdraw',
    
    /*用户放弃支付*/
    'give_up_recharge'=> 'Order\RechargeController@giveUpRecharge',
    
    /*注册协议*/
    'register_agreement'=> 'User\RegisterController@getAgreement',
    
    /*验证交易密码是否正确*/
    'check_tradePassword'=> 'Password\TradingController@checkPassword',
    
    /*验证登录密码是否正确*/
    'verify_password' => 'Password\PasswordController@checkPassword',


/*修改登录密码*/
    'set_password'=> 'Password\PasswordController@changePassword',
    
    /*验证旧的交易密码 接口功能重复*/
    'verify_old_trade'=> 'Password\TradingController@checkPassword',
    
    /*修改交易密码*/
    'set_new_trade'=> 'Password\TradingController@changePassword',
    
    /*设置新的交易密码*/
    'do_forget_tradepassword'=> 'Password\TradingController@modifyPassword',
    
    /*账户中心-忘记录登录密码-短信验证*/
    'forget_tradepassword'=> 'User\UserController@checkSmsCode',
    
    /*账户中心-修改手机号-设置新手机号*/
    'do_edit_phone'=> 'User\UserController@doEditPhone',
    
    /*账户中心-忘记交易密码-验证身份证*/
    'verify_identity'=> 'User\UserController@verifyIdentity',
    
    /*发送验证码*/
    'send_sms'=> 'User\SmsController@sendSms',
    
    /*发送语音验证码*/
    'user_send_voice'=> 'User\SmsController@sendVoiceSms',
    
    
    /*登录页面-忘记登录密码-设置新的登录密码*/
    'get_login'=> 'Password\GetBackController@resetPassword',
    
    /*账户中心-忘记交易密码-验证手机号*/
    'get_back_trade'=> 'User\UserController@checkSmsCode',
    
    /*登录页面-忘记登录密码-验证验证码*/
    'verify_loginSms'=> 'User\SmsController@checkSmsCode',

    'check_register_code'=>'User\SmsController@checkRegisterCode',


/*三要素验卡*/
    'user_verify_tiecard'=> 'User\VerifyController@checkCard',
    
    /*记录用户手机信息*/
    'phone_code_record'=> 'User\UserController@addPhoneErrorRecord',
    
    /*更多模块-消息中心*/
    'user_notice'=> 'User\UserController@userNoticeList',
    
    /*关于-意见反馈*/
    'suggest_add'=> 'User\SuggestController@addSuggest',
    /*广告*/
    //请求启动页图片
    //钱包活动数据
    //转入成功页面广告接口
    //加载资产页广告点击右上角相关内容
    //投资成功页面-下面的广告
    //更多模块-实名认证成功
    //登录模块-登录页面-广告
    'ads_show'=> 'Ad\AdController@index',
    /*请求活动是否弹出显示	*/
    'get_max_adsid'=> 'Ad\AdController@getMaxAdSid',
    /*债转*/
    /*确认转让信息页面数据*/
    'user_pre_credit_assign'=> 'CreditAssign\CreditAssignController@userPreCreditAssign',
    /*执行项目的债权转让操作*/
    'user_do_credit_assign'=> 'CreditAssign\CreditAssignController@userDoCreditAssign',
    /*取消转让中的项*/
    'user_cancel_credit_assign'=> 'CreditAssign\CreditAssignController@userCancelCreditAssign',
    /*债转合同*/
    //'invest_credit_assign_agreement'=> 'CreditAssign\CreditAssignController@investCreditAssignAgreement',
    'invest_credit_assign_agreement'=> 'User\ContractController@contract',
    /*投资债转*/
    'invest_credit_assign'=> 'CreditAssign\CreditAssignController@doInvest',


    /*债权转让列表接*/
    'assign_project'=> 'CreditAssign\ProjectController@assignProject',
    /*债权转让列表*/
    'user_credit_assign'=> 'CreditAssign\CreditAssignController@userCreditAssign',
    /*债权转让项目详情*/
    'credit_assign_detail'=> 'CreditAssign\ProjectController@creditAssignDetail',
    /*债权转让投资描述*/
    'user_credit_assign_desc'=> 'CreditAssign\CreditAssignController@userCreditAssignDesc',

    /*请求tabBar图片	*/
    'menu_button'=> 'AppButton\AppButtonController@menuButton',
    /**
     * 首页
     */
    'home'  => 'Home\HomeController@Index',

    //App3.0首页接口
    'home_v3'  => 'V3\HomeController@index',

    //App3.0首页接口更多
    'more_v3'  =>'V3\ActiveController@index',

    //App3.0增加添加合伙人关系
    'add_invite'  => 'V3\InviteController@addRecord',

    /* 定期项目列表 */
    'project_index' => 'Project\IndexController@index',
    'project_more' => 'Project\IndexController@refundingList',

    'android_refund_plan_by_date' => 'User\RefundPlanController@androidRefundPlanByDate',

     'refund_plan_by_date' => 'User\RefundPlanController@refundPlanByDate',

    'ad_show'   => 'Ad\AdController@index',
    
    'refund_plan' => 'User\RefundPlanController@refundPlan',

    /*账户中心-忘记录登录密码-短信验证*/
    'forget_password'   => 'User\UserController@checkSmsCode',

    /*检测是否登录*/
    'check_login'       => 'User\VerifyController@checkIsLogin',

    'invest_contract' => 'User\ContractController@contract',

    /*合同*/
    'invest_contract_down' => 'User\ContractController@contractDown',

    /*合同预览*/
    'invest_contract_show' => 'User\ContractController@contractShowPdf',

    /*合同发送至邮箱*/
    'invest_contract_send'=> 'User\ContractController@contractSendEmail',

    'invest_agreement' => 'User\ContractController@agreement',
    'faq'    => 'Active\ActiveController@faq',
    'team'    => 'Active\ActiveController@team',


];