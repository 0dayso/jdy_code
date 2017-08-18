<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/12
 * Time: 下午6:19
 * Desc: 零钱计划项目
 */

namespace App\Http\Controllers\Project\Invest;

use App\Http\Controllers\Controller;
use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Logics\Logic;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;


class CurrentController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/current/invest",
     *   tags={"Current"},
     *   summary="零钱计划项目转入",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *     @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *   ),
     *     @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="投资金额",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="投资零钱计划项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="投资零钱计划项目失败。",
     *   )
     * )
     */
    public function invest(Request $request)
    {

        $userId = (int)$request->input('user_id');

        $cash = $request->input('cash');

        $cash = ToolMoney::formatDbCashAdd($cash);

        $logic = new CurrentLogic();

        $return = $logic->invest($userId, $cash);

        if( $return['status'] ){

            $returnJson = Logic::callSuccess($return['data']);

        }else{

            $returnJson = Logic::callError($return['msg'], $return['code'], $return['data']);

        }

        self::returnJson($returnJson);

    }

    /**
     * @SWG\Post(
     *   path="/current/investOut",
     *   tags={"Current"},
     *   summary="零钱计划项目转出",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *     @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *   ),
     *     @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="转出金额",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="转出成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="转出失败。",
     *   )
     * )
     */
    public function investOut( Request $request )
    {

        $userId = (int)$request->input('user_id');

        $cash = (float)$request->input('cash');

        $cash = ToolMoney::formatDbCashAdd($cash);

        $logic = new CurrentLogic();

        $return = $logic->investOut($userId, $cash);

        if( $return['status'] ){

            $returnJson = Logic::callSuccess($return['data']);

        }else{

            $returnJson = Logic::callError($return['msg']);

        }

        self::returnJson($returnJson);

    }

}