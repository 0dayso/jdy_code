<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Logics\AdminUsers\AdminUsersLogic;
use App\Http\Dbs\AdminUsers\AdminUsersDb;
use App\Http\Models\AdminUsers\AdminUsersModel;
use App\Http\Models\Common\PasswordModel;
use App\Models\AdminUser;
use App\Tools\AdminUser as Admin;
use App\Tools\ToolTime;
use Maatwebsite\Excel\Classes\Cache;
use Validator;
use App\Http\Controllers\Admin\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Auth;
use Session;
use Hash;

class AuthController extends Controller
{

    private $salt1 = "^^*&%^$^%$^%HKJGd)(*)*_)*^*&%FUGHGKL987FJ_HJGGUYT";
    private $salt2 = "aa*&%^$^%$^%HKJGd)(*)*_)*kfhalsfhalsfhawoiryqw79812491231YTaa";

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectPath = '';

    protected $redirectAfterLogout = '/admin/login';

    protected $guard = 'admin';

    protected $loginView = 'admin.auth.login';

    protected $registerView = 'admin.auth.register';


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectPath = route('admin.home');
        $this->redirectAfterLogout = route('admin.login');
        $this->middleware('guest:admin', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admin_users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return AdminUser::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return '账号或密码错误';
    }

    protected function getLockoutErrorMessage() {
        return '错误次数过多已锁定，请联系管理员';
    }

    /**
     * 密码错误次数过多锁定时间
     * @return int
     */
    protected function lockoutTime() {
        return 300000; //秒
    }

    public function verifyLogin(Request $request)
    {

        $data = $request->all();

        $loginSign =  $data['sign'];

        if( $loginSign != env('LOGIN_SIGN') && env('APP_ENV') == 'production' ){

            header("Content-type: text/html; charset=utf-8");

            exit('拒绝非法访问！');

        }

        //取消验证码
        /*if( !$this->checkCaptcha($data['captcha']) ){

            return redirect()->back()->with('message','验证码错误!');

        }*/

        $this->verifyOldUser($data);

        //提前加了一层登录验证,用来判断用户是否锁定
        $this->verifyUserLock($request);

        $loginRes = $this->login($request);

        if(!empty(Auth::guard('admin')->user()) && ($request->input('verify') != Auth::guard('admin')->user()->verify)) {

            Auth::guard($this->getGuard())->logout();

            return redirect()->back()->with('message','工号错误!');

        }

        $this->setAdminUserStatus();
        $this->checkUserUpdatePwd();

        return $loginRes;

    }

    /**
     * @param array $data
     * @return string
     * @desc 检测旧版用户登录密码
     */
    private function verifyOldUser($data=[])
    {

        $email = isset($data['email']) ? $data['email'] : '';

        $password = isset($data['password']) ? $data['password'] : '';

        $userModel = new AdminUser();

        $userInfo = $userModel->where('email', $email)->first();

        if( empty($userInfo) || !isset($userInfo['password']) ){

            return '';

        }

        $checkResult = $this->checkPassword($password, $userInfo['password']);

        if( $checkResult ){

            $md5Password = bcrypt($password);

            return $userModel->where('id', $userInfo['id'])->update(['password' => $md5Password]);

        }

    }


    /**
     * @param $password
     * @param $encryptPassword
     * @return bool
     * @desc 检测密码是否正确
     */
    public function checkPassword($password, $encryptPassword){

        $allPasswd  = $this->encryptPassword($password);

        $passwd1    = $allPasswd["passwd1"];

        $passwd2    = $allPasswd["passwd2"];

        $passwd     = $allPasswd["passwd"];

        $encrpt1    = substr($encryptPassword, 0, 32);

        $encrpt2    = substr($encryptPassword, -32);

        if($passwd == $encryptPassword && $passwd1 == $encrpt1 && $passwd2 == $encrpt2 && $encrpt1 == hash("md5", $encrpt2 . $this->salt2)){

            return true;

        }else{

            return false;

        }

    }

