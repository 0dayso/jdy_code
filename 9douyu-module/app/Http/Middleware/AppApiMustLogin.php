<?php

namespace App\Http\Middleware;

use App\Http\Logics\AppLogic;
use App\Http\Logics\User\SessionLogic;

use Closure;


/**
 * app4.0+ 注册需要登陆后可访问的中间件
 *
 * Class AppApiMustLogin
 * @package App\Http\Middleware
 */
class AppApiMustLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session = SessionLogic::getTokenSession();

        \Log::info('app4.0+ AppApiMustLogin', ['path'=> $request->path(), 'data'=> $session]);

        if(empty($session)){

            return AppLogic::callError(AppLogic::CODE_NO_USER_ID);

        }
        return $next($request);
    }
}
