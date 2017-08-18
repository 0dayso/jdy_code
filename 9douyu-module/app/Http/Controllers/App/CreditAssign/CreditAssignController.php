<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/5
 * Time: 下午7:17
 */

namespace App\Http\Controllers\App\CreditAssign;


use App\Http\Controllers\App\UserController;
use App\Http\Logics\CreditAssign\CreditAssignLogic;
use Illuminate\Http\Request;

class CreditAssignController extends UserController
{

    /**
     *
     * @SWG\Post(
     *   path="/user_pre_credit_assign",
     *   tags={"APP-CreditAssign:债权转让相关接口"},
     *   summary="确认转让信息页面数据 [CreditAssign\CreditAssignController@userPreCreditAssign]",
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
     *     description="债权信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="债权信息获取失败。",
     *   )
     * )
     */

    /**
     * @param Request $request
     * @return array
     */
    public function userPreCreditAssign(Request $request){

        $investId = $request->input('id', 0);

        $logic = new CreditAssignLogic();

        $data = $logic -> userPreCreditAssign($investId);

        return self::appReturnJson($data);

    }

    /**
     * @SWG\Post(
     *   path="/user_do_credit_assign",
     *   tags={"APP-CreditAssign:债权转让相关接口"},
     *   summary="执行项目的债权转让操作 [CreditAssign\CreditAssignController@userDoCreditAssign]",
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
     *     @SWG\Parameter(
     *      name="assignPrincipal",
     *      in="formData",
     *      description="转让金额",
     *      required=true,
     *      type="integer",
     *      default="0",
     *   ),
     *     @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="projectId",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *      default="0",
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
     *     description="债权信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="债权信息获取失败。",
     *   )
     * )
     */

    public function userDoCreditAssign(Request $request){

        //投资ID(新系统按投资ID进行债转)
        //$projectId           = $request->input('projectId','');
        $investId           = $request->input('projectId','');
        //债转金额(目前为全部转让)
        $cash               = $request->input('assignPrincipal',0);
        //交易密码
        $tradingPassword    = $request->input('trading_password','');
        //获取用户信息
        $userInfo           = $this->getUser();

        $logic = new CreditAssignLogic();

        $data = $logic -> userDoCreditAssign($investId,$cash,$tradingPassword,$userInfo);

        return self::appReturnJson($data);

    }

    /**
     * @SWG\Post(
     *   path="/user_credit_assign",
     *   tags={"APP-CreditAssign:债权转让相关接口"},
     *   summary="债权转让列表 [CreditAssign\CreditAssignController@userCreditAssign]",
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
     *     description="债权信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="债权信息获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 债权转让列表
     */
    public function userCreditAssign(Request $request){

        $logic = new CreditAssignLogic();

        $userId = $this->userId;

        $data = $logic -> userCreditAssign($userId);

        return self::appReturnJson($data);

    }

    /**
     * @SWG\Post(
     *   path="/user_cancel_credit_assign",
     *   tags={"APP-CreditAssign:债权转让相关接口"},
     *   summary="取消转让中的项目 [CreditAssign\CreditAssignController@userCancelCreditAssign]",
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
     *    @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="债权信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="债权信息获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 取消转让中的项
     */
    public function userCancelCreditAssign(Request $request){

        $id = $request->input('id',0);
        $tradingPassword = $request->input('trading_password','');
        $userInfo           = $this->getUser();


        $logic = new CreditAssignLogic();

        $data = $logic -> userCancelCreditAssign($id,$tradingPassword,$userInfo);

        return self::appReturnJson($data);

    }

    /**
     * @SWG\Post(
     *   path="/invest_credit_assign",
     *   tags={"APP-CreditAssign:债权转让相关接口"},
     *   summary="投资债转项目 [CreditAssign\CreditAssignController@doInvest]",
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
     *    @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *    @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="投资金额",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *     @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="投资债权成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="投资债权失败。",
     *   )
     * )
     */
    public function doInvest(Request $request){

        $projectId = $request->input('project_id',0);
        $cash      = $request->input('cash',0);
        $tradingPassword = $request->input('tradingPassword','');

        $userInfo = $this->getUser();
        $logic = new CreditAssignLogic();

        $data = $logic -> doInvest($projectId,(int)$cash,$tradingPassword,$userInfo, $this->getClient());

        return self::appReturnJson($data);
    }

    /**
     *
     */
    public function userCreditAssignDesc(){

        $logic = new CreditAssignLogic();

        $data  = $logic -> userCreditAssignDesc();

        return self::appReturnJson($data);

    }

}