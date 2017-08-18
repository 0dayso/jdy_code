<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/6
 * Time: 14:18
 */
namespace App\Http\Controllers\App\User;

use App\Http\Controllers\App\UserController;
use App\Http\Logics\User\SuggestLogic;
use Illuminate\Http\Request;

class SuggestController extends UserController{



    /**
     * @SWG\Post(
     *   path="/suggest_add",
     *   tags={"APP-User"},
     *   summary="添加用户反馈意见 [User\SuggestController@addSuggest]",
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
     *      name="phone_type",
     *      in="formData",
     *      description="手机型号",
     *      required=true,
     *      type="string",
     *      default="iphone 6s",
     *   ),
     *     @SWG\Parameter(
     *      name="phone_version",
     *      in="formData",
     *      description="手机版本",
     *      required=true,
     *      type="string",
     *      default="9.1.10",
     *   ),
     *     @SWG\Parameter(
     *      name="content",
     *      in="formData",
     *      description="意见内容",
     *      required=true,
     *      type="string",
     *      default="提现到账太慢",
     *   ),
     *     @SWG\Parameter(
     *      name="phone_sysytem_version",
     *      in="formData",
     *      description="手机操作版本",
     *      required=true,
     *      type="integer",
     *      default="9.1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="添加用户反馈意见成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="添加用户反馈意见失败。",
     *   )
     * )
     */
    public function addSuggest(Request $request){

        $post['content']                = $request->input('content','');//内容
        //设备信息
        $post['client']                 = $request->input('client',''); //来源端 ios android
        $post['version']                = $request->input('version','');    //版本号
        $post['phone_type']             = $request->input('phone_type',''); //手机型号
        $post['phone_version']          = $request->input('phone_version','');
        $post['phone_system_version']   = $request->input('phone_system_version',''); //操作系统版本

        $post['user_id']                = $this->getUserId();


        $logic      = new SuggestLogic();
        $result     = $logic->addSuggest($post);

        return self::appReturnJson($result);
    }
}