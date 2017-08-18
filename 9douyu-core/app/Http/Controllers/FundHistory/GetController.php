<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 下午2:04
 */

namespace App\Http\Controllers\FundHistory;

use App\Http\Controllers\Controller;

use App\Http\Logics\Fund\FundHistoryLogic;

use Illuminate\Http\Request;

class GetController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/fundHistory/getList",
     *   tags={"User"},
     *   summary="根据事件ID、用户ID、偏移量、每页条数 获取列表数据",
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
     *      default="209c02k29",
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *      default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页量",
     *      required=true,
     *      type="integer",
     *      default="20"
     *   ),
     *   @SWG\Parameter(
     *      name="userId",
     *      in="formData",
     *      description="用户ID",
     *      required=false,
     *      type="integer",
     *      default=""
     *   ),
     *  @SWG\Parameter(
     *      name="typeCode",
     *      in="formData",
     *      description="类型[全部,充值, 提现, 投资, 本息回款, 零钱计划, 活动奖励, 充值提现]",
     *      required=true,
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="getAllEventId",
     *      enum={"getAllEventId", "getRechargeEventId", "getWithdrawEventId", "getInvestEventId", "getRefundEventId", "getCurrentEventId", "getRewardEventId", "getWithdrawAndRechargeEventId"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取资金流水成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取资金流水失败。",
     *   )
     * )
     */
    public function getList(Request $request){

        $data             = $request->all();

        $fundHistoryLogic = new FundHistoryLogic;

        $result           = $fundHistoryLogic->getLists($data);

        self::returnJson($result);


    }

    /**
     * @SWG\Post(
     *   path="/fundHistory/getCurrentList",
     *   tags={"User"},
     *   summary="根据用户ID、偏移量、每页条数 获取列表数据",
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
     *      default="209c02k29",
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *      default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页量",
     *      required=true,
     *      type="integer",
     *      default="20"
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=false,
     *      type="integer",
     *      default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划资金流水成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划资金流水失败。",
     *   )
     * )
     */
    public function getCurrentList(Request $request){

        $userId     = $request->input('user_id',0);
        $page       = $request->input('page',1);
        $size       = $request->input('size',10);
        
        $logic      = new FundHistoryLogic();
        $result     = $logic->getCurrentInvestList($userId,$page,$size);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/fundHistory/getYesterdayCurrentFundData",
     *   tags={"Current"},
     *   summary="根据用户ID、偏移量、每页条数 获取列表数据",
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
     *      default="209c02k29",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划资金流水成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划资金流水失败。",
     *   )
     * )
     */
    public function getYesterdayCurrentFundData(){
        
        $logic      = new FundHistoryLogic();
        $result     = $logic->getYesterdayCurrentFundData();

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/current/getTodayCurrentInvestOutAmount",
     *   tags={"Current"},
     *   summary="获取用户今日已转出总金额",
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
     *      default="209c02k29",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划今日转出总金额成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划今日转出总金额失败。",
     *   )
     * )
     */
    public function getTodayCurrentInvestOutAmount(){
        
        $logic      = new FundHistoryLogic();
        $result     = $logic->getTodayCurrentInvestOutAmount();

        return self::returnJson($result);
        
    }

    /**
     * @SWG\Post(
     *   path="/current/getTodayCurrentInvestAmount",
     *   tags={"Current"},
     *   summary="获取用户今日已转入总金额",
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
     *      default="209c02k29",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划今日转入总金额成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划今日转入总金额失败。",
     *   )
     * )
     */
    public function getTodayCurrentInvestAmount(){

        $logic      = new FundHistoryLogic();
        $result     = $logic->getTodayCurrentInvestAmount();

        return self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/current/getTodayAutoInvestCurrentTotalByUserId",
     *   tags={"Current"},
     *   summary="获取用户id获取今日自动转入金额总数",
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
     *      default="209c02k29",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=false,
     *      type="integer",
     *      default="1"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="失败。",
     *   )
     * )
     */
    public function getTodayAutoInvestCurrentTotalByUserId(Request $request){

        $userId = $request->input('user_id', 0);

        $logic = new FundHistoryLogic();

        $result = $logic->getTodayAutoInvestCurrentTotalByUserId($userId);

        return self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/current/getPlatformTodayAutoInvestCurrentTotal",
     *   tags={"Current"},
     *   summary="根据时间获取平台自动转入零钱计划金额总数",
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
     *      default="209c02k29",
     *   ),
     *   @SWG\Parameter(
     *      name="date",
     *      in="formData",
     *      description="日期可选",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="失败。",
     *   )
     * )
     */
    public function getPlatformTodayAutoInvestCurrentTotal(Request $request)
    {

        $date = $request->input('date', '');

        $logic = new FundHistoryLogic();

        $result = $logic->getTodayAutoInvestCurrentTotal($date);

        return self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/fundHistory/getChangeCashGroupByEventId",
     *   tags={"User"},
     *   summary="根据事件类型分组进行数据统计",
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
     *      default="209c02k29",
     *   ),
     *   @SWG\Parameter(
     *      name="date",
     *      in="formData",
     *      description="日期可选",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="失败。",
     *   )
     * )
     */
    public function getChangeCashGroupByEventId(Request $request)
    {

        $date = $request->input('date', '');
        
        $result = FundHistoryLogic::getChangeCashGroupByEventId($date);

        return self::returnJson($result);

    }

    /**
     * @param Request $request
     * @return array
     * @desc 根据时间获取用户自动投资活期的列表信息
     */
    public function getAutoInvestCurrentListByDate( Request $request ){

        $startDate = $request->input('start_date', '');

        $endDate = $request->input('end_date', '');

        $logic = new FundHistoryLogic();

        $result = $logic->getAutoInvestCurrentListByDate($startDate, $endDate);

        return self::returnJson($result);

    }

}