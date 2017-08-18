<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/15
 * Time: 下午1:54
 * Desc: 系统配置
 */

namespace App\Http\Controllers\Module\SystemConfig;

use App\Http\Controllers\Controller;
use App\Http\Logics\Module\SystemConfig\SystemConfigLogic;
use Illuminate\Http\Request;

class SystemConfigController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/systemConfig/list",
     *   tags={"Common"},
     *   summary="系统配置列表",
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function index()
    {

        $logic = new SystemConfigLogic();

        $list = $logic->getList();

        self::returnJson($list);

    }

    /**
     * @SWG\Post(
     *   path="/systemConfig/get",
     *   tags={"Common"},
     *   summary="通过id获取配置详情",
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="配置id",
     *      required=true,
     *      type="integer",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function getInfoById( Request $request )
    {

        $id = $request->input('id');

        $logic = new SystemConfigLogic();

        $info = $logic->getInfoById($id);

        self::returnJson($info);

    }

    /**
     * @SWG\Post(
     *   path="/systemConfig/edit",
     *   tags={"Common"},
     *   summary="编辑配置信息",
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="配置id",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="名称",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="key",
     *      in="formData",
     *      description="unserialize 处理后的键名",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="value",
     *      in="formData",
     *      description="unserialize 处理后的键值",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="管理员id",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="状态 1：开启；0：关闭；",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="second_des",
     *      in="formData",
     *      description="二级参数的值描述",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function editInfo( Request $request )
    {

        $logic = new SystemConfigLogic();

        $data = $request->all();

        $id = isset($data['id']) ? $data['id'] : 0;

        $res = $logic->editInfo($id, $data);

        self::returnJson($res);

    }



    /**
     * @SWG\Post(
     *   path="/systemConfig/editByKey",
     *   tags={"Common"},
     *   summary="编辑配置信息(通过key来编辑)",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="名称",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="key",
     *      in="formData",
     *      description="键名",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="value",
     *      in="formData",
     *      description="serialize 处理后的键值",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="管理员id",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="状态 1：开启；0：关闭；",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="second_des",
     *      in="formData",
     *      description="二级参数的值描述",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="修改成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="修改失败。",
     *   )
     * )
     */
    public function editByKey(Request $request){

        $logic = new SystemConfigLogic();

        $data = $request->all();
        
        $key = isset($data['key']) ? $data['key'] : '';

        $res = $logic->editByKey($key, $data);

        self::returnJson($res);
    }

    /**
     * @SWG\Post(
     *   path="/systemConfig/add",
     *   tags={"Common"},
     *   summary="添加配置信息",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="名称",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="key",
     *      in="formData",
     *      description="unserialize 处理后的键名",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="value",
     *      in="formData",
     *      description="unserialize 处理后的键值",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="管理员id",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="状态 1：开启；0：关闭；",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="second_des",
     *      in="formData",
     *      description="二级参数的值描述",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function addInfo( Request $request )
    {

        $data = $request->all();

        $logic = new SystemConfigLogic();

        $res = $logic->addInfo($data);

        self::returnJson($res);

    }




}