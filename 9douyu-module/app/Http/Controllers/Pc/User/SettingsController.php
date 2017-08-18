<?php
/**
 * Created by PhpStorm.
 * User: caelyn,hexing
 * Date: 16/6/18
 * Time: 下午6:02
 * Desc: 用户中心设置
 */

namespace App\Http\Controllers\Pc\User;

use App\Http\Controllers\Pc\UserController;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\PasswordLogic;
use App\Http\Logics\User\Setting\UserCheckLogic;

use App\Http\Logics\User\SessionLogic;

use App\Http\Logics\User\Setting\CodeLogic;

use App\Http\Logics\User\UserLogic;

use Illuminate\Http\Request;
use Redirect;
use Session;

class SettingsController extends UserController
{

    /**
     * 修改密码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function password()
    {

        return view('pc.user.password');

    }

    /**
     * 修改密码
     *
     * @param Request $request
     * @return mixed
     */
    public function doPassword(Request $request){

        $request = $request->all();

        $userId = $this->getUserId();

        $userLogic = new UserLogic();

        $res = $userLogic->changePassword($userId,$request['oldPassword'],$request['newPassword'],$request['confirmPassword']);

        if(!$res['status']){

            return Redirect::back()->with('errors',$res['msg']);

        }


        return Redirect::to('/user/settings/success');

    }


    /**
     * 设置交易密码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tradingPassword()
    {

        return view('pc.user.setTradingPassword');

    }

    /**
     * @param Request $request
     * @return mixed
     * 设置交易密码
     */
    public function doTradingPassword(Request $request){

        $password = $request->input('password');

        $password2 = $request->input('password2');

        if($password !== $password2){

            return redirect()->back()->withInput($request->input())->with('errors', '两次交易密码不一致');

        }

        //$userLogic = new UserLogic();

        $userId = $this->getUserId();

        $passwordLogic = new PasswordLogic();

        $result = $passwordLogic->setTradingPassword($password,$userId);

        if($result['status']){

            //跳转至交易密码
            return Redirect::to('/user/settings/success');

        }else {

            //返回
            return redirect()->back()->withInput($request->input())->with('errors', $result['msg']);

        }

    }

    /**
     * 修改交易密码
     * @param Request $request
     * @return mixed
     */
    public function doChangeTradingPassword( Request $request )
    {

        $request = $request->all();

        $userLogic = new UserLogic();

        $userId = $this->getUserId();

        $res = $userLogic->changePassword($userId,$request['oldPassword'],$request['newPassword'],$request['confirmPassword'],'tradingPassword');

        if(!$res['status']){

            return Redirect::to('/user/settings/fail')->with('errors',$res['msg']);

        }

        return Redirect::to('/user/settings/success');

    }

    /**
     * 找回交易密码－第一步－验证页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function vaildTradingPassword(){

        $userId = $this->getUserId();

        $userLogic = new UserLogic();
        $user = $userLogic->getUser($userId);

        $assign['phone'] = $user['phone'];
        $assign['identity_card'] = $user['identity_card'];

        return view('pc.user.vaildTradingPassword',$assign);
    }

    /**
     * 找回交易密码－第二步－设置交易密码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function findTradingPassword(Request $request){

        return view('pc.user.findTradingPassword');

    }



    /**
     * 修改成功页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success(){
        //todo 修改成功页模版
        return view('pc.user.success');
    }

    /**
     * 修改失败页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fail(){
        //todo 修改失败页模版
        return view('pc.user.fail');
    }


    /**
     * 修改手机号 第一步 验证交易密码视图
     */
    public function modifyPhoneViewStepOne(){
        return view('pc.user.setting.modifyPhoneStepOne');
    }

    /**
     * 修改手机号 - 验证交易密码
     */
    public function verifyTransactionPassword(Request $request){
        $session = SessionLogic::getTokenSession();
        if(!$session)
            return redirect('/login/index')->with('message', '请先登录');

        $data           = [
            'password'              => $request->input('password'),
            'trading_password'      => $session['trading_password'],
        ];

        $logicReturn = UserCheckLogic::verifyTransactionPassword($data);

        if($logicReturn['status']){
            $userToken =  base64_encode(md5($session['id'] . $session['trading_password'] . $session['phone']));
            return redirect('/user/setting/phone/stepTwo/'.$userToken);
        }else{
            return redirect('/user/setting/phone/stepOne')->with('message', $logicReturn['msg']);
        }
    }


    /**
     * 修改手机号 - 第二步视图 验证手机验证码
     */
    public function modifyPhoneViewStepTwo($token = 0){
        $session = SessionLogic::getTokenSession();

        if(!$session)
            return redirect('/login/index')->with('message', '请先登录');

        $is = UserCheckLogic::verifyTransactionPasswordToken($token, $session);

        if(!$is){
            return redirect('/user/setting/phone/stepOne')->with('message','请先验证交易密码');
        }

        return view('pc.user.setting.modifyPhoneStepTwo', ['token'=> $token]);
    }

    /**
     * 修改手机号- 验证手机号 - 发送验证码
     */
    public function sendSms(Request $request){
        $session = SessionLogic::getTokenSession();

        if(!$session)
            return redirect('/login/index')->with('message', '请先登录');

        $phone = $request->input('phone');

        $codeLogic                = new CodeLogic;
        $logicResult             = $codeLogic->sendPhoneModifySms($phone);

        Session::save();

        return self::returnJson($logicResult);
    }

    /**
     * 修改手机号
     *
     * @param Request $request
     * @return mixed
     */
    public function modifyPhone(Request $request)
    {
            $session = SessionLogic::getTokenSession();
            if (!$session)
                    return redirect('/login/index')->with('message', '请先登录');

            $data = $request->all();
            $data = UserLogic::modifyPhoneFormatInput($data);
            // token 验证
            $is = UserCheckLogic::verifyTransactionPasswordToken($data['token'], $session);

            if (!$is) {
                    return redirect('/user/setting/phone/stepOne')->with('message', '请先验证交易密码');
            }

            $logicResult = UserLogic::modifyPhone($data);

            return self::returnJson($logicResult);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 三要素实名
     */
    public function verify(Request $request){

        return view('pc.user/verifyBindCard');

    }

    /**
     * @SWG\Post(
     *   path="/user/setting/doVerify",
     *   tags={"PC-User"},
     *   summary="三要素实名 [Pc\User\SettingsController@verify]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="姓名",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="id_card",
     *      in="formData",
     *      description="身份证号",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="实名+绑卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="实名+绑卡失败。",
     *   )
     * )
     */
    public function doVerify( Request $request )
    {

        $name       = $request->input('name','');

        $userId     = $this->getUserId();

        $from       = RequestSourceLogic::getSource();

        $cardNo     = $request->input('card_no','');

        $idCard     = $request->input('id_card','');

        $logic      = new UserLogic();

        $result     = $logic->verify($userId,$name,$cardNo,$idCard,$from);

        if($result['status']){

            //跳转至交易密码
            return redirect('/user/setting/tradingPassword')->with('message', '实名认证成功！');

        }else {

            //返回
            return redirect()->back()->withInput($request->input())->with('errors', $result['msg']);
            
        }

    }

        
}
