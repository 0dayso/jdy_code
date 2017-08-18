<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/8/30
 * Time: 下午3:17
 */

namespace App\Http\Controllers\ThirdApi;

use App\Http\Logics\Logic;
use App\Http\Logics\RequestSourceLogic;

use App\Http\Logics\ThirdApi\JyfLogic;

use App\Http\Logics\User\RegisterLogic;

use App\Http\Logics\Weixin\BindLogic;

use App\Http\Models\Activity\ActivityFundHistoryModel;
use App\Tools\DesUtils;

use Illuminate\Support\Facades\Redirect;

use Session, Log;

class JyfController extends ApiController
{

    /**
     * 通过openid 获取用户信息
     * ['user_id' => '']
     */
    public function getUserInfo(){
        $request       = app('request');

        $openId        = $request->input('open_id');

        $logicReturn   = JyfLogic::getUserInfoByOpenId($openId);

        return self::returnJson($logicReturn);
    }


    /**
     * @desc 注册流程处理
     */
    public function doRegister(){
        $request       = app('request');

        $phone         = $request->input('phone');
        //注册信息搜集
        $data =[
            'request_source'            => RequestSourceLogic::SOURCE_WAP,
            'phone'                     => $phone,                                                 // 手机号
            'password'                  => $request->input('password'),                            // 密码
            'phone_code'                => $request->input('code'),                                // 手机验证码
            'aggreement'                => $request->input('aggreement', 1),                       // 注册协议
            'invite_phone'              => $request->input('invite_phone'),                        // 邀请手机号
        ];

        $openId = $request->input('open_id');

        $registerLogic         = new RegisterLogic();
        $logicRegisterReturn   = $registerLogic->doRegister($data);

        Log::info(__METHOD__, $logicRegisterReturn);

        if($logicRegisterReturn['status']){
            BindLogic::bind($phone, $openId, $logicRegisterReturn['data']['coreApiData']['id']);
        }

        return self::returnJson($logicRegisterReturn);
    }

    /**
     * 注册验证码发送
     */
    public function sendRegisterSms(){
        $request        = app('request');
        $phone          = $request->input('phone');

        $registerLogic = new RegisterLogic;
        $logicResult   = $registerLogic->sendRegisterSms($phone);

        if(!$logicResult['status']){
            if(strpos($logicResult['msg'] ,'已注册')){
                $logicResult['code'] = 'registered';
            }
        }
        return self::returnJson($logicResult);
    }

    /**
     * 注册成功 发送密码短信
     */
    public function sendRegisterSucceedSms(){
        $request        = app('request');
        $phone          = $request->input('phone');
        $password       = $request->input('password');

        $logicResult   = JyfLogic::sendRegisterSucceedSms($phone, $password);

        return self::returnJson($logicResult);
    }


    /**
     * 用户余额加钱
     */
    public function addAmount(){
        $request       = app('request');
        $param         = $request->all();
        $logicResult   = JyfLogic::balanceAdd($param);

        return self::returnJson($logicResult);
    }


    /**
     * 结算对账数据抓取
     *
     * @return string
     */
    public function getYmfReconciliation(){
        $request       = app('request');
        $param         = $request->all();
        $logicResult   = JyfLogic::getYmfReconciliation($param);

        return self::returnJson($logicResult);
    }


    /**
     * 发送模板消息
     *
     * @return string
     */
    public function sendTemplateMessage(){
        $request       = app('request');
        $param         = $request->all();
        $logicResult   = JyfLogic::sendTemplateMessage($param);

        return self::returnJson($logicResult);
    }

    /**
     * 获取微信token
     *
     * @return string
     */
    public function getWechatToken(){
        $wechat        = app('wechat');
        $accessToken   = $wechat->access_token;
        $token         = $accessToken->getToken();
        $js            = $wechat->js;
        $ticket        = $js->ticket();

        $logicResult   = Logic::callSuccess(['token'=> $token, 'ticket'=> $ticket]);

        return self::returnJson($logicResult);
    }


}