<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/30
 * Time: 上午10:38
 */

namespace App\Http\Middleware;

use App\Tools\AdminUser;
use App\Tools\Helpers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Controllers\Admin\Controller;
use Closure;
use Route,URL,Auth;

class CheckUpdatePassword extends Controller
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    public function handle($request, Closure $next)
    {
        $userId = AdminUser::getAdminUserId();

        $statusKey = AdminUser::UPDATE_PWD_STATUS.$userId;

        $production =   formalEnvironment();

        if(\Cache::has($statusKey) && $production == true){

            return redirect('admin/update_password')->with('fail','请修改密码!');

        }

        return $next($request);
    }
}
