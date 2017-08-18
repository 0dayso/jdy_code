<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


//Route::get('/', 'Pc\Home\IndexController@index');   //网站首页


Route::get('/picture/{id}', 'Picture\PictureController@index');   //图片展示
Route::get('/uploads/{path}', 'Picture\OssPictureController@uploadsPath',function($path){})->where('path', '[\w\/\.-]*');
Route::get('/resources/{path}', 'Picture\OssPictureController@resourcesPath',function($path){})->where('path', '[\w\/\.-]*');

$env = \App::environment();

if(!\App::environment("production")) {
//swagger
    Route::get('swagger/index', 'Swagger\ApiController@index');
    Route::get('swagger/config', 'Swagger\ApiController@config');
}
/**
 * 充值提现支付模块
 */
Route::group(['prefix' => 'pay', 'namespace' => 'Pay'], function() {
    Route::get('index','RechargeController@index');
    Route::get('appConfirm/{payChannel}/{userId}/{bankId}/{cash}/{cardNo}/{orderNo}/{version}/{client}','RechargeController@appConfirm');
    Route::post('submit','RechargeController@submit');
    Route::post('qdbSubmit','RechargeController@qdbSubmit');
    Route::post('reaSubmit','RechargeController@reaSubmit');
    Route::post('umpSubmit','RechargeController@umpSubmit');
    Route::post('bestSubmit','RechargeController@bestSubmit');
    //TODO: 丰付支付
    Route::post('sumaSubmit','RechargeController@sumaSubmit');

    Route::any('return/{platform}/{from}','ReturnController@index');
    Route::any('notice/{platform}','ReturnController@notice');
    Route::get('success/{from}/{cash?}','ReturnController@success');
    Route::get('fail/{from}/{msg?}','ReturnController@fail');

    Route::post('sendSign','AjaxController@sendSign');
    Route::post('sendCode','AjaxController@sendCode');
    Route::post('checkCode','AjaxController@checkCode');

    //Route::get('withdraw','WithdrawController@index');
    //Route::post('withdraw/submit','WithdrawController@submit');
    //Route::get('withdraw/success','WithdrawController@success');
});


/*common*/
Route::get('captcha/{tmp}', 'Common\CaptchaController@create');
Route::post('common/sendCode', 'Common\AjaxController@sendCode');
Route::post('common/checkCode', 'Common\AjaxController@checkCode');
Route::post('common/checkCaptcha', 'Common\AjaxController@checkCaptcha');

//登陆获取 token

//Route::get('login', 'User\LoginController@index');                          // test pc or wap 登陆页面
//Route::get('register', 'User\RegisterController@index');                    // test pc or wap 注册

//Route::post('login/doLogin', 'User\LoginController@doLogin');                     // 登陆
//Route::post('register/doRegister', 'User\RegisterController@doRegister');         // 注册
Route::post('register/stepOne', 'User\RegisterController@stepOne');         // 注册第一步

//Route::post('register/sendSms', 'User\RegisterController@sendSms');         // 发送注册短信验证码

Route::post('login/out', 'User\LoginController@out');  //登出
//Route::get('login/out', 'User\LoginController@out');   //登出


//$app->get('loginProlong', 'User\LoginController@prolongSessionIndex');  // test 延长登陆时间页面


/*回调接收数据*/
Route::any('receive/updateBill', 'Api\ReceiveController@updateBill');

//回款自动进零钱计划事件通知接收入口
//Route::any('receive/autoCurrentInvest', 'Api\ReceiveController@autoCurrentInvest');

//储蓄卡鉴权接口(对外)
Route::post('api/checkDepositCard','Api\CardController@checkDepositCard');

//信用卡/储蓄卡鉴权接口(快金专用)
Route::post('api/checkCard','Api\CardController@checkCard');

//卡bin接口(快金专用)
Route::post('api/fetchCardInfo','Api\CardController@fetchCardInfo');


//swagger
//Route::get('swagger/index', 'Swagger\ApiController@index');
//Route::get('swagger/config', 'Swagger\ApiController@config');

//日志查看
//Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


Route::post('upload/img', 'Common\UploadController@ajaxImgUpload');


/**********对接新系统的api接口路由统一 start *************/

//发送红包
//Route::post('api/send/userBonus', 'Bonus\UserBonusController@doSendApi');


/*todo 张爽 模块对接路由 新系统上线后可以删除掉对应的路由及方法*/

/*
//创建定期项目
Route::post('project/doCreateApi', 'Api\Jdy\ProjectController@apiDoCreate');

//零钱计划转入
Route::post('current/doInvestApi','Api\Jdy\CurrentController@doInvestApi');

//零钱计划转出
Route::post('current/doInvestOutApi','Api\Jdy\CurrentController@doInvestOutApi');

//创建项目
Route::post('current/project/doCreateApi','Api\Jdy\CurrentController@doCreateProjectApi');

//创建零钱计划利率
Route::post('current/rate/doCreateApi','Api\Jdy\CurrentController@doCreateRateApi');

//创建充值订单号
Route::post('order/recharge/doCreateApi','Api\Jdy\OrderController@doCreateApi');

//支付成功修改订单状态
Route::post('order/recharge/doSuccApi','Api\Jdy\OrderController@doSuccApi');

//支付失败修改订单状态
Route::post('order/recharge/doFailedApi','Api\Jdy\OrderController@doFailedApi');


//创建提现订单
Route::post('pay/withdraw/submitApi','Api\Jdy\OrderController@submitApi');

//定期投资
Route::post('invest/term/submitApi','Api\Jdy\TermController@submitApi');

Route::post('project/doInvestApi','Api\Jdy\InvestController@project');

//提现自动对账
Route::post('withdraw/matchBill','Api\Jdy\WithdrawBillController@matchBill');

*/
/*todo 结束*/

/* todo 贺兴 数据对接 开始 */
// 注册
/*
Route::post('api/jdy/register/doRegister', 'Api\Jdy\UserController@doRegister');

// 实名
Route::post('api/jdy/user/setting/doVerify', 'Api\Jdy\UserController@doVerify');

// 修改手机号
Route::post('api/jdy/user/setting/phone/modify', 'Api\Jdy\UserController@doModifyPhone');

// 修改密码
Route::post('api/jdy/user/setting/doPassword', 'Api\Jdy\UserController@doPassword');

// 修改交易密码
Route::post('api/jdy/user/setting/doTradingPassword', 'Api\Jdy\UserController@doTradingPassword');

// 后台创建红包
Route::post('api/jdy/admin/bonus/doCreate' , 'Api\Jdy\BonusController@PostCreate');

// 后台编辑红包
Route::post('api/jdy/admin/bonus/doUpdate', 'Api\Jdy\BonusController@PostUpdate');
*/

/* todo 贺兴 数据对接 结束 */
/*用户实名+绑卡*/
//Route::post('user/setting/verifyApi','Api\Jdy\UserController@verify');  // 贺兴

//电视墙接口
Route::post('api/tvShow', 'Api\OfficeTv\OfficeTvController@index');  //电视墙首页数据
Route::post('api/tvShow/defaultData', 'Api\OfficeTv\OfficeTvController@defaultData');  //电视墙默认数据
/**
 * 入口
 */
Route::any('app/gateway','App\GatewayController@index');
/**********对接新系统的api接口路由统一 end *************/


//合同测试
//Route::post('contract', 'App\User\ContractController@contract');

//网站地图
Route::get('robots.txt', 'SiteMapController@robots');
Route::get('sitemap/{mapkey}/{childMapkey}.xml', 'SiteMapController@getChildSiteMap');
//Route::get('sitemap.xml', 'SiteMapController@index');
