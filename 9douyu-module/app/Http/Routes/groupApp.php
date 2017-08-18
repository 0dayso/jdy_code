<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/18
 * Time: 下午5:19
 */

/**
 * [ios && android]
 */
Route::pattern(
    'appSubDomain',
    "(". env('IOS_SUB_DOMAIN') . "|" . env('ANDROID_SUB_DOMAIN') . ")"
);

Route::group(['prefix' => '/', 'namespace' => 'App', 'domain'=>"{appSubDomain}". env('MAIN_DOMAIN')], function()
{

    //App3.0首页接口
    Route::post('home_v3', 'V3\HomeController@index');  //3.0接口首页

    //App3.0首页接口
    Route::post('more_v3', 'V3\ActiveController@index');  //3.0接口首页

    //App3.0增加添加合伙人关系
    Route::post('add_invite', 'V3\InviteController@addRecord');

    /*上传头像*/
    Route::post('up_avatar', 'User\AvatarController@upAvatar');

    /*安卓推广版的启动页的的活动数据接口*/
    Route::post('novice_info', 'Active\ActiveController@noviceInfo');

    /*更多*/
    Route::post('get_active_url', 'Active\ActiveController@getUrl');

    /*   优惠券怎么使用 */
    Route::get('app/topic/bonusDesc', 'Topic\AppTopicController@bonusDesc');
    /* [项目详情] 资产安全 */
    Route::get('app/topic/safe', 'Topic\AppTopicController@safe');
    /* [项目详情] 理财介绍*/
    Route::get('app/topic/financing/desc/{projectLineKey}', 'Topic\AppTopicController@financingDesc');

    /*项目详情*/
    Route::post('project_detail', 'Project\DetailController@get');

    /*项目详情 - 产品详情*/
    Route::get('app/project/product/detail/{id}', 'Project\DetailController@getCreditDetail');

    /*项目详情 - 产品详情*/
    Route::get('app/project/credit/detail/{id}', 'Project\DetailController@getCreditInfo');

    /*项目详情 - 投资记录*/
    Route::post('project_invest_records', 'Project\DetailController@getInvestRecord');

    /*用户检测*/;
    Route::post('check_phone', 'User\LoginController@checkPhone');

    /*用户登录*/
    Route::post('login', 'User\LoginController@doLogin');

    /*用户登出*/
    Route::post('logout', 'User\LoginController@doLogout');

    /*用户注册 - 注册手机号 - 发送短信 */
    Route::post('user_sendSms', 'User\RegisterController@sendSms');

    /*注册手机号 - 设置密码*/
    Route::post('register','User\RegisterController@doRegister');

    /* 实名状态 */
    Route::post('get_user_verify_status','User\VerifyController@verifyStatus');

    /* app内 h5 登陆 */
    Route::any('app_sign_login','AppController@loginWapPage');

    /* 投资页面-输入框数据显示 */
    Route::post('get_invest_percent', 'Project\DetailController@getInvestPercent');

    /* 获取头像 */
    Route::post('get_avatar_url', 'User\AvatarController@getAvatar');

    /* 检测版本 */
    Route::post('check_version', 'Version\VersionController@checkVersions');

    /* 下载信息 */
    Route::post('invite_user_down', 'Download\DownLoadController@getDown');


    /*我的资产*/
    Route::post('user_info', 'User\UserController@index');

    /*用户优惠券列表*/
    Route::post('user_bonus', 'User\BonusController@index');

    /*定期投资页面-获取优惠劵接口*/
    Route::post('user_usable_bonus', 'User\BonusController@userProjectAbleBonusList');

    /*零钱计划加息券信息接口*/
    Route::post('user_current_bonus_list', 'User\BonusController@userCurrentAbleBonusList');

    /*请求域名配置*/
    Route::post('get_server_list', 'Server\ServerController@getServerList');


    /*用户充值银行卡*/
    Route::post('bank_rechargeCards', 'BankCard\RechargeController@rechargeCards');
    /*充值银行卡列表*/
    Route::post('bank_rechargeBanks', 'BankCard\RechargeController@rechargeBanks');
    /*用户提现银行卡*/
    Route::post('bank_withdrawCards', 'BankCard\WithdrawController@withdrawCards');
    /*提现银行卡列表*/
    Route::post('bank_withdrawBanks', 'BankCard\WithdrawController@withdrawBanks');
    /*绑定提现银行卡*/
    Route::post('bank_addwithDrawCard', 'BankCard\WithdrawController@bindWithdrawCard');
    /*用户充值*/
    Route::post('user_recharge', 'Order\RechargeController@makeOrder');

    /*APP3.X版本支持人工选择支付通道*/
    Route::post('user_recharge_v3','Order\RechargeController@makeOrderV3');
    /*定期投资记录*/
    Route::post('user_invest_record', 'User\TermController@investRecord');
    /*定期记录详情*/
    Route::post('user_invest_detail', 'User\TermController@recordDetail');
    /*用户余额*/
    Route::post('user_balance', 'User\UserController@getBalance');
    /*预期收益*/
    Route::post('invest_profit', 'Project\InvestController@getInvestProfit');
    /*项目可投金额*/
    Route::post('project_left_amount', 'Project\InvestController@projectLeftAmount');
    /*投资定期*/
    Route::post('invest_project', 'Project\InvestController@termInvest');

    /* 定期项目列表 */
    Route::post('project_index','Project\IndexController@index');
    Route::post('project_more','Project\IndexController@refundingList');

    /*零钱计划投资页面*/
    Route::post('current_invest','Current\InvestController@investDetail');

    /*零钱计划项目详情页*/
    Route::post('current_project_detail','Current\DetailController@get');

    /*零钱计划转入*/
    Route::post('current_doinvest','Current\InvestController@doInvest');

    /*零钱计划转出前的详情页面*/
    Route::post('current_invest_out','Current\InvestController@investOutDetail');

    /*零钱计划投资协议*/
    Route::post('invest_current_agreement','Current\InvestController@getAgreement');

    /*零钱计划介绍*/
    Route::get('app/topic/current','Topic\AppTopicController@currentDesc');


    /*零钱计划转出*/
    Route::post('current_doinvest_out','Current\InvestController@doInvestOut');

    /*使用零钱计划加息券*/
    Route::post('user_do_current_bonus','User\BonusController@doUserCurrentBonus');

    /*交易密码弹出框 公共方法*/
    Route::post('verify_trade','Password\TradingController@verifyTrade');

    /*零钱计划用户近一周收益*/
    Route::post('current_interest_history','Refund\CurrentController@interestList');

    //获取零钱计划用户转入转出明细
    Route::post('current_invest_history','Fund\FundHistoryController@getCurrentInvestList');

    /*交易明细*/
    Route::post('fund_history','Fund\FundHistoryController@getList');

    /*提现预览*/
    Route::post('pre_withdraw','Order\WithdrawController@preDoWithdraw');

    /*创建提现订单*/
    Route::post('user_withdraw','Order\WithdrawController@doWithdraw');

    /*用户放弃支付*/
    Route::post('give_up_recharge','Order\RechargeController@giveUpRecharge');

    /*注册协议*/
    Route::post('register_agreement','User\RegisterController@getAgreement');

    /*验证交易密码是否正确*/
    Route::post('check_tradePassword','Password\TradingController@checkPassword');

    /*验证登录密码是否正确*/
    Route::post('verify_password','Password\PasswordController@checkPassword');

    /*修改登录密码*/
    Route::post('set_password','Password\PasswordController@changePassword');

    /*验证旧的交易密码 接口功能重复*/
    Route::post('verify_old_trade','Password\TradingController@checkPassword');

    /*修改交易密码*/
    Route::post('set_new_trade','Password\TradingController@changePassword');

    /*设置新的交易密码*/
    Route::post('do_forget_tradepassword','Password\TradingController@modifyPassword');

    /*账户中心-忘记录登录密码-短信验证*/
    Route::post('forget_password','User\UserController@checkSmsCode');

    /*账户中心-修改手机号-设置新手机号*/
    Route::post('do_edit_phone','User\UserController@doEditPhone');

    /*账户中心-忘记交易密码-验证身份证*/
    Route::post('verify_identity','User\UserController@verifyIdentity');

    /*发送验证码*/
    Route::post('send_sms','User\SmsController@sendSms');

    /*3.1.0判断注册验证码是否正确*/
    Route::post('check_register_code','User\SmsController@checkRegisterCode');

    /*发送语音验证码*/
    Route::post('user_send_voice','User\SmsController@sendVoiceSms');


    /*登录页面-忘记登录密码-设置新的登录密码*/
    Route::post('get_login','Password\GetBackController@resetPassword');

    /*账户中心-忘记交易密码-验证手机号*/
    Route::post('get_back_trade','User\UserController@checkSmsCode');

    /*登录页面-忘记登录密码-验证验证码*/
    Route::post('verify_loginSms','User\SmsController@checkSmsCode');

    /*三要素验卡*/
    Route::post('user_verify_tiecard','User\VerifyController@checkCard');

    /*记录用户手机信息*/
    Route::post('phone_code_record','User\UserController@addPhoneErrorRecord');

    /*更多模块-消息中心*/
    Route::post('user_notice','User\UserController@userNoticeList');

    /*关于-意见反馈*/
    Route::post('suggest_add','User\SuggestController@addSuggest');



    /*广告*/
    //请求启动页图片
    //钱包活动数据
    //转入成功页面广告接口
    //加载资产页广告点击右上角相关内容
    //投资成功页面-下面的广告
    //更多模块-实名认证成功
    //登录模块-登录页面-广告
    Route::post('ads_show', 'Ad\AdController@index');
    /*请求活动是否弹出显示	*/
    Route::post('get_max_adsid','Ad\AdController@getMaxAdSid');

    /*债转*/
    /*确认转让信息页面数据*/
    Route::post('user_pre_credit_assign', 'CreditAssign\CreditAssignController@userPreCreditAssign');
    /*执行项目的债权转让操作*/
    Route::post('user_do_credit_assign', 'CreditAssign\CreditAssignController@userDoCreditAssign');
    /*取消转让中的项*/
    Route::post('user_cancel_credit_assign', 'CreditAssign\CreditAssignController@userCancelCreditAssign');
    /*投资债转*/
    Route::post('invest_credit_assign', 'CreditAssign\CreditAssignController@doInvest');

    /*债权转让列表*/
    Route::post('user_credit_assign', 'CreditAssign\CreditAssignController@userCreditAssign');
    /*债权转让列表接*/
    Route::post('assign_project', 'CreditAssign\ProjectController@assignProject');
    /*债权转让项目详情*/
    Route::post('credit_assign_detail', 'CreditAssign\ProjectController@creditAssignDetail');
    /*债权转让投资描述*/
    Route::post('user_credit_assign_desc', 'CreditAssign\CreditAssignController@userCreditAssignDesc');

    /*请求tabBar图片	*/
    Route::post('menu_button','AppButton\AppButtonController@menuButton');

    /*App端请求入口*/

    /*回款计划 [User\RefundPlanController@refundPlan]*/
    Route::post('refund_plan','User\RefundPlanController@refundPlan');
    /*当月回款具体信息 [User\RefundPlanController@refundPlanByDate]*/
    Route::post('refund_plan_by_date','User\RefundPlanController@refundPlanByDate');
    /*Android当月回款计划页 [User\RefundPlanController@androidRefundPlanByDate]*/
    Route::post('android_refund_plan_by_date','User\RefundPlanController@androidRefundPlanByDate');
    /*昨日收益 [User\UserController@userYesterdayInterest]*/
    Route::post('user_yesterday_interest','User\UserController@userYesterdayInterest');
    //更新渠道的点击数
    Route::post('activate_channel','Channel\ActivateController@doActivate');

    /*合同*/
    Route::post('invest_contract', 'User\ContractController@contract');

    /*合同*/
    Route::post('invest_contract_down', 'User\ContractController@contractDown');

    /*合同预览*/
    Route::post('invest_contract_show', 'User\ContractController@contractShowPdf');

    /*合同发送至邮件*/
    Route::post('invest_contract_send', 'User\ContractController@contractSendEmail');

    /*协议*/
    Route::post('invest_agreement','User\ContractController@agreement');

    /*债转合同*/
    Route::post('invest_credit_assign_agreement', 'User\ContractController@contract');

    /*是否登录*/
    Route::post('check_login', 'User\VerifyController@checkIsLogin');

    /**
     * 入口
     */
    Route::any('app/gateway','GatewayController@index');

});
