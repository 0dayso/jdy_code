<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/12
 * Time: 上午11:13
 * Desc: 邀请关系信息
 */

namespace App\Http\Controllers\App\V3;

use App\Http\Controllers\App\AppController;
use App\Http\Logics\Invite\InviteLogic;
use Illuminate\Http\Request;

class InviteController extends AppController
{

    /**
     * @SWG\Post(
     *   path="/add_invite",
     *   tags={"APP-3.0"},
     *   summary="设置中心-添加合伙人-[V3\InviteController@addRecord]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
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
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default="",
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
     *      default="3.0",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取信息成功。",
     *   ),
     * )
     */
    public function addRecord( Request $request ){

        $phone = $request->input('phone');

        $userId = $this->getUserId();

        $logic = new InviteLogic();

        $user = $this->getUser();

        $myPhone = $user['phone'];

        $result = $logic->doAddAppInviteRecord($phone, $userId, $myPhone);

        return self::appReturnJson($result);

    }



}