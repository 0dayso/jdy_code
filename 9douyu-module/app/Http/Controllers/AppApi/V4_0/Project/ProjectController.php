<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/1/24
 * Time: 下午10:33
 */

namespace App\Http\Controllers\AppApi\V4_0\Project;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Project\ProjectAppLogic;
use App\Http\Logics\User\PasswordLogic;
use Illuminate\Http\Request;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Dbs\Project\ProjectDb;

class ProjectController extends AppController{

    protected $projectAppLogic = null;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->projectAppLogic = new ProjectAppLogic();

    }

    /**
     * @SWG\Post(
     *   path="/project_index",
     *   tags={"APP-Project"},
     *   summary="理财列表-定期项目 [Project\ProjectController@index]",
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
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
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
     *     description="获取项目列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取项目列表失败。",
     *   )
     * )
     */

    /**
     * @desc app4.0定期理财列表
     * @return array
     */
    public function index(Request $request){

        $page = $request->input('page', 1);
        $size = $request->input('size', 6);

        $projectList = [];

        //App4.0首页定期项目列表
        $projectLogic = new ProjectLogic();

        $projectList   = $projectLogic->getAppV4ProjectList( [ProjectDb::PROJECT_PRODUCT_LINE_JSX, ProjectDb::PROJECT_PRODUCT_LINE_JAX], $page, $size, [ProjectDb::STATUS_INVESTING, ProjectDb::STATUS_REFUNDING, ProjectDb::STATUS_FINISHED]);

        return AppLogic::callSuccess($projectList);
    }

    /**
     * @SWG\Post(
     *   path="/project_detail",
     *   tags={"APP-Project"},
     *   summary="定期项目详情 [Project\ProjectController@projectDetail]",
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
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=false,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="项目Id",
     *      required=true,
     *      type="number",
     *      default="3466",
     *   ),
     *   @SWG\Parameter(
     *      name="uuid",
     *      in="formData",
     *      description="uuid",
     *      required=true,
     *      type="number",
     *      default="1",
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
    /**
     * @param Request $request
     * @return array
     */
    public function projectDetail(Request $request){

        $projectId = $request->input('project_id', 0);

        $actToken  = $request->input('act_token' , '') ;

        $actToken  = !empty( $actToken ) ? $actToken : $request->input('activity');

        $userId = $this->getUserId();

        $result = $this->projectAppLogic->getAppV4ProjectDetail($projectId, $userId , $actToken);

        return $this->returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/project_get_interest",
     *   tags={"APP-Project"},
     *   summary="定期预期收益 [Project\ProjectController@projectGetInterest]",
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
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=false,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="项目Id",
     *      required=true,
     *      type="number",
     *      default="3466",
     *   ),
     *   @SWG\Parameter(
     *      name="user_bonus_id",
     *      in="formData",
     *      description="优惠券Id",
     *      required=false,
     *      type="number",
     *      default="0",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="金额",
     *      required=true,
     *      type="number",
     *      default="10000",
     *   ),
     *   @SWG\Parameter(
     *      name="project_type",
     *      in="formData",
     *      description="项目类型 1债转 0正常定期",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="0",
     *      enum={"0","1"}
     *   ),
     *   @SWG\Parameter(
     *      name="uuid",
     *      in="formData",
     *      description="uuid",
     *      required=true,
     *      type="number",
     *      default="1",
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
    /**
     * @param Request $request
     * @return array
     * @desc 预期收益
     */
    public function projectGetInterest(Request $request){

        $projectId   = $request->input('project_id');

        $userBonusId = $request->input('user_bonus_id',0);

        $cash        = $request->input('cash',10000);

        $projectType = $request->input('project_type',0); // 0普通项目,1债转

        $result = $this->projectAppLogic->getAppV4ProjectGetInterest($cash, $projectId, $userBonusId, $projectType);

        return $this->returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/project_invest_records",
     *   tags={"APP-Project"},
     *   summary="定期项目投资记录 [Project\ProjectController@projectInvestRecords]",
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
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=false,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="项目Id",
     *      required=true,
     *      type="number",
     *      default="3466",
     *   ),
     *   @SWG\Parameter(
     *      name="uuid",
     *      in="formData",
     *      description="uuid",
     *      required=true,
     *      type="number",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="分页",
     *      required=true,
     *      type="number",
     *      default="1",
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
    /**
     * @param Request $request
     * @return array
     * 投资记录
     */
    public function projectInvestRecords(Request $request){

        $id              = $request->input('project_id');
        $page            = $request->input('page', 1);
        $size            = $request->input('size', 10);

        $logicResult = $this->projectAppLogic->getAppV4ProjectInvestRecords($id, $page, $size);

        return $this->returnJsonData($logicResult);

    }

    /**
     * @SWG\Post(
     *   path="/project_able_user_bonus",
     *   tags={"APP-Project"},
     *   summary="定期项目可用优惠券 [Project\ProjectController@projectAbleUserBonus]",
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
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=false,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="项目Id",
     *      required=true,
     *      type="number",
     *      default="3466",
     *   ),
     *   @SWG\Parameter(
     *      name="uuid",
     *      in="formData",
     *      description="uuid",
     *      required=true,
     *      type="number",
     *      default="1",
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
    /**
     * @param Request $request
     * @return array
     * 定期优惠券列表
     */
    public function projectAbleUserBonus(Request $request){

        $projectId = $request->input('project_id');

        $userId = $this->getUserId();

        $client = $this->client;

        $logicResult = $this->projectAppLogic->getAppV4ProjectAbleUserBonus($userId, $projectId, $client);

        return  $this->returnJsonData($logicResult);

    }

    /**
     * @SWG\Post(
     *   path="/project_refund_record",
     *   tags={"APP-Project"},
     *   summary="定期项目回款计划 [Project\ProjectController@projectRefundRecord]",
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
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="4.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=false,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="项目Id",
     *      required=true,
     *      type="number",
     *      default="3466",
     *   ),
     *   @SWG\Parameter(
     *      name="uuid",
     *      in="formData",
     *      description="uuid",
     *      required=true,
     *      type="number",
     *      default="1",
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
    public function projectRefundRecord(Request $request){

        $id = $request->input('project_id', 0);

        $result = $this->projectAppLogic->getAppV4ProjectRefundRecord( $id );

        return $this->returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/project_invest",
     *   tags={"APP-Project"},
     *   summary="定期投资 [Project\ProjectController@projectInvest]",
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
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
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
     *      name="project_id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="投资金额",
     *      required=true,
     *      type="integer",
     *      default="10000"
     *   ),
     *   @SWG\Parameter(
     *      name="user_bonus_id",
     *      in="formData",
     *      description="使用优惠券ID",
     *      required=true,
     *      type="integer",
     *      default="0"
     *   ),
     *     @SWG\Parameter(
     *      name="act_token",
     *      in="formData",
     *      description="活动token",
     *      required=true,
     *      type="string",
     *      default="1494666285_117_3732"
     *   ),
     *   @SWG\Parameter(
     *      name="tradingPassword",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="integer",
     *      default="0"
     *   ),
     *   @SWG\Parameter(
     *      name="novice_type",
     *      in="formData",
     *      description="是否新手项目 1.是 0.否",
     *      required=false,
     *      type="string",
     *      default="1",
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
     *     description="投资成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="投资失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * 定期投资
     */
    public function projectInvest(Request $request){

        $userId             = $this->getUserId();

        $projectId          = $request->input('project_id');

        $cash               = $request->input('cash');

        $tradePassword      = $request->input('tradingPassword');

        $userBonusId        = $request->input('user_bonus_id');

        $investType         = $request->input('payment_type','');

        $actToken           = $request->input('act_token');

        $actToken           = !empty($actToken) ? $actToken : $request->input('activity');

        $projectType        = $request->input('novice_type','');

        $source             = $this->client;

        $termLogic          = new TermLogic();

        $passwordLogic      = new PasswordLogic();

        //如果是新手项目，判断限制条件（普付宝质押项目，未投资过，投资限额）
        if(!empty($projectType)){

            $checkProjectLimit = $termLogic->checkNoviceProjectLimit($projectId, $userId, $cash);

            if( !$checkProjectLimit['status'] ){

                return $this->returnJsonData($checkProjectLimit);

            }

        }

        //验证交易密码
        $checkRes = $passwordLogic->checkTradingPasswordForApp($tradePassword, $userId);

        if( !$checkRes['status'] ){

            return $this->returnJsonData($checkRes);

        }

        $actToken   =   ProjectAppLogic::getActTokenByUserIdUseCache ($userId , $actToken);

        if(!empty($investType) && $investType == 'current'){
            //投资操作
            $invest = $termLogic->doInvestByCurrent($userId,$projectId,$cash,$tradePassword,$userBonusId,$source,$actToken);
        }else{
            //投资操作
            $invest = $termLogic->doInvest($userId,$projectId,$cash,$tradePassword,$userBonusId,$source,$actToken);
        }

        if($invest['status'] && !empty($userBonusId))
        {
            $invest['data'] = [
                'is_use_bonus' => 1,
            ];
        }

        /*if(!$invest['status']){

            return $this->returnJsonData($invest);

        }*/

        return $this->returnJsonData($invest);

        //投资返回app数据
        //$invest['data'] = $termLogic->getInvestBackForApp($projectId,$cash,$userBonusId);

        //return $this->returnJsonData($invest);

    }

}


