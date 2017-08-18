<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:53
 * Desc: 实名认证
 */
namespace App\Http\Controllers\App\Version;

use App\Http\Controllers\App\AppController;

use App\Http\Logics\Version\VersionLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Http\Request;
/**
 * 版本
 *
 * Class VersionController
 * @package App\Http\Controllers\App\user
 */
class VersionController extends AppController{

    /**
     * @SWG\Post(
     *   path="/check_version",
     *   tags={"APP-Version"},
     *   summary="检测版本更新接口 [Version\VersionController@checkVersion]",
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
     *     description="版本检测 -> 成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="版本检测 -> 失败。",
     *   )
     * )
     */
    public function checkVersions(Request $request){
        //todo app 下载地址 如安卓：app/dl/jiudouyu_2.1.103.apk

        $client       = strtolower($request->input('client'));
        $version      = strtolower($request->input('version'));
        $userId       = $this->getUserId();
        $versionLogic = new VersionLogic;
        $result       = $versionLogic->checkVersion($client, $version, $userId);

        $this->appReturnJson($result);
    }
}