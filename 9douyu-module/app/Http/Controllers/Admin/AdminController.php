<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\LaravelController as BaseController;
use App\Http\Logics\User\AdminLogLogic;
use App\Tools\AdminUser;
use Illuminate\Http\Request;

/**
 * 后台模块基础类
 * Class AdminController
 * @package App\Http\Controllers\Admin
 */
class AdminController extends BaseController
{
    public function __construct(Request $request)
    {
        $this->middleware('auth.admin');
        $this->middleware('admin.login');
        $this->middleware('auth.checkUpdatePassword');

        $userId = AdminUser::getAdminUserId();

        if($userId != 0){
            $param = [
                'user_id'      => $userId,
                'url'          => $request->path(),
                'http_referer' => isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '',
                'ip'           => isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : \EasyWeChat\Payment\get_client_ip(),
                'data'         => $request->all() ? json_encode($request->all()) : '',
            ];
            $logic = new AdminLogLogic();
            $logic->createRecord($param);
        }

    }

    /**
     * @param $data
     * @return string
     * @desc ajax返回json
     */
    public function ajaxJson($data){

        return json_encode($data);

    }

}
