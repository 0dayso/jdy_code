<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/22
 * Time: 14:21
 */

namespace App\Http\Controllers\Pc\Current;

use App\Http\Controllers\Pc\UserController;
use App\Http\Logics\Current\CreditLogic;

class CreditController extends UserController{

    /**
     * @SWG\Post(
     *   path="/current/viewCredit",
     *   tags={"Current"},
     *   summary="零钱计划用户查看债权信息 [User\CurrentController@viewCredit]",
     *   @SWG\Response(
     *     response=200,
     *     description="零钱计划用户查看债权信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="零钱计划用户查看债权信息失败。",
     *   )
     * )
     */
    public function view(){


        $userId = self::getUserId();

        $result = CreditLogic::viewCredit($userId);

        return self::returnJson($result);
    }
    
}