<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/18
 * Time: 下午5:19
 */

/*
 * pc 路由
 */

Route::group(['prefix' => '/', 'namespace' => 'Pc' ,'domain'=> $domain], function()
{
    Route::get('/', 'Home\IndexController@index');   //网站首页

    Route::get('login', 'User\LoginController@index');                          //pc 登陆页面
    Route::post('login/doLogin', 'User\LoginController@doLogin');               //pc 执行登陆
    Route::get('logout', 'User\LoginController@out');                           //pc 执行登出

    Route::get('forgetLoginPassword', 'User\LoginController@forgetLoginPassword');//pc 找回登录密码
    Route::any('resetLoginPassword', 'User\LoginController@resetLoginPassword');//pc 找回登录密码
    Route::any('doResetLoginPassword', 'User\LoginController@doResetLoginPassword');//pc 设置登录密码处理
    Route::get('forgetPasswordSetSuccess', 'User\LoginController@forgetPasswordSetSuccess');//pc 找回登录密码成功页面
    Route::post('resetLoginPassword/sendSms', 'User\LoginController@sendSms');//pc 找回登录密码成功页面

    Route::get('register', 'User\RegisterController@index');                    //pc 注册页面
    Route::post('register/doRegister', 'User\RegisterController@doRegister');   //pc 注册处理
    Route::post('register/sendSms', 'User\RegisterController@sendSms');         // 发送注册短信验证码
    Route::post('register/getTestingPhoneCode', 'User\RegisterController@getTestingPhoneCode');         // 测试过程获取注册短信验证码

    /*项目详情*/
    Route::get('project/index', 'Project\IndexController@index');
    Route::get('project/sdf', 'Project\IndexController@sdfList');

    /*项目详情*/
    Route::get('project/detail/{id}', 'Project\ProjectDetailController@get');
    /*项目详情 文章内别名*/
    Route::get('project/{id}', 'Project\ProjectDetailController@get');

    /*PC端零钱计划项目详情页*/
    Route::get('project/current/detail','Project\ProjectDetailController@getCurrent');

    /*用户中心*/
    Route::get('user', 'User\IndexController@index');

    /*文章详情*/
    Route::get('article/{id}','Article\ArticleController@detail');
    //平台数据统计
    Route::get('zt/statistics', 'Zt\StatisticsController@index');

    Route::get('pc','Home\IndexController@index');

    /*帮助中心*/
    Route::get('help/{id?}','Article\ArticleController@help');

    /*关于我们*/
    /*公司介绍Company Profile*/
    Route::get('about', 'Article\AboutController@index');
    Route::get('about/index', 'Article\AboutController@index');
    Route::get('about/index.html', 'Article\AboutController@index');
    /*中国耀盛China Glory Shine*/
    Route::get('about/sunholding', 'Article\AboutController@sunholding');
    /*合作伙伴Partner*/
    Route::get('about/partner', 'Article\AboutController@partner');
    /*媒体报道Media*/
    Route::get('about/media', 'Article\AboutController@media');
    /*网站公告Notice*/
    Route::get('about/notice/{q?}', 'Article\AboutController@notice');
    /*加入我们Join Us*/
    Route::get('about/joinus', 'Article\AboutController@joinus');
    /*分支机构Branch*/
    Route::get('about/branch', 'Article\AboutController@branch');
    /*安全保障*/
    Route::get('about/insurance', 'Article\AboutController@insurance');
    /*新手指引*/
    Route::get('content/article/newentrance', 'Article\AboutController@newentrance');
    /**/
    Route::any('content/article/reservefund', 'Article\AboutController@reservefund');
    /* 认证跳转页 */
    Route::get('home/util/cnnic', 'Home\UntilController@cnnic');
    Route::get('home/util/plist', 'Home\UntilController@plist');

    /*首页*/
    Route::get('home','Home\IndexController@index');

    /*用户中心零钱计划页面*/
    Route::post('user/currentFund','User\IndexController@currentFund');

    /*用户中心 账户设置 手机号修改 第一步视图 */
    Route::get('user/setting/phone/stepOne', 'User\SettingsController@modifyPhoneViewStepOne');

    /*用户中心 账户设置 手机号修改 第二步视图 */
    Route::get('user/setting/phone/stepTwo/{token}', 'User\SettingsController@modifyPhoneViewStepTwo');

    /*用户中心 账户设置 修改手机号 执行修改*/
    Route::post('user/setting/phone/modify', 'User\SettingsController@modifyPhone');

    /*用户中心 账户设置 手机号修改 交易密码验证*/
    Route::post('user/setting/phone/doVerifyTransactionPassword', 'User\SettingsController@verifyTransactionPassword');

    /*用户中心 账户设置 修改手机号 执行修改*/
    Route::post('user/setting/phone/sendSms', 'User\SettingsController@sendSms');

    /*用户实名+绑卡*/
    Route::get('user/setting/verify','User\SettingsController@verify');
    Route::get('user/verify','User\IndexController@verify');

    Route::post('user/setting/doVerify','User\SettingsController@doVerify');

    /*修改密码*/
    //Route::get('user/password', 'User\SettingsController@password');
    Route::get('user/setting/tradingPassword', 'User\SettingsController@tradingPassword');
    Route::post('user/setting/doTradingPassword', 'User\SettingsController@doTradingPassword');
    Route::post('user/doPassword', 'User\SettingsController@doPassword');
    Route::get('user/settings/success', 'User\SettingsController@success');
    Route::get('user/settings/fail', 'User\SettingsController@fail');

    /* 修改交易密码 */
    Route::post('user/doTradingPassword', 'User\SettingsController@doChangeTradingPassword');

    /*找回交易密码*/
    Route::get('user/vaildTradingPassword', 'User\SettingsController@vaildTradingPassword');
    Route::post('user/findTradingPassword', 'User\SettingsController@findTradingPassword');


    /*PC端首页平台数据详情*/
    Route::get('getHomeStatistics','Home\StatisticsController@index');
    Route::get('user/bankcard','User\CardController@index');
    Route::get('user/bankcard/add','User\CardController@addBankCard');
    Route::post('user/bankcard/submit','User\CardController@submit');
    Route::get('user/bankcard/success','User\CardController@success');

    Route::get('user/fundhistory','User\FundHistoryController@getListByType');
    Route::get('user/investList','User\DownloadFileController@userInvestList');
    Route::post('contract/doCreateDownLoad' ,  'User\DownloadFileController@doCreateDownLoad');
    Route::post('contract/checkContractStatus' ,  'User\DownloadFileController@checkContractStatus');



    /*投资模块*/
    Route::post('invest/term/confirm','Invest\TermController@confirm');
    Route::post('invest/term/submit','Invest\TermController@submit');
    Route::get('invest/term/success','Invest\TermController@success');
    Route::get('invest/term/fail','Invest\TermController@fail');
    Route::post('invest/project/confirm','Invest\ProjectController@confirmInvest');
    Route::get('invest/project/success','Invest\ProjectController@success');

    /*新的定期投资页面*/
    Route::any('invest/project/doInvest','Invest\ProjectController@doInvest');

    /*零钱计划转入*/
    Route::post('invest/current/doInvest','Invest\CurrentController@doInvest');

    /*零钱计划转入成功*/
    Route::get('invest/current/investSuccess','Invest\CurrentController@investSuccess');

    /*零钱计划投资确认页面*/
    Route::any('invest/current/confirm','Invest\CurrentController@confirm');
    /*零钱计划转出*/
    Route::post('invest/current/doInvestOut','Invest\CurrentController@doInvestOut');

    //提现相关页面
    Route::get('pay/withdraw','Order\WithdrawController@index');
    Route::post('pay/withdraw/submit','Order\WithdrawController@submit');
    Route::get('pay/withdraw/success/{orderId}','Order\WithdrawController@success');

    /*闪电付息*/
    Route::get('project/sdf', 'Project\PreProjectController@index');
    /*闪电付息 确认投资页 Invest/Project/preDoInvest?id=1888*/
    Route::any('invest/sdf/investConfirm', 'Project\PreProjectController@investConfirm');
    /*闪电付息 确认交易密码 Ajax*/
    Route::post('user/ajaxCheckTradePassword', 'User\IndexController@checkTradePassword');

    /*PC端查看零钱计划债权*/
    Route::post('current/viewCredit','Current\CreditController@view');

    Route::post('current/checkAjax', 'Invest\CurrentController@checkAjax');

    /**
     * 定期资产
     */
    Route::get('user/term/investing', 'User\RefundController@getInvesting');
    Route::get('user/term/refunding', 'User\RefundController@getRefunding');
    Route::get('user/term/refunded', 'User\RefundController@getRefunded');


    /*****************************充值相关******************************/

    Route::get('recharge/index','Pay\RechargeController@index');
    Route::post('recharge/submit','Pay\RechargeController@submit');
    Route::post('recharge/qdbSubmit','Pay\RechargeController@qdbSubmit');
    Route::post('recharge/reaSubmit','Pay\RechargeController@reaSubmit');
    Route::post('recharge/umpSubmit','Pay\RechargeController@umpSubmit');
    Route::post('recharge/bestSubmit','Pay\RechargeController@bestSubmit');
    #丰付支付
    Route::post('recharge/sumaSubmit',  'Pay\RechargeController@sumaSubmit');

    Route::get('app_guide','Article\ArticleController@download');

    /*************************PC端活动相关***********************************/
    /* 记录活动标示*/
    Route::post('activity/setActToken', 'Activity\ActivityController@setActToken');

    Route::get('activity/spike', 'Activity\SpikeController@activity');
    //项目加息
    Route::get('activity/interest', 'Activity\SpikeController@interest');

    // 晋升中关村金融协会副会长
    Route::get('activity/president', 'Activity\PresidentController@index');

    // 极客评选专题
    Route::get('activity/geeks', 'Activity\GeeksController@index');
    Route::get('activity/geeks/receiveBonus', 'Activity\GeeksController@doReceiveBonus');

    Route::get('activity/national', 'Activity\NationalController@index');
    //投资PK
    Route::get('activity/investpk/firstphase', 'Activity\InvestGameController@firstPhase');
    //投资PK 第二期
    Route::get('activity/investment/secondPhase', 'Activity\InvestGameController@secondPhase');
    //投资PK第三期
    Route::get('activity/investment/thirdPhase', 'Activity\InvestGameController@thirdPhase');
    //投资PK第四期
    Route::get('activity/investment/forthPhase', 'Activity\InvestGameController@forthPhase');


    Route::get('activity/halloween', 'Activity\HalloweenController@index');
    Route::post('activity/halloween/doLottery', 'Activity\HalloweenController@doLuckDraw');

    /* 投票 chanllenge 高校挑战赛*/
    Route::get('activity/challenge', 'Activity\ActivityVoteController@index');
    Route::get('activity/challenge/detail', 'Activity\ActivityVoteController@detail');
    Route::post('activity/challenge/doVote', 'Activity\ActivityVoteController@doVote');
    Route::any('redirect/{type}/{source}/{sourceId}', 'Home\IndexController@index');
    //双蛋活动 红包雨
    Route::get('activity/festival', 'Activity\DoubleFestivalController@festival');
    Route::get('activity/festivalTwo', 'Activity\DoubleFestivalController@festivalTwo');
    // 3% 零钱计划 加息
    Route::get('activity/bonusDay', 'Activity\ReceiveBonusController@bonus');

    //春节活动
    Route::get('activity/springFestival', 'Activity\SpringFestivalController@index');
    //春节签到
    Route::post('activity/spring/signIn', 'Activity\SpringFestivalController@doSignIn');
    //春节抽奖
    Route::post('activity/spring/lottery', 'Activity\SpringFestivalController@doLotterySpring');

    Route::post('activity/spring/exchange', 'Activity\SpringFestivalController@doExchange');
    //春游活动
    Route::get('activity/invitation', 'Activity\InvitationController@index');
    // 元宵节
    Route::get('activity/lantern', 'Activity\LanternController@index');
    Route::post('activity/lantern/doGuessRiddles', 'Activity\LanternController@doGuessRiddles');
    //投资加币活动
    Route::get('activity/canadian', 'Activity\CanadianController@index');
    //春风十里 领现金券活动
    Route::get('activity/coupon', 'Activity\CouponController@coupon');
    Route::post('activity/receive', 'Activity\CouponController@doReceiveBonus');
    //五一活动
    Route::get('activity/LabourDay', 'Activity\LabourDayController@index');
    //五一签到
    Route::post('activity/LabourDay/signIn', 'Activity\LabourDayController@doSignIn');
    //五一抽奖
    Route::post('activity/LabourDay/lottery', 'Activity\LabourDayController@doLottery');
    //五一兑换红包
    Route::post('activity/LabourDay/exchange', 'Activity\LabourDayController@doExchange');

    // 快金-我要借款
    Route::get('timecash/timecashloan', 'TimeCash\TimeCashController@index');
    Route::post('timecash/dotimecashloan', 'TimeCash\TimeCashController@doLoan');

    // 315专题
    Route::get('activity/zt315', 'Activity\Zt315Controller@index');
    // 金融大会专题
    Route::get('activity/finance', 'Activity\FinanceController@index');
    // 江苏银行
    Route::get('activity/custody', 'Activity\CustodyController@index');
     // 江西银行存管
    Route::get('activity/secondCustody', 'Activity\CustodyController@secondCustody');
     // 夏不为利
    Route::get('activity/July', 'Activity\JulyController@July');

    // 立秋
    Route::get('activity/Autumn', 'Activity\AutumnController@index');
    Route::get('activity/autumn/project', 'Activity\AutumnController@getProject');
    Route::post('activity/autumn/luckDraw', 'Activity\AutumnController@doLuckDraw');

    // 新手活动
    Route::get('Novice/extension', 'Activity\NoviceController@extension');

    // 风险承受能力测评表1
    Route::get('user/riskAssessment', 'User\IndexController@riskAssessment');
    // 风险承受能力测评表1
    Route::get('user/riskAssessment2', 'User\IndexController@riskAssessment2');
    // 风险承受能力测评表2
    Route::any('user/riskAssessmentSecond', 'User\IndexController@riskAssessmentSecond');
    // 风险承受能力测评
    Route::post('user/assessment', 'User\IndexController@doAssessment');

    /***************三周年的庆典活动****************/
    //第一趴 first part
    Route::get('thirdAnniversary/firstPart', 'Activity\ThirdAnniversaryController@firstPart');
    //抽奖活动
    Route::post('thirdAnniversary/luckDraw', 'Activity\ThirdAnniversaryController@doLuckDraw');
    //用户抽奖信息
    Route::get('thirdAnniversary/userLevel', 'Activity\ThirdAnniversaryController@getLotteryConfig');

    //周年庆第二趴
    Route::get('thirdAnniversary/secondPart', 'Activity\ThirdAnniversaryController@secondPart');
    //排名数据
    Route::get('thirdAnniversary/ranking', 'Activity\ThirdAnniversaryController@getSecondRanking');
    //周年庆第三趴
    Route::get('thirdAnniversary/thirdPart', 'Activity\ThirdAnniversaryController@thirdPart');
    //周年庆第三趴展示的奖品的中奖记录
    Route::get('thirdAnniversary/triplePrize', 'Activity\ThirdAnniversaryController@getThirdLottery');
    /**********三周年公共部分数据*************/
    //三周年伴手礼记录和奖品配置
    Route::get('thirdAnniversary/souvenir', 'Activity\ThirdAnniversaryController@getLottery');
    //项目数据
    Route::get('thirdAnniversary/showProject', 'Activity\ThirdAnniversaryController@getProject');
    //获取投资总额
    Route::get('thirdAnniversary/summation', 'Activity\ThirdAnniversaryController@getInvestPercentage');

    Route::get('registerAgreement', 'Article\ArticleController@registerAgreement');


});
