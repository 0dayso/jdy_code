<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/18
 * Time: 下午5:19
 */
/**
 * 第三方 api
 */

Route::group(['prefix' => '/', 'namespace' => 'ThirdApi', 'domain' => env('THIRD_API_DOMAIN')], function()
{
    /**
     * 普付宝
     */
    Route::post('pfb', 'PfbController@index');
    /**
     * 普付宝
     */
    Route::post('api/pfb', 'PfbController@index');

    /**
     * 九一付 获取用户信息
     */
    Route::post('api/jyf/getUserInfo', 'JyfController@getUserInfo');

    /**
     * 九一付 获取token
     */
    Route::post('api/requestToken', 'ApiController@requestToken');
    /**
     * 九一付 注册
     */
    Route::post('api/jyf/doRegister', 'JyfController@doRegister');

    /**
     * 发送模板消息
     */
    Route::post('api/jyf/sendTemplateMessage', 'JyfController@sendTemplateMessage');

    /**
     * 获取微信token
     */
    Route::post('api/jyf/getWechatToken', 'JyfController@getWechatToken');

    /**
     * 九一付 注册 发送短信验证码
     */
    Route::post('api/jyf/sendRegisterSms', 'JyfController@sendRegisterSms');

    /**
     * 九一付 注册成功发送初始密码提醒接口
     */
    Route::post('api/jyf/sendRegisterSucceedSms', 'JyfController@sendRegisterSucceedSms');


    /**
     * 九一付 注册 增加余额
     */
    Route::post('api/jyf/addAmount', 'JyfController@addAmount');

    /**
     * 对账
     */
    Route::post('api/jyf/reconciliation', 'JyfController@getYmfReconciliation');

    /**
     * 网贷之家数据接口
     */
    Route::get('api/wdzj/Investing', 'WdzjController@getInvestingProject');

    Route::get('api/wdzj/getProjectByDate', 'WdzjController@getProjectByDate');

    /**
     * 零壹财经的数据接口
     */
    Route::get('api/lycj/getToken', 'LycjController@getToken');
    Route::get('api/lycj/getProjectByStatus', 'LycjController@getProjectByStatus');
    Route::get('api/lycj/getInvestmentRecord', 'LycjController@getInvestmentRecord');
    Route::get('api/lycj/setDataValidation', 'LycjController@setDataValidation');

    /**
     * 中关村协会数据接口
     */
    Route::post('api/zgc/searchCreditData', 'ZgcController@searchCreditData');

    /**
     * 网贷天眼数据接口
     */
    Route::get('api/wdty/getProjectByDate', 'WdtyController@getProjectByDate');
    Route::get('api/wdty/getInvestByProjectId', 'WdtyController@getInvestByProjectId');

    /**
     * 融360的数据接口
     */
    Route::get('api/r360/successProject', 'R360Controller@getSuccessProjectList');
    Route::get('api/r360/investIngProject', 'R360Controller@getInvestProjectList');
    Route::get('api/r360/projectStatus',    'R360Controller@getProjectStatus');


    /**
     * 小微金融数据接口
     */
    Route::post('api/xiaowei/memberRishInfo', 'XiaoWeiController@memberRishInfo');
    Route::post('api/xiaowei/queryRiskInfo', 'XiaoWeiController@queryRiskInfo');



});
