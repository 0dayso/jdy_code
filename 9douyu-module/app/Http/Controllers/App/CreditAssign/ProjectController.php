<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/9/27
 * Time: 上午10:35
 */

namespace App\Http\Controllers\App\CreditAssign;


use App\Http\Controllers\App\AppController;
use App\Http\Logics\CreditAssign\CreditAssignLogic;
use Illuminate\Http\Request;

class ProjectController extends AppController
{

    /**
     * @SWG\Post(
     *   path="/assign_project",
     *   tags={"APP-CreditAssign:债权转让相关接口"},
     *   summary="债权转让列表接 [CreditAssign\ProjectController@assignProject]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="2.2.3",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="债权信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="债权信息获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 债权转让列表接
     */
    public function assignProject(Request $request){

        $logic = new CreditAssignLogic();

        $page = $request->input('page', 1);

        $size = $request->input('size', 10);

        $data = $logic -> assignProject($page, $size);

        return self::appReturnJson($data);

    }
    

    /**
     * @SWG\Post(
     *   path="/credit_assign_detail",
     *   tags={"APP-CreditAssign:债权转让相关接口"},
     *   summary="债权转让项目详情 [CreditAssign\ProjectController@creditAssignDetail]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="2.2.3",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="债权转让项目详情",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="债权信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="债权信息获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 债权项目详情
     */
    public function creditAssignDetail(Request $request){

        $id = $request->input('project_id', 0);

        $logic = new CreditAssignLogic();

        $data = $logic -> creditAssignDetail($id);

        return self::appReturnJson($data);

    }

}