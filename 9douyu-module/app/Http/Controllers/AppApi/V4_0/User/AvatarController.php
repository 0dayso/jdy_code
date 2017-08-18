<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 17/03/22
 * Time: 下午12:01
 * Desc: 头像
 */

namespace App\Http\Controllers\AppApi\V4_0\User;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\User\AvatarLogic;
use Illuminate\Http\Request;


class AvatarController extends AppController
{
    /**
     * @SWG\Post(
     *   path="/up_avatar",
     *   tags={"APP-User"},
     *   summary="用户上传头像接口 -> [User\AvatarController@upAvatar]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="头像 -> 上传成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="头像 -> 上传失败。",
     *   )
     * )
     */
    public function upAvatar(Request $request){

        $client  = strtolower($request->input('client'));
        $version = strtolower($request->input('version'));
        $userId  = $this->getUserId();
        //二进制数据流
        $data = !isset($_FILES['file']) ? [] : $_FILES['file'] ;

        $avatarLogic = new AvatarLogic();
        $result = $avatarLogic->upAvatar($userId,$client,$version,$data);

        return $this->returnJsonData($result);
    }
}
