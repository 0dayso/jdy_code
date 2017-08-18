<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\Controller;
use App\Http\Logics\AdminUsers\AdminUsersLogic;
use App\Tools\ToolStr;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Validator;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $guard = 'admin';

    protected $broker = 'admin_users';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 召回密码
     */
    public function forgetPassword(Request $request)
    {
        $this->verifySign( $request->input('sign') );

        return view('admin.auth.passwords.forget');
    }

    /**
     * @param $sign
     * @desc 验证sign
     */
    protected function verifySign( $sign )
    {
        if( $sign != env("LOGIN_SIGN")  &&  env('APP_ENV') == 'production') {

            header("Content-type: text/html; charset=utf-8");

            exit('拒绝非法访问！');
        }
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    
    public function sendEmailPassword(Request $request)
    {
        $email      =   $request->input('email','');
        
        $verify     =   $request->input('verify','');


        $logic      =   new AdminUsersLogic();

        $return     =   $logic->sendEmailPassword($email,$verify);

        if($return['status']){

            return redirect('/admin/login?sign='.env("LOGIN_SIGN"))->with('message',$return['data']);
        }

        return redirect()->back()->withInput($request->input())->with('message',$return['msg']);
    }

}
