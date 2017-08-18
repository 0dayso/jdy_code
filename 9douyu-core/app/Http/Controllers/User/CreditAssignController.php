<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/9/26
 * Time: 下午3:30
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Logics\Project\CreditAssignLogic;
use Illuminate\Http\Request;

class CreditAssignController extends Controller
{

    /**
     * @param Request $request
     *
     */
    /**
     * @SWG\Post(
     *   path="/user/creditAssignList",
     *   tags={"User"},
     *   summary="可债转项目列表",
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
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户可债转项目列表信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户可债转项目列表信息失败。",
     *   )
     * )
     */
    public function index(Request $request){

        $userId = $request->input('user_id', 0);

        $creditAssignLogic = new CreditAssignLogic();

        $return = $creditAssignLogic->userCreditAssign($userId);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/user/creditAssignInvestIds",
     *   tags={"User"},
     *   summary="获取用户已转让的投资Id数组",
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
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取信息失败。",
     *   )
     * )
     */
    public function getCreditAssignInvestIds(Request $request){

        $userId = $request->input('user_id', 0);

        $creditAssignLogic = new CreditAssignLogic();

        $return = $creditAssignLogic->getCreditAssignInvestIds($userId);

        self::returnJson($return);

    }

    /**
     * @param Request $request
     * @desc 确认转让信息页面数据
     */
    /**
     * @SWG\Post(
     *   path="/userPreCreditAssign",
     *   tags={"User"},
     *   summary="获取用户已转让的投资Id数组",
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
     *      name="invest_id",
     *      in="formData",
     *      description="投资id",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取信息失败。",
     *   )
     * )
     */
    public function userPreCreditAssign(Request $request){

        $investId = $request->input('invest_id', 0);

        $creditAssignLogic = new CreditAssignLogic();

        $return = $creditAssignLogic->getPreCreditAssign( $investId );

        self::returnJson($return);

    }

    /**
     * @param Request $request
     * @desc 根据投资人id 获取债转项目信息
     */
    /**
     * @SWG\Post(
     *   path="/getCreditAssignByInvestId",
     *   tags={"User"},
     *   summary="根据投资人id 获取债转项目信息",
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
     *      name="invest_id",
     *      in="formData",
     *      description="投资id",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取信息失败。",
     *   )
     * )
     */
    public function getCreditAssignByInvestId(Request $request){
        $investId = $request->input('invest_id', 0);

        $creditAssignLogic = new CreditAssignLogic();

        $return = $creditAssignLogic->getCreditAssignByInvestId( $investId );

        self::returnJson($return);

    }


}