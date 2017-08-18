<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/1/24
 * Time: 下午16:37
 */

namespace App\Http\Controllers\AppApi\V4_0\Project;

use App\Http\Controllers\AppApi\AppController;
use Illuminate\Http\Request;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\CreditAssign\CreditAssignLogic;

class CreditAssignController extends AppController{

    /**
     * @SWG\Post(
     *   path="/assign_project",
     *   tags={"APP-Project"},
     *   summary="理财列表-债权转让 [Project\CreditAssignController@index]",
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
     *      default="4.0.0",
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
     *      name="page",
     *      in="formData",
     *      description="页数",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="条数",
     *      required=true,
     *      type="string",
     *      default="10",
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
     * @desc 债权转让列表
     * @param $request array
     * @return array $creditAssignData
     */
    public function index(Request $request){

        $creditAssignData = [];

        $creditAssignLogic = new CreditAssignLogic();

        $page = $request->input('page', 1);

        $size = $request->input('size', 10);

        $creditAssignData = $creditAssignLogic->assignAppV4Project($page, $size);

//        if($creditAssignData['status'] == true){

            //格式化债转的接口
            $creditAssignData = $creditAssignLogic->formatAppV4AssignProject($creditAssignData['data']);

            return AppLogic::callSuccess($creditAssignData);
//        }

//        return AppLogic::callError(AppLogic::CODE_NO_MORE_ASSIGN_LIST);
    }

    /**
     * @SWG\Post(
     *   path="/user_do_credit_assign",
     *   tags={"APP-Project"},
     *   summary="执行项目的债权转让操作 [Project\CreditAssignController@userDoCreditAssign]",
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
     *      default="4.0.0",
     *   ),
     *   @SWG\Parameter(
     *      name="invest_id",
     *      in="formData",
     *      description="投资ID",
     *      required=true,
     *      type="number",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="assign_principal",
     *      in="formData",
     *      description="债转金额",
     *      required=true,
     *      type="number",
     *      default="1000",
     *   ),
     *   @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="qwe123",
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
     *     description="信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="信息获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * 执行项目的债权转让操作
     */
    public function userDoCreditAssign(Request $request){

        //投资ID(新系统按投资ID进行债转)
        $investId           = $request->input('invest_id','');
        //债转金额(目前为全部转让)
        $cash               = $request->input('assign_principal',0);
        //交易密码
        $tradingPassword    = $request->input('trading_password','');
        //获取用户信息
        $userInfo           = $this->getUser();

        $logic = new CreditAssignLogic();

        $data = $logic -> userDoCreditAssign($investId,$cash,$tradingPassword,$userInfo);

        return self::returnJsonData($data);

    }


    /**
     * @SWG\Post(
     *   path="/user_do_cancel_credit_assign",
     *   tags={"APP-Project"},
     *   summary="执行取消项目的债权转让操作 [Project\CreditAssignController@userDoCancelCreditAssign]",
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
     *      default="4.0.0",
     *   ),
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="债转项目ID",
     *      required=true,
     *      type="number",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="qwe123",
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
     *     description="信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="信息获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * 取消债权转让
     */
    public function userDoCancelCreditAssign(Request $request){

        $id = $request->input('id',0);
        $tradingPassword = $request->input('trading_password','');
        $userInfo           = $this->getUser();


        $logic = new CreditAssignLogic();

        $data = $logic -> userCancelCreditAssign($id,$tradingPassword,$userInfo);

        return self::returnJsonData($data);

    }

    /**
     * @SWG\Post(
     *   path="/credit_assign_project_invest",
     *   tags={"APP-Project"},
     *   summary="投资债转项目 [V4_0\Project\CreditAssignController@creditAssignProjectInvest]",
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
     *   @SWG\Parameter(
     *      name="payment_type",
     *      in="formData",
     *      description="支付类型 1.current-零钱投资 2.balance(或不选)-余额投资",
     *      required=false,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="",
     *      enum={"current"}
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
    public function creditAssignProjectInvest(Request $request){

        $projectId = $request->input('project_id',0);
        $cash      = $request->input('cash',0);
        $tradingPassword = $request->input('trading_password','');
        $investType      = $request->input('payment_type','');

        $userInfo = $this->getUser();
        $logic = new CreditAssignLogic();

        if(!empty($investType) && $investType == 'current'){
            $data = $logic -> doInvestByCurrent($projectId,(int)$cash,$tradingPassword,$userInfo, $this->client);
        }else{
            //投资操作
            $data = $logic -> doInvest($projectId,(int)$cash,$tradingPassword,$userInfo, $this->client);
        }

        return self::returnJsonData($data);
    }

    /**
     * @SWG\Post(
     *   path="/credit_assign_project_detail",
     *   tags={"APP-Project"},
     *   summary="债转项目详情 [V4_0\Project\CreditAssignController@creditAssignProjectDetail]",
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
    /**
     * @param Request $request
     * @return array
     * @desc
     */
    public function creditAssignProjectDetail(Request $request){

        $id = $request->input('project_id', 0);

        $userId = $this->getUserId();

        $logic = new CreditAssignLogic();

        $data = $logic -> creditAssignDetailV4Format($id, $userId);

        return self::returnJsonData($data);

    }


}
