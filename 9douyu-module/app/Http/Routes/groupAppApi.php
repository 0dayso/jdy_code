<?php

$api  = app('Dingo\Api\Routing\Router');



$api->version(['4.0','4.1.0','4.1.1','4.1.2','4.1.3','4.1.4'], ['namespace' => 'App\\Http\\Controllers\\AppApi\\', 'middleware' => 'appApi'], function($api){
    /**
     * 需要登陆才可以访问的路由组
     */
    $api->group(['middleware' => 'AppApiMustLogin'], function ($api) {
        /* 三要素验卡+支付密码 = 实名认证接口 */
        $api->post('user_verify_bank_card', ['as' => 'user_verify_bank_card', 'uses'=>'V4_0\User\VerifyController@checkCard']);
        /* 实名状态 */
        $api->post('get_user_verify_status', ['as' => 'get_user_verify_status', 'uses'=>'V4_0\User\VerifyController@verifyStatus']);
        /*是否登录*/
        $api->post('check_login', ['as' => 'check_login', 'uses'=>'V4_0\User\VerifyController@checkIsLogin']);

        /*用户充值银行卡*/
        $api->post('bank_rechargeCards', ['as' => 'bank_rechargeCards', 'uses'=>'V4_0\BankCard\RechargeController@rechargeCards']);
        /*充值银行卡列表*/
        $api->post('bank_rechargeBanks', ['as' => 'bank_rechargeBanks', 'uses'=>'V4_0\BankCard\RechargeController@rechargeBanks']);
        /*用户放弃支付*/
        $api->post('give_up_recharge', ['as' => 'give_up_recharge', 'uses'=>'V4_0\Order\RechargeController@giveUpRecharge']);
        /*用户充值*/
        $api->post('user_recharge', ['as' => 'user_recharge', 'uses'=>'V4_0\Order\RechargeController@makeOrderV4']);

        /*用户提现银行卡*/
        $api->post('bank_withdrawCards', ['as' => 'bank_withdrawCards', 'uses'=>'V4_0\BankCard\WithdrawController@withdrawCards']);
        /*提现银行卡列表*/
        $api->post('bank_withdrawBanks', ['as' => 'bank_withdrawBanks', 'uses'=>'V4_0\BankCard\WithdrawController@withdrawBanks']);
        /*绑定提现银行卡*/
        $api->post('bank_addwithDrawCard', ['as' => 'bank_addwithDrawCard', 'uses'=>'V4_0\BankCard\WithdrawController@bindWithdrawCard']);
        /*提现预览*/
        $api->post('pre_withdraw', ['as' => 'pre_withdraw', 'uses'=>'V4_0\Order\WithdrawController@preDoWithdraw']);
        /*创建提现订单*/
        $api->post('user_withdraw', ['as' => 'user_withdraw', 'uses'=>'V4_0\Order\WithdrawController@doWithdraw']);

        /*用户优惠券*/
        $api->post('user_bonus', ['as' => 'user_bonus', 'uses'=>'V4_0\Bonus\UserBonusController@getUserBonus']);
        /*资产明细*/
        $api->post('fund_history', ['as' => 'fund_history', 'uses'=>'V4_0\Fund\FundHistoryController@getList']);

        /*个人信息*/
        $api->post('user_base_info', ['as' => 'user_base_info', 'uses'=>'V4_0\User\UserController@userInfo']);
        /*上传头像*/
        $api->post('up_avatar', ['as' => 'up_avatar', 'uses'=>'V4_0\User\AvatarController@upAvatar']);
        /*修改个人邮箱信息*/
        $api->post('update_email', ['as' => 'update_email', 'uses'=>'V4_0\User\SetController@updateEmail']);
        /*修改个人详细地址信息*/
        $api->post('update_address', ['as' => 'update_address', 'uses'=>'V4_0\User\SetController@updateAddress']);
        /*修改登录密码*/
        $api->post('update_password', ['as' => 'update_password', 'uses'=>'V4_0\User\SetController@updatePassword']);
        /*修改交易密码*/
        $api->post('update_trading_password', ['as' => 'update_trading_password', 'uses'=>'V4_0\User\SetController@updateTradingPassword']);
        /*找回交易密码*/
        $api->post('find_trading', ['as' => 'find_trading', 'uses'=>'V4_0\User\SetController@findTradingPwd']);
        /*修改手机号*/
        $api->post('do_edit_phone',['as' => 'find_trading', 'uses'=>'V4_0\User\UserController@doEditPhone']);

        //验证交易密码
        $api->post('check_trading_pwd', ['as' => 'check_trading_pwd', 'uses'=>'V4_0\User\SetController@checkTradingPassword']);

        //活期用户可用优惠券列表
        $api->post('current_able_user_bonus', ['as' => 'current_able_user_bonus_v4', 'uses'=>'V4_0\Current\CurrentController@currentAbleUserBonus']);

        //活期转入
        $api->post('current_do_invest', ['as' => 'current_do_invest_v4', 'uses'=>'V4_0\Current\CurrentController@currentDoInvest']);

        //活期转出
        $api->post('current_do_invest_out', ['as' => 'current_do_invest_out_v4', 'uses'=>'V4_0\Current\CurrentController@currentDoInvestOut']);

        //活期收益列表
        $api->post('current_interest_history', ['as' => 'current_interest_history_v4', 'uses'=>'V4_0\Current\CurrentController@currentInterestHistory']);

        //活期使用优惠券
        $api->post('current_used_user_bonus', ['as' => 'current_used_user_bonus_v4', 'uses'=>'V4_0\Current\CurrentController@currentUsedUserBonus']);

        //定期用户可用优惠券列表
        $api->post('project_able_user_bonus', ['as' => 'project_able_user_bonus_v4', 'uses'=>'V4_0\Project\ProjectController@projectAbleUserBonus']);

        //定期投资
        $api->post('project_invest', ['as' => 'project_invest_v4', 'uses'=>'V4_0\Project\ProjectController@projectInvest']);

        /*执行项目的债权转让操作*/
        $api->post('user_do_credit_assign', ['as' => 'user_do_credit_assign_v4', 'uses'=>'V4_0\Project\CreditAssignController@userDoCreditAssign']);

        /*取消转让中的项*/
        $api->post('user_do_cancel_credit_assign', ['as' => 'user_do_credit_assign_v4', 'uses'=>'V4_0\Project\CreditAssignController@userDoCancelCreditAssign']);

        /*债权转让项目投资*/
        $api->post('credit_assign_project_invest', ['as' => 'credit_assign_project_invest_v4', 'uses'=>'V4_0\Project\CreditAssignController@creditAssignProjectInvest']);

        /*站内信*/
        $api->post('site_notice', ['as' => 'site_notice', 'uses'=>'V4_0\Notice\NoticeController@getSiteNotice']);

        //站内公告标记为已读
        $api->post('read_notice', ['as' => 'read_notice', 'uses'=>'V4_0\Notice\NoticeController@readNotice']);
        //检测是否显示小红点
        $api->post('check_is_show_notice_tip', ['as' => 'check_is_show_notice_tip', 'uses'=>'V4_0\Notice\NoticeController@checkIsShowNoticeTip']);
        /*公告*/
        $api->post('notice', ['as' => 'notice', 'uses'=>'V4_0\Notice\NoticeController@getNotice']);

        /*用户中心*/
        $api->post('user_assets', ['as' => 'user_assets', 'uses'=>'V4_0\User\UserController@index']);//我的资产

        /*用户回款*/
        $api->post('refund_record', ['as' => 'refund_record', 'uses'=>'V4_0\User\RefundRecordController@refundRecord']);//用户回款首页
        $api->post('month_refund_record', ['as' => 'month_refund_record', 'uses'=>'V4_0\User\RefundRecordController@monthRefundRecord']);//用户本月全部回款
        $api->post('refund_detail', ['as' => 'refund_detail', 'uses'=>'V4_0\User\RefundRecordController@refundRecordDetail']);//用户本月全部回款


        /*我的资产-定期资产*/
        $api->post('user_term', ['as' => 'user_term', 'uses'=>'V4_0\User\TermController@index']);
        $api->post('user_term_detail', ['as' => 'user_term_detail', 'uses'=>'V4_0\User\TermController@detail']);

        //合同
        $api->post('contract', ['as' => 'contract_v4', 'uses'=>'V4_0\User\ContractController@contract']);
        $api->post('invest_contract_send', ['as' => 'contract_v4', 'uses'=>'V4_0\User\ContractController@contractSendEmail']);


        /************************************************4.1版本*******************************************************/

        /*用户回款*/
        $api->post('refund_record_day', ['as' => 'refund_record_day', 'uses'=>'V4_1\User\RefundRecordController@dayRefundRecord']);       //用户当日全部回款
        $api->post('refund_record_month', ['as' => 'refund_record_month', 'uses'=>'V4_1\User\RefundRecordController@monthRefundRecord']); //用户本月全部回款

    });



    /**************** 活期 start ****************/

    //活期详情接口
    $api->post('current_detail', ['as' => 'current_detail_v4', 'uses'=>'V4_0\Current\CurrentController@currentDetail']);

    //活期预期收益接口
    $api->post('current_get_interest', ['as' => 'current_get_interest_v4', 'uses'=>'V4_0\Current\CurrentController@currentGetInterest']);

    /**************** 活期 end ****************/

    /**************** 定期 start ****************/

    //定期详情
    $api->post('project_detail', ['as' => 'project_detail_v4', 'uses'=>'V4_0\Project\ProjectController@projectDetail']);

    //定期预期收益
    $api->post('project_get_interest', ['as' => 'project_get_interest_v4', 'uses'=>'V4_0\Project\ProjectController@projectGetInterest']);

    //定期投资记录
    $api->post('project_invest_records', ['as' => 'project_invest_records_v4', 'uses'=>'V4_0\Project\ProjectController@projectInvestRecords']);
;
    //定期回款计划
    $api->post('project_refund_record', ['as' => 'project_refund_record_v4', 'uses'=>'V4_0\Project\ProjectController@projectRefundRecord']);

    /**************** 定期 end ****************/

    /*债权转让项目详情*/
    $api->post('credit_assign_project_detail', ['as' => 'credit_assign_project_detail_v4', 'uses'=>'V4_0\Project\CreditAssignController@creditAssignProjectDetail']);

    /* 用户登录 */
    $api->post('login', ['as' => 'login', 'uses'=>'V4_0\User\LoginController@doLogin']);

    /* 用户检测 */;
    $api->post('check_phone', ['as' => 'check_phone', 'uses'=>'V4_0\User\LoginController@checkPhone']);

    /* 用户登出 */
    $api->post('logout', ['as' => 'logout', 'uses'=>'V4_0\User\LoginController@doLogout']);

    /* 注册协议 */
    $api->post('register_agreement', ['as' => 'register_agreement', 'uses'=>'V4_0\User\RegisterController@getAgreement']);

    /* 检测注册验证码 */
    $api->post('check_register_code', ['as' => 'check_register_code', 'uses'=>'V4_0\User\RegisterController@checkRegisterCode']);


    /*注册手机号 - 设置密码*/
    $api->post('register', ['as' => 'doRegister', 'uses'=>'V4_0\User\RegisterController@doRegister']);


    /*App4.0首页接口数据*/
    $api->post('home', ['as' => 'home', 'uses'=>'V4_0\Home\HomeController@index']);
    /*首页弹窗*/
    $api->post('home_pop', ['as' => 'home_pop', 'uses'=>'V4_0\Home\HomeController@indexPop']);

    /*理财列表*/
    $api->post('current_index', ['as' => 'current_index', 'uses'=>'V4_0\Project\CurrentController@index']);//零钱计划
    $api->post('project_index', ['as' => 'project_index', 'uses'=>'V4_0\Project\ProjectController@index']);//定期理财列表
    $api->post('assign_project', ['as' => 'assign_project', 'uses'=>'V4_0\Project\CreditAssignController@index']);//理财列表



    $api->post('send_sms', ['as' => 'send_sms', 'uses'=>'V4_0\User\SmsController@sendSms']);//发送手机验证码
    $api->post('check_code', ['as' => 'check_code', 'uses'=>'V4_0\User\SmsController@checkSmsCode']);//验证验证码并判断是否实名
    $api->post('check_real_name', ['as' => 'check_real_name', 'uses'=>'V4_0\User\UserController@checkRealName']);//验证实名信息(名字+身份证号)
    $api->post('find_password', ['as' => 'find_password', 'uses'=>'V4_0\User\SetController@findPassword']);//找回登录密码



    $api->post('more', ['as' => 'more_v4', 'uses'=>'V4_0\User\SetController@more']);//更多接口
    $api->post('feedback', ['as' => 'feedback_v4', 'uses'=>'V4_0\User\SetController@feedback']); //意见反馈

    $api->post('ad_show', ['as' => 'ad_show', 'uses'=>'V4_0\Home\HomeController@adShow']);
    $api->post('ads_show', ['as' => 'ads_show', 'uses'=>'V4_0\Home\HomeController@adShow']);

});

