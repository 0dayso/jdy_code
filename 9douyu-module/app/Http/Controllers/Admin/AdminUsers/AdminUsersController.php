<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/31
 * Time: 下午7:58
 */

namespace App\Http\Controllers\Admin\AdminUsers;

use App\Http\Controllers\Controller;
use App\Http\Logics\AdminUsers\AdminUsersLogic;
use App\Tools\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth;

class AdminUsersController extends Controller
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 修改密码页面
     */
    public function resetPassword()
    {
        return view('admin.user.updatePassword');
    }

    /**
     * @param Request $request
     * @return mixed
     * @desc 执行修改密码
     */
    public function doResetPassword(Request $request)
    {
        $userId = AdminUser::getAdminUserId();

        $oldPwd = $request->input('old_password','');
        $newPwd = $request->input('new_password','');
        $secondPwd = $request->input('second_password','');

        $logic = new AdminUsersLogic();
        $res   = $logic -> resetPassword($userId, $oldPwd, $newPwd, $secondPwd);

        if($res['status']){

            Auth::guard('admin')->logout();

            return redirect('/admin/login?sign='.env("LOGIN_SIGN"))->with('message',$res['data']);

        }

        return redirect()->back()->with('fail',$res['msg']);

    }

}