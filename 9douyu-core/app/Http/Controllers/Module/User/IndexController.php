<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/17
 * Time: 下午8:50
 * Desc: 用户账户中心
 */

namespace App\Http\Controllers\Module\User;

use App\Http\Controllers\Controller;
use App\Http\Logics\User\IndexLogic;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/user/modify/password",
     *   tags={"User"},
     *   summary="更新登录密码",
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="password",
     *      in="formData",
     *      description="加密过的密码",
     *      required=true,
     *      type="string",
     *   ),
     *  @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="执行成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="执行失败。",
     *   )
     * )
     */
    public function doModifyPassword( Request $request )
    {

        $userId = $request->input('user_id');

        $password = $request->input('password');

        $logic = new IndexLogic();

        $result = $logic->doModifyPassword($userId, $password);

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/user/modify/tradingPassword",
     *   tags={"User"},
     *   summary="更新交易密码",
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="password",
     *      in="formData",
     *      description="加密过的密码",
     *      required=true,
     *      type="string",
     *   ),
     *  @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="执行成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="执行失败。",
     *   )
     * )
     */
    public function doModifyTradingPassword( Request $request )
    {

        $userId = $request->input('user_id');

        $tradingPassword = $request->input('password');

        $logic = new IndexLogic();

        $result = $logic->doModifyTradingPassword($userId, $tradingPassword);

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/user/frozenAccount",
     *   tags={"User"},
     *   summary="用户实网账户冻结",
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *  @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="3ba7919294c977fea3fb3be18c01eac8",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="执行成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="执行失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @desc 实网冻结
     */
    public function doFrozenAccount( Request $request )
    {

        $userId = $request->input('user_id');

        $logic  = new IndexLogic();

        $result = $logic->doFrozenAccount($userId);

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/user/unFrozenAccount",
     *   tags={"User"},
     *   summary="用户实网账户解冻",
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *  @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="3ba7919294c977fea3fb3be18c01eac8",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="执行成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="执行失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @desc 实网解冻
     */
    public function doUnFrozenAccount( Request $request )
    {

        $userId = $request->input('user_id');

        $logic  = new IndexLogic();

        $result = $logic->doUnFrozenAccount($userId);

        self::returnJson($result);

    }
}