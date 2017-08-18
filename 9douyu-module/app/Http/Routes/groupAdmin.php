<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/18
 * Time: 下午5:19
 */
/**
 * 后台组    domain/admin
 *          admin/same/router  对应  Admin/same/controller@action
 */

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'domain' => env('APP_ADMIN_URL')], function()
{
    Route::auth();
    Route::get('login', ['as' => 'admin.login', 'uses' => 'Auth\AuthController@showLoginForm']);
    Route::post('login', 'Auth\AuthController@verifyLogin');
    Route::get('forgetPassword', 'Auth\PasswordController@forgetPassword');
    Route::post('password/sendToEmail', 'Auth\PasswordController@sendEmailPassword');
    Route::get('/', ['as' => 'admin.home', 'uses' => 'HomeController@index']);
    Route::get('home', ['as' => 'admin.home', 'uses' => 'HomeController@index']);
    Route::get('index', ['as' => 'admin.index', 'uses' => 'HomeController@home']);
    Route::resource('admin_user', 'AdminUserController');
    Route::post('admin_user/destroyall',['as'=>'admin.admin_user.destroy.all','uses'=>'AdminUserController@destroyAll']);
    Route::resource('role', 'RoleController');
    Route::post('role/destroyall',['as'=>'admin.role.destroy.all','uses'=>'RoleController@destroyAll']);
    Route::get('role/{id}/permissions',['as'=>'admin.role.permissions','uses'=>'RoleController@permissions']);
    Route::post('role/{id}/permissions',['as'=>'admin.role.permissions','uses'=>'RoleController@storePermissions']);
    Route::resource('permission', 'PermissionController');
    Route::post('permission/destroyall',['as'=>'admin.permission.destroy.all','uses'=>'PermissionController@destroyAll']);
    Route::get('update_password',['as'=>'admin.update_password','uses'=>'AdminUsers\AdminUsersController@resetPassword']);
    Route::post('doUpdatePassword',['as'=>'admin.doUpdatePassword','uses'=>'AdminUsers\AdminUsersController@doResetPassword']);
    Route::get('statData', ['as' => 'admin.statdata.statData', 'uses' => 'StatCenter\StatCenterController@homeStatData']);
    /*控制面板*/
    Route::get('console', ['as' => 'admin.console', 'uses' => 'ConsoleController@index']);

    /* 红包创建 */
    Route::get('bonus/create', ['as' => 'admin.bonus.create', 'uses' => 'Bonus\BonusController@getCreate']);
    Route::post('bonus/doCreate', ['as' => 'admin.bonus.doCreate', 'uses' => 'Bonus\BonusController@PostCreate']);

    Route::get('bonus/lists', ['as' => 'admin.bonus.lists', 'uses' => 'Bonus\BonusController@getLists']);

    Route::get('bonus/update/{id}', ['as' => 'admin.bonus.update', 'uses' => 'Bonus\BonusController@getUpdate']);
    Route::get('bonus/publish/{id}', ['as' => 'admin.bonus.publish', 'uses' => 'Bonus\BonusController@publishBonus']);

    //红包延期
    Route::get('/bonus/delay', ['as' => 'admin.bonus.delay', 'uses' => 'Bonus\UserBonusController@bonusDelay']);
    Route::post('/bonus/doBonusDelay', ['as' => 'admin.bonus.doBonusDelay', 'uses' => 'Bonus\UserBonusController@doBonusDelay']);

    //红包使用状态
    Route::get('/bonus/usedStatus', ['as' => 'admin.bonus.usedStatus', 'uses' => 'Bonus\UserBonusController@getUserBonusStatus']);


    Route::post('bonus/doUpdate', ['as' => 'admin.bonus.doUpdate', 'uses' => 'Bonus\BonusController@PostUpdate']);

    /*添加保理债权*/
    Route::get('credit/create/factoring', ['as' => 'admin.credit.create.factoring', 'uses' => 'Credit\CreditFactoringController@create']);

    /*添加项目集债权*/
    Route::get('credit/create/group', ['as' => 'admin.credit.create.group', 'uses' => 'Credit\CreditGroupController@create']);

    /*添加房产抵押债权*/
    Route::get('credit/create/housing', ['as' => 'admin.credit.create.housing', 'uses' => 'Credit\CreditHousingController@create']);

    /*添加耀盛信贷债权*/
    Route::get('credit/create/loan', ['as' => 'admin.credit.create.loan', 'uses' => 'Credit\CreditLoanController@create']);

    /*添加九省心债权*/
    Route::get('credit/create/nine', ['as' => 'admin.credit.create.nine', 'uses' => 'Credit\CreditNineController@create']);

    /*添加第三方债权*/
    Route::get('credit/create/third', ['as' => 'admin.credit.create.third', 'uses' => 'Credit\CreditThirdController@create']);

    /**新分散债权录入*/
    Route::get('credit/create/disperse', ['as' => 'admin.credit.create.disperse', 'uses' => 'Credit\CreditDisperseController@create']);

    /*执行添加保理债权*/
    Route::post('credit/doCreate/factoring', ['as' => 'admin.credit.doCreate.factoring', 'uses' => 'Credit\CreditFactoringController@doCreate']);

    /*执行添加项目集债权*/
    Route::post('credit/doCreate/group', ['as' => 'admin.credit.doCreate.group', 'uses' => 'Credit\CreditGroupController@doCreate']);

    /*执行添加房产抵押债权*/
    Route::post('credit/doCreate/housing', ['as' => 'admin.credit.doCreate.housing', 'uses' => 'Credit\CreditHousingController@doCreate']);

    /*执行添加耀盛信贷债权*/
    Route::post('credit/doCreate/loan', ['as' => 'admin.credit.doCreate.loan', 'uses' => 'Credit\CreditLoanController@doCreate']);

    /*执行添加九省心债权*/
    Route::post('credit/doCreate/nine', ['as' => 'admin.credit.doCreate.nine', 'uses' => 'Credit\CreditNineController@doCreate']);

    /*执行添加第三方债权*/
    Route::post('credit/doCreate/third', ['as' => 'admin.credit.doCreate.third', 'uses' => 'Credit\CreditThirdController@doCreate']);

    /**执行录入分散债权*/
    Route::post('credit/doCreate/disperse', ['as' => 'admin.credit.doCreate.disperse', 'uses' => 'Credit\CreditDisperseController@doCreate']);

    //新版债权添加后执行发布
    Route::post('credit/doOnline/disperse', ['as' => 'admin.credit.doOnline.disperse', 'uses' => 'Credit\CreditDisperseController@doOnline']);


    /*执行编辑保理债权*/
    Route::post('credit/doEdit/factoring', ['as' => 'admin.credit.doEdit.factoring', 'uses' => 'Credit\CreditFactoringController@doEdit']);

    /*执行编辑项目集债权*/
    Route::post('credit/doEdit/group', ['as' => 'admin.credit.doEdit.group', 'uses' => 'Credit\CreditGroupController@doEdit']);

    /*执行编辑房产抵押债权*/
    Route::post('credit/doEdit/housing', ['as' => 'admin.credit.doEdit.housing', 'uses' => 'Credit\CreditHousingController@doEdit']);

    /*执行编辑耀盛信贷债权*/
    Route::post('credit/doEdit/loan', ['as' => 'admin.credit.doEdit.loan', 'uses' => 'Credit\CreditLoanController@doEdit']);

    /*执行编辑九省心债权*/
    Route::post('credit/doEdit/nine', ['as' => 'admin.credit.doEdit.nine', 'uses' => 'Credit\CreditNineController@doEdit']);

    /*执行编辑第三方债权*/
    Route::post('credit/doEdit/third', ['as' => 'admin.credit.doEdit.third', 'uses' => 'Credit\CreditThirdController@doEdit']);


    /*列表保理债权*/
    Route::get('credit/lists/factoring', ['as' => 'admin.credit.lists.factoring', 'uses' => 'Credit\CreditFactoringController@lists']);

    /*列表项目集债权*/
    Route::get('credit/lists/group', ['as' => 'admin.credit.lists.group', 'uses' => 'Credit\CreditGroupController@lists']);

    /*列表房产抵押债权*/
    Route::get('credit/lists/housing', ['as' => 'admin.credit.lists.housing', 'uses' => 'Credit\CreditHousingController@lists']);

    /*列表耀盛信贷债权*/
    Route::get('credit/lists/loan', ['as' => 'admin.credit.lists.loan', 'uses' => 'Credit\CreditLoanController@lists']);

    /*列表九省心债权*/
    Route::get('credit/lists/nine', ['as' => 'admin.credit.lists.nine', 'uses' => 'Credit\CreditNineController@lists']);

    /*列表第三方债权*/
    Route::get('credit/lists/third', ['as' => 'admin.credit.lists.third', 'uses' => 'Credit\CreditThirdController@lists']);

    /**列表新分散债权*/
    Route::get('credit/lists/disperse', ['as' => 'admin.credit.lists.disperse', 'uses' => 'Credit\CreditDisperseController@lists']);

    /*债权列表集合*/
    Route::get('credit/lists', ['as' => 'admin.credit.lists', 'uses' => 'Credit\CreditListsController@lists']);

    /*编辑保理债权*/
    Route::get('credit/edit/factoring/{id}', ['as' => 'admin.credit.edit.factoring', 'uses' => 'Credit\CreditFactoringController@edit']);

    /*编辑项目集债权*/
    Route::get('credit/edit/group/{id}', ['as' => 'admin.credit.edit.group', 'uses' => 'Credit\CreditGroupController@edit']);

    /*编辑房产抵押债权*/
    Route::get('credit/edit/housing/{id}', ['as' => 'admin.credit.edit.housing', 'uses' => 'Credit\CreditHousingController@edit']);

    /*编辑耀盛信贷债权*/
    Route::get('credit/edit/loan/{id}', ['as' => 'admin.credit.edit.loan', 'uses' => 'Credit\CreditLoanController@edit']);

    /*编辑九省心债权*/
    Route::get('credit/edit/nine/{id}', ['as' => 'admin.credit.edit.nine', 'uses' => 'Credit\CreditNineController@edit']);

    /*编辑第三方债权*/
    Route::get('credit/edit/third/{id}', ['as' => 'admin.credit.edit.third', 'uses' => 'Credit\CreditThirdController@edit']);

    /*添加项目*/
    Route::get('/project/create/{productId?}', ['as' => 'admin.project.create', 'uses' => 'Project\ProjectController@create']);
    Route::get('/project/create/101', ['as' => 'admin.project.create.101', 'uses' => 'Project\ProjectController@create']);
    Route::get('/project/create/103', ['as' => 'admin.project.create.103', 'uses' => 'Project\ProjectController@create']);
    Route::get('/project/create/106', ['as' => 'admin.project.create.106', 'uses' => 'Project\ProjectController@create']);
    Route::get('/project/create/112', ['as' => 'admin.project.create.112', 'uses' => 'Project\ProjectController@create']);
    Route::get('/project/create/200', ['as' => 'admin.project.create.200', 'uses' => 'Project\ProjectController@create']);
    Route::get('/project/create/306', ['as' => 'admin.project.create.306', 'uses' => 'Project\ProjectController@create']);
    Route::get('/project/create/312', ['as' => 'admin.project.create.312', 'uses' => 'Project\ProjectController@create']);
    Route::get('/project/orderExport', ['as' => 'admin.project.orderExport', 'uses' => 'Project\ProjectController@orderExport']);
    Route::post('/project/beforeRefundRecord', ['as' => 'admin.project.beforeRefundRecord', 'uses' => 'Project\ProjectController@doBeforeRefundRecord']);

    /*执行添加项目*/
    Route::post('/project/doCreate', ['as' => 'admin.project.doCreate', 'uses' => 'Project\ProjectController@doCreate']);
    /*编辑项目*/
    Route::get('/project/update/{id}', ['as' => 'admin.project.update', 'uses' => 'Project\ProjectController@update']);
    /*执行编辑项目*/
    Route::post('/project/doUpdate', ['as' => 'admin.project.doUpdate', 'uses' => 'Project\ProjectController@doUpdate']);
    /*执行删除项目*/
    Route::post('/project/doDelete', ['as' => 'admin.project.doDelete', 'uses' => 'Project\ProjectController@doDelete']);
    /*审核通过*/
    Route::post('/project/doPass', ['as' => 'admin.project.doPass', 'uses' => 'Project\ProjectController@doPass']);
    /*发布*/
    Route::post('/project/doPublish', ['as' => 'admin.project.doPublish', 'uses' => 'Project\ProjectController@doPublish']);

    /*新定期项目*/
    Route::get('/project/createNew',  ['as' => 'admin.project.new.create', 'uses' => 'Project\ProjectController@createNew']);

    Route::post('/project/doCreateNew', ['as' => 'admin.project.new.doCreate', 'uses' => 'Project\ProjectController@doCreateNew']);

    Route::get('/project/updateNew/{id}', ['as' => 'admin.project.new.update', 'uses' => 'Project\ProjectController@updateNew']);

    Route::post('/project/doUpdateNew', ['as' => 'admin.project.new.doUpdate', 'uses' => 'Project\ProjectController@doUpdateNew']);



    /*发送用户活动加息券*/
    Route::get('/bonus/send', ['as' => 'admin.bonus.send', 'uses' => 'Bonus\UserBonusController@sendBonus']);
    /*执行发送用户活动加息券*/
    Route::post('/bonus/doSend', ['as' => 'admin.bonus.doSend', 'uses' => 'Bonus\UserBonusController@doSendBonus']);

    //项目列表
    Route::get('/project/lists', ['as' => 'admin.project.lists', 'uses' => 'Project\ProjectController@index']);

    /*提现对账*/
    Route::get('withdraw/checkBill', ['as' => 'admin.withdraw.checkBill', 'uses' => 'Order\WithdrawBillController@checkBill']);
    Route::post('withdraw/uploadBill', ['as' => 'admin.withdraw.uploadBill', 'uses' => 'Order\WithdrawBillController@uploadBill']);

    Route::get('withdraw/sendBatchMsg/{id}',['as' => 'admin.withdraw.sendBatchMsg','uses' => 'Order\WithdrawController@sendBatchMsg']);

    Route::get('withdraw/info/{orderId}',['as' => 'admin.withdraw.info','uses' => 'Order\WithdrawController@info']);

    Route::post('withdraw/doEdit',['as' => 'admin.withdraw.doEdit','uses' => 'Order\WithdrawController@doEdit']);



    // Route::post('withdraw/matchBill', ['as' => 'admin.withdraw.matchBill', 'uses' => 'Order\WithdrawBillController@matchBill']);
    Route::get('withdraw', ['as' => 'admin.withdraw', 'uses' => 'Order\WithdrawController@index']);

    Route::get('withdrawRecord', ['as' => 'admin.withdrawRecord', 'uses' => 'Order\WithdrawController@withdrawRecord']);


    Route::post('withdraw/sendEmail',['as' => 'admin.withdraw.sendEmail','uses' => 'Order\WithdrawController@sendEmail']);

    Route::post('withdraw/sendEmailWithdraw',['as' => 'admin.withdraw.sendEmailWithdraw','uses' => 'Order\WithdrawController@sendEmailWithdraw']);

    /*后台配置*/
    Route::get('system_config', ['as' => 'admin.system_config', 'uses' => 'SystemConfig\SystemConfigController@index']);
    Route::get('system_config/create', ['as' => 'admin.system_config.create', 'uses' => 'SystemConfig\SystemConfigController@create']);
    Route::get('system_config/update', ['as' => 'admin.system_config.update', 'uses' => 'SystemConfig\SystemConfigController@update']);
    Route::post('system_config/doCreate', ['as' => 'admin.system_config.doCreate', 'uses' => 'SystemConfig\SystemConfigController@doCreate']);
    Route::post('system_config/doUpdate', ['as' => 'admin.system_config.doUpdate', 'uses' => 'SystemConfig\SystemConfigController@doUpdate']);
    Route::post('system_config/doDelete', ['as' => 'admin.system_config.doDelete', 'uses' => 'SystemConfig\SystemConfigController@doDelete']);

    /*文章管理 - 文章分类*/
    Route::get('article/category', ['as' => 'admin.article.category', 'uses' => 'Article\CategoryController@index']);
    Route::get('article/category/create', ['as' => 'admin.article.category.create', 'uses' => 'Article\CategoryController@create']);
    Route::post('article/category/doCreate', ['as' => 'admin.article.category.doCreate', 'uses' => 'Article\CategoryController@doCreate']);
    Route::get('article/category/update', ['as' => 'admin.article.category.update', 'uses' => 'Article\CategoryController@update']);
    Route::post('article/category/doUpdate', ['as' => 'admin.article.category.doUpdate', 'uses' => 'Article\CategoryController@doUpdate']);
    /*文章管理 - 文章*/
    Route::get('article', ['as' => 'admin.article', 'uses' => 'Article\ArticleController@index']);
    Route::get('article/create', ['as' => 'admin.article.create', 'uses' => 'Article\ArticleController@create']);
    Route::get('article/update', ['as' => 'admin.article.update', 'uses' => 'Article\ArticleController@update']);
    Route::post('article/doCreate', ['as' => 'admin.article.doCreate', 'uses' => 'Article\ArticleController@doCreate']);
    Route::post('article/doUpdate', ['as' => 'admin.article.doUpdate', 'uses' => 'Article\ArticleController@doUpdate']);
    Route::get('article/doDelete/{id}', ['as' => 'admin.article.doDelete', 'uses' => 'Article\ArticleController@doDelete']);

    /*零钱计划管理*/

    Route::get('current/credit/create', ['as' => 'admin.current.credit.create', 'uses' => 'Current\CreditController@create']);
    /*执行添加零钱计划债权*/
    Route::post('current/credit/doCreate', ['as' => 'admin.current.credit.doCreate', 'uses' => 'Current\CreditController@doCreate']);

    Route::get('current/credit/lists', ['as' => 'admin.current.credit.lists', 'uses' => 'Current\CreditController@creditList']);

    Route::get('current/credit/edit/{id}', ['as' => 'admin.current.credit.edit', 'uses' => 'Current\CreditController@edit']);

    Route::post('current/credit/doEdit', ['as' => 'admin.current.credit.doEdit', 'uses' => 'Current\CreditController@doEdit']);

    Route::get('current/credit/detail/lists/{id}', ['as' => 'admin.current.credit.detail.lists', 'uses' => 'Current\CreditController@creditDetailList']);

    //添加零钱计划利率处理
    Route::post('current/rate/doCreate', ['as' => 'admin.current.rate.doCreate', 'uses' => 'Current\RateController@doCreate']);
    //编辑零钱计划利率
    Route::post('current/rate/doEdit', ['as' => 'admin.current.rate.doEdit', 'uses' => 'Current\RateController@doEdit']);
    //零钱计划利率列表
    Route::get('current/rate/lists', ['as' => 'admin.current.rate.lists', 'uses' => 'Current\RateController@lists']);
    //添加零钱计划利率
    Route::get('current/rate/create', ['as' => 'admin.current.rate.create', 'uses' => 'Current\RateController@create']);

    Route::get('current/rate/edit/{id}', ['as' => 'admin.current.rate.edit', 'uses' => 'Current\RateController@edit']);

    //列表
    Route::get('current/limit/lists', ['as' => 'admin.current.limit.lists', 'uses' => 'Current\LimitController@lists']);
    //添加
    Route::get('current/limit/create', ['as' => 'admin.current.limit.create', 'uses' => 'Current\LimitController@create']);
    //执行添加
    Route::post('current/limit/doCreate', ['as' => 'admin.current.limit.doCreate', 'uses' => 'Current\LimitController@doCreate']);
    //编辑
    Route::get('current/limit/edit', ['as' => 'admin.current.limit.edit', 'uses' => 'Current\LimitController@edit']);
    //执行编辑
    Route::post('current/limit/doEdit', ['as' => 'admin.current.limit.doEdit', 'uses' => 'Current\LimitController@doEdit']);

    //数据统计展示
    Route::get('current/fund', ['as' => 'admin.current.fund', 'uses' => 'Current\FundStatisticsController@index']);
    //数据统计导出
    Route::get('current/export', ['as' => 'admin.current.export', 'uses' => 'Current\FundStatisticsController@doExport']);

    //零钱计划计息历史记录列表
    Route::get('current/interest/history', ['as' => 'admin.current.interest.history', 'uses' => 'Current\InterestController@index']);


    //广告位相关
    Route::get('ad/positionList', ['as' => 'admin.ad.positionList', 'uses' => 'Ad\AdController@positionList']);
    Route::post('ad/addPosition', ['as' => 'admin.ad.addPosition', 'uses' => 'Ad\AdController@doAddPosition']);
    Route::get('ad/viewPic', ['as' => 'admin.ad.viewPic', 'uses' => 'Ad\AdController@viewPic']);
    Route::get('ad/delPosition', ['as' => 'admin.ad.delPosition', 'uses' => 'Ad\AdController@delPosition']);
    Route::get('ad/editPosition/{id}', ['as' => 'admin.ad.editPosition', 'uses' => 'Ad\AdController@editPosition']);

    Route::post('ad/doEditPosition', ['as' => 'admin.ad.doEditPosition', 'uses' => 'Ad\AdController@doEditPosition']);



    //广告管理
    Route::get('ad/addAd', ['as' => 'admin.ad.addAd', 'uses' => 'Ad\AdController@addAd']); //添加广告
    Route::post('ad/doAddAd', ['as' => 'admin.ad.doAddAd', 'uses' => 'Ad\AdController@doAddAd']);    //执行添加
    Route::get('ad/adList', ['as' => 'admin.ad.adList', 'uses' => 'Ad\AdController@adList']);   //广告列表
    Route::get('ad/delAd', ['as' => 'admin.ad.delAd', 'uses' => 'Ad\AdController@delAd']);    //删除广告
    Route::get('ad/editAd/{id}', ['as' => 'admin.ad.editAd', 'uses' => 'Ad\AdController@editAd']);    //编辑广告
    Route::post('ad/doEditAd', ['as' => 'admin.ad.doEditAd', 'uses' => 'Ad\AdController@doEditAd']);    //编辑广告保存

    Route::get('sms/check', ['as' => 'admin.sms.check', 'uses' => 'Sms\SmsController@smsCheck']);    //短信内容敏感词检测
    Route::post('sms/doCheck', ['as' => 'admin.sms.doCheck', 'uses' => 'Sms\SmsController@doSmsContentCheck']);    //执行短信内容敏感词的检测





    //注册用户管理
    Route::get('user/index', ['as' => 'admin.user.index', 'uses' => 'User\UserController@index']);//用户列表
    Route::get('user/remind', ['as' => 'admin.user.remind', 'uses' => 'User\UserController@remindInvest']);//待提醒投资用户列表
    Route::get('user/change', ['as' => 'admin.user.change', 'uses' => 'User\UserController@changePhone']);//更换用户手机号
    Route::post('user/doChange', ['as' => 'admin.user.doChange', 'uses' => 'User\UserController@doChangePhone']);//更换用户手机号操作
    Route::get('user/unbindFamily', ['as'=> 'admin.user.unbindFamily', 'uses' => 'User\UserController@unbindFamily']);//家庭账户解绑
    Route::post('user/doUnbindFamily', ['as'=> 'admin.user.doUnbindFamily', 'uses' => 'User\UserController@doUnbindFamily']);//家庭账户解绑操作
    Route::post('user/doUserStatusBlock', ['as'=> 'admin.user.doUserStatusBlock', 'uses' => 'User\UserController@doUserStatusBlock']);//用户账户锁定
    Route::get('user/info/{id}', ['as'=> 'admin.user.info', 'uses' => 'User\UserController@userInfo']);//用户详情
    Route::get('user/changeBalance' ,   ['as' => 'admin.user.changeBalance',  'uses' => 'User\UserController@changeBalance']);    //用户奖励加币\扣款功能
    Route::post('user/doChangeBalance' ,   ['as' => 'admin.user.doChangeBalance',  'uses' => 'User\UserController@doChangeBalance']);    //执行奖励加币\扣款功能

    Route::post('user/doUserStatusFrozen', ['as' => 'admin.user.doUserStatusFrozen',  'uses' => 'User\UserController@doUserStatusFrozen']); //冻结用户
    Route::post('user/doUserStatusUnFrozen', ['as' => 'admin.user.doUserStatusUnFrozen',  'uses' => 'User\UserController@doUserStatusUnFrozen']); //解冻用户
    Route::get('user/loginInfo', ['as' => 'admin.user.loginInfo',  'uses' => 'User\UserController@checkUserLoginInfo']); //解冻用户


    //银行卡管理
    Route::get('bankcard/change', ['as'=> 'admin.bankcard.change', 'uses'=>'BankCard\BankCardController@changeCard']);//更换银行卡
    Route::post('bankcard/doChange', ['as'=> 'admin.bankcard.doChange', 'uses'=>'BankCard\BankCardController@doChangeCard']);//更换银行卡操作
    Route::get('bankcard/checkUserCard', ['as'=> 'admin.bankcard.checkUserCard', 'uses'=>'BankCard\BankCardController@checkUserCard']);//检测银行卡的实名信息
    Route::post('bankcard/doCheckUserCard', ['as'=> 'admin.bankcard.doCheckUserCard', 'uses'=>'BankCard\BankCardController@doCheckUserCard']);//检测银行卡的实名信息操作
    Route::post('bankcard/getCheckUserInfo', ['as'=> 'admin.bankcard.getCheckUserInfo', 'uses'=>'BankCard\BankCardController@getCheckUserInfo']);//获取检测银行卡实名用户的信息

    Route::get('bankcard/unbind',['as' => 'admin.bankcard.unbind','uses' => 'BankCard\BankCardController@unbind']);//先锋支付银行卡解绑
    Route::post('bankcard/doUnbind', ['as'=> 'admin.bankcard.doUnbind', 'uses'=>'BankCard\BankCardController@doUnbind']);//先锋支付银行卡解绑操作

    //合伙人管理
    Route::get('partner/index', ['as'=> 'admin.partner.index', 'uses'=>'Partner\PartnerController@index']);
    //合伙人管理详情
    Route::get('partner/detail', ['as'=> 'admin.partner.detail', 'uses'=>'Partner\PartnerController@detail']);

    Route::get('inviteRates', ['as'=> 'admin.partner.inviteRates', 'uses'=>'Partner\PartnerController@inviteRates']);  //邀请加息券
    Route::post('addInviteRates', ['as'=> 'admin.partner.AddInviteRates', 'uses'=>'Partner\PartnerController@AddInviteRates']);  //添加邀请加息券
    Route::get('delInviteRates', ['as'=> 'admin.partner.DelInviteRates', 'uses'=>'Partner\PartnerController@DelInviteRates']);  //删除邀请加息券
    //合伙人添加邀请关系
    Route::post('partner/addInvite',['as'=> 'admin.partner.addInvite', 'uses'=>'Partner\PartnerController@addInvite'] );
    //@llper 合伙人佣金收益
    Route::get('partner/rewardExport', ['as'=> 'admin.partner.rewardExport', 'uses'=>'Partner\PartnerController@activityRewardExport']);
    //@llper 合伙人解绑
    Route::get('partner/unbindInvite',  ['as' => 'admin.partner.unbindInvite', 'uses' => 'Partner\PartnerController@unbindInvite']);

    //投资管理
    Route::get('invest/index', ['as'=> 'admin.invest.index', 'uses'=>'Invest\InvestController@index']);

    Route::post('editor/image/upload', ['as' => 'admin.editor.image.upload', 'uses' => 'UploadController@editorImageUpload']);    // 编辑器图片上传
    Route::post('editor/image/manager', ['as' => 'admin.editor.image.manager', 'uses' => 'UploadController@imageManager']);       // 图片管理


    Route::get('paylimit/lists/{pay_type?}/{bank_id?}',['as' => 'admin.paylimit.lists','uses' => 'Recharge\PayLimitController@index']);

    Route::get('paylimit/create',['as' => 'admin.paylimit.create','uses' => 'Recharge\PayLimitController@create']);

    Route::post('paylimit/doCreate',['as' => 'admin.paylimit.doCreate','uses' => 'Recharge\PayLimitController@doCreate']);

    Route::get('paylimit/edit/{id?}',['as' => 'admin.paylimit.edit','uses' => 'Recharge\PayLimitController@edit']);

    Route::post('paylimit/doEdit',['as' => 'admin.paylimit.doEdit','uses' => 'Recharge\PayLimitController@doEdit']);

    Route::get('paylimit/doEditStatus/{id}/{status}',['as' => 'admin.paylimit.doEditStatus','uses' => 'Recharge\PayLimitController@doEditStatus']);

    Route::get('recharge/lists',['as' => 'admin.recharge.lists','uses' => 'Recharge\OrderController@index']);
    //网银支持的银行列表
    Route::get('bankcode/online/lists',['as' => 'admin.bankcode.lists','uses' => 'Recharge\OnlineController@index']);
    //编辑网银支持的银行
    Route::get('bankcode/online/doEditStatus/{bankId}/{status}/{type}',['as' => 'admin.bankcode.doEditStatus','uses' => 'Recharge\OnlineController@doEditStatus']);
    //导出充值总额
    Route::post('recharge/exportTotal',['as' => 'admin.recharge.exportTotal','uses' => 'Recharge\OrderController@doExportRecharge']);


    //订单查询
    Route::get('recharge/orderSearch',['as' => 'admin.recharge.orderSearch','uses' => 'Recharge\OrderController@orderSearch']);

    //对账相关
    Route::get('accounts/batchList',['as' => 'admin.accounts.batchList','uses' => 'Recharge\CheckBatchController@getList']);

    Route::post('accounts/batch/add',['as' => 'admin.accounts.addBatch','uses' => 'Recharge\CheckBatchController@doAddBatch']);

    Route::get('accounts/batch/review',['as' => 'admin.accounts.review','uses' => 'Recharge\CheckBatchController@doReview']);

    Route::get('accounts/batch/delete',['as' => 'admin.accounts.delete','uses' => 'Recharge\CheckBatchController@doDelete']);

    Route::get('accounts/checkList',['as' => 'admin.accounts.checkList','uses' => 'Recharge\AccountsOrderController@getList']);

    Route::get('accounts/untreated',['as' => 'admin.accounts.untreated','uses' => 'Recharge\AccountsOrderController@untreated']);

    Route::get('accounts/handled',['as' => 'admin.accounts.handled','uses' => 'Recharge\AccountsOrderController@handled']);

    Route::post('accounts/doHandled',['as' => 'admin.accounts.doHandled','uses' => 'Recharge\AccountsOrderController@doHandled']);



    //掉单处理接口
    Route::get('recharge/missOrderHandle',['as' => 'admin.recharge.missOrderHandle','uses' =>'Recharge\OrderController@missOrderHandle']);

    Route::post('recharge/missOrderSearch',['as' => 'admin.recharge.missOrderSearch','uses' =>'Recharge\OrderController@missOrderSearch']);

    Route::post('recharge/doMissOrderHandle',['as' => 'admin.recharge.doMissOrderHandle','uses' =>'Recharge\OrderController@doMissOrderHandle']);

    Route::get('fund/lists',['as' => 'admin.fund.lists','uses' => 'Fund\GetController@index']);
    //@llper 账户管理资金统计
    Route::get('fund/fundStat',['as' => 'admin.fund.fundStat','uses' => 'Fund\GetController@fundStat']);
    //@linglu   中金云数据统计
    Route::get('fund/investRefundStat',['as' => 'admin.fund.investRefundStat','uses' => 'Fund\GetController@investRefundStat']);

    Route::get('fund/fundHistoryStat',['as' => 'admin.fund.fundHistoryStat','uses' => 'Fund\GetController@fundHistoryStat']);

    //自媒体管理

    //分组相关操作
    Route::get('media/group/lists',['as' => 'admin.media.group.lists','uses' => 'Media\GroupController@index']);

    Route::get('media/group/edit/{id}',['as' => 'admin.media.group.edit','uses' => 'Media\GroupController@edit']);

    Route::post('media/group/doEdit',['as' => 'admin.media.group.doEdit','uses' => 'Media\GroupController@doEdit']);

    Route::get('media/group/create',['as' => 'admin.media.group.create','uses' => 'Media\GroupController@create']);

    Route::post('media/group/doCreate',['as' => 'admin.media.group.doCreate','uses' => 'Media\GroupController@doCreate']);

    Route::get('media/group/delete/{id}',['as' => 'admin.media.group.delete','uses' => 'Media\GroupController@delete']);


    //渠道相关操作
    Route::get('media/channel/lists',['as' => 'admin.media.channel.lists','uses' => 'Media\ChannelController@index']);

    Route::get('media/channel/edit/{id}',['as' => 'admin.media.channel.edit','uses' => 'Media\ChannelController@edit']);

    Route::post('media/channel/doEdit',['as' => 'admin.media.channel.doEdit','uses' => 'Media\ChannelController@doEdit']);

    Route::get('media/channel/create',['as' => 'admin.media.channel.create','uses' => 'Media\ChannelController@create']);

    Route::post('media/channel/doCreate',['as' => 'admin.media.channel.doCreate','uses' => 'Media\ChannelController@doCreate']);

    Route::get('media/channel/delete/{id}',['as' => 'admin.media.channel.delete','uses' => 'Media\ChannelController@delete']);


    //批量相关
    Route::get('batch/index',          ['as' => 'admin.batch.index',    'uses' => 'Batch\BatchListController@index']);
    Route::get('batch/audit/{id}',     ['as' => 'admin.batch.audit',    'uses' => 'Batch\BatchListController@audit']);
    Route::get('batch/del/{id}',       ['as' => 'admin.batch.del',      'uses' => 'Batch\BatchListController@del']);
    Route::post('batch/add',           ['as' => 'admin.batch.add',      'uses' => 'Batch\BatchListController@doAdd']);

    //邀请相关
    Route::get('invite/index',['as' => 'admin.invite.index','uses' => 'Invite\InviteController@index']);

    //后台活动配置相关
    Route::get('activity_config',           ['as' => 'admin.activity_config',          'uses' => 'Activity\ActivityConfigController@index']);
    Route::get('activity_config/create',    ['as' => 'admin.activity_config.create',   'uses' => 'Activity\ActivityConfigController@create']);
    Route::get('activity_config/update',    ['as' => 'admin.activity_config.update',   'uses' => 'Activity\ActivityConfigController@update']);
    Route::post('activity_config/doCreate', ['as' => 'admin.activity_config.doCreate', 'uses' => 'Activity\ActivityConfigController@doCreate']);
    Route::post('activity_config/doUpdate', ['as' => 'admin.activity_config.doUpdate', 'uses' => 'Activity\ActivityConfigController@doUpdate']);
    Route::post('activity_config/doDelete', ['as' => 'admin.activity_config.doDelete', 'uses' => 'Activity\ActivityConfigController@doDelete']);


    //抽奖相关
    Route::get('lottery/configList' ,   ['as' => 'admin.activity.lottery.configList',   'uses' => 'Activity\LotteryController@getLotteryConfigLimit']);
    Route::get('lottery/addConfig' ,    ['as' => 'admin.activity.lottery.addConfig',    'uses' => 'Activity\LotteryController@addLotteryConfig']);
    Route::post('lottery/doAddConfig' , ['as' => 'admin.activity.lottery.doAddConfig',  'uses' => 'Activity\LotteryController@doAddConfig']);
    Route::get('lottery/editConfig' ,   ['as' => 'admin.activity.lottery.editConfig',   'uses' => 'Activity\LotteryController@editConfig']);
    Route::post('lottery/doEditConfig' ,['as' => 'admin.activity.lottery.doEditConfig', 'uses' => 'Activity\LotteryController@doEditConfig']);
    Route::get('lottery/record' ,       ['as' => 'admin.activity.lottery.record',       'uses' => 'Activity\LotteryController@getRecord']);
    Route::get('lottery/addRecord' ,    ['as' => 'admin.activity.lottery.addRecord',    'uses' => 'Activity\LotteryController@addLotteryRecord']);
    Route::post('lottery/doAddRecord' , ['as' => 'admin.activity.lottery.doAddRecord',  'uses' => 'Activity\LotteryController@doAddLotteryRecord']);
    Route::get('lottery/editRecord' ,   ['as' => 'admin.activity.lottery.editRecord',   'uses' => 'Activity\LotteryController@editLotteryRecord']);
    Route::post('lottery/doEditRecord' ,['as' => 'admin.activity.lottery.doEditRecord', 'uses' => 'Activity\LotteryController@doEditLotteryRecord']);
    Route::post('lottery/lotteryJson',  ['as' => 'admin.activity.lottery.lotteryJson',  'uses' => 'Activity\LotteryController@getLotteryByGroup']);

    //后台微刊管理
    Route::get('micro' ,                ['as' => 'admin.micro.list',        'uses' => 'Ad\MicroJournalController@getMicroJournalList']);
    Route::get('micro/addMicro' ,       ['as' => 'admin.micro.addMicro',    'uses' => 'Ad\MicroJournalController@addMicroJournal']);
    Route::get('micro/editMicro' ,      ['as' => 'admin.micro.editMicro',   'uses' => 'Ad\MicroJournalController@editMicroJournal']);
    Route::post('micro/doAddMicro' ,    ['as' => 'admin.micro.doAddMicro',  'uses' => 'Ad\MicroJournalController@doAdd']);
    Route::post('micro/doEditMicro' ,   ['as' => 'admin.micro.doEditMicro', 'uses' => 'Ad\MicroJournalController@doEdit']);
    Route::get('micro/delete' ,         ['as' => 'admin.micro.delete',      'uses' => 'Ad\MicroJournalController@delete']);

    //后台外呼数据管理
    Route::any('outCall/index',['as' => 'admin.outCall.index', 'uses'=>'OutCall\OutCallController@index']);

    //合同
    Route::get('contract' ,                ['as' => 'admin.contract.index',        'uses' => 'Contract\ContractController@createDownLoad']);
    Route::post('contract/doCreateDownLoad' ,                ['as' => 'admin.contract.doCreateDownLoad',        'uses' => 'Contract\ContractController@doCreateDownLoad']);


    //----------------新版活期------------
    //添加零钱计划利率处理
    Route::post('currentNew/rate/doCreate', ['as' => 'admin.currentNew.rate.doCreate', 'uses' => 'CurrentNew\RateController@doCreate']);
    //编辑零钱计划利率
    Route::post('currentNew/rate/doEdit', ['as' => 'admin.currentNew.rate.doEdit', 'uses' => 'CurrentNew\RateController@doEdit']);
    //零钱计划利率列表
    Route::get('currentNew/rate/lists', ['as' => 'admin.currentNew.rate.lists', 'uses' => 'CurrentNew\RateController@lists']);
    //添加零钱计划利率
    Route::get('currentNew/rate/create', ['as' => 'admin.currentNew.rate.create', 'uses' => 'CurrentNew\RateController@create']);

    Route::get('currentNew/rate/edit/{id}', ['as' => 'admin.currentNew.rate.edit', 'uses' => 'CurrentNew\RateController@edit']);

    Route::get('currentNew/project/lists', ['as' => 'admin.currentNew.project.lists', 'uses' => 'CurrentNew\ProjectController@index']);

    Route::get('currentNew/project/create', ['as' => 'admin.currentNew.project.create', 'uses' => 'CurrentNew\ProjectController@create']);

    Route::post('currentNew/project/doCreate', ['as' => 'admin.currentNew.project.doCreate', 'uses' => 'CurrentNew\ProjectController@doCreate']);

    Route::get('currentNew/project/edit/{id}', ['as' => 'admin.currentNew.project.edit', 'uses' => 'CurrentNew\ProjectController@edit']);

    Route::post('currentNew/project/doEdit', ['as' => 'admin.currentNew.project.doEdit', 'uses' => 'CurrentNew\ProjectController@doEdit']);


    /*-----------------九斗鱼借款账户体系债权信息---------------------------------*/

    //九斗鱼借款账户体系添加债权
    Route::get('credit/create/loanUser', [ 'as' => 'admin.credit.create.loanUser', 'uses' => 'Credit\CreditUserLoanController@create']);

    //九斗鱼账户借款账户体系执行添加债权
    Route::post('credit/create/doLoanUser', [ 'as' => 'admin.credit.create.doLoanUser', 'uses' => 'Credit\CreditUserLoanController@doCreate']);

    //九斗鱼账户体系债权列表
    Route::get('credit/lists/loanUser', [ 'as' => 'admin.credit.lists.loanUser', 'uses' => 'Credit\CreditUserLoanController@lists']);


    /*-------------------九斗鱼后台查询导出项目关联指定的债权信息-----------------------------*/
    Route::get('data/get/creditList', [ 'as' => 'admin.data.get.creditList', 'uses' => 'Data\CreditGetController@getCredit']);

    Route::get('data/get/creditHouse', [ 'as' => 'admin.data.get.creditHouse', 'uses' => 'Data\CreditGetController@getBuildCredit']);

    /*---------------------------------债权合并优化路由---------------------------------------------*/

    /*新债权列表集合*/
    Route::get('credit/new/lists', ['as' => 'admin.credit.new.lists', 'uses' => 'Credit\CreditAllController@newLists']);

    //合并后债权创建
    Route::get('credit/create/all', ['as' => 'admin.credit.create.all', 'uses' => 'Credit\CreditAllController@create']);
    //执行合并后债权创建
    Route::post('credit/doCreate/all', ['as' => 'admin.credit.doCreate.all', 'uses' => 'Credit\CreditAllController@doCreate']);

    //债权扩展信息编辑页面
    Route::get('credit/edit/extend/{type}/{creditId}', ['as' => 'admin.credit.edit.extend', 'uses' => 'Credit\CreditExtendController@edit']);

    //债权扩展信息编辑处理
    Route::post('credit/doEdit/extend', ['as' => 'admin.credit.doEdit.extend', 'uses' => 'Credit\CreditExtendController@doEdit']);

    //合并后债权编辑
    Route::get('credit/edit/all/{id}', ['as' => 'admin.credit.edit.all', 'uses' => 'Credit\CreditAllController@edit']);
    //执行合并后债权编辑
    Route::post('credit/doEdit/all', ['as' => 'admin.credit.doEdit.all', 'uses' => 'Credit\CreditAllController@doEdit']);
});
