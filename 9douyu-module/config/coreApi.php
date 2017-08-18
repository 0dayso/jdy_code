<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/16
 * Time: 下午4:15
 */

return [

    //事件
    'moduleEvent'       => [
        'doEventRegister'                  => '/event/register',    //事件注册
        'getEventList'                     => '/event/list',        //获取事件列表
    ],

    //订单
    'moduleOrder'       => [

        //充值订单
        'doCreateRechargeOrder'            => '/recharge/order/create', //创建充值订单
        'doSuccRechargeOrder'              => '/recharge/order/success', //充值成功
        'doFailedRechargeOrder'            => '/recharge/order/failed', //充值失败
        'doTimeoutRechargeOrder'           => '/recharge/order/timeout',//充值订单超时
        'getRechargeOrderTotal'            => '/recharge/order/getRechargeOrderTotal',//充值统计
        //提现订单
        'doCreateWithdrawOrder'            => '/withdraw/order/create', //创建提现订单
        'doSuccWithdrawOrder'              => '/withdraw/order/success', //提现成功
        'doFailedWithdrawOrder'            => '/withdraw/order/failed', //提现失败
        'doCancelWithdrawOrder'            => '/withdraw/order/cancel', //取消提现
        'doSendWithdrawNoticeSms'          => '/withdraw/order/submitToBank',//提现发送通知短信(单条)
        'doBatchSendWithdrawNoticeSms'     => '/withdraw/order/batchSubmitToBank',//提现批量发送通知短信(单条)
        'doBatchWithdrawCheckAmount'       => '/withdraw/order/batchCheckAccount',//提现批量订单状态更新(后台对账)
        'getWithdrawOrders'                => '/withdraw/order/getWithdrawOrders',//获取提现列表（后台）

        'getWithdrawRecord'                => '/withdraw/order/getWithdrawList',//获取T+0提现处理列表

        'sendWithdrawMsg'                  => '/withdraw/order/sendWithdrawMsg',//给T+0指定时间段提现发送短信

        'sendWithdrawEmail'                => '/withdraw/order/sendWithdrawEmail',//给T+0指定时间段发送邮件
        'sendWithdrawEmailNew'             => '/withdraw/order/sendWithdrawEmailNew',//提现代付-给T+0指定时间段发送邮件

        'getTodayInvalidRechargeNum'       => '/recharge/order/getInvalidOrderNum',//获取今日某个支付通道无效的提现订单
        'getOrderInfo'                     => '/order/get',            //获取订单信息
        'getUserMonthWithdrawNum'          => '/withdraw/getNum',               //获取用户本月有效的提现次数,用于计息手续费
        'getList'                          => '/order/getList',  //获取自己历史列表
        'getSuccPayChannelList'            => '/recharge/order/getSuccPayChannel',//获取用户非网银支付成功的充值渠道列表
        'getSuccOrderNum'                  => '/recharge/order/getSuccOrderNum',//获取用户除网银支付外的成功充值订单数量
        'getAdminOrderList'                => '/order/getAdminList',//后台根据条件获取充值订单列表
        'getRechargeStatistics'            => '/recharge/order/getRechargeStatistics',//获取充值订单的数据统计
        'getWithdrawStatistics'            => '/withdraw/order/getWithdrawStatistics',//获取提现订单的数据统计
        'getWithdrawUserCashFive'          => '/withdraw/order/getWithdrawUserCashFive',//获取提现大于5万的用户信息

        'missOrderHandle'                  => '/recharge/order/missSuccess',//掉单加币
        'getFailRechargeOrderByTime'       => '/recharge/order/getFailRechargeOrderByTime',//获取某段时间内充值失败订单
    ],

    //银行卡

    'moduleBankCard'    => [

        'getUserBindCard'                  => '/recharge/card/get',     //获取用户绑定银行卡信息
        'getUserWithdrawCard'              => '/withdraw/card/get',//获取用户提现银行卡信息
        'getWithdrawCardById'              => '/withdraw/card/getById',//根据主键ID获取银行信息
        'doCreateWithdrawCard'             => '/withdraw/card/create',//添加提现银行卡
        'doDeleteWithdrawCard'             => '/withdraw/card/delete',//删除用户提现银行卡
        'doChangeBindCard'                 => '/recharge/card/change',//更换绑定银行卡
    ],


    //账户中心
    'moduleUser'        => [
        'getCoreApiBaseUserInfo'           => '/user/get/baseUserInfo', //获取个核心用户模块用户基础信息
        'getCoreApiUserInfo'               => '/user/get/userInfo', //获取核心用户模块用户信息
        'doCoreApiRegister'                => '/user/register/create',  //调用核心用户注册接口
        'getCoreApiUserInfoAccount'        => '/user/get/userInterest',  //调用核心用户资产信息
        'getUserListByIds'                 => '/user/get/userList',  //通过id获取用户列表信息
        'getNoInvestUser'                 => '/user/get/getNoInvestUserList',  //根据余额和未投资天数获取用户信息
        'getUserListAll'                   => '/user/get/adminUserListAll',  //获取用户列表[管理后台]
        'doActivate'                       => '/user/register/doActivate',//用户激活
        'doVerify'                         => '/user/verify',//用户实名+绑卡
        'doVerifyTradingPassword'          => '/user/verifyTradingPassword',//用户实名+绑卡+交易密码
        'getRefundDetail'                  => '/user/term/refundplan',//获取用户投资项目的回款计划
        'getNoFinishList'                  => '/user/term/nofinish',//未完结的项目列表
        'getRefundingList'                 => '/user/term/refunding',//回款中的项目列表
        'getRefundedList'                  => '/user/term/refunded',//已回款的项目列表
        'getInvestingList'                 => '/user/term/investing',//投资中的项目列表
        'doPassword'                       => '/user/modify/password', //修改登陆密码
        'doTradingPassword'                => '/user/modify/tradingPassword', //修改登陆密码
        'doModifyPhone'                    => '/user/modify/phone',// 修改手机号
        'doIncBalance'                     => '/user/incBalance', //更新账户余额(加)
        'doDelBalance'                     => '/user/delBalance', //更新账户余额(扣)
        'doRealName'                       => '/user/realName',//用户实名
        'getUserStatistics'                => '/user/get/getUserStatistics', //获取用户数据统计信息
        'getUserAmountByDate'              => '/user/get/userAmountDate',//某个日期段的注册总数
        'getUserTotal'                     => '/user/get/getUserTotal', //获取总注册数
        'doStatusBlock'                    => '/user/statusBlock', //锁定账户状态
        'getBirthdayUser'                  => '/user/get/getBirthdayUser', //获取当日生日的用户
        'getUserByIdCards'                 => '/user/get/getUserByIdCards', //通过多个身份证号获取用户数据
        'getUserByPhones'                 => '/user/get/getUserByPhones', //通过多个手机号获取用户数据
        'doUserFrozen'                     => '/user/frozenAccount',        //账户冻结
        'doUserUnFrozen'                   => '/user/unFrozenAccount',      //账户解冻
        //@llper 账户资金统计
        'getFundStatisticsWithDay'         => '/user/get/getFundStatisticsWithDay',
        'getPartnerPrincipal'              => '/user/getPartnerPrincipal',//获取被邀请人本金明细
        'getInvestListByUserId'            => '/user/getInvestListByUserId',  //获取用户的投资记录
        'getUserInvestDataByUserId'        => '/user/getUserInvestDataByUserId',  //获取用户的投资记录（包括普付宝项目）
        'getCoreApiInvestStat'             => '/user/getCoreApiInvestStat',     //获取借款投资相关统计信息

        //TODO: APP4.0-新增
        'getAppV4UserTermNoFinish'          => '/user/term/getAppV4UserTermNoFinish',   //我的资产-定期资产-持有中
        'getAppV4UserTermAssignment'        => '/user/term/getAppV4UserTermAssignment', //我的资产-定期资产-转让中
        'getAppV4UserTermFinish'            => '/user/term/getAppV4UserTermFinish',     //我的资产-定期资产-已完结
        'getAppV4UserTermDetail'            => '/user/term/getAppV4UserTermDetail',     //我的资产-定期资产-详情
    ],

    //定期项目
    'moduleProject'     => [
        //定期
        'doCreate'                              => '/project/create',   //创建
        'doDelete'                              => '/project/delete',   //删除
        'doUpdate'                              => '/project/update',   //更新
        'doPass'                                => '/project/doPass',   //审核通过
        'doNoPass'                              => '/project/doNoPass',   //审核不通过
        'doPublish'                             => '/project/doPublish',   //项目发布
        'detail'                                => '/project/detail',   //详情
        'getJsxList'                            => '/project/jsxlist',//九省心项目列表
        'getJaxList'                            => '/project/jaxlist',//九安心项目列表
        'getSdfLists'                           => '/project/sdflists',//闪电付息项目列表
        'getAdminJsxList'                       => '/project/admin/jsxlist',//九省心项目列表
        'getAdminJaxList'                       => '/project/admin/jaxlist',//九安心项目列表
        'getAdminSdfLists'                      => '/project/admin/sdflists',//闪电付息项目列表
        'getLists'                              => '/project/list',    //项目列表
        'getListByStatus'                       => '/project/listByStatus',                 //通过类型获取项目列表
        'getFinishedList'                       => '/project/getFinishedList',           //获取已完结项目列表
        'getProjectList'                       => '/project/projectList',           //App4.0定期理财列表数据获取

        'doInvest'                              => '/project/invest',//定期项目投资
        'doInvestByCurrent'                     => '/project/investByCurrent',//零钱计划投资定期项目
        'getPlanInterest'                       => '/project/invest/getPlanInterest',//获取预期收益
        'doCreateBonusRefundRecord'             => '/project/invest/createRateRecord',//创建加息券回款记录
        'getProjectListByIds'                   => '/project/list',//获取projectIds的项目信息
        'refundPlan'                            => '/project/refundPlan',//获取项目的还款计划
        'getIndexProjectPack'                   => '/project/home',        //获取首页项目数据包
        'getProjectPackAppV413'                 => '/project/getProjectPackAppV413',    //APP4.1.3-首页项目
        'getFinishedProjectIds'                 => '/project/finished',    //获取已经完结的项目id
        'getRefundingList'                      => '/project/listByStatus',    //获取还款中的项目列表
        'getSdfList'                            => '/project/sdflist',     //闪电付息项目列表
        'getPfbList'                            => '/project/pfbList',     //普付宝项目列表
        'getPfbProjectDetail'                   => '/project/pfbDetail',   //普付宝项目详情（1个）
        'getPfbInvestList'                      => '/user/term/pfbInvestList',  //普付宝用户的可质押投资列表
        'getPfbInvestTotal'                     => '/user/term/getPfbInvestTotal',  //普付宝用户的可质押订单总额
        'getTimingProject'                      => '/project/timing',  //普付宝用户的可质押订单总额
        'getAppointJsxProject'                  => '/project/appointed',  //获取指定的最新的项目
        'getProjectIdsStatistics'               => '/project/getIds',  //根据条件获取项目ID j九省心

        'getNewInvest'                          => '/invest/getNewInvest',   //最新投资记录
        'getInvestAmountByDate'                 => '/invest/getInvestAmountByDate',  //根据日期获取投资总额记录
        'getInvestAmount'                       => '/invest/getTermInvestTotal', //获取总投资额
        'getRefundingProjectListByUpdateTime'   =>  '/project/getRefundingProjectListByUpdateTime',//根据项目的最后更新时间获取项目列表,主要功能为,后台按时间查询项目满标的列表
        'getProjectWithTime'                    =>  '/project/getProjectWithTime',//根据时间获取非普付宝的项目(可见的项目)
        'getNewestProjectEveryType'             =>  '/project/getNewestProjectEveryType',//每一个项目类型最新的项目
        'getListByIds'                          =>  '/invest/getListByIds',//通过多个id获取多个投资记录的信息

        'beforeRefundRecord'                    => '/project/beforeRefundRecord', //项目提前还款
        #@llper 时间段内已完结项目
        'getFinishedProjectByTime'              => '/project/getFinishedProjectByTime',
        'getInvestIngProject'                   => '/project/getInvestIngProject',      //通过发布时间获取再投项目
        'getNormalInvestByProjectIds'           => '/invest/getNormalInvestByProjectIds',      //通过发布时间获取再投项目
        'getLastInvestTimeByProjectId'          => '/invest/getLastInvestTimeByProjectId',      //从核心获取最后一次投资的数据(不包含原项目债转的记录)
        'getCreditProjectById'                  => '/project/getCreditProjectById' ,         //获取项目-项目回款相关信息
        'getProjectByFullTime'                  => '/project/getProjectByFullTime' ,         //获取项目-通过项目的满标时间
        'getAllProjectIdByTime'                 => '/project/getAllProjectIdByTime'          //通过时间,按照ProductLine获取项目id
    ],

    //零钱计划
    'moduleCurrent'     => [
        'getCreditAmount'                  => '/current/getCreditAmount',//获取用户零钱计划债权金额
        'getCurrentAccountAmount'          => '/current/getCurrentAccountAmount',//获取用户零钱计划资金留存
        'getUserInfo'                      => '/current/getUserInfo',//获取零钱计划帐户信息
        'getUserFund'                      => '/current/userFund',//用户中心零钱计划页面
        'getInvestUserNum'                 => '/current/userNum',//获取零钱计划投资总人数
        'getInvestAmount'                  => '/current/investAmount',//获取零钱计划投资总金额
        'getTodayInvestOutAmount'          => '/current/userTodayInvestOutAmount',//获取用户今日零钱计划转出总金额
        'doInvest'                         => '/current/invest',//零钱计划转入
        'doInvestOut'                      => '/current/investOut',//零钱计划转出
        'doInterestAccrual'                => '/current/refund/doRefundJob',//零钱计划计息
        'getInterestList'                  => '/current/userInterestList',//用户近一周收益
        'getYesterdayFund'                 => '/fundHistory/getYesterdayCurrentFundData',//获取零钱计划昨日转入转出记录
        'getYesterdayInterest'             => '/current/getYesterdayInterest',//获取零钱计划昨日总收益
        'getTodayCurrentInvestOutAmount'   => '/current/getTodayCurrentInvestOutAmount',//获取零钱计划用户今日转出总金额
        'getTodayCurrentInvestAmount'      => '/current/getTodayCurrentInvestAmount',//获取零钱计划用户今日转入总金额
        'getTodayCurrentAutoInvestAmountByUserId'  => '/current/getTodayAutoInvestCurrentTotalByUserId',//获取零钱计划用户今日自动转入总金额
        'getPlatformTodayAutoInvestCurrentTotal'  => '/current/getPlatformTodayAutoInvestCurrentTotal',//获取平台今日自动转入零钱计划总金额
        'getAdminCurrentInterestHistory'   => '/current/getAdminInterestHistory',//获取活期计息历史记录【管理后台】
        'getAutoInvestCurrentListByDate'   => '/current/getAutoInvestCurrentListByDate' //根据时间获取用户自动投资活期的列表信息
    ],

    //债权
    'moduleCreditAssign' => [
        'getUserCreditAssign'              => '/user/creditAssignList',     //用户中心债权转让列表
        'getCreditAssignList'              => '/project/getCreditAssignList',   //变现宝专区债权转让列表
        'doCreateProject'                  => '/project/creditAssign/create',   //创建债转项目
        'getUsableInvestId'                => '/project/credigetInvestIdtAssign/',
        'doCancel'                         => '/project/creditAssign/cancel', //取消债转项目
        'doInvest'                         => '/project/creditAssign/invest', //购买债转项目
        'doInvestByCurrent'                => '/project/creditAssign/investByCurrent', //使用零钱购买债转项目
        'getCreditAssignDetail'            => '/project/creditAssign/detail', //债转项目详情
        'getUserCreditAssignInvestIds'     => '/user/creditAssignInvestIds', //获取用户已转让的投资Id数组
        'getInvestingCount'                => '/project/creditAssign/getInvestingCount', //获取可投的债转项目总数
        'userPreCreditAssign'              => '/userPreCreditAssign',    //确认转让信息页面数据
        'getCreditAssignByInvestId'        => '/getCreditAssignByInvestId',//获取投资债转记录以及投资债转项目记录
    ],

    //回款列表
    'moduleRefund'      => [
        'getCoreRefundedRecord'             => '/refund/refunded',   //已回款列表
        'getCoreRefundingRecord'            => '/refund/refunding',   //回款中列表
        'getUserRefundRecordByDay'          => '/refund/getRefundByDay',   //获取用户某天回款记录
        'getUserRefundRecordById'           => '/refund/getRefundDetailById',   //通过ID获取用户回款详情
        'getCoreRefundPlanByMonthByUserId'  => '/refund/getRefundPlanByMonthByUserId',   //回款中列表
        'getFirstRefundRecord'              => '/refund/refundPlanByProjectId',         //首次回款计划
        'getRefundTotalByUserIds'           => '/refund/getRefundTotalByUserIds',       //通过多个用户id获取待回款本金之和
        'getRefundingTotal'                 => '/refund/getRefundingTotal',  //获取待收本息总额
        'getRefundProjectByTime'            => '/refund/getRefundProjectIdByTimes',       //通过时间活动回款的项目
        'getRefundingPrincipalListByUserIds'=> '/refund/getRefundByUserIds',  //根据用户ids获取回款本金列表
        'getArticleNoticeByTimes'           => '/article/getRefundNoticeData',  //还款公告
        'getTodayRefundUser'                => '/refund/getTodayRefundUser',  //获取今日还款用户
        'getRefundUserByDate'               => '/refund/getRefundUserByDate',  //根据时间获取还款用户列表
        'getSumInterestByProjectIds'        => '/refund/getSumInterestByProjectIds', //根据项目id获取利息
        'getInterestTypeByProjectIds'       => '/refund/getInterestTypeByProjectIds', //根据项目id获取利息的分类
        'getRefundTotalGroupByTime'         => '/refund/getRefundTotalGroupByTime', //根据时间段获取每天回款总额
        'getRefundProjectIdsAndCashByDate'  => '/refund/getRefundProjectIdsAndCashByDate'   //根据时间获取还款的项目id和金额
    ],

    //资金流水
    'moduleFundHistory' => [
        'getList'                           => '/fundHistory/getList',  //获取自己历史列表
        'getCurrentList'                    => '/fundHistory/getCurrentList',  //获取自己零钱计划历史列表
        'getChangeCashGroupByEventId'       => '/fundHistory/getChangeCashGroupByEventId'   //根据事件类型分组进行数据统计

    ],

    //系统配置
    'moduleConfig'      => [
        'getConfigList'                     => '/systemConfig/list',   //配置列表
        'addConfig'                         => '/systemConfig/add',    //添加配置
        'editConfig'                        => '/systemConfig/edit',   //修改配置
        'getConfig'                         => '/systemConfig/get',    //获取单个配置id
        'editConfigByKey'                   => '/systemConfig/editByKey',//通过key来编辑配置信息
    ],


    'moduleStatistics' => [

        'getHomeStatistics'                     => '/getHomeStatistics',//首页项目统计页面
        'getJdyStatistics'                      => '/getStatistics', //九斗鱼平台数据
        'getHomeStat'                           => '/getHomeStat',  //后台首页展示数据
    ]
];
