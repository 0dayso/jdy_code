<?php
/**
 * User: caelyn
 * Date: 16/7/5
 * Time: 上午10:42
 *
 */
namespace App\Http\Controllers\Weixin\Family;

use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Family\FamilyLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\SmsModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Http\Request;
use App\Lang\LangModel;
use App\Tools\FamilyAuth;
use App\Http\Dbs\User\InviteDb;
use Redirect;
use Session;

class FamilyController extends WeixinController
{

    protected $isLogin = false;

    protected $userId;

    protected $client;

    public function __construct(){

        parent::__construct();

        $this->client = RequestSourceLogic::getSource();

        $this->userId = $this->getUserId();
        //设置安卓的cookie
        if($this->client == 'android' && $this->userId ){
            $request = app('request');
            $token = strtolower($request->input('token'));
            $logic = new PartnerLogic();
            $logic->setCookieAndroid($token, $this->client);
        }
        if($this->userId>0) {

            $this->isLogin = true;
        }
    }

    /**
     * [是否登录跳转]
     */
    protected function isLogin(){

        if($this->isLogin===false){

            return Redirect::to('family/home')->send(); 
        }
    }

    /**
     * [家庭账户首页]
     */
    public function home(){
        Session::put("CLIENT", $this->client);
        //先刷新用户授权登录状态
        FamilyAuth::refreshAuthLoginStatus($this->userId);
        //已经有授权用户，不需要进入介绍页
        if(FamilyLogic::hasAuthAccount($this->getUserId())) {

            return Redirect::to('family/accountList');
        }
        return view('wap.family.home',['isLogin'=>$this->isLogin,'client'=>$this->client]);
    }

    /**
     * [选择为谁开通家庭账户]
     */
    public function forWho(){

        //刷新用户授权登录状态
        FamilyAuth::refreshAuthLoginStatus($this->userId);

        $this->isLogin();

        $familyLogic = new FamilyLogic();

        $hotAccount = $familyLogic->getHotAccount();

        return view('wap.family.forWho',['hotAccount'=>$hotAccount]);

    }

    /**
     * [选择更多角色]
     */
    public function more(){

        $this->isLogin();

        $val = SystemConfigModel::getConfig('FAMILY_ACCOUNT_TAG.MORE');

        $family = explode(',',$val);

        $roles = array_chunk($family, 4);

        return view('wap.family.more',['familyRoles'=>$roles]);
    }


    /**
     * [填写手机号]
     * @param  [string] $role [角色名称]
     */
    public function phone($role){

        $this->isLogin();

        if(empty($role)) {

            return Redirect::to('family/forWho')->with('error','请选择角色');
        }

        Session::put("FAMILY_ROLE", $role);

        $leftTime = SmsModel::getSendCodeLeftTime();

        return view('wap.family.phone',[
            'familyRole'    =>$role,
            'client'        =>Session::get("CLIENT"),
            'leftTime'      =>$leftTime,
        ]);
    }

    /**
     * 家庭账户自定义发送验证码短信
     */
    public function sendCode(Request $request){

        if($this->isLogin===false){

            return  ['status'=>false,'msg'=>LangModel::getLang('ERROR_FAMILY_LOGOUT')];
        }

        $user       = $this->getUser();

        $phone      = $request->input('phone');

        $type       = strtoupper($request->input('type'));

        $code       = SmsModel::getRandCode();

        SmsModel::setPhoneVerifyCode($code,$phone);

        $sendName = !empty($user['real_name']) ? $user['real_name'] : $user['phone'];

        $msg        = sprintf(LangModel::getLang('PHONE_VERIFY_CODE_'.$type),$code,$sendName);

        \Log::info('FamilyVerifyCode:手机号,'.$phone.',短信内容'.$msg);

        $result     = SmsModel::verifySms($phone,$msg);                                               

        return $result;
    }

    /**
     * 检查验证码;该号码是否是自己的账号
     */
    public function checkPhoneVerify(Request $request) {

        if($this->isLogin===false){

            return  ['status'=>false,'msg'=>LangModel::getLang('ERROR_FAMILY_LOGOUT')];
        }

        $user       = $this->getUser();

        $code       = $request->input('code');

        $phone      = $request->input('phone');

        $res        = SmsModel::checkPhoneCode($code,$phone);

        if($res['status'] && $user['phone']==$phone){

            $res    = ['status'=>false,'msg'=>LangModel::getLang('ERROR_FAMILY_ADD_SELF')];

        }
        return $res;
    }