    /**
     * @param $password
     * @return array
     * @desc 获取加密密码
     */
    protected function encryptPassword($password){

        $encryptPassword2 = hash("md5", $password . $this->salt1);

        $encryptPassword1 = hash("md5", $encryptPassword2 . $this->salt2);

        $encryptPassword  = $encryptPassword1 . $encryptPassword2;

        return ["passwd1" => $encryptPassword1, "passwd2" => $encryptPassword2, "passwd" => $encryptPassword];

    }

    /**
     * @param $captcha
     * @return bool
     * @desc 检测验证码
     */
    protected function checkCaptcha($captcha){

        if( empty($captcha) ){

            return false;

        }

        if( $captcha == Session::get('captcha')){

            Session::forget('captcha');

            return true;

        }

        return false;

    }

    /**
     * 用户登录成功后记录_token 缓存
     * 记录当前登录用户的唯一性标示
     */
    protected function setAdminUserStatus()
    {
        $adminId    =   Admin::getAdminUserId();

        if($adminId || $adminId !=0){

            $_token     =   md5($adminId.time());

            \Session::put('admin_token',$_token);

            $statusKey  =   Admin::LOGIN_STATUS.$adminId;

            $actionTime =   Admin::ACTION_OUTTIME.$adminId;

            if(\Cache::get($statusKey)){

                \Cache::forget($statusKey);
                \Cache::forget($actionTime);
            }

            \Cache::put($statusKey,$_token,AdminUsersLogic::getManagerLoginStatusMaxTime() );
            \Cache::put($actionTime,rand(),AdminUsersLogic::getManagerNothingActionMaxTime() );
        }

    }

    /**
     * @throws \Exception
     * @desc 用户首次登陆或者3个月未修改密码时,强行修改密码
     */
    protected function checkUserUpdatePwd(){

        $userId = Admin::getAdminUserId();

        if($userId != 0){

            $model = new AdminUsersModel();

            $userInfo = $model->getUserInfoById($userId);

            $now = ToolTime::dbNow();

            $userResetTime = isset($userInfo['reset_time']) ? $userInfo['reset_time'] : $userInfo['updated_at'];

            $diffDays = ToolTime::getDayDiff($userResetTime, $now);

            $statusKey = Admin::UPDATE_PWD_STATUS.$userId;

            //用户未修改天数大于30天
            if( $diffDays >= AdminUsersLogic::getResetPasswordTime() ){

                \Cache::forever($statusKey,1);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    protected function verifyUserLock(Request $request){

        $email = $request->input('email','');
        $password = $request->input('password','');

        $model = new AdminUsersModel();
        $userInfo = $model->getUserInfoByEmail($email);

        $pwdModel = new PasswordModel();

        if(\Cache::has(AdminUsersDb::LOCK_KEY.$email)){
            return redirect()->back()->with('message',self::getLockoutErrorMessage() );
        }

        if(!$pwdModel->validateAdminPassword($password,$userInfo['password'],true)){
            $errorNum = is_null(\Cache::get(Admin::MANAGER_LOCK_INFO.$email)) ? 1 : \Cache::get(Admin::MANAGER_LOCK_INFO.$email)+1;
            \Cache::put(Admin::MANAGER_LOCK_INFO.$email,$errorNum,AdminUsersLogic::getManagerLoginErrorTime() );

            $maxErrotTimes  =   AdminUsersLogic::getManagerLoginErrorMaxLimit() ;

            if(\Cache::has(AdminUsersDb::LOCK_KEY.$email) || $errorNum >= $maxErrotTimes){
                \Cache::put(AdminUsersDb::LOCK_KEY.$email,1,AdminUsersLogic::getManagerLockTime() );//锁定2小时
                return redirect()->back()->with('message',self::getLockoutErrorMessage() );
            }

            $num = $maxErrotTimes - $errorNum;
            return redirect()->back()->with('message','登录失败，还有'.$num.'次机会!');
        }

        \Cache::forget(Admin::MANAGER_LOCK_INFO.$email);
    }

}
