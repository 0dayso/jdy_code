<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:53
 * Desc: 实名认证
 */
namespace App\Http\Controllers\App\user;

use App\Http\Controllers\App\AppController;

use App\Http\Logics\Logic;
use App\Http\Logics\User\AvatarLogic;
use App\Http\Logics\User\SessionLogic;

/**
 * 头像
 *
 * Class AvatarController
 * @package App\Http\Controllers\App\user
 */
class AvatarController extends AppController{

    /**
     * @SWG\Post(
     *   path="/get_avatar_url",
     *   tags={"APP-User"},
     *   summary="请求用户头像接口 -> [User\AvatarController@getAvatar]",
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
     *     description="头像 -> 获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="头像 -> 获取失败。",
     *   )
     * )
     */
    public function getAvatar(){
        //todo 静态头像资源依赖：http://www.9douyu.com/resources/image/日期目录/头像名称
        $logicResult = AvatarLogic::getAvatar();
        $this->appReturnJson($logicResult);
    }


    /**
     * @一力 兼容 ios 是否登录
     */
    public function getAvatarInfo(){
        $userInfo = SessionLogic::getTokenSession();
        if($userInfo){
            $data =  Logic::callSuccess();
        }else{
            $data =  Logic::callError('请先登陆');
        }
        $this->appReturnJson($data);
    }


    /**
     * @SWG\Post(
     *   path="/up_avatar",
     *   tags={"APP-User"},
     *   summary="请求用户上传头像接口 -> [User\AvatarController@upAvatar]",
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
     *     description="头像 -> 获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="头像 -> 获取失败。",
     *   )
     * )
     */
    public function upAvatar(){
        $result = ["status" => "4042", "msg" => "系统升级 暂不支持上传头像"];
        $result = Logic::callError('系统升级 暂不支持上传头像' , 4042, $result);
        $this->appReturnJson($result);
    }
}