<?php
/**
 * User: caelyn
 * Date: 16/7/1
 * Time: 下午2:40
 * Desc: 投资相关
 */
namespace App\Http\Controllers\App\Project;

use App\Http\Controllers\App\AppController;
use App\Http\Logics\CreditAssign\CreditAssignLogic;
use App\Http\Logics\User\PasswordLogic;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Lang\AppLang;
use Illuminate\Http\Request;
use App\Tools\ToolMoney;

class InvestController extends AppController
{
	/**
     * @SWG\Post(
     *   path="/project_left_amount",
     *   tags={"APP-Invest"},
     *   summary="项目可投金额 [Project\InvestController@projectLeftAmount]",
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
     *      name="project_id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取项目可投金额成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取项目可投金额失败。",
     *   )
     * )
     */
	public function projectLeftAmount(Request $request)
	{
		$projectId = $request->input('project_id');

		$ProjectLinkCreditModel = new ProjectLinkCreditModel();

          $project   = $ProjectLinkCreditModel->getCoreProjectDetail($projectId);

		$data['items'] = ToolMoney::formatDbCashDelete($project['total_amount'] - $project['invested_amount']);
          
		return $this->appReturnJson(self::callSuccess($data));
	}

	/**
     * @SWG\Post(
     *   path="/invest_profit",
     *   tags={"APP-Invest"},
     *   summary="项目预期收益(计算器，加息奖励，预期收益接口) [Project\InvestController@getInvestProfit]",
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
     *      name="project_id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="user_bonus_id",
     *      in="formData",
     *      description="使用红包ID",
     *      required=true,
     *      type="integer",
     *      default="0"
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="返回利息收益 ｜ 返回预期收益",
     *      required=true,
     *      type="integer",
     *      default="0"
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
     *      name="project_way",
     *      in="formData",
     *      description="类型",
     *      required=true,
     *      type="integer",
     *      default="20"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取项目预期收益成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取项目预期收益失败。",
     *   )
     * )
     */
	public function getInvestProfit(Request $request) 
	{

        $projectId = $request->input('project_id');

		$userBonusId = $request->input('user_bonus_id',0);

		$type = $request->input('type',0);

		$cash = $request->input('cash',10000);

		$project_way = $request->input('project_way',30);//默认旧系统九省心type

        if($project_way == 40){ //债权转让

            $creditAssignLogic = new CreditAssignLogic();

            $profit = $creditAssignLogic->getInvestProfit($projectId, $cash, $type);


        }else{

            $projectDetailLogic = new ProjectDetailLogic();

            $profit = $projectDetailLogic->getInvestProfit($projectId,$userBonusId,$type,$cash,$project_way);

        }

        return $this->appReturnJson($profit);
	}

     /**
     * @SWG\Post(
     *   path="/invest_project",
     *   tags={"APP-Invest"},
     *   summary="定期投资 [Project\InvestController@termInvest]",
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
     *      description="使用红包ID",
     *      required=true,
     *      type="integer",
     *      default="0"
     *   ),
     *   @SWG\Parameter(
     *      name="tradingPassword",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="integer",
     *      default="0"
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
     public function termInvest(Request $request){

          $userId             = $this->checkUserIdIsLogin();

          $projectId          = $request->input('project_id');

          $cash               = $request->input('cash');

          $tradePassword      = $request->input('tradingPassword');

          $userBonusId        = $request->input('bonus_id');

          $source             = RequestSourceLogic::getSource();

          $termLogic          = new TermLogic();

          $passwordLogic      = new PasswordLogic();

          $checkRes = $passwordLogic->checkTradingPasswordForApp($tradePassword, $userId);

          if( !$checkRes['status'] ){

                return $this->appReturnJson($checkRes, self::CODE_ERROR);

          }

          //投资操作
          $invest = $termLogic->doInvest($userId,$projectId,$cash,$tradePassword,$userBonusId,$source);

          if(!$invest['status']){

               return $this->appReturnJson($invest);

          }

          //投资返回app数据
          $invest['data'] = $termLogic->getInvestBackForApp($projectId,$cash,$userBonusId);

          return $this->appReturnJson($invest);
     }

     //投资债转
     public function creditInvest(Request $request){
          return $this->appReturnJson(self::callError(AppLang::APP_PROJECT_INFO_ERROR));
     }
}




