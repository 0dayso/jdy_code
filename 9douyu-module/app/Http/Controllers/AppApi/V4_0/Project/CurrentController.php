<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/1/23
 * Time: 下午19:03
 */

namespace App\Http\Controllers\AppApi\V4_0\Project;

use App\Http\Controllers\AppApi\AppController;
use Illuminate\Http\Request;
use App\Http\Logics\Project\CurrentLogic;
use App\Http\Logics\AppLogic;


/**
 * class CurrentController
 * @package App\Http\Controllers\AppApi\V4_0
 */
class CurrentController extends AppController{

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/current_index",
     *   tags={"APP-Project"},
     *   summary="理财列表-零钱计划 [Project\CurrentController@indexl]",
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
     *      default="4.0.0",
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
     *     description="获取数据成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */
    public function index(Request $request){

        $client = $this->client;
        $userId = $this->getUserId();

        $currentList = [];

        //App4.0零钱计划数据
        $currentLogic = new CurrentLogic();
        $currentList  = $currentLogic->getAppHomeV4Current($userId,$client);
        //格式化活期信息
        $currentList  = $currentLogic->formatAppV4ListCurrentData($currentList);

        return AppLogic::callSuccess($currentList);
    }



}
