<?php

namespace App\Http\Middleware;

use App\Http\Logics\AdminUsers\AdminUsersLogic;
use App\Tools\AdminUser;
use App\Tools\Helpers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Controllers\Admin\Controller;
use Closure;
use Route,URL,Auth;
class AuthCheckAdminLogin extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $statusKey  =   AdminUser::LOGIN_STATUS.AdminUser::getAdminUserId();

        $actionTime =   AdminUser::ACTION_OUTTIME.AdminUser::getAdminUserId();

        $redirectUrl=   '/admin/login?sign='.env("LOGIN_SIGN");

        $production =   formalEnvironment();

        if( !\Cache::get($actionTime) && $production == true ){

            Auth::guard('admin')->logout();

            return redirect($redirectUrl)->with('message','长时间未操作系统，强制退出，请重新登录系统!');
        }else{

            \Cache::put($actionTime,rand(),AdminUsersLogic::getManagerNothingActionMaxTime() );
        }

        $cacheToken =   \Cache::get($statusKey);

        if( (!$cacheToken || empty($cacheToken) ) && $production == true ){

            Auth::guard('admin')->logout();

            return redirect($redirectUrl)->with('message','您在线的时间长超过系统最大时间，请重新登录系统!');

        }
        $sessionToken   =   \Session::get('admin_token');

        if($sessionToken != $cacheToken && $production == true){

            Auth::guard('admin')->logout();
            //exit('您的账号在别处登录，请确认账号是否异常');
            return redirect($redirectUrl)->with('message','您的账号在别处登录，请确认账号是否异常！');

        }
        return $next($request);
    }
}
