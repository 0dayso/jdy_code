<?php
/**
 * 创建用户
 *
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/13
 * Time: 上午10:14
 */
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Logics\User\RegisterLogic;

/**
 * 用户注册接口
 * Class RegisterController
 * @package App\Http\Controllers\User
 */
class RegisterController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/user/register/create",
     *   tags={"User"},
     *   summary="用户注册",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="password",
     *      in="formData",
     *      description="密码(32-65位长度)",
     *      required=true,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="real_name",
     *      in="formData",
     *      description="身份证 名",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="identity_card",
     *      in="formData",
     *      description="身份证 号",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="注册成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="注册失败。",
     *   )
     * )
     */
    public function create(Request $request, RegisterLogic $registerLogic){

        //define request logic data.
        $logicCreateData      =[
                   'phone'         => $request->input('phone'),
                   'password_hash' => $request->input('password'),
                   'real_name'     => $request->input('real_name'),
                   'identity_card' => $request->input('identity_card'),
        ];
        // todo 【重构 API】 测试后移除本行
        $id = $request->input('id');
        if(!empty($id)) {
            $logicCreateData['id'] = $id;
        }
        //创建用户
        $logicReturn = $registerLogic->create($logicCreateData);

        self::returnJson($logicReturn);
    }

    /**
     * @SWG\Post(
     *   path="/user/register/doActivate",
     *   tags={"User"},
     *   summary="用户激活【暂时不用】",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="userId",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="激活用户成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="激活用户失败。",
     *   )
     * )
     */
    private function doActivate(Request $request, RegisterLogic $registerLogic){
        $userId       = $request->input('userId');
        // 激活用户
        $logicReturn  = $registerLogic->doActivate($userId);

        self::returnJson($logicReturn);
    }

}