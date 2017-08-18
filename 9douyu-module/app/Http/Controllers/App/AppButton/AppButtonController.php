<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/6
 * Time: 下午3:15
 */

namespace App\Http\Controllers\App\AppButton;


use App\Http\Controllers\App\AppController;
use App\Http\Logics\AppButton\AppButtonLogic;

class AppButtonController extends AppController
{

    /**
     * @SWG\Post(
     *   path="/menu_button",
     *   tags={"APP-Ad:广告相关接口"},
     *   summary="请求tabBar图片 [AppButton\AppButtonController@menuButton]",
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
     *      description="客户端版本号",
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
     *     description="按钮信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="按钮信息获取失败。",
     *   )
     * )
     */
    /**
     * @return array
     * @desc 请求tabBar图片
     */
    public function menuButton(){

        $logic = new AppButtonLogic();

        $result = $logic -> menuButton();

        return self::appReturnJson($result);

    }

}