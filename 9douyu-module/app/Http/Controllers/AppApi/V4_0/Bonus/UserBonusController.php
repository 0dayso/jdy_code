<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/2/28
 * Time: 下午2:11
 */

namespace App\Http\Controllers\AppApi\V4_0\Bonus;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use Illuminate\Http\Request;

class UserBonusController extends AppController
{

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/user_bonus",
     *   tags={"APP-User"},
     *   summary="用户优惠券 [Bonus\UserBonusController@getUserBonus]",
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
     *      description="查询类型(1:可用,2:已使用,3:过期)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="1",
     *      enum={"1","2","3"}
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页数",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页条数",
     *      required=true,
     *      type="string",
     *      default="5",
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
    public function getUserBonus(Request $request){
        $userId = $this->getUserId();

        $type = (int)$request->input('type', 1);
        $page = (int)$request->input('page', 1);
        $size = (int)$request->input('size', 10);

        $logic = new UserBonusLogic();

        $res = $logic -> getUserBonusList($userId, $page, $size, $type);

        return $this->returnJsonData($res);
    }
}