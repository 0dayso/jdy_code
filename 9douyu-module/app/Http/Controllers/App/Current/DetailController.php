<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:41
 * Desc: 零钱计划详情
 */

namespace App\Http\Controllers\App\Current;

use App\Http\Controllers\App\AppController;
use App\Http\Logics\Project\CurrentLogic;
use Illuminate\Http\Request;

class DetailController extends AppController{


    /**
     * @SWG\Post(
     *   path="/current_project_detail",
     *   tags={"APP-Current"},
     *   summary="零钱计划项目详情 [Current\DetailController@get]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划项目详情成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划项目详情失败。",
     *   )
     * )
     */
    public function get(Request $request){

        $userId = $this->getUserId();
        $logic      = new CurrentLogic();
        $result     = $logic->getDetail($userId);

        return self::appReturnJson($result);
    }


    
}
