<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/25
 * Time: 14:11
 */

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Logics\Project\CreditAssignLogic;
use Illuminate\Http\Request;

class CreditAssignController extends Controller{


    /**
     * @SWG\Post(
     *   path="/project/creditAssign/create",
     *   tags={"Project"},
     *   summary="添加债权项目",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_dev_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *    @SWG\Parameter(
     *      name="invest_id",
     *      in="formData",
     *      description="投资ID",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="amount",
     *      in="formData",
     *      description="项目金额",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="创建项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="创建项目失败。",
     *   )
     * )
     */
    public function create(Request $request){

        $investId  = $request->input('invest_id',0);
        $amount    = $request->input('amount',0.0);

        $logic = new CreditAssignLogic();

        $result = $logic->create($investId,$amount);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/project/creditAssign/cancel",
     *   tags={"Project"},
     *   summary="取消债权项目",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_dev_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *    @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="债转项目ID",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="创建项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="创建项目失败。",
     *   )
     * )
     */
    public function cancel(Request $request){

        $projectId = $request->input('project_id',0);
        $userId = $request->input('user_id',0);

        $logic = new CreditAssignLogic();

        $result = $logic->cancel($projectId,$userId);

        return self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/project/getCreditAssignList",
     *   tags={"Project"},
     *   summary="债权项目列表",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_dev_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="条数",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="债权项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="债权项目列表失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 根据条件获取债转项目列表
     */
    public function getList(Request $request){

        $logic = new CreditAssignLogic();

        $page = $request->input('page', 1);

        $size = $request->input('size', 10);

        $list = $logic->getList($page, $size);

        return self::returnJson($list);

    }

    /**
     * @SWG\Post(
     *   path="/project/creditAssign/detail",
     *   tags={"Project"},
     *   summary="债权项目列表",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_dev_user",
     *   ),
     *   @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="债转项目Id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="债权项目详情成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="债权项目详情失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 根据主键ID获取债权项目信息
     */
    public function getById(Request $request){

        $projectId= $request->input('project_id', 0);

        $logic = new CreditAssignLogic();

        $list = $logic->getDetailById($projectId);

        return self::returnJson($list);

    }

    /**
     * @SWG\Post(
     *   path="/project/creditAssign/invest",
     *   tags={"Project"},
     *   summary="投资债权项目",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_dev_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *    @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *   ),
     *    @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="购买金额",
     *      required=true,
     *      type="integer",
     *   ),
     *     @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="债转项目ID",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="创建项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="创建项目失败。",
     *   )
     * )
     */
    public function invest(Request $request){

        $projectId = $request->input('project_id',0);
        $userId    = $request->input('user_id',0);
        $cash      = $request->input('cash');

        $logic = new CreditAssignLogic();
        $result = $logic->invest($projectId,$userId,$cash);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/project/creditAssign/investByCurrent",
     *   tags={"Project"},
     *   summary="投资债权项目",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_dev_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *    @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *   ),
     *    @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="购买金额",
     *      required=true,
     *      type="integer",
     *   ),
     *     @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="债转项目ID",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="创建项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="创建项目失败。",
     *   )
     * )
     */
    public function investByCurrent(Request $request){

        $projectId = $request->input('project_id',0);
        $userId    = $request->input('user_id',0);
        $cash      = $request->input('cash');

        $logic = new CreditAssignLogic();
        $result = $logic->investByCurrent($projectId,$userId,$cash);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/project/creditAssign/getInvestId",
     *   tags={"Project"},
     *   summary="匹配投资investId",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_dev_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *    @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *   ),
     *    @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="投资金额",
     *      required=true,
     *      type="integer",
     *   ),
     *     @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="债转项目ID",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="匹配投资ID成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="匹配投资ID失败。",
     *   )
     * )
     */
    public function getInvestId(Request $request){

        $projectId = $request->input('project_id',0);
        $userId    = $request->input('user_id',0);
        $cash      = $request->input('cash');

        $logic = new CreditAssignLogic();
        $result = $logic->getInvestId($projectId,$userId,$cash);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/project/creditAssign/getInvestingCount",
     *   tags={"Project"},
     *   summary="获取债权转让可投资个数",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_dev_user",
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
     *     description="债权项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="债权项目列表失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 获取债权转让可投资个数
     */
    public function getInvestingCount(Request $request){

        $logic  = new CreditAssignLogic();

        $result = $logic->getInvestingCount();

        return self::returnJson($result);

    }
}
