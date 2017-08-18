<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/21
 * Time: 下午2:15
 */

namespace App\Http\Controllers\Weixin\User;

use App\Http\Controllers\Weixin\UserController;

use App\Http\Logics\User\UserLogic;
use App\Http\Logics\User\SessionLogic;
use App\Http\Logics\User\PasswordLogic;

use Redirect, Illuminate\Http\Request;
/**
 * 用户个人信息设置相关
 *
 * Class InformationController
 * @package App\Http\Controllers\Weixin\User
 */
class InformationController extends UserController
{
    /**
     * 修改密码
     */
    public function modifyLoginPassword(){
        return view('wap.user.information/modifyloginpassword');
    }

    /**
     * 执行修改登录密码
     */
    public function doModifyLoginPassword(Request $request){

        $request   = $request->all();

        $userId    = $this->getUserId();

        $userLogic = new UserLogic();

        $res = $userLogic->changePassword($userId, $request['oldPassword'], $request['password']);

        if(!$res['status']){
            return Redirect::back()->with('msg',$res['msg']);
        }
        return Redirect::to('/user/modifyLoginPasswordSuccess');
    }

    /**
     * 修改登录密码成功
     */
    function modifyLoginPasswordSuccess(){
        return view('wap.user.information.modifyloginpasswordsuccess');
    }


    /**
     * 修改交易密码
     */
    public function modifyTradingPassword(){
        return view('wap.user.information.modifytradingpassword');
    }

    /**
     * @return mixed
     * 设置交易密码
     */
    public function setTradingPassword(){

        return view('wap.user.information.settradingpassword');

    }

    /**
     * @param Request $request
     * @return mixed
     * 设置交易密码逻辑
     */
    public function doSetTradingPassword(Request $request){

        $tradingPassword = $request->input('tradingpassword','');

        $logic = new PasswordLogic();

        $userId = $this->getUserId();

        $res = $logic->setTradingPassword($tradingPassword,$userId);

        if(!$res['status']){
            return Redirect::back()->with('msg', $res['msg']);
        }
        return Redirect::to('/user/setTradingPasswordSuccess');
    }

    /**
     * 执行修改交易密码
     */
    public function doModifyTradingPassword(Request $request){

        $request = $request->all();

        $userLogic = new UserLogic();

        $userId = $this->getUserId();

        $res = $userLogic->changePassword(
            $userId,
            $request['oldPassword'],
            $request['tradepassword'],
            null ,
            'tradingPassword'
        );

        if(!$res['status']){
            return Redirect::back()->with('msg', $res['msg']);
        }
        return Redirect::to('user/modifyTradingPasswordSuccess');
    }

    /**
     * 修改交易密码成功页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modifyTradingPasswordSuccess(){
        return view('wap.user.information/modifytradingpasswordsuccess');
    }

    /**
     * 设置交易密码成功页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setTradingPasswordSuccess(){
        return view('wap.user.information.settradingpasswordsuccess');
    }

    /**
     * 安全中心首页
     */
    public function index(){
        $passwordLogic = new PasswordLogic();
        //判断用户数否登陆
        $userId = $this->getUserId();
       
        $userInfo         = $this->getUser();  //用户详情
        $userVerifyStatus = $this->getVerifyStatus();  //检测是否实名
        $isSetTradingPassword   = $passwordLogic->checkIsSetTradingPassword($userId);  //检测是否设置交易密码
        $data =[
            'user'      => $userInfo,
            'verifyStatus'  => $userVerifyStatus,
            'isSetPassword'  => $isSetTradingPassword['status'],
            'noticeActive'   => 'active'
        ];
        return view('wap.user.information.index',$data);
    }
}