    /**
     * [创建家庭用户]
     * @return [redirect] [跳转验卡]
     */
    public function doPostPhone(Request $request){

        $this->isLogin();

        $phone = $request->input('phone');

        $familyRole = Session::get("FAMILY_ROLE") ? Session::get("FAMILY_ROLE") : $request->input('familyRole');

        $familyLogic = new FamilyLogic();

        $result      = $familyLogic->addFamily($this->userId,$phone,$familyRole,$this->client);

        if($result['status']){

            if($result['data']['isBind']){

                return Redirect::to('family/accountList');
            }
            return Redirect::to('family/verifyIdentity');
        }else{

            return Redirect::to('family/phone/'.$familyRole)->with('error',$result['msg'])->withInput();
        }
    }

    /**
     * 验证身份页面
     */
    public function verifyIdentity(){

        $this->isLogin();

        $familyId   = Session::get("FAMILY_ID");

        $familyRole = Session::get("FAMILY_ROLE");

        if(empty($familyId) || empty($familyRole)) {

            return Redirect::to('family/forWho')->with('error',LangModel::getLang('ERROR_FAMILY_PARAM_LESS'));

        }
        return view('wap.family.verifyIdentity',['familyRole'=>$familyRole]);
    }


    /**
     * 身份验证,添加授权关系
     */
    public function doVerify(Request $request){

        if($this->isLogin===false){

            return  ['status'=>false,'msg'=>LangModel::getLang('ERROR_FAMILY_LOGOUT')];
        }

        //实名认证
        $familyName     = $request->input('real_name');

        $familyId       = Session::get("FAMILY_ID");

        $from           = $this->client;

        $cardNo         = $request->input('card_number');

        $idCard         = $request->input('identity_card');

        $userLogic      = new UserLogic();

        $result         = $userLogic->verify($familyId,$familyName,$cardNo,$idCard,$from);

        $familyRole     = Session::get('FAMILY_ROLE');

        if($result['status']){

            $familyLogic    = new FamilyLogic();

            $result         = $familyLogic->addFamilyRelation($this->userId,$familyId,$familyRole);
            if($result['status']){
                return Redirect::to('family/accountList');
            }else{
                return Redirect::to('family/verifyIdentity')->with('error',$result['msg'])->withInput();
            }
        }else{
            return Redirect::to('family/verifyIdentity')->with('error',$result['msg'])->withInput();
        }

        //return self::returnJson($result);
    }

    /**
     * [家庭账户列表]
     */
    public function accountList(){

        $this->isLogin();

        FamilyAuth::refreshAuthLoginStatus($this->userId);

        $familyLogic = new FamilyLogic();

        $result = $familyLogic->getByMyUid($this->getUserId());

        $userLogic  = new UserLogic();

        $family = [];

        foreach($result as $key=>$vo){

            $data = $userLogic->getAppUserInfo($vo['family_id']);

            $data = $data['data']['items'];

            $family[$key]                   = $vo;

            $family[$key]['currentInvest']  = $data['current_cash'];//tmp data

            $family[$key]['invest']         = $data['doing_invest_amount'];//tmp data
            //tmp data
            $authStr = FamilyAuth::getAuthStr($this->userId, $vo['family_id']);

            $family[$key]['url'] = '/family/loginAuthAccount/'.$authStr;
        }

        $assign = [
            'familyRole' => Session::get('FAMILY_ROLE_SUCCESS'),
            'family'     => $family,
            'client'     => Session::get("CLIENT")
        ];

        FamilyLogic::unsetSession();

        return view('wap.family.accountList',$assign);
    }

    /**
     * [家庭账户自动登录]
     * @param  [string] $authStr [加密串]
     * @return [redirect]
     */
    public function loginAuthAccount($authStr){

        if(!empty($authStr)) {

            $res = FamilyAuth::authLogin($authStr);

            if(!empty($res)) {

                return Redirect::to('user');
            }
        }
        return Redirect::to('family/home')->msg('error',LangModel::getLang('ERROR_FAMILY_AUTH_FAIL'));
    }

