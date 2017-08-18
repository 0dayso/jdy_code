<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/13
 * Time: 下午1:45
 * Desc: 零钱计划模块相关功能
 */

namespace App\Http\Controllers\Module\Refund;

use App\Http\Controllers\Controller;
use App\Http\Logics\Logic;
use App\Http\Logics\Refund\CurrentLogic;
use App\Jobs\Refund\CurrentJob;
use Illuminate\Http\Request;
use Queue;

class CurrentController extends Controller
{


    /**
     * @SWG\Post(
     *   path="/current/refund/doRefundJob",
     *   tags={"Current"},
     *   summary="零钱计划计息拆分触发接口",
     *   @SWG\Parameter(
     *      name="rate",
     *      in="formData",
     *      description="零钱计划利率",
     *      required=true,
     *      type="integer",
     *      default="2",
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
     *     description="加入队列成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="加入队列失败。",
     *   )
     * )
     */
    public function doRefundJob( Request $request )
    {

        $rate = $request->input('rate');

        $logic = new CurrentLogic();

        $return = $logic->doRefundJob($rate);

        self::returnJson($return);

    }


    /**
     * @SWG\Post(
     *   path="/current/refund/getTotalInterest",
     *   tags={"Current"},
     *   summary="获取零钱计划用户总收益",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划用户总收益成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划用户总收益失败。",
     *   )
     * )
     */
    public function getTotalInterest(){

        $logic  = new CurrentLogic();

        $result = $logic->getTotalInterest();

        return self::returnJson($result);
    }



    /**
     * @SWG\Post(
     *   path="/current/getYesterdayInterest",
     *   tags={"Current"},
     *   summary="获取零钱计划用户昨日总收益",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划用户总收益成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划用户总收益失败。",
     *   )
     * )
     */
    public function getYesterdayInterest(){
        
        $logic  = new CurrentLogic();

        $result = $logic->getYesterdayInterest();

        return self::returnJson($result);
    }
}