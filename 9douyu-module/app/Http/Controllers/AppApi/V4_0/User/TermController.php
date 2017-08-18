<?php
/**
 * @desc    APPV4.0 我的资产-定期资产
 * @date    2017-03-08
 * @author  @linglu
 */

namespace App\Http\Controllers\AppApi\V4_0\User;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\Invest\TermLogic;
use Illuminate\Http\Request;
use App\Http\Logics\AppLogic;


class TermController extends AppController{

    /**
     * @SWG\Post(
     *   path="/user_term",
     *   tags={"APP-User"},
     *   summary="用户中心 定期资产 [User\TermController@index]",
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
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
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
     *      name="type",
     *      in="formData",
     *      description="展示类型:持有中,转让中,已完结",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="investing",
     *      enum={"investing","assignment","finish"}
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页数",
     *      required=false,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页显示条数",
     *      required=false,
     *      type="string",
     *      default="10",
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
     * @desc    用户中心我的资产-定期资产
     * @param   $request array
     * @return  array
     */
    public function index(Request $request){

        $userId = $this->getUserId();
        $page 	= $request->input('page',  1);
        $size 	= $request->input('size',10);
        # type  投资中，转让中，已完结
        $type 	= $request->input('type','investing');

        $termLogic  = new TermLogic();

        $resData    = $termLogic->appV4UserTermRecord($userId,$type,$page,$size);

        return AppLogic::callSuccess($resData);
    }


    /**
     * @SWG\Post(
     *   path="/user_term_detail",
     *   tags={"APP-User"},
     *   summary="用户中心 定期资产-投资详情 [User\TermController@detail]",
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
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
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
     *      name="invest_id",
     *      in="formData",
     *      description="投资ID",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
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
     * @desc    用户中心我的资产-定期资产-资产详情
     * @param   $request array
     * @return  array
     */
    public function detail(Request $request){

        $userId = $this->getUserId();
        $investId 	= $request->input('invest_id', '');
        $termLogic  = new TermLogic();

        $resData    = $termLogic->appV4UserTermDetail($userId,$investId);

        return $this->returnJsonData($resData);
    }
}