    /**
     * [家庭账户退出登录]
     * @param  [type] $authStr [加密穿]
     * @return [array]
     */
    public function logoutAuthAccount(Request $request) {

        $authStr = $request->input('familyAuth');

        $returnArr = ['status' => false, 'msg' => LangModel::getLang('ERROR_FAMILY_AUTH_FAIL')];

        if(!empty($authStr)) {

            $res = FamilyAuth::authLogout($authStr);

            if(!empty($res)) {

                $returnArr = ['status' => true, 'msg' => LangModel::getLang('ERROR_FAMILY_LOGOUT_SUCCESS')];
            }
        }
        Session::save();
        return self::returnJson($returnArr);
    }

    /*
     * 家庭账户活动推广页（新手活动）
     * 内置判断：用户的登录状态，
     */
    public function guide(Request $request){

        $inviteId   = $request->input('invite_id');
        
        $channel    = $request->input('channel');

        $spreadId   = $request->input('spread_id','');//推广ID(新)

        if($spreadId){

            cookie('spread_id',$spreadId);
        }
        //自媒体判断
        if(!empty($inviteId) && $inviteId!=1) {

            cookie("invite_type",InviteDb::TYPE_MEDIA); //自媒体邀请

            cookie("user_type",InviteDb::USER_TYPE_MEDIA);  //邀请类型

            cookie('channel_type',$channel);

            cookie("invite_id",$inviteId);
        }

        $channel = empty($channel) ? '9douyu' : strtolower($channel);

        if($inviteId ==101){

            return view('wap.family.guide-1',['channel'=>$channel]);
        }else {
            $source       = RequestSourceLogic::getSource();

            $downLink     = FamilyLogic::downAppLink($channel,$source);

            return view('wap.family.guide',['channel'=>$channel,'isLogin'=>$this->isLogin,'downLink'=>$downLink]);
        }
    }


    /*
     * app推广页 － 检测手机号码
     */
    public function checkUniquePhone(Request $request) {

        $phone                  = $request->input('phone');

        $redirectUrl            = env('APP_URL_WX')."/family/code";

        $familyLogic            = new FamilyLogic();

        $result                 = $familyLogic->checkUniquePhone($phone,$redirectUrl);

        if(!$result['status']){

            return Redirect::back()->with('errors',$result['msg']);

        }
        return Redirect::to($redirectUrl);
    }

    /*
     * 推广页注册页面
     */
    public function code(){

        $phone                  = Session::get('ACTIVATE_PHONE');

        $inviteId               = cookie("invite_id");

        $channel                = cookie("channel_type");

        $spreadId               = cookie("spread_id");//新推广ID

        $redirectUrl            = env('APP_URL_WX')."/family/guide";

        //组装跳转链接
        if($channel)    $redirectUrl .= "?channel=".$channel;

        if($inviteId)   $redirectUrl .= "&invite_id=".$inviteId;

        if($spreadId)   $redirectUrl .= "&spread_id=".$spreadId;

        if( empty($phone) ) return Redirect::to($redirectUrl);

        $source       = RequestSourceLogic::getSource();

        $assign = [
            'returnUrl'      => "/family/code",

            'downLink'       => FamilyLogic::downAppLink($channel,$source),

            'codeStatus'     => (Session::get('ACTIVATE_CODE_STATUS') == 'fail') ? "failure" : "success",

            'phoneStatus'    => (Session::get('ACTIVATE_PHONE_STATUS') == 'activate') ? "failure" : "success",

            'isLogin'        => $this->isLogin,

            'phone'          => $phone,

            'channel'        => $channel?$channel:"9douyu",

            'leftTime'       => SmsModel::getSendCodeLeftTime()
        ];
        return view('wap.family.code',$assign);
    }

    /**
     * [家庭账户推广注册]
     */
    public function register(Request $request){

        $code       = $request->input('code');

        $phone      = $request->input('phone');

        $res        = SmsModel::checkPhoneCode($code,$phone);

        if(!$res['status']){

           return   $res['msg'];
        }

        $familyLogic   = new FamilyLogic;

        $password         = $request->input('password');

        $return        = $familyLogic->doRegister($phone,$password);

        if($return['status']){

            FamilyLogic::clearInvite();

            return Redirect::to($request->input('returnSuccessUrl'));
        }
        return Redirect::back()->with('errors',$return['msg']);
    }

    /**
     * [家庭账户推广介绍]
     */
    public function intro()
    {

        return view('wap.family.intro');

    }

}