/*****************************************************4.1版本******************************************************/
$api->version(['4.1.0','4.1.1','4.1.2','4.1.3','4.1.4'], ['namespace' => 'App\\Http\\Controllers\\AppApi\\', 'middleware' => 'appApi'], function($api){

    /**
     * 需要登陆才可以访问的路由组
     */
    $api->group(['middleware' => 'AppApiMustLogin'], function ($api) {

        /*用户回款*/
        $api->post('refund_record_day', ['as' => 'refund_record_day', 'uses'=>'V4_1\User\RefundRecordController@dayRefundRecord']);       //用户当日全部回款
        $api->post('refund_record_month', ['as' => 'refund_record_month', 'uses'=>'V4_1\User\RefundRecordController@monthRefundRecord']); //用户本月全部回款

    });

    /*注册手机号 - 设置密码*/
    $api->post('register', ['as' => 'doSignup', 'uses'=>'V4_1\User\RegisterController@doRegister']);

});

/*****************************************************4.1.3版本******************************************************/
$api->version(['4.1.3'], ['namespace' => 'App\\Http\\Controllers\\AppApi\\', 'middleware' => 'appApi'], function($api){

    /*App4.1.3首页接口数据*/
    $api->post('home', ['as' => 'home', 'uses'=>'V4_1_3\Home\HomeController@index']);
    $api->post('project_index', ['as' => 'project_index', 'uses'=>'V4_1_3\Project\ProjectController@index']);//定期理财列表

});