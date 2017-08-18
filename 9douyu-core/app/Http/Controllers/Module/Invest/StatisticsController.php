<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 20:23
 */
namespace App\Http\Controllers\Module\Invest;

use App\Http\Controllers\Controller;
use App\Http\Logics\Invest\InvestLogic;
use App\Http\Logics\Module\Invest\StatisticsLogic;
use App\Http\Logics\Refund\RefundRecordLogic;
use Illuminate\Http\Request;

class StatisticsController extends Controller{



    /**
     * @SWG\Post(
     *   path="/getHomeStatistics",
     *   tags={"Project"},
     *   summary="获取首页平台统计数据明细",
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
     *     description="获取首页平台统计数据明细成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取首页平台统计数据明细失败。",
     *   )
     * )
     */
    public function index(){
        
        $logic  = new StatisticsLogic();
        $result = $logic->getStatistics();


        return self::returnJson($result);
    }

    /**
     * @SWG\POST(
     *  path="/invest/getNewInvest",
     *  tags={"Project"},
     *  summary="获取最新投资记录",
     *  @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="个数",
     *      required=false,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取投资记录成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取投资记录失败。",
     *   )
     * )
     */
    public function getInvestNew(Request $request){

        $size  = $request->input('size');

        $size  = empty($size) ? 0 : $size;

        $logic = new InvestLogic();

        $list  = $logic->getInvestNew($size);

        self::returnJson($list);
    }

    /**
     * @SWG\POST(
     *  path="/invest/getInvestAmountByDate",
     *  tags={"Project"},
     *  summary="根据日期获取投资总额列表",
     *  @SWG\Parameter(
     *      name="start",
     *      in="formData",
     *      description="开始日期，如：Y-m-d",
     *      required=false,
     *      type="string"
     *   ),
     *  @SWG\Parameter(
     *      name="end",
     *      in="formData",
     *      description="结束日期，如：Y-m-d",
     *      required=false,
     *      type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取投资总额记录成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取投资总额记录失败。",
     *   )
     * )
     */
    public function getInvestAmountByDate(Request $request){

        $startDate = $request->input('start');

        $endDate   = $request->input('end');

        $logic     = new InvestLogic();

        $list      = $logic->getInvestAmountByDate($startDate,$endDate);

        self::returnJson($list);
    }

    /**
     * @SWG\POST(
     *  path="/invest/getTermInvestTotal",
     *  tags={"Project"},
     *  summary="根据开始结束日期获取投资总额",
     *  @SWG\Parameter(
     *      name="start",
     *      in="formData",
     *      description="开始日期，如：Y-m-d",
     *      required=false,
     *      type="string"
     *   ),
     *  @SWG\Parameter(
     *      name="end",
     *      in="formData",
     *      description="结束日期，如：Y-m-d",
     *      required=false,
     *      type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取投资总额记录成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取投资总额记录失败。",
     *   )
     * )
     */
    public function getInvestTermTotal( Request $request){

        $startDate = $request->input('start');

        $endDate   = $request->input('end');

        $logic     = new InvestLogic();

        $cash      = $logic->getInvestTermTotal($startDate,$endDate);

        self::returnJson(['cash'=>$cash]);

    }
}