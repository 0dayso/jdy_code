<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/18
 * Time: 下午5:19
 */
/**
 * 微信
 */

Route::group(['prefix' => '/', 'namespace' => 'Weixin', 'domain' => $domain], function()
{
    /*提现银行卡*/
    Route::get('bank/userCard', 'User\CardController@index');

    /*回款计划 */
    Route::get('RefundPlan','User\RefundPlanController@refundPlan');
    /*当月回款具体信息 */
    Route::get('RefundPlan/byDate/{date}/{total}/{num}','User\RefundPlanController@refundPlanByDate');

    /*项目列表页*/
    Route::get('project/lists', 'Project\ProjectController@index');
    /*更多项目列表*/
    Route::get('project/more', 'Project\ProjectController@more');

    /*项目详情*/
    Route::get('project/detail/{id}', 'Project\ProjectDetailController@get');
    Route::get('project/calculator', 'Project\ProjectDetailController@calculator');
    Route::get('project/companyDetail/{id}', 'Project\ProjectDetailController@companyDetail');
    Route::get('project/detail', 'Project\ProjectDetailController@detail');

    /*投资*/
    Route::get('invest/project/confirm/{id}', 'Invest\ProjectController@confirm');
    Route::post('invest/project/doInvest', 'Invest\ProjectController@doInvest');
    Route::get('invest/project/success', 'Invest\ProjectController@success');

    /*定期项目投资详情*/
    Route::get('invest/detail/{id}', 'Invest\ProjectController@detail');

    /*微信首页*/
    Route::get('/', 'Home\IndexController@index');
    /* 登陆 检测手机号页面*/
    //Route::get('login', 'User\LoginController@checkPhone');
    /* 登录注册流程拆分后的登录页面*/
    Route::get('login', 'User\LoginController@login');

    //找回登录密码
    Route::get('findLoginPassword', 'User\LoginController@findLoginPassword');
    //重置登录密码
    Route::any('resetLoginPassword', 'User\LoginController@resetLoginPassword');
    //重置登录密码操作
    Route::post('doResetLoginPassword', 'User\LoginController@doResetLoginPassword');
    //发送找回登录密码的信息短信验证码
    Route::post('sendFindPasswordSms', 'User\LoginController@sendFindPasswordSms');
    Route::post('register/getTestingPhoneCode', 'User\RegisterController@getTestingPhoneCode');         // 测试过程获取注册短信验证码

    /*退出账号路由*/
    Route::get('logout', 'User\LoginController@out');
    /* 注册页面*/
    Route::get('register', 'User\RegisterController@index');
    //用户注册确认和app3.1.0保持一致
    Route::any('registerConfirm', 'User\RegisterController@registerConfirm');
    //用户注册信息ajax的验证
    Route::post('registerAjaxFormCheck', 'User\RegisterController@registerAjaxFormCheck');

    Route::get('agreement', 'User\RegisterController@agreement');
    Route::post('register/doRegister', 'User\RegisterController@doRegister');
    /*注册发送验证码*/
    Route::post('register/sendSms', 'User\RegisterController@sendSms');
    /*注册验证短信验证码*/
    Route::post('register/checkPhoneCode', 'User\RegisterController@checkPhoneCode');

    /* 执行检测手机号 */
    Route::post('doCheckPhone', 'User\LoginController@doCheckPhone');
    /* 登陆页面 */
    Route::get('login/index', 'User\LoginController@index');
    /* 微信 执行登陆 */
    Route::post('login/doLogin', 'User\LoginController@doLogin');

    /*账户中心*/
    Route::get('user','User\IndexController@index');

    Route::get('user/account','User\IndexController@accountBalance');

    Route::post('user/account/getLogList','User\IndexController@getLogList');

    Route::post('user/checkTradePassword','UserController@checkTradePassword');

    /* 修改登陆密码页面 */
    Route::get('user/modifyLoginPassword', 'User\InformationController@modifyLoginPassword');

    /* 设置交易密码页面 */
    Route::get('user/setTradingPassword','User\InformationController@setTradingPassword');

    /* 设置交易密码页面 */
    Route::post('user/doSetTradingPassword','User\InformationController@doSetTradingPassword');

    /* 设置交易密码成功 */
    Route::get('user/setTradingPasswordSuccess','User\InformationController@setTradingPasswordSuccess');

    /* 执行修改密码 */
    Route::Post('user/doModifyLoginPassword', 'User\InformationController@doModifyLoginPassword');
    /* 修改密码成功页面 */
    Route::get('user/modifyLoginPasswordSuccess', 'User\InformationController@modifyLoginPasswordSuccess');

    /* 修改交易密码页面 */
    Route::get('user/modifyTradingPassword', 'User\InformationController@modifyTradingPassword');
    /* 执行交易修改密码 */
    Route::Post('user/doModifyTradingPassword', 'User\InformationController@doModifyTradingPassword');
    /* 修改交易密码成功页面 */
    Route::get('user/modifyTradingPasswordSuccess', 'User\InformationController@modifyTradingPasswordSuccess');
    /*安全中心*/
    Route::get('information', 'User\InformationController@index');

    /*用户优惠券*/
    //可用优惠券列表
    Route::get('bonus/index', 'User\IndexController@ableBonusList');
    //不可用优惠券列表
    Route::get('bonus/unused', 'User\IndexController@unableBonusList');

    /*普付宝项目列表*/
    Route::get('pfb/projectList','Project\ProjectController@pfbList');

    /**************************微信 sdk start. ********************************************************************/
    /* 微信 */
    Route::any('replyData', 'WeixinServerController@serve');

    /* 微信登陆授权 */
    Route::any('wechat/login', 'Module\WeixinServerUserController@login');

    /* 微信注册授权 */
    Route::any('wechat/register', 'Module\WeixinServerUserController@register');

    /* 用户通用回调地址 */
    Route::any('wechat/commonCallback/{from}/{code}', 'Module\WeixinServerUserController@commonCallback');

    /* 用户微信注册回调 */
    Route::any('wechat/registerCallback', 'Module\WeixinServerUserController@registerCallback');

    /* 用户登陆微信回调 */
    Route::any('wechat/loginCallback', 'Module\WeixinServerUserController@loginCallback');

    /* 用户微信登陆回调后绑定 */
    Route::any('wechat/loginBind/{openid}', 'Module\WeixinServerUserController@loginBind');

    /* 未登录用户 登陆 绑定执行 */
    Route::any('wechat/doLoginBind', 'Module\WeixinServerUserController@doLoginBind');

    /* 用户微信注册回调后绑定 */
    Route::any('wechat/registerBind/{openid}', 'Module\WeixinServerUserController@registerBind');

    /* 用户微信注册 绑定执行 */
    Route::any('wechat/doRegisterBind', 'Module\WeixinServerUserController@doRegisterBind');

    /* 用户微信注册回调后绑定 */
    Route::any('wechat/unBind/{openid}', 'Module\WeixinServerUserController@unBind');

    /* 微信菜单添加 */
    Route::any('wechat/menu/add', 'Module\WeixinServerMenuController@add');
    /* 微信菜单删除 */
    Route::any('wechat/menu/del', 'Module\WeixinServerMenuController@del');

    /**
     * 外部系统 获取openid
     */
    Route::get('wechat/jyf/get/openid', 'Module\WeixinServerUserController@getOpenId');

    /**************************微信 sdk end. **********************************************************************/
    /* 家庭账户首页 */
    Route::get('family/home', 'Family\FamilyController@home');
    /* 家庭账户列表 */
    Route::get('family/accountList', 'Family\FamilyController@accountList');
    /* 家庭账户 for who */
    Route::get('family/forWho', 'Family\FamilyController@forWho');
    /* 家庭账户 选择更多角色 */
    Route::get('family/more', 'Family\FamilyController@more');
    /* 家庭账户 申请 */
    Route::get('family/phone/{role}', 'Family\FamilyController@phone');
    /* 家庭账户 验证 */
    Route::post('family/sendCode', 'Family\FamilyController@sendCode');
    /* 家庭账户 检查验证码 */
    Route::post('family/checkPhoneVerify', 'Family\FamilyController@checkPhoneVerify');
    /* 家庭账户 创建 */
    Route::post('family/doPostPhone', 'Family\FamilyController@doPostPhone');
    /* 家庭账户 绑卡页面 */
    Route::get('family/verifyIdentity', 'Family\FamilyController@verifyIdentity');
    /* 家庭账户 添加绑卡 */
    Route::post('family/doVerify', 'Family\FamilyController@doVerify');
    /* 家庭账户 登录 */
    Route::get('family/loginAuthAccount/{authStr}', 'Family\FamilyController@loginAuthAccount');
    /* 家庭账户 登录 */
    Route::post('family/logoutAuthAccount', 'Family\FamilyController@logoutAuthAccount');
    /* 家庭账户 推广页 */
    Route::get('family/guide', 'Family\FamilyController@guide');
    /* 家庭账户 推广页检测手机号 */
    Route::post('family/checkUniquePhone', 'Family\FamilyController@checkUniquePhone');
    /* 家庭账户 推广页注册页 */
    Route::get('family/code', 'Family\FamilyController@code');
    /* 家庭账户 推广页注册 */
    Route::post('family/register', 'Family\FamilyController@register');
    /* 家庭账户 介绍 */
    Route::get('family/intro', 'Family\FamilyController@intro');

    /**************************微信 sdk end. **********************************************************************/

    /* 合伙人 合伙人专题页 */
    Route::get('activity/y2015partner/{from?}',     'Activity\PartnerController@index');
    Route::get('activity/partner/{from?}',     'Activity\PartnerController@index');
    /* 合伙人 参与合伙人 */
    //Route::get('y2015partner/add',                  'Activity\UserPartnerController@createPartner');
    /* 合伙人 参加合伙人活动信息页 */
    //Route::get('ActivityPartner',                   'Activity\UserPartnerController@index');
    /* 合伙人 邀请二维码*/
    //Route::get('ActivityPartner/scanCode',          'Activity\UserPartnerController@scanCode');
    /* 合伙人 佣金排名页面 */
    //Route::get('ActivityPartner/commission',        'Activity\UserPartnerController@commission');
    /* 合伙人 邀请合伙人数/邀请的合伙人待收页面 */
    //Route::get('ActivityPartner/details',           'Activity\UserPartnerController@details');
    /* 合伙人 资金记录页面 */
    //Route::get('ActivityPartner/profit',            'Activity\UserPartnerController@profit');
    /* 合伙人 转出成功页 */
    Route::get('ActivityPartner/turnOutSuccess',    'Activity\UserPartnerController@turnOutSuccess');
    /* 合伙人 转出验证交易密码 */
    Route::post('password/ajaxCheckTradePassword',  'Activity\UserPartnerController@checkTradePassword');
    /* 合伙人 转出 */
    Route::any('ActivityPartner/doWithdraw',       'Activity\UserPartnerController@doWithdraw');
    //使用佣金加息券
    Route::post('activity/partner1/doUseRate',       'Activity\Partner1Controller@doUseRate');


    /****************************合伙人3******************************/
    // 中心页
    Route::get('activity/partner1',     'Activity\Partner1Controller@partner1');
    // 佣金列表
    Route::get('activity/partner2',     'Activity\Partner1Controller@partner2');
    // 邀请好友列表
    Route::get('activity/partner3',     'Activity\Partner1Controller@partner3');
    // 邀请好友规则
    Route::get('activity/rule',         'Activity\PartnerController@rule');

    /* 闪电付息 */
    Route::get('project/sdf', 'Project\ProjectSdfController@index');
    /* 闪电付息 项目详情页*/
    Route::any('project/sdf/detail', 'Project\ProjectSdfController@detail');
    /* 闪电付息 确认投资页 */
    Route::any('Invest/Project/preDoInvest', 'Project\ProjectSdfController@investConfirm');
    /*******************************************零钱计划投资相关 ****************************************************/

    Route::get('project/current/detail','Project\ProjectDetailController@getCurrent');

    Route::get('project/descriptions', 'Project\ProjectController@descriptions');

    Route::get('invest/current/confirm','Invest\CurrentController@confirm');

    Route::post('invest/current/doInvest','Invest\CurrentController@doInvest');

    Route::get('invest/current/investOut','Invest\CurrentController@investOut');

    Route::get('invest/current/investSuccess','Invest\CurrentController@investSuccess');

    Route::post('invest/current/doInvestOut','Invest\CurrentController@doInvestOut');

    Route::get('invest/current/investOutSuccess','Invest\CurrentController@investOutSuccess');

    /*******************************************订单银行卡相关************************************************/
    /*wap端提现页面*/
    Route::get('withdraw', 'Order\WithdrawController@index');
    Route::any('withdraw/preview', 'Order\WithdrawController@withdrawPreview');
    Route::post('withdraw/submit', 'Order\WithdrawController@submit');
    Route::get('withdraw/success/{orderId}', 'Order\WithdrawController@success');

    //零钱计划页面
    Route::get('current','User\CurrentController@index');

    //查看零钱计划债权列表
    Route::get('current/viewCredit','User\CurrentController@viewCredit');

    /*实名认证相关*/
    Route::get('user/verify','User\VerifyController@index');

    Route::post('user/doVerify','User\VerifyController@doVerify');

    Route::get('user/verifySuccess','User\VerifyController@verifySuccess');

    /*推广落地页*/
    //Route::any('Novice/extension','User\RegisterController@mediaRegister');




    /*****************************充值相关******************************/

    Route::get('recharge/index','Pay\RechargeController@index');
    Route::get('recharge/appConfirm/{payChannel}/{userId}/{bankId}/{cash}/{cardNo}/{orderNo}/{version}/{client}','Pay\RechargeController@appConfirm');
    Route::post('recharge/submit','Pay\RechargeController@submit');
    Route::post('recharge/qdbSubmit','Pay\RechargeController@qdbSubmit');
    Route::post('recharge/reaSubmit','Pay\RechargeController@reaSubmit');
    Route::post('recharge/umpSubmit','Pay\RechargeController@umpSubmit');
    Route::post('recharge/bestSubmit','Pay\RechargeController@bestSubmit');

    Route::get('return/{platform}/{from}','Pay\ReturnController@index');
    Route::get('notice/{platform}','Pay\ReturnController@notice');
    Route::get('success/{from}','Pay\ReturnController@success');
    Route::get('fail/{from}','Pay\ReturnController@fail');

    /****************************文章相关******************************/

    /*了解九斗鱼*/
    Route::get('article/intro', 'Article\TopicController@introduce');
    /*资产安全*/
    Route::get('article/safe', 'Article\TopicController@safe');
    /*一分钟了解九斗鱼--中国耀盛*/
    Route::get('article/sunFund', 'Article\TopicController@sunFund');
    // 提现说明
    Route::get('article/withdrawIntro', 'Article\TopicController@withdrawIntro');
    // 充值说明
    Route::get('article/rechargeIntro', 'Article\TopicController@rechargeIntro');
    /*平台数据*/
    Route::get('article/statistics', 'Article\TopicController@dataStatistics');
    /*app下载引导页*/
    Route::get('zt/appguide', 'Activity\ZtController@appGuide');
    Route::get('zt/appguide.html', 'Activity\ZtController@appGuide');
    /*资讯列表*/
    Route::get('Article/getArticleList', 'Article\TopicController@getArticleList');
    /*App4.0资讯列表*/
    Route::get('Article/getAppV4ArticleList', 'Article\TopicController@getAppV4ArticleList');
    /*资讯文章详情*/
    Route::get('Article/index/{id}', 'Article\TopicController@index');
    /*常见问题介绍*/
    Route::get('Article/question/{id}', 'Article\TopicController@questionArticle');
    /*集团介绍*/
    Route::get('article/sunholding', 'Article\TopicController@sunHolding');
    /*江西银行资金存管*/
    Route::get('activity/custody', 'Activity\CustodyController@index');

    /*江西银行资金存管第二版*/
    Route::get('activity/secondCustody', 'Activity\CustodyController@second');

    /*关于我们app4.0*/
    Route::get('article/about', 'Article\TopicController@about');

    /*安全保障app4.0*/
    Route::get('article/security', 'Article\TopicController@security');

    /*新手指引*/
    Route::get('article/newbieguide', 'Article\TopicController@newbieguide');
    /*微信推送教程*/
    Route::get('article/pushandroid', 'Article\TopicController@pushandroid');
    Route::get('article/pushios', 'Article\TopicController@pushios');
    /*热门问题*/
    Route::get('article/hotQuestion', 'Article\TopicController@question');

    /*风险评估问卷调查 app4.0*/
    Route::get('article/questionnaire', 'Article\TopicController@questionnaire');
    /*风险评估问卷提交*/
    Route::post('article/doQuestionnaire', 'Article\TopicController@doQuestionNaire');
    /***************************************************Wap端活动相关*****************************************************/

    /* 记录活动标示*/
    Route::post('activity/setActToken', 'Activity\ActivityController@setActToken');
    /* 秒杀活动 */
    Route::get('activity/spike', 'Activity\SpikeController@activity');
    Route::get('activity/interest', 'Activity\SpikeController@interest');
    /* 十一国庆节活动*/
    Route::get('activity/national', 'Activity\NationalDayController@index');


    Route::get('activity/national/doLottery', 'Activity\NationalDayController@doLuckDraw');
    //十一活动ajax签到
    Route::post('activity/national/signAjax', 'Activity\NationalDayController@nationSignAjax');

    Route::get('activity/investpk/firstphase', 'Activity\InvestGameController@firstPhase');
    //投资PK第二期
    Route::get('activity/investment/secondPhase', 'Activity\InvestGameController@secondPhase');

    //投资PK第三期
    Route::get('activity/investment/thirdPhase', 'Activity\InvestGameController@thirdPhase');

    //投资PK第四期
    Route::get('activity/investment/forthPhase', 'Activity\InvestGameController@forthPhase');

    /* 万圣节活动 */
    Route::get('activity/halloween', 'Activity\HalloweenController@index');
    Route::post('activity/halloween/doLottery', 'Activity\HalloweenController@doLuckDraw');
    // 双蛋活动
    Route::get('activity/festival', 'Activity\DoubleFestivalController@festival');
    Route::get('activity/festivalTwo', 'Activity\DoubleFestivalController@festivalTwo');
    Route::get('activity/festival/doLottery', 'Activity\DoubleFestivalController@doLuckDraw');
    // 互联网金融专题
    Route::get('activity/president', 'Activity\PresidentController@president');

    // 春节活动专题
    Route::get('activity/springFestival', 'Activity\SpringFestivalController@index');
    //春节签到
    Route::post('activity/spring/signIn', 'Activity\SpringFestivalController@doSignIn');
    //春节抽奖
    Route::post('activity/spring/lottery', 'Activity\SpringFestivalController@doLotterySpring');

    Route::post('activity/spring/exchange', 'Activity\SpringFestivalController@doExchange');

    // 五一活动专题
    Route::get('activity/LabourDay', 'Activity\LabourDayController@index');
    //五一签到
    Route::post('activity/LabourDay/signIn', 'Activity\LabourDayController@doSignIn');
    //五一抽奖
    Route::post('activity/LabourDay/lottery', 'Activity\LabourDayController@doLottery');
    //五一兑换红包
    Route::post('activity/LabourDay/exchange', 'Activity\LabourDayController@doExchange');

    // 夏不为利 畅享七月
    Route::get('activity/July', 'Activity\JulyController@index');
    //立秋
    Route::get('activity/Autumn', 'Activity\AutumnController@index');
    Route::get('activity/autumn/project', 'Activity\AutumnController@getProject');
    Route::post('activity/autumn/luckDraw', 'Activity\AutumnController@doLuckDraw');

    // 互联网金融专题
    Route::get('activity/geeks', 'Activity\GeeksController@geeks');
    // 315专题
    Route::get('activity/zt315', 'Activity\Zt315Controller@index');
    // 金融大会专题
    Route::get('activity/finance', 'Activity\FinanceController@index');

    //元宵节活动
    Route::get('activity/lantern', 'Activity\LanternController@lantern');
    Route::post('activity/lantern/doGuessRiddles', 'Activity\LanternController@doGuessRiddles');

    Route::get('activity/bonusDay', 'Activity\ReceiveBonusController@index');
    Route::post('activity/receiveBonus', 'Activity\ReceiveBonusController@doReceiveBonus');
    Route::get('activity/geeks/receiveBonus', 'Activity\GeeksController@doReceiveBonus');
    //投资加币活动
    Route::get('activity/canadian', 'Activity\CanadianController@index');
    //春游活动
    Route::get('activity/invitation', 'Activity\InvitationController@index');
    //春风十里，现金券活动
    Route::get('activity/coupon', 'Activity\CouponController@coupon');
    Route::post('activity/receive', 'Activity\CouponController@doReceiveBonus');

    /****************************月刊相关******************************/

    Route::get('zt/newspaper1608', 'Activity\ZtController@newspaper1608');
    Route::get('zt/newspaper1608.html', 'Activity\ZtController@newspaper1608');

    Route::get('zt/newspaper1609', 'Activity\ZtController@newspaper1609');
    Route::get('zt/newspaper1609.html', 'Activity\ZtController@newspaper1609');

    Route::get('zt/newspaper1610', 'Activity\ZtController@newspaper1610');
    Route::get('zt/newspaper1610.html', 'Activity\ZtController@newspaper1610');

    Route::get('zt/newspaper1611', 'Activity\ZtController@newspaper1611');
    Route::get('zt/newspaper1611.html', 'Activity\ZtController@newspaper1611');

    Route::get('zt/newspaper1612', 'Activity\ZtController@newspaper1612');
    Route::get('zt/newspaper1612.html', 'Activity\ZtController@newspaper1612');

    Route::get('zt/newspaper1701', 'Activity\ZtController@newspaper1701');
    Route::get('zt/newspaper1701.html', 'Activity\ZtController@newspaper1701');

    Route::get('zt/newspaper1702', 'Activity\ZtController@newspaper1702');
    Route::get('zt/newspaper1702.html', 'Activity\ZtController@newspaper1702');

    Route::get('zt/newspaper1703', 'Activity\ZtController@newspaper1703');
    Route::get('zt/newspaper1703.html', 'Activity\ZtController@newspaper1703');

    Route::get('zt/newspaper1706', 'Activity\ZtController@newspaper1706');
    Route::get('zt/newspaper1706.html', 'Activity\ZtController@newspaper1706');

    Route::get('zt/newspaper1707', 'Activity\ZtController@newspaper1707');
    Route::get('zt/newspaper1707.html', 'Activity\ZtController@newspaper1707');
    
    Route::get('app/team', 'Activity\ZtController@team');
    Route::get('app/faq', 'Activity\ZtController@faq');

    /****************************零钱加息******************************/
    Route::get('zt/currentAdd', 'Activity\ZtController@currentAdd');
    Route::get('zt/currentAdd.html', 'Activity\ZtController@currentAdd');

    /****************************推广注册相关******************************/
    Route::get('Novice/extension', 'Activity\PromotionController@index');
    Route::get('Novice/success', 'Activity\PromotionController@success');
    Route::get('Novice/articles', 'Activity\PromotionController@noviceProjectList');
    Route::get('Novice/introduce', 'Activity\PromotionController@introduce');
    Route::get('Novice/success1', 'Activity\PromotionController@success1');
    //Route::get('Novice/another', 'Activity\PromotionController@another');

    /****************************ROI渠道推广注册相关***********************/
    Route::get('Roi/extension', 'Activity\PromotionController@roiIndex');

    Route::get('appButton/maimaicn', function (){
        return redirect('http://m.maimaicn.com/buyer/lingyuangouzq.html?activeId=11&mId=496139');
    });
        /***************三周年的庆典活动****************/

    //第一趴 first part
    Route::get('thirdAnniversary/firstPart', 'Activity\ThirdAnniversaryController@firstPart');
    //抽奖活动
    Route::post('thirdAnniversary/luckDraw', 'Activity\ThirdAnniversaryController@doLuckDraw');
    //用户抽奖信息
    Route::get('thirdAnniversary/userLevel', 'Activity\ThirdAnniversaryController@getLotteryConfig');
    //周年庆第二趴
    Route::get('thirdAnniversary/secondPart', 'Activity\ThirdAnniversaryController@secondPart');

    //周年庆第三趴
    Route::get('thirdAnniversary/thirdPart', 'Activity\ThirdAnniversaryController@thirdPart');
    //周年庆第三趴展示的奖品的中奖记录
    Route::get('thirdAnniversary/triplePrize', 'Activity\ThirdAnniversaryController@getThirdLottery');
    //用户在红包雨中的状态
    Route::get('thirdAnniversary/bonusStatus', 'Activity\ThirdAnniversaryController@getBonusRainStatus');
    //周年庆第三趴红包雨的抽奖
    Route::post('thirdAnniversary/doLottery', 'Activity\ThirdAnniversaryController@doLottery');
    /**********三周年公共部分数据*************/
    //三周年伴手礼记录和奖品配置
    Route::get('thirdAnniversary/souvenir', 'Activity\ThirdAnniversaryController@getLottery');
    //排名数据
    Route::get('thirdAnniversary/ranking', 'Activity\ThirdAnniversaryController@getSecondRanking');
    //项目数据
    Route::get('thirdAnniversary/showProject', 'Activity\ThirdAnniversaryController@getProject');
    //获取投资总额
    Route::get('thirdAnniversary/summation', 'Activity\ThirdAnniversaryController@getInvestPercentage');


    Route::get('appButton/timeCash/yiqixiu', function (){
        return redirect('http://m.timecash.cn/Promotion/Survey');
    });


    /*合同预览*/
    Route::get('contract_show', 'User\ContractController@contractShow');

    /*协议预览*/
    Route::get('agreement', 'User\ContractController@agreementShow');

    Route::get('test', 'Activity\TestController@test');


    Route::get('current/new', 'CurrentNew\ProjectController@index');

    Route::get('current/creditList', 'CurrentNew\ProjectController@creditList');


    Route::post('current/new/invest', 'CurrentNew\ProjectController@invest');

    Route::get('registerAgreement', 'Article\TopicController@registerAgreement');



});
