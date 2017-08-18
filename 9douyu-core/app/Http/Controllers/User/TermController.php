<?php
/**
 * 检测用户
 *
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/4/14
 * Time: 上午10:14
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Http\Logics\Project\CreditAssignLogic;
use App\Http\Logics\User\TermLogic;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * @SWG\Post(
     *   path="/user/term/refunding",
     *   tags={"User"},
     *   summary="获取用户定期回款中的项目记录",
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
     *      default=""
     *   ),
     *   @SWG\Parameter(
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
     *     description="获取定期回款中项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取定期回款中项目失败。",
     *   )
     * )
     */
    public function getRefunding(Request $request){
        $userId       = $request->input('user_id',0);
        $page       = $request->input('page',1);
        $size       = $request->input('size',1);
        $termLogic    = new TermLogic();
        $result = $termLogic->getRefunding($userId,$size);
        self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/user/term/nofinish",
     *   tags={"User"},
     *   summary="获取用户定期未完结的项目记录",
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
     *      default=""
     *   ),
     *   @SWG\Parameter(
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
     *     description="获取定期未完结项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取定期未完结项目失败。",
     *   )
     * )
     */
    public function getNoFinish(Request $request) {
        $userId       = $request->input('user_id',0);
        $page         = $request->input('page',1);
        $size         = $request->input('size',1);
        $termLogic    = new TermLogic();
        $result       = $termLogic->getNoFinish($userId,$size);
        self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/user/term/refunded",
     *   tags={"User"},
     *   summary="获取用户定期已回款的项目记录",
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
     *      default=""
     *   ),
     *  @SWG\Parameter(
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
     *     description="获取定期已回款项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取定期已回款项目失败。",
     *   )
     * )
     */
    public function getRefunded(Request $request){
        $userId       = $request->input('user_id',0);
        $page       = $request->input('page',1);
        $size       = $request->input('size',1);
        $termLogic    = new TermLogic();
        $result = $termLogic->getRefunded($userId,$size);
        self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/user/term/investing",
     *   tags={"User"},
     *   summary="获取用户定期投资中的项目记录",
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
     *      default=""
     *   ),
     *  @SWG\Parameter(
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
     *     description="获取定期投资中项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取定期投资中项目失败。",
     *   )
     * )
     */
    public function getInvesting(Request $request){
        $userId       = $request->input('user_id',0);
        $page       = $request->input('page',1);
        $size       = $request->input('size',1);
        $termLogic    = new TermLogic();
        $result = $termLogic->getInvesting($userId,$size);
        self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/user/term/refundplan",
     *   tags={"User"},
     *   summary="获取用户项目的回款计划",
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
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="invest_id",
     *      in="formData",
     *      description="投资ID",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取定期已回款项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取定期已回款项目失败。",
     *   )
     * )
     */
    public function getRefundPlan(Request $request){
        $userId = $request->input('user_id',0);
        $investId = $request->input('invest_id',0);
        $termLogic    = new TermLogic();
        $result = $termLogic->getRefundPlan($userId,$investId);
        self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/user/term/pfbInvestList",
     *   tags={"User"},
     *   summary="获取普付宝用户的投资记录",
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
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页数",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *    @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页数量",
     *      required=true,
     *      type="integer",
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
    public function getPfbInvestList( Request $request ){

        $userId = $request->input('user_id');

        $page = $request->input('page', 1);

        $size = $request->input('size', 100);

        $termLogic    = new TermLogic();

        $result = $termLogic->getPfbInvestList($userId, $page, $size);
        
        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/user/term/getPfbInvestTotal",
     *   tags={"User"},
     *   summary="获取普付宝用户的投资质押项目总额",
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
    public function getPfbInvestTotal( Request $request ){

        $userId = $request->input('user_id');

        $termLogic    = new TermLogic();

        $result = $termLogic->getPfbInvestTotal($userId);

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/user/term/getAppV4UserTermNoFinish",
     *   tags={"User"},
     *   summary="APP4.0 我的资产-定期资产-持有中",
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
     *      default=""
     *   ),
     *   @SWG\Parameter(
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
     *     description="获取定期未完结项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取定期未完结项目失败。",
     *   )
     * )
     */
    public function getAppV4UserTermNoFinish(Request $request) {
        $userId       = $request->input('user_id',0);
        $page         = $request->input('page', 1);
        $size         = $request->input('size', 10);
        $termLogic    = new TermLogic();

        $result       = $termLogic->getAppV4UserTermNoFinish($userId, $page, $size);
        self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/user/term/getAppV4UserTermFinish",
     *   tags={"User"},
     *   summary="APP4.0 我的资产-定期资产-已完结",
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
     *      default=""
     *   ),
     *   @SWG\Parameter(
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
     *     description="获取定期未完结项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取定期未完结项目失败。",
     *   )
     * )
     */
    public function getAppV4UserTermFinish(Request $request) {
        $userId       = $request->input('user_id',0);
        $page         = $request->input('page', 1);
        $size         = $request->input('size', 10);
        $termLogic    = new TermLogic();
        $result       = $termLogic->getAppV4UserTermFinish($userId, $page, $size);
        self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/user/term/getAppV4UserTermAssignment",
     *   tags={"User"},
     *   summary="APP4.0 我的资产-定期资产-转让中",
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
     *      default=""
     *   ),
     *   @SWG\Parameter(
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
     *     description="获取转让中项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取转让中项目失败。",
     *   )
     * )
     */
    public function getAppV4UserTermAssignment(Request $request) {
        $userId = $request->input('user_id',0);
        $page   = $request->input('page', 1);
        $size   = $request->input('size', 10);

        $logic  = new CreditAssignLogic();

        $result = $logic->doingAssignmentInvest($userId, $page, $size);
        self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/user/term/getAppV4UserTermDetail",
     *   tags={"User"},
     *   summary="APP4.0 我的资产-定期资产-项目详情",
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
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="invest_id",
     *      in="formData",
     *      description="投资ID",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取数据成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */
    public function getAppV4UserTermDetail(Request $request) {
        $userId     = $request->input('user_id',    '');
        $investId   = $request->input('invest_id',  '');

        $termLogic  = new TermLogic();
        $result     = $termLogic->getAppV4UserTermDetail($userId, $investId);
        self::returnJson($result);
    }


}