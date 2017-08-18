<?php

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/3
 * Time: 上午10:42
 *
 */

namespace App\Http\Controllers\App\Project;

use App\Http\Controllers\App\AppController;

use App\Http\Logics\Project\ProjectDetailLogic;

use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
/**
 * 项目详情
 * Class ProjectDetailController
 * @package App\Http\Controllers\App\Project
 */
class DetailController extends AppController
{
    /**
     * 项目详情逻辑类
     * @var ProjectDetailLogic|null
     */
    protected $ProjectDetailLogic = null;



    public function appendConstruct(){
        \Debugbar::disable();
        $this->ProjectDetailLogic = new ProjectDetailLogic;
    }

    /**
     * @SWG\Post(
     *   path="/project_detail",
     *   tags={"APP-Project"},
     *   summary="定期项目详情 [Project\DetailController@get]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
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
     *      description="版本号",
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
     *      description="项目编号",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取定期项目详情成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取定期项目详情失败。",
     *   )
     * )
     */
    public function get(Request $request){
        $id              = $request->input('project_id');

        $logicResult     = $this->ProjectDetailLogic->appGet($id);

        $this->appReturnJson($logicResult);
    }

    /**
     * @SWG\Post(
     *   path="/project_invest_records",
     *   tags={"APP-Project"},
     *   summary="项目的投资详情 [Project\DetailController@getInvestRecord]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
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
     *      description="版本号",
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
     *      description="项目编号",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取项目的投资详情成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取项目的投资详情失败。",
     *   )
     * )
     */
    public function getInvestRecord(Request $request){
        $id              = $request->input('project_id');
        $page            = $request->input('p', 1);
        $logicResult     = $this->ProjectDetailLogic->appGetInvestRecord($id, $page);

        $this->appReturnJson($logicResult);
    }

    /**
     * @SWG\Get(
     *   path="/app/project/product/detail/{id}",
     *   tags={"APP-Project"},
     *   summary="根据项目ID显示债权信息 [Project\DetailController@getCreditDetail]",
     *   @SWG\Parameter(
     *         description="项目ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="根据项目ID显示债权信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="根据项目ID显示债权信息失败。",
     *   )
     * )
     */
    public function getCreditDetail($appSubDomain = null, $id=0, Request $request){

        $id              = $request->input("project_id" , $id);

        $logicResult     = $this->ProjectDetailLogic->getProductCreditDetail($id);

        if($logicResult['status']){
            $logicResult = $logicResult['data'];
            return view('app.project.detail.'.$logicResult['view'], $logicResult['data']);
        }else{
            return response('404 not found！', 404);
        }
    }

    /**
     * @SWG\Post(
     *   path="/get_invest_percent",
     *   tags={"APP-Project"},
     *   summary="投资页面-输入框数据显示 [Project\DetailController@getInvestPercent]",
     *   @SWG\Response(
     *     response=200,
     *     description="成功。",
     *   )
     * )
     */
    public function getInvestPercent(){
        $data = ProjectDetailLogic::getInvestPercent();
        $this->appReturnJson($data);
    }

    /**
     * @param null $appSubDomain
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     * @desc 项目第三方债权借款人信息
     */
    public function getCreditInfo($appSubDomain = null, $id=0, Request $request){

        $id              = $request->input("project_id" , $id);

        $logicResult     = $this->ProjectDetailLogic->getProductCreditDetail($id);

        if($logicResult['status']){
            $logicResult = $logicResult['data'];
            return view('app.project.detail.third-credit', $logicResult['data']);
        }else{
            return response('404 not found！', 404);
        }

    }

}

