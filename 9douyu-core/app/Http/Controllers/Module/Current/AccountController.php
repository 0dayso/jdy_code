<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/8
 * Time: 18:38
 * Desc: 零钱计划账户相关
 */
namespace App\Http\Controllers\Module\Current;

use App\Http\Controllers\Controller;
use App\Http\Logics\Module\Current\AccountLogic;
use App\Http\Logics\Module\Current\InterestHistoryLogic;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;

class AccountController extends Controller{

    /**
     * @SWG\Post(
     *   path="/current/getCreditAmount",
     *   tags={"Current"},
     *   summary="获取零钱计划用户债权匹配金额",
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
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划用户债权匹配金额成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划用户债权匹配金额失败。",
     *   )
     * )
     */
    public function getCreditAmount(Request $request){

        $userId = $request->input('user_id',0);
        
        $logic = new AccountLogic();
        
        $result = $logic->getCreditAmount($userId);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/current/getUserInfo",
     *   tags={"Current"},
     *   summary="获取用户零钱计划帐户信息",
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
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户零钱计划帐户信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户零钱计划帐户信息失败。",
     *   )
     * )
     */
    public function getUserInfo(Request $request){

        $userId = $request->input('user_id',0);

        $logic = new AccountLogic();

        $result = $logic->getUserInfo($userId);
        
        return self::returnJson($result);
    }



    /**
     * @SWG\Post(
     *   path="/current/userFund",
     *   tags={"Current"},
     *   summary="用户中心零钱计划资产页面",
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
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划用户信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划用户信息失败。",
     *   )
     * )
     */
    public function userFund(Request $request){
        
        $userId     = $request->input('user_id',0);     //用户ID
        $page       = $request->input('page',1);        //页码
        $size       = $request->input('size',5);        //每页显示记录数


        $logic  = new AccountLogic();
        
        $result = $logic->getUserFund($userId,$page,$size);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/current/userNum",
     *   tags={"Current"},
     *   summary="零钱计划投资总人数",
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
     *     description="获取零钱计划投资总人数成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划投资总人数失败。",
     *   )
     * )
     */
    public function userNum(){

        $logic  = new AccountLogic();

        $result = $logic->getUserNum();

        return self::returnJson($result);
    }



    /**
     * @SWG\Post(
     *   path="/current/userTodayInvestOutAmount",
     *   tags={"Current"},
     *   summary="零钱计划用户今日转出总金额",
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
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="零钱计划用户今日转出总金额成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="零钱计划用户今日转出总金额失败。",
     *   )
     * )
     */
    public function getTodayInvestOutAmount(Request $request){
        
        $userId = $request->input('user_id',0);

        $logic  = new AccountLogic();

        $result = $logic->getTodayInvestOutAmount($userId);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/current/investAmount",
     *   tags={"Current"},
     *   summary="零钱计划总的转入金额",
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
     *     description="零钱计划总的转入金额。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="零钱计划总的转入失败。",
     *   )
     * )
     */
    public function getInvestAmount(){
        
        $logic  = new AccountLogic();

        $result = $logic->getInvestAmount();

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/current/userInterestList",
     *   tags={"Current"},
     *   summary="获取零钱计划用户近一周收益",
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
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划用户近一周收益成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划用户近一周收益失败。",
     *   )
     * )
     */
    public function getInterestList(Request $request){

        $userId     = $request->input('user_id',0);
        $logic      = new AccountLogic();

        $result     = $logic->getInterestList($userId);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/current/getAdminInterestHistory",
     *   tags={"Current"},
     *   summary="获取活期计息记录列表【管理后台】",
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
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="258082"
     *   ),
     *    @SWG\Parameter(
     *      name="startTime",
     *      in="formData",
     *      description="计息开始时间",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="endTime",
     *      in="formData",
     *      description="计息结束时间",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *     @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页数",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *  @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="分页limit",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取活期计息记录列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取活期计息记录列表失败。",
     *   )
     * )
     */
    public function getAdminInterestListAll(Request $request){

        $params = $request->all();

        $interestLogic = new InterestHistoryLogic();

        $result  = $interestLogic->getAdminFundHistoryListAll($params);

        return self::returnJson($result);
    }
    /**
     * @SWG\Post(
     *   path="/current/getCurrentAccountAmount",
     *   tags={"Current"},
     *   summary="获取零钱计划的资金留存",
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
     *     description="获取零钱计划的资金留存成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划的资金留存失败。",
     *   )
     * )
     */
    public function getCurrentAccountAmount()
    {
        $logic      = new AccountLogic();

        $result     = $logic->getCurrentAccountAmount();

        return self::returnJson($result);

    }

}