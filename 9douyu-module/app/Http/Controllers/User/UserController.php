<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Dbs\Project\ProjectDb;
use Illuminate\Http\Request;

/**
 * 用户模块基础类
 * Class UserController
 * @package App\Http\Controllers\User
 */
class UserController extends BaseController
{

    public function index()
    {
        $userId = $this -> getUserId();

        $logic = new UserLogic();

        $userInfo = $logic -> getUser($userId);

        //用户资产
        $userAccount = $logic -> getUserInfoAccount($userId);

        $projectJsx  = $userAccount['project']['product_line'][ProjectDb::PROJECT_PRODUCT_LINE_JSX];
        $projectJax  = $userAccount['project']['product_line'][ProjectDb::PROJECT_PRODUCT_LINE_JAX];
        $projectSdf  = $userAccount['project']['product_line'][ProjectDb::PROJECT_PRODUCT_LINE_SDF];

        $userAccount['project']['total_amount'] = $projectJsx['interest'] + $projectJsx['principal'] + $projectJax['interest'] + $projectJax['principal'] + $projectSdf['principal'];
        $userAccount['project']['total_amount_principal'] = $projectJsx['principal'] + $projectJax['principal'] + $projectSdf['principal'];
        $userAccount['project']['total_amount_interest']  = $projectJsx['interest'] + $projectJax['interest'];

        $userInfo['total_amount'] = $userAccount['current']['cash'] + $userInfo['balance'] + $userAccount['project']['total_amount'];

        //可用优惠券
        $totalBonus    = $logic -> getUserTotalBonus($userId);

        $viewData = [
            'user_info'         => $userInfo,
            'total_bonus'       => $totalBonus,
            'current_account'   => empty($userAccount['current'])?0:$userAccount['current'],
            'project_account'   => $userAccount['project'],
            'project_jsx'       => $projectJsx ?:[],
            'project_jax'       => $projectJax ?:[],
            'project_sdf'       => $projectSdf ?:[],
        ];

        return view('pc.user.user', $viewData);

    }

}
