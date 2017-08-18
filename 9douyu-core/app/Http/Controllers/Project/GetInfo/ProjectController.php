<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/19
 * Time: 下午4:45
 */

namespace App\Http\Controllers\Project\GetInfo;


use App\Http\Controllers\Project\ProjectBaseController;
use App\Http\Logics\Logic;
use App\Http\Logics\Module\Invest\RateLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\Refund\RefundRecordLogic;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ProjectController extends ProjectBaseController
{

    /**
     * @SWG\Post(
     *   path="/project/detail",
     *   tags={"Project"},
     *   summary="定期项目详情",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取定期项目信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取定期项目信息失败。",
     *   )
     * )
     */
    public function detail(Request $request)
    {

        $projectId = (int)$request->input('project_id');

        $logic = new ProjectLogic();

        $project = $logic->getDetailById($projectId);

        $project = $logic->formatProject($project);

        $return = Logic::callSuccess($project);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/jsxlist",
     *   tags={"Project"},
     *   summary="九省心项目列表",
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
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *     default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *    @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","110","120","130","150","160"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取九省心项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取九省心项目列表失败。",
     *   )
     * )
     */

    public function JSXList(Request $request)
    {

        $page = (int)$request->input('page', 1);

        $size = (int)$request->input('size', 6);

        $status = $request->input('status', '');

        $logic = new ProjectLogic();

        $list = $logic->getJSXList($page, $size, $status);

        if( !empty($list['list']) ){

            foreach( $list['list'] as $key => $val ){

                $list['list'][$key] = $logic->formatProject($val);

            }

        }

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @param Request $request
     * @desc 获取普付宝项目列表
     */
    public function pfbList(Request $request)
    {

        $page = (int)$request->input('page', 1);

        $size = (int)$request->input('size', 6);

        $logic = new ProjectLogic();

        $list = $logic->getPfbList($page, $size);

        foreach( $list as $key => $val ){

            $list[$key] = $logic->formatProject($val);

        }

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @param Request $request
     * @desc 获取普付宝项目列表
     */
    public function pfbDetail(Request $request)
    {

        $logic = new ProjectLogic();

        $detail = $logic->getPfbProject();

        $data = $logic->formatProject($detail);

        $return = Logic::callSuccess($data);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/jaxlist",
     *   tags={"Project"},
     *   summary="九安心项目列表",
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
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *     default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *    @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","110","120","130","150","160"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取九安心项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取九安心项目列表失败。",
     *   )
     * )
     */
    public function JAXList(Request $request)
    {

        $page = (int)$request->input('page', 1);

        $size = (int)$request->input('size', 6);

        $status = $request->input('status', '');

        $logic = new ProjectLogic();

        $list = $logic->getJAXList($page, $size, $status);

        if( !empty($list['list']) ){

            foreach( $list['list'] as $key => $val ){

                $list['list'][$key] = $logic->formatProject($val);

            }

        }

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/sdflists",
     *   tags={"Project"},
     *   summary="闪电付息项目列表",
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
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *     default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *    @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","110","120","130","150","160"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取闪电付息项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取闪电付息项目列表失败。",
     *   )
     * )
     */
    public function SDFList(Request $request)
    {

        $page = (int)$request->input('page', 1);

        $size = (int)$request->input('size', 6);

        $status = $request->input('status', '');

        $logic = new ProjectLogic();

        $list = $logic->getSDFList($page, $size, $status);

        if( !empty($list['list']) ){

            foreach( $list['list'] as $key => $val ){

                $list['list'][$key] = $logic->formatProject($val);

            }

        }

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }
    /**
     * @SWG\Post(
     *   path="/project/projectList",
     *   tags={"Project"},
     *   summary="定期理财项目列表",
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
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *     default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *    @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","110","120","130","150","160"}
     *   ),
     *    @SWG\Parameter(
     *      name="product_line",
     *      in="formData",
     *      description="项目产品线(100-九省心 200-九安心 300-闪电付息)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","200","300"}
     *   ),

     *   @SWG\Response(
     *     response=200,
     *     description="获取理财列表定期项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取理财列表定期项目列表失败。",
     *   )
     * )
     */

    public function getProjectList(Request $request){

        $page = (int)$request->input('page', 1);

        $size = (int)$request->input('size', 6);

        $status = $request->input('status', '');

        $productLine = $request->input('product_line', '');

        $logic = new ProjectLogic();

        $list = $logic->getProjectList($productLine, $page, $size, $status);

        if( !empty($list['list']) ){

            foreach( $list['list'] as $key => $val ){

                $list['list'][$key] = $logic->formatProject($val);

            }

        }

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/listByStatus",
     *   tags={"Project"},
     *   summary="通过状态获取对应项目列表",
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
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *     default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *    @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","110","120","130","150","160"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取项目列表失败。",
     *   )
     * )
     */
    public function listByStatus(Request $request){

        $page = (int)$request->input('page', 1);

        $size = (int)$request->input('size', 10);

        $status = $request->input('status', '');

        $ids    = $request->input('ids', false);

        $logic = new ProjectLogic();

        $list = $logic->getListByStatus($page, $size, $status, $ids);

        if( !empty($list['list']) ){

            foreach( $list['list'] as $key => $val ){

                $list['list'][$key] = $logic->formatProject($val);

            }

        }

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/admin/jsxlist",
     *   tags={"Project"},
     *   summary="九省心项目列表",
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
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *     default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *    @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","110","120","130","150","160"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取九省心项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取九省心项目列表失败。",
     *   )
     * )
     */

    public function JSXAdminList(Request $request)
    {

        $page = (int)$request->input('page', 1);

        $size = (int)$request->input('size', 10);

        $status = $request->input('status', '');

        $logic = new ProjectLogic();

        $list = $logic->getAdminJSXList($page, $size, $status);

        if( !empty($list['list']) ){

            foreach( $list['list'] as $key => $val ){

                $list['list'][$key] = $logic->formatProject($val);

            }

        }

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/admin/jaxlist",
     *   tags={"Project"},
     *   summary="九安心项目列表",
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
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *     default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *    @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","110","120","130","150","160"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取九安心项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取九安心项目列表失败。",
     *   )
     * )
     */
    public function JAXAdminList(Request $request)
    {

        $page = (int)$request->input('page', 1);

        $size = (int)$request->input('size', 10);

        $status = $request->input('status', '');

        $logic = new ProjectLogic();

        $list = $logic->getAdminJAXList($page, $size, $status);

        if( !empty($list['list']) ){

            foreach( $list['list'] as $key => $val ){

                $list['list'][$key] = $logic->formatProject($val);

            }

        }

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/admin/sdflists",
     *   tags={"Project"},
     *   summary="闪电付息项目列表",
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
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *     default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *    @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","110","120","130","150","160"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取闪电付息项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取闪电付息项目列表失败。",
     *   )
     * )
     */
    public function SDFAdminList(Request $request)
    {

        $page = (int)$request->input('page', 1);

        $size = (int)$request->input('size', 10);

        $status = $request->input('status', '');

        $logic = new ProjectLogic();

        $list = $logic->getAdminSDFList($page, $size, $status);

        if( !empty($list['list']) ){

            foreach( $list['list'] as $key => $val ){

                $list['list'][$key] = $logic->formatProject($val);

            }

        }

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/getFinishedList",
     *   tags={"Project"},
     *   summary="通过状态获取对应项目列表",
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
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *     default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=true,
     *      type="integer",
     *     default="6"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取项目列表失败。",
     *   )
     * )
     */
    public function getFinishedList(Request $request){

        $page = (int)$request->input('page', 1);

        $size = (int)$request->input('size', 10);

        $logic = new ProjectLogic();

        $list = $logic->getFinishedList($page, $size);

        if( !empty($list['list']) ){

            foreach( $list['list'] as $key => $val ){

                $list['list'][$key] = $logic->formatProject($val);

            }

        }

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/refundPlan",
     *   tags={"Project"},
     *   summary="项目还款计划",
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
     *     default=""
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
    public function refundPlan(Request $request){
        $projectId = (int)$request->input('project_id', 0);
        $logic = new ProjectLogic();
        $list = $logic->getRefundPlan($projectId);
        self::returnJson($list);
    }


    /**
     * @SWG\Post(
     *   path="/project/list",
     *   tags={"Project"},
     *   summary="通过多个id获取项目列表",
     *   @SWG\Parameter(
     *      name="project_ids",
     *      in="formData",
     *      description="（多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="1,2,3",
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
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function getListByIds(Request $request)
    {

        $projectIds = $request->input('project_ids');

        $logic = new ProjectLogic();

        $list = $logic->getListByIds($projectIds);

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/home",
     *   tags={"Project"},
     *   summary="获取首页项目列表",
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
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
    public function getHomeList()
    {

        $logic = new ProjectLogic();

        //$list = $logic->getHomeList();

        $list = $logic->getNewHomeList();

        self::returnJson($list);

    }

    /**
     * @SWG\Post(
     *   path="/project/finished",
     *   tags={"Project"},
     *   summary="获取已完结的项目项目id",
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="time",
     *      in="formData",
     *      description="时间",
     *      required=true,
     *      type="string",
     *      default="2016-06-01",
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
    public function getFinishedIds( Request $request )
    {

        $time = $request->input('time');

        $time = $time ? $time : ToolTime::dbDate();

        $logic = new ProjectLogic();

        $res = $logic->getFinishedIds($time);

        self::returnJson($res);

    }

    /**
     * @SWG\Post(
     *   path="/project/sdflist",
     *   tags={"Project"},
     *   summary="获取页面闪电付息列表",
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
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
     * @desc 获取页面闪电付息列表
     */
    public function getSdfProject(){

        $logic = new ProjectLogic();

        $result = $logic->getSdfProject();

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/project/timing",
     *   tags={"Project"},
     *   summary="获取定时发布的项目列表",
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *     @SWG\Parameter(
     *      name="times",
     *      in="formData",
     *      description="发布时间",
     *      required=true,
     *      type="string",
     *   ),
     *     @SWG\Parameter(
     *      name="invest_time",
     *      in="formData",
     *      description="项目期限（one,three多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="one,six",
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
     * @desc 获取定时发布的项目数据
     */
    public function getTimingProject( Request $request){

        $logic      =   new ProjectLogic();
        $times      =   $request->input('times');
        $investTimes=   $request->input('invest_time');
        $result     =   $logic->getTimingProject($times,$investTimes);

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/project/appointed",
     *   tags={"Project"},
     *   summary="获取定时发布的项目列表",
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *     @SWG\Parameter(
     *      name="times",
     *      in="formData",
     *      description="项目期限（one,three多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="one,six",
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
     * @desc 获取定时发布的项目数据
     */
    public function getAppointJsxProject( Request $request){

        $logic      =   new ProjectLogic();
        $times      =   $request->input('times');
        $result     =   $logic->AppointJsxTimingProject($times);

        self::returnJson($result);

    }
    /**
     * @SWG\Post(
     *   path="/project/getIds",
     *   tags={"Project"},
     *   summary="获取项目的id",
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *     @SWG\Parameter(
     *      name="times",
     *      in="formData",
     *      description="项目期限（one,three多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="start_time",
     *      in="formData",
     *      description="开始时间",
     *      required=true,
     *      type="string",
     *      default="one,six",
     *   ),
     *     @SWG\Parameter(
     *      name="end_time",
     *      in="formData",
     *      description="结束时间",
     *      required=true,
     *      type="string",
     *      default="",
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
     * @desc 获取项目id
     */
    public function getProjectIdsStatistics( Request $request)
    {

        $logic = new ProjectLogic();

        $statistics = $request->all();

        $result = $logic->getProjectIdsStatistics($statistics);

        self::returnJson($result);
    }

    /* @param Request $request
     * @desc 根据项目的最后更新时间获取项目列表,主要功能为,后台按时间查询项目满标的列表
     */
    public function getRefundingProjectListByUpdateTime( Request $request ){

        $startTime = $request->input('start_time');

        $endTime = $request->input('end_time');

        $logic = new ProjectLogic();

        $result = $logic->getRefundingProjectListByUpdateTime($startTime, $endTime);

        self::returnJson($result);

    }

    /**
     * @param Request $request
     * @desc 获取非普付宝的项目(可见的项目)
     */
    public function getProjectWithTime(Request $request)
    {
        $startTime      =   $request->input('start_time');

        $endTime        =   $request->input('end_time');

        $pageIndex      =   $request->input("page",1);

        $pageSize       =   $request->input('page_size',50);

        $logic          =   new ProjectLogic();

        $result         =   $logic->getProjectWithTime($startTime,$endTime,$pageIndex,$pageSize);

        self::returnJson($result);
    }

    /**
     * @param Request $request
     * @desc 获取非普付宝的项目(可见的项目)
     */
    public function getInvestIngProject(Request $request)
    {
        $startTime      =   $request->input('start_time');

        $endTime        =   $request->input('end_time');

        $pageIndex      =   $request->input("page",1);

        $pageSize       =   $request->input('page_size',50);

        $logic          =   new ProjectLogic();

        $result         =   $logic->getInvestIngProject($startTime,$endTime,$pageIndex,$pageSize);

        self::returnJson($result);

    }

    /**
     * @return array
     * @desc 获取产品线中的每一个最新的产品
     */
    public function getNewestProjectEveryType()
    {
        $logic      =   new ProjectLogic();

        $result     =   $logic->getNewestProjectEveryType();

        return self::returnJson($result);

    }

    /**
     * @desc    获取时间段内已完结项目
     * @date    2016年11月21日
     * @author  @llper
     * @param   Request $request
     *
     */
    public function getFinishedProjectByTime(Request $request)
    {
        $startTime      =   $request->input('start_time');
        $endTime        =   $request->input('end_time');
        $isBefore       =   $request->input('is_before');

        $logic          =   new ProjectLogic();
        $result         =   $logic->getFinishedProjectByTime($startTime,$endTime,$isBefore);

        self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/project/getCreditProjectById",
     *   tags={"Project"},
     *   summary="通过多个id获取项目需要回款金额",
     *   @SWG\Parameter(
     *      name="project_ids",
     *      in="formData",
     *      description="（多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="1,2,3",
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
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    /**
     * @desc    获取项目项目回款信息
     **/
    public function getCreditProjectById(Request $request)
    {

        $projectIds = $request->input('project_ids');

        $logic = new RefundRecordLogic();

        $list = $logic->getProjectNeedRefundById($projectIds);

        $return = Logic::callSuccess($list);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/project/getProjectByFullTime",
     *   tags={"Project"},
     *   summary="通过项目满标时间获取项目信息",
     *   @SWG\Parameter(
     *      name="start_time",
     *      in="formData",
     *      description="开始时间",
     *      required=true,
     *      type="string",
     *      default="2016-12-01 00:00:00",
     *   ),
     *      @SWG\Parameter(
     *      name="end_time",
     *      in="formData",
     *      description="截止时间",
     *      required=true,
     *      type="string",
     *      default="2016-12-31 23:59:59",
     *   ),
     *      @SWG\Parameter(
     *      name="is_pledge",
     *      in="formData",
     *      description="0全部,1普付宝项目,2不包含普付宝",
     *      required=true,
     *      type="string",
     *      default="0",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="0",
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
     */
    public function getProjectByFullTime(Request $request)
    {
        $startTime      =   $request->input('start_time');

        $endTime        =   $request->input('end_time');

        $isPledge       =   $request->input('is_pledge',0);

        $logic          =   new ProjectLogic();

        $result         =   $logic->getProjectByFullTime($startTime,$endTime,$isPledge);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/project/getAllProjectIdByTime",
     *   tags={"Project"},
     *   summary="通过时间,按照ProductLine获取项目id",
     *   @SWG\Parameter(
     *      name="start_time",
     *      in="formData",
     *      description="开始时间",
     *      required=true,
     *      type="string",
     *      default="2016-12-01 00:00:00",
     *   ),
     *      @SWG\Parameter(
     *      name="end_time",
     *      in="formData",
     *      description="截止时间",
     *      required=true,
     *      type="string",
     *      default="2016-12-31 23:59:59",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="0",
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
     * @desc 通过时间,按照ProductLine获取项目id
     */
    public function getAllProjectIdByTime(Request $request)
    {
        $startTime      =   $request->input('start_time');

        $endTime        =   $request->input('end_time');

        $logic          =   new ProjectLogic();

        $result         =   $logic->getAllProjectIdByTime($startTime,$endTime);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/project/getProjectPackAppV413",
     *   tags={"Project"},
     *   summary="获取APP4.1.3-首页项目列表",
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
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
    public function getProjectPackAppV413()
    {

        $logic  = new ProjectLogic();

        $list   = $logic->getProjectPackAppV413();

        self::returnJson($list);

    }


}
