<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:57
 * Desc: 零钱计划收益相关
 */

namespace App\Http\Controllers\App\Refund;

use App\Http\Controllers\App\AppController;
use App\Http\Logics\User\UserLogic;
use Illuminate\Http\Request;

class CurrentController extends AppController
{

    /**
     * @SWG\Post(
     *   path="/current_interest_history",
     *   tags={"APP-Current"},
     *   summary="零钱计划近一周收益列表 [Refund\CurrentController@interestList]",
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
     *     description="获取零钱计划近一周收益列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划近一周收益列表失败。",
     *   )
     * )
     */
    public function interestList(Request $request)
    {

        $userId = $this->getUserId();

        $logic = new UserLogic();
        $result = $logic->getCurrentInterestList($userId);

        return self::appReturnJson($result);
    }
}