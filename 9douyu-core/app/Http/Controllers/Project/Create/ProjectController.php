<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/4/19
 * Time: 下午4:45
 */

namespace App\Http\Controllers\Project\Create;

use App\Http\Controllers\Controller;
use App\Http\Logics\Logic;
use App\Http\Logics\Project\ProjectLogic;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/project/create",
     *   tags={"Project"},
     *   summary="添加项目",
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
     *      name="total_amount",
     *      in="formData",
     *      description="项目金额",
     *      required=true,
     *      type="integer",
     *   ),
     *  @SWG\Parameter(
     *      name="invest_days",
     *      in="formData",
     *      description="融资时间",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="invest_time",
     *      in="formData",
     *      description="投资期限",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="refund_type",
     *      in="formData",
     *      description="还款方式(10-到期还本息 20-先息后本 30-前置付息)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="10",
     *      enum={"10","20","30"}
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="项目类型(0-XX天 1-一月期 3-三月期 6-六月期 12-十二月期)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="0",
     *      enum={"0","1","3","6","12"}
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="项目名称",
     *      required=true,
     *      type="string",
     *   ),
     *  @SWG\Parameter(
     *      name="product_line",
     *      in="formData",
     *      description="产品线(100-九省心 200-九安心 300-前置付息)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","200","300"}
     *   ),
     *   @SWG\Parameter(
     *      name="base_rate",
     *      in="formData",
     *      description="基准利率",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="after_rate",
     *      in="formData",
     *      description="平台加息",
     *      required=false,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="created_by",
     *      in="formData",
     *      description="创建人",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="publish_time",
     *      in="formData",
     *      description="项目发布时间",
     *      required=true,
     *      type="string",
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
    public function create(Request $request)
    {

        $data = $request->all();

        $logic = new ProjectLogic();

        $return = $logic -> create($data);

        if( $return['status'] ){

            $returnJson = Logic::callSuccess($return['data']);

        }else{

            $returnJson = Logic::callError($return['msg'], $return['code'], $return['data']);

        }

        self::returnJson($returnJson);

    }

    /**
     * @SWG\Post(
     *   path="/project/delete",
     *   tags={"Project"},
     *   summary="删除项目",
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
     *      name="project_id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="删除项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="删除项目失败。",
     *   )
     * )
     */
    public function delete(Request $request){

        $id = (int)$request->input('project_id');     //项目总额

        $logic = new ProjectLogic();

        $return = $logic -> doDelete($id);

        if( $return['status'] ){

            $returnJson = Logic::callSuccess($return['data']);

        }else{

            $returnJson = Logic::callError($return['msg'], $return['code'], $return['data']);

        }

        self::returnJson($returnJson);

    }

    /**
     * @SWG\Post(
     *   path="/project/update",
     *   tags={"Project"},
     *   summary="更新项目",
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
     *      name="project_id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="total_amount",
     *      in="formData",
     *      description="项目金额",
     *      required=true,
     *      type="integer",
     *   ),
     *  @SWG\Parameter(
     *      name="invest_days",
     *      in="formData",
     *      description="融资时间",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="invest_time",
     *      in="formData",
     *      description="投资期限",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="refund_type",
     *      in="formData",
     *      description="还款方式(10-到期还本息 20-先息后本 30-前置付息)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="10",
     *      enum={"10","20","30"}
     *   ),
     *  @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="项目类型(0-XX天 1-一月期 3-三月期 6-六月期 12-十二月期)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="0",
     *      enum={"0","1","3","6","12"}
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="项目名称",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="product_line",
     *      in="formData",
     *      description="产品线(100-九省心 200-九安心 300-前置付息)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","200","300"}
     *   ),
     *   @SWG\Parameter(
     *      name="base_rate",
     *      in="formData",
     *      description="基准利率",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="after_rate",
     *      in="formData",
     *      description="平台加息",
     *      required=false,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="created_by",
     *      in="formData",
     *      description="创建人",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="publish_time",
     *      in="formData",
     *      description="项目发布时间",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="product_line",
     *      in="formData",
     *      description="状态(100-未审核|110-未通过|120-未发布|130-投资中|150|还款中|160-已完结)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","110","120","130","140","150","160"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="更新项目信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="更新项目信息失败。",
     *   )
     * )
     */
    public function update(Request $request){

        $data   = $request->all();

        $logic  = new ProjectLogic();

        $id     = $data['project_id'];

        $return = $logic->doUpdate($id, $data);

        if( $return['status'] ){

            $returnJson = Logic::callSuccess($return['data']);

        }else{

            $returnJson = Logic::callError($return['msg'], $return['code'], $return['data']);

        }

        self::returnJson($returnJson);

    }

    /**
     * @SWG\Post(
     *   path="/project/doPass",
     *   tags={"Project"},
     *   summary="项目审核通过",
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="（多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return mixed
     * @desc 审核通过
     */
    public function updateStatusUnPublish(Request $request){

        $id     = $request->input('id', 0);

        $logic  = new ProjectLogic();

        $result = $logic->updateStatusUnPublish( $id );

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/project/doNoPass",
     *   tags={"Project"},
     *   summary="项目审核不通过",
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="（多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return mixed
     * @desc 审核不通过
     */
    public function updateStatusAuditeFail(Request $request){

        $id      = $request->input('id', 0);

        $logic  = new ProjectLogic();

        $result = $logic->updateStatusAuditeFail( $id );

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/project/doPublish",
     *   tags={"Project"},
     *   summary="项目发布",
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="（多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return mixed
     * @desc 项目发布
     */
    public function updateStatusInvesting(Request $request){

        $id     = $request->input('id', 0);

        $logic  = new ProjectLogic();

        $result = $logic->updateStatusInvesting( $id );

        self::returnJson($result);

    }

}