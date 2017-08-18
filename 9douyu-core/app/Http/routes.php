<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * 获取oAuth2 token
 */
$app->post('oauth/access_token', function() {
    return response()->json(app('oauth2-server.authorizer')->issueAccessToken());
});


//swagger
//$app->get('swagger/config', 'Swagger\ApiController@config');
//$app->get('swagger/index', 'Swagger\ApiController@index');


$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['namespace' => 'App\\Http\\Controllers\\', 'middleware' => 'api.auth'], function ($api) use ($app) {
    //dingo stats
    $api->post('/access_token_ok', function(){
        return [
            'status' => true
        ];
    });
    $api->get('/access_token_ok', function(){
        return [
            'status' => true
        ];
    });

    /**
     * 注册模块
     */
    // 注册
    $api->post('/user/register/create',     'User\RegisterController@create');
    // 调用激活用户
    $api->post('/user/register/doActivate', 'User\RegisterController@doActivate');
    // 数据查询 开放字段['id', 'phone', 'password', 'status']
    $api->post('/user/get/baseUserInfo',  'User\GetController@baseUserInfoByPhone');
    $api->post('/user/get/userInfo',  'User\GetController@userInfoById');
    $api->post('/user/get/userList',  'User\GetController@getListByUserIds');
    $api->post('/user/modify/phone', 'User\ModifyController@modifyPhone');
    $api->post('/user/incBalance', 'User\ModifyController@doIncreaseBalanceToCurrentAccount');
    $api->post('/user/delBalance', 'User\ModifyController@doDecreaseBalance');
    $api->post('/user/statusBlock', 'User\ModifyController@modifyStatusBlock');//锁定账户
    $api->post('/user/get/adminUserListAll', 'User\GetController@getAdminUserListAll');//获取用户列表[后台用户管理]
    $api->post('/user/get/getNoInvestUserList', 'User\GetController@getNoInvestUserList');//获取长时间未投资用户列表[后台用户管理]
    $api->post('/user/getPartnerPrincipal','User\GetController@getPartnerPrincipal');//获取被邀请人待收明细
    $api->post('/user/getInvestListByUserId','User\GetController@getInvestListByUserId'); //获取用户投资记录
    $api->post('/user/getUserInvestDataByUserId','User\GetController@getUserInvestDataByUserId'); //获取用户投资记录（包括普付宝项目）

    $api->post('/user/get/userAmountDate', 'User\GetController@getUserAmountByDate');//根据日期获取用户注册数
    $api->post('/user/get/getUserTotal', 'User\GetController@getUserTotal');  //获取总注册用户数
    $api->post('/user/get/getUserStatistics', 'User\GetController@getUserStatistics');  //获取用户统计数据
    $api->post('/user/get/getBirthdayUser', 'User\GetController@getBirthdayUser');  //获取当日生日的用户
    $api->post('/user/get/getUserByIdCards', 'User\GetController@getUserByIdCards');  //通过身份证号获取用户数据
    $api->post('/user/get/getUserByPhones', 'User\GetController@getUserByPhones');  //通过多个手机号获取用户数据

    /*获取用户总收益*/
    $api->post('user/get/userInterest', 'User\GetController@userInterestById');
    /*获取账户资金总统计信息*-@llper-*/
    $api->post('user/get/getFundStatisticsWithDay', 'User\GetController@getFundStatisticsWithDay');
    //TODO: 借款投资相关统计
    $api->post('user/getCoreApiInvestStat',         'User\GetController@getCoreApiInvestStat');

    /*用户定期资产*/
    $api->post('user/term/nofinish', 'User\TermController@getNoFinish');
    $api->post('user/term/refunding', 'User\TermController@getRefunding');
    $api->post('user/term/refunded', 'User\TermController@getRefunded');
    $api->post('user/term/investing', 'User\TermController@getInvesting');
    $api->post('user/term/pfbInvestList', 'User\TermController@getPfbInvestList');
    $api->post('user/term/getPfbInvestTotal', 'User\TermController@getPfbInvestTotal');
    /*用户项目的回款计划*/
    $api->post('user/term/refundplan', 'User\TermController@getRefundPlan');
    //TODO:APP4.0-我的资产-定期资产 -@llper
    /*定期资产-持有中*/
    $api->post('user/term/getAppV4UserTermNoFinish',    'User\TermController@getAppV4UserTermNoFinish');
    /*定期资产-转让中*/
    $api->post('user/term/getAppV4UserTermAssignment',  'User\TermController@getAppV4UserTermAssignment');
    /*定期资产-已完结*/
    $api->post('user/term/getAppV4UserTermFinish',      'User\TermController@getAppV4UserTermFinish');
    /*定期资产-项目详情*/
    $api->post('user/term/getAppV4UserTermDetail',      'User\TermController@getAppV4UserTermDetail');

    //实名
    $app->post('user/realName', 'User\VerifyController@realName');
    //绑卡+实名
    $app->post('user/verify', 'User\VerifyController@verify');
    //实名+绑卡+交易密码
    $app->post('user/verifyTradingPassword', 'User\VerifyController@verifyTradingPassword');
    $app->post('user/modify/password', 'Module\User\IndexController@doModifyPassword');
    $app->post('user/modify/tradingPassword', 'Module\User\IndexController@doModifyTradingPassword');

    //用户实网账户冻结
    $app->post('user/frozenAccount', 'Module\User\IndexController@doFrozenAccount');
    //用户实网账户冻结
    $app->post('user/unFrozenAccount', 'Module\User\IndexController@doUnFrozenAccount');


    //获取用户绑定卡信息
    $api->post('recharge/card/get', 'BankCard\RechargeController@getAuthCardByUserId');

    //换卡接口
    $api->post('recharge/card/change', 'BankCard\RechargeController@changeCard');


    //获取用户提现银行卡列表
    $api->post('withdraw/card/get', 'BankCard\WithdrawController@getWithdrawCardByUserId');

    //添加提现银行卡
    $api->post('withdraw/card/create', 'BankCard\WithdrawController@bindCard');

    //删除提现银行卡
    $api->post('withdraw/card/delete', 'BankCard\WithdrawController@deleteCard');

    //根据ID获取提现银行卡信息
    $api->post('withdraw/card/getById','BankCard\WithdrawController@getWithdrawCardById');

    //创建充值订单
    $api->post('recharge/order/create', 'Order\RechargeController@makeOrder');

    //支付成功回调处理
    $api->post('recharge/order/success', 'Order\RechargeController@succOrder');

    //掉单处理
    $api->post('/recharge/order/missSuccess','Order\RechargeController@missOrderHandle');

    //支付失败回调处理
    $api->post('recharge/order/failed', 'Order\RechargeController@failedOrder');

    //订单超时
    $api->post('recharge/order/timeout', 'Order\RechargeController@timeoutOrder');

    //获取用户某个支付通道当天无效订单数
    $api->post('recharge/order/getInvalidOrderNum', 'Order\RechargeController@getUserTodayInvalidOrderNumByPayChannel');

    /*获取用户除网银支付外的成功充值订单数量*/
    $api->post('recharge/order/getSuccOrderNum','Order\RechargeController@getUserSuccOrderNum');

    /*获取用户非网银支付成功的充值渠道列表*/
    $api->post('recharge/order/getSuccPayChannel','Order\RechargeController@getLastedSuccPayChannel');

    //根据订单号获取订单信息
    $api->post('order/get', 'Order\OrderController@getOrder');

    //根据订单号获取订单信息
    $api->post('order/getList', 'Order\OrderController@getOrderLists');

    //$api->post('recharge/order/getAdminList','Order\RechargeController@getAdminList');
    //充值数据统计
    $api->post('recharge/order/getRechargeStatistics','Order\RechargeController@getRechargeStatistics');
    //充值总额统计
    $api->post('recharge/order/getRechargeOrderTotal','Order\RechargeController@getRechargeOrderTotal');
    //获取某段时间内充值失败订单
    $api->post('recharge/order/getFailRechargeOrderByTime','Order\RechargeController@getFailRechargeOrderByTime');

    $api->post('order/getAdminList','Order\OrderController@getAdminList');



    //创建提现订单
    $api->post('withdraw/order/create', 'Order\WithdrawController@makeOrder');

    //T+0提现列表信息
    $api->post('withdraw/order/getWithdrawList','Order\OperateController@getWithdrawList');

    //提现申请提交银行修改状态
    $api->post('withdraw/order/submitToBank', 'Order\WithdrawController@submitToBank');

    //提现列表
    $api->post('withdraw/order/getWithdrawOrders', 'Order\WithdrawController@getWithdrawOrders');

    //批量发送提现短信
    $api->post('withdraw/order/batchSubmitToBank', 'Order\OperateController@batchWithdrawSubmitToBank');

    $api->post('withdraw/order/sendWithdrawEmail','Order\OperateController@sendWithdrawEmail');
    //TODO：提现-发邮件(new)
    $api->post('withdraw/order/sendWithdrawEmailNew','Order\OperateController@sendWithdrawEmailNew');

    //提现订单批量对账
    $api->post('withdraw/order/batchCheckAccount', 'Order\OperateController@batchCheckAccount');

    //获取本月有效提现次数
    $api->post('withdraw/getNum', 'Order\WithdrawController@getWithdrawNum');


    //提现成功
    $api->post('withdraw/order/success', 'Order\WithdrawController@succOrder');

    //取消提现
    $api->post('withdraw/order/cancel', 'Order\WithdrawController@cancelOrder');

    //提现失败/取消提现
    $api->post('withdraw/order/failed', 'Order\WithdrawController@failedOrder');

    //提现数据统计
    $api->post('withdraw/order/getWithdrawStatistics','Order\WithdrawController@getWithdrawStatistics');
    //获取时间段内提现大于5万用户的信息
    $api->post('withdraw/order/getWithdrawUserCashFive','Order\WithdrawController@getWithdrawUserCashFive');
    /*
     * 接口已废弃
    //绑定卡限额
    $api->post('recharge/limit/user', 'Recharge\LimitController@getUserBindCardLimit');

    接口已废弃
    //未绑定限额列表
    $api->post('recharge/limit/list', 'Recharge\LimitController@getRechargeCardLimit');

    接口已废弃
    //支付获取所有可用的支付通道
    $api->post('recharge/route', 'Recharge\RouteController@userRechargeChannel');
    */

    $api->post('project/refundPlan', 'Project\GetInfo\ProjectController@refundPlan');                     //项目回款计划


    $api->post('project/list', 'Project\GetInfo\ProjectController@getListByIds');                     //定期项目列表
    $api->post('project/home', 'Project\GetInfo\ProjectController@getHomeList');                     //首页项目列表
    $api->post('project/getProjectPackAppV413', 'Project\GetInfo\ProjectController@getProjectPackAppV413'); //APP4.1.3-首页项目列表

    //零钱计划
    $api->post('current/invest', 'Project\Invest\CurrentController@invest');                        //零钱计划转入
    $api->post('current/investOut', 'Project\Invest\CurrentController@investOut');                  //零钱计划转出

    //定期
    $api->post('project/invest', 'Project\Invest\ProjectController@invest');                        //投资定期
    $api->post('project/investByCurrent', 'Project\Invest\ProjectController@investByCurrent');      //零钱计划投资定期
    $api->post('project/invest/createRateRecord', 'Module\Invest\ProjectBonusRateController@createRateRecord');   //加息券生成的回款记录
    $api->post('project/invest/getPlanInterest', 'Module\Invest\ProjectBonusRateController@getPlanInterest');   //加息券生成的预期收益
    $api->post('project/detail', 'Project\GetInfo\ProjectController@detail');                       //定期项目信息
    $api->post('project/projectList', 'Project\GetInfo\ProjectController@getProjectList');                 //理财列表定期项目列表
    $api->post('project/jsxlist', 'Project\GetInfo\ProjectController@JSXList');                     //定期项目分类列表
    $api->post('project/jaxlist', 'Project\GetInfo\ProjectController@JAXList');                     //定期项目分类列表
    $api->post('project/pfbList', 'Project\GetInfo\ProjectController@pfbList');                     //普付宝项目列表
    $api->post('project/pfbDetail', 'Project\GetInfo\ProjectController@pfbDetail');                 //普付宝项目详情（1个）
    $api->post('project/sdflists', 'Project\GetInfo\ProjectController@SDFList');                    //定期项目分类列表
    $api->post('project/listByStatus', 'Project\GetInfo\ProjectController@listByStatus');           //定期项目分类列表

    $api->post('project/admin/jsxlist', 'Project\GetInfo\ProjectController@JSXAdminList');                     //定期项目分类列表
    $api->post('project/admin/jaxlist', 'Project\GetInfo\ProjectController@JAXAdminList');                     //定期项目分类列表
    $api->post('project/admin/sdflists', 'Project\GetInfo\ProjectController@SDFAdminList');                    //定期项目分类列表

    $api->post('project/getFinishedList', 'Project\GetInfo\ProjectController@getFinishedList');        //定期已完结项目列表
    $api->post('project/refunding', 'Project\GetInfo\ProjectController@getFinishedList');        //定期已完结项目列表
    $api->post('project/sdflist', 'Project\GetInfo\ProjectController@getSdfProject');                     //定期闪电付息项目列表
    $api->post('project/finished', 'Project\GetInfo\ProjectController@getFinishedIds');                     //定期项目分类列表
    $api->post('project/timing', 'Project\GetInfo\ProjectController@getTimingProject');  // 秒杀定时项目
    $api->post('project/appointed', 'Project\GetInfo\ProjectController@getAppointJsxProject');  // 获取九省心指定的项目
    $api->post('project/getIds', 'Project\GetInfo\ProjectController@getProjectIdsStatistics');  // 项目id计划
    $api->post('project/getNewestProjectEveryType', 'Project\GetInfo\ProjectController@getNewestProjectEveryType');  // 获取产品线最新的项目

    $api->post('project/getProjectWithTime', 'Project\GetInfo\ProjectController@getProjectWithTime');  // 获取非普付宝的项目(可见的项目)
    $api->post('project/getInvestIngProject', 'Project\GetInfo\ProjectController@getInvestIngProject');  // 获取非普付宝的可见的再投项目
    $api->post('project/getProjectByFullTime', 'Project\GetInfo\ProjectController@getProjectByFullTime');  //通过项目满标时间获取项目数据

    #@llper 时间段内已完结项目
    $api->post('project/getFinishedProjectByTime', 'Project\GetInfo\ProjectController@getFinishedProjectByTime');
    $api->post('project/getCreditProjectById', 'Project\GetInfo\ProjectController@getCreditProjectById');
    $api->post('project/getAllProjectIdByTime', 'Project\GetInfo\ProjectController@getAllProjectIdByTime'); //通过时间,按照ProductLine获取项目id

    //批量接收零钱计划加息券计息用户数据
    $api->post('current/bonusInterestAccrual', 'Module\Current\BonusController@interestAccrual');

    //获取零钱计划用户债权金额
    $api->post('current/getCreditAmount', 'Module\Current\AccountController@getCreditAmount');

    //获取零钱计划帐户信息
    $api->post('current/userFund', 'Module\Current\AccountController@userFund');

    //零钱计划用户近一周收益
    $api->post('current/userInterestList','Module\Current\AccountController@getInterestList');

    //投资零钱计划总人数
    $api->post('current/userNum', 'Module\Current\AccountController@userNum');

    //获取用户今日零钱计划转出总额
    $api->post('current/userTodayInvestOutAmount', 'Module\Current\AccountController@getTodayInvestOutAmount');

    //获取零钱计划历史转入总额
    $api->post('current/investAmount', 'Module\Current\AccountController@getInvestAmount');

    //零钱计划账户信息
    $api->post('current/getUserInfo', 'Module\Current\AccountController@getUserInfo');

    //根据时间获取用户自动投资活期的列表信息
    $api->post('current/getAutoInvestCurrentListByDate', 'FundHistory\GetController@getAutoInvestCurrentListByDate');


    //获取活期计息记录列表【管理后台】
    $api->post('current/getAdminInterestHistory', 'Module\Current\AccountController@getAdminInterestListAll');
    //获取零钱计划的资金留存
    $api->post('current/getCurrentAccountAmount', 'Module\Current\AccountController@getCurrentAccountAmount');

    //事件回调
    $api->post('event/list', 'Event\EventController@getEventList');
    $api->post('event/register', 'Event\EventController@register');
    $api->post('apiMonitor/testAll', 'ApiMonitor\MonitorController@testAllApi');

    //本地测试获取sign
    $api->post('getSign', 'Controller@getSign');

    //回款相关
    $api->post('refund/refunded', 'Module\Refund\RefundRecordController@getRefundedList');   //已回款的记录
    $api->post('refund/refunding', 'Module\Refund\RefundRecordController@getRefundingList');  //回款中的记录
    $api->post('refund/getRefundByDay', 'Module\Refund\RefundRecordController@getRefundByDay');  //获取用户某一天的回款记录
    $api->post('refund/getRefundDetailById', 'Module\Refund\RefundRecordController@getRefundDetailById');  //获取用户回款记录的详情
    $api->post('refund/getTotalInterest', 'Module\Refund\RefundRecordController@getTotalInterest');  //定期投资已回款总收益
    $api->post('refund/getRefundAmount', 'Module\Refund\RefundRecordController@getRefundAmount');  //获取定期投资已回款总本息
    $api->post('refund/getRefundPlanByMonthByUserId', 'Module\Refund\RefundRecordController@getRefundPlanByMonthByUserId'); //通过用户id获取每月的待回款金额(只取当前月之后的12个月的记录)
    $api->post('refund/getRefundTotalByUserIds', 'Module\Refund\RefundRecordController@getRefundTotalByUserIds');   //通过多个用户id获取待回款本金之和
    $api->post('refund/refundPlanByProjectId', 'Module\Refund\RefundRecordController@getRefundPlanByProjectId');   //根据项目ID和投资金额获取预回款计划
    $api->post('refund/getRefundingTotal', 'Module\Refund\RefundRecordController@getRefundingTotal');  //获取待收本息总额
    $api->post('refund/getRefundProjectIdByTimes', 'Module\Refund\RefundRecordController@getRefundProjectIdByTimes');   //根据项目ID和投资金额获取预回款计划
    $api->post('refund/getRefundByUserIds', 'Module\Refund\RefundRecordController@getRefundByUserIds');   //通过多个用户id获取待回款本金之和
    $api->post('refund/getTodayRefundUser', 'Module\Refund\RefundRecordController@getTodayRefundUser');   //获取今日回款的用户
    $api->post('refund/getRefundUserByDate', 'Module\Refund\RefundRecordController@getRefundUserByDate');   //获取今日回款的用户
    $api->post('refund/getInterestTypeByProjectIds', 'Module\Refund\RefundRecordController@getInterestTypeByProjectIds');   //根据项目id 获取收益的类型
    $api->post('refund/getRefundTotalGroupByTime', 'Module\Refund\RefundRecordController@getRefundTotalGroupByTime');   //根据时间段获取每天回款总额

    //项目
    $api->post('project/create', 'Project\Create\ProjectController@create');                    // 创建定期项目
    $api->post('project/delete', 'Project\Create\ProjectController@delete');                    // 删除定期项目
    $api->post('project/update', 'Project\Create\ProjectController@update');                    // 更新定期项目
    $api->post('project/doPass', 'Project\Create\ProjectController@updateStatusUnPublish');     // 更新项目状态-审核通过
    $api->post('project/doNoPass', 'Project\Create\ProjectController@updateStatusAuditeFail');  // 更新项目状态-审核不通过
    $api->post('project/doPublish', 'Project\Create\ProjectController@updateStatusInvesting');  // 更新项目状态-发布

    $api->post('/project/creditAssign/create','Project\CreditAssignController@create');         //创建债转项目

    $api->post('/project/creditAssign/cancel','Project\CreditAssignController@cancel');         //取消债转项目

    $api->post('/project/creditAssign/invest','Project\CreditAssignController@invest');         //投资
    $api->post('/project/creditAssign/investByCurrent','Project\CreditAssignController@investByCurrent');//使用零钱投资债转

    $api->post('/project/creditAssign/getInvestId','Project\CreditAssignController@getInvestId'); //匹配投资ID


    //资金流水列表
    $api->post('fundHistory/getList', 'FundHistory\GetController@getList');
    //根据事件类型分组进行数据统计
    $api->post('fundHistory/getChangeCashGroupByEventId', 'FundHistory\GetController@getChangeCashGroupByEventId');


    $api->post('fundHistory/getYesterdayCurrentFundData','FundHistory\GetController@getYesterdayCurrentFundData');

    //零钱计划资金流水
    $api->post('fundHistory/getCurrentList','FundHistory\GetController@getCurrentList');

    //零钱计划项目分拆计息
    $api->post('current/refund/doRefundJob', 'Module\Refund\CurrentController@doRefundJob');

    //零钱计划用户总收益
    $api->post('current/refund/getTotalInterest', 'Module\Refund\CurrentController@getTotalInterest');

    //获取零钱计划昨日总利息
    $api->post('current/getYesterdayInterest','Module\Refund\CurrentController@getYesterdayInterest');
    //获取零钱计划用户今日转出总金额
    $api->post('current/getTodayCurrentInvestOutAmount','FundHistory\GetController@getTodayCurrentInvestOutAmount');
    //获取零钱计划用户今日转入总金额
    $api->post('current/getTodayCurrentInvestAmount','FundHistory\GetController@getTodayCurrentInvestAmount');
    //获取零钱计划用户今日自动转入转入总金额
    $api->post('current/getTodayAutoInvestCurrentTotalByUserId','FundHistory\GetController@getTodayAutoInvestCurrentTotalByUserId');
    //根据时间获取平台自动转入零钱计划的金额总数
    $api->post('current/getPlatformTodayAutoInvestCurrentTotal','FundHistory\GetController@getPlatformTodayAutoInvestCurrentTotal');


    $api->post('getHomeStatistics', 'Module\Invest\StatisticsController@index');//首页平台数据明细

    //系统配置
    $api->post('systemConfig/list', 'Module\SystemConfig\SystemConfigController@index');   //配置列表
    $api->post('systemConfig/add', 'Module\SystemConfig\SystemConfigController@addInfo');    //添加配置
    $api->post('systemConfig/edit', 'Module\SystemConfig\SystemConfigController@editInfo');   //修改配置
    $api->post('systemConfig/get', 'Module\SystemConfig\SystemConfigController@getInfoById');    //获取单个配置id
    $api->post('systemConfig/editByKey','Module\SystemConfig\SystemConfigController@editByKey');//通过key来编辑配置信息
    //投资记录
    $api->post('invest/getNewInvest','Module\Invest\StatisticsController@getInvestNew');  //最新投资记录
    $api->post('invest/getInvestAmountByDate', 'Module\Invest\StatisticsController@getInvestAmountByDate');  //根据日期获取投资总额列表
    $api->post('invest/getTermInvestTotal', 'Module\Invest\StatisticsController@getInvestTermTotal');   //根据日期获取投资总额
    $api->post('invest/getListByIds', 'Project\Invest\ProjectController@getListByIds');   //通过多个ID获取投资记录
    $api->post('invest/getNormalInvestByProjectIds', 'Project\Invest\ProjectController@getNormalInvestListByProjectIds');   //通过项目id获取正常的投资记录
    $api->post('invest/getLastInvestTimeByProjectId', 'Project\Invest\ProjectController@getLastInvestTimeByProjectId');   //从核心获取最后一次投资的数据(不包含原项目债转的记录)

    //还款公告
    $api->post('article/getRefundNoticeData', 'Module\Refund\RefundRecordController@getArticleNoticeByTimes');   //还款公告的数据

    //九斗鱼平台数据
    $api->post('/getStatistics', 'Module\Statistics\StatisticsController@getStatistics');   //根据日期获取投资总额
    $api->post('/getHomeStat',  'Module\Statistics\StatisticsController@getHomeStat');      //后台首页展示数据

    //根据项目的最后更新时间获取项目列表,主要功能为,后台按时间查询项目满标的列表
    $api->post('project/getRefundingProjectListByUpdateTime', 'Project\GetInfo\ProjectController@getRefundingProjectListByUpdateTime');

    /*债权转让*/
    $api->post('/user/creditAssignList','User\CreditAssignController@index');            //用户债权转让列表
    $api->post('/project/getCreditAssignList','Project\CreditAssignController@getList'); //债转项目列表
    $api->post('/project/creditAssign/detail','Project\CreditAssignController@getById');  //债转项目详情
    $api->post('/user/creditAssignInvestIds', 'User\CreditAssignController@getCreditAssignInvestIds'); //获取用户已转让的投资Id数组
    $api->post('/project/creditAssign/getInvestingCount', 'Project\CreditAssignController@getInvestingCount'); //获取可投的债转项目总数

    $api->post('userPreCreditAssign', 'User\CreditAssignController@userPreCreditAssign'); //债权转让确认信息页
    $api->post('getCreditAssignByInvestId', 'User\CreditAssignController@getCreditAssignByInvestId'); //根据投资id获取投资信息以及债转项目详情
    //根据项目的最后更新时间获取项目列表,主要功能为,后台按时间查询项目满标的列表

    //根据项目id获取回款利息
    $api->post('refund/getSumInterestByProjectIds', 'Module\Refund\RefundRecordController@getSumInterestByProjectIds');

    //提前还款
    $api->post('project/beforeRefundRecord', 'Project\BeforeRefundRecordController@index');

    //根据时间获取还款的项目id和金额
    $api->post('refund/getRefundProjectIdsAndCashByDate', 'Module\Refund\RefundRecordController@getRefundProjectIdsAndCashByDate');

});
