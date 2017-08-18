<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/27
 * Time: 下午5:02
 * Desc: 获取App配置
 */

namespace App\Http\Controllers\App\Server;


use App\Http\Controllers\App\AppController;
use App\Http\Logics\Server\ServerLogic;
use Illuminate\Http\Request;

class ServerController extends AppController
{

    /**
     * @SWG\Post(
     *   path="/get_server_list",
     *   tags={"APP-Config"},
     *   summary="app域名切换配置 [Server\ServerController@getServerList]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取app域名切换配置成功。",
     *   ),
     * )
     */
    public function getServerList(Request $request){

        $logic = new ServerLogic();

        $client = empty($this -> client)?'ios':$this -> client;

        $res = $logic -> getServerLogic($client);

        return self::appReturnJson($res);

    }

}