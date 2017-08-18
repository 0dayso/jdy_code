<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/12
 * Time: 下午6:19
 * Desc: 定期项目
 */

namespace App\Http\Controllers\Project\Invest;

use App\Http\Controllers\Controller;
use App\Http\Logics\Invest\InvestLogic;
use App\Http\Logics\Invest\ProjectLogic;
use App\Http\Logics\Logic;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;


class ProjectController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/project/invest",
     *   tags={"Project"},
     *   summary="定期项目投资",
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
     *      name="project_id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *   ),
     *     @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *   ),
     *     @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="投资金额",
     *      required=true,
     *      type="integer",
     *   ),
     *  @SWG\Parameter(
     *      name="bonus_cash",
     *      in="formData",
     *      description="红包金额",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="投资定期期项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="投资定期期项目失败。",
     *   )
     * )
     */
    public function invest(Request $request)
    {

        $userId = (int)$request->input('user_id');

        $projectId = (int)$request->input('project_id');

        $cash = (int)$request->input('cash');

        $bonusCash = (int)$request->input('bonus_cash');

        $bonusRate = $request->input('bonus_rate');

       // $cash = $cash + $bonusCash;

        $cash = ToolMoney::formatDbCashAdd($cash);

        $cash = abs($cash);

        $bonusCash = abs($bonusCash);

        $logic = new ProjectLogic();

        $return = $logic->invest($userId, $projectId, $cash, $bonusCash, $bonusRate);

        if( $return['status'] ){

            $returnJson = Logic::callSuccess($return['data']);

        }else{

            $returnJson = Logic::callError($return['msg'], $return['code'], $return['data']);

        }

        self::returnJson($returnJson);
    }

    /**
     * @SWG\Post(
     *   path="/project/investByCurrent",
     *   tags={"Project"},
     *   summary="零钱计划投资定期项目",
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
     *      name="project_id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="integer",
     *   ),
     *     @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *   ),
     *     @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="投资金额",
     *      required=true,
     *      type="integer",
     *   ),
     *  @SWG\Parameter(
     *      name="bonus_cash",
     *      in="formData",
     *      description="红包金额",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="零钱计划投资定期期项目成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="零钱计划投资定期期项目失败。",
     *   )
     * )
     */
    public function investByCurrent(Request $request)
    {

        $userId = (int)$request->input('user_id');

        $projectId = (int)$request->input('project_id');

        $cash = (int)$request->input('cash');

        $bonusCash = (int)$request->input('bonus_cash');

        $bonusRate = $request->input('bonus_rate');

        $cash = ToolMoney::formatDbCashAdd($cash);

        $cash = abs($cash);

        $bonusCash = abs($bonusCash);

        $logic = new ProjectLogic();

        $return = $logic->investByCurrent($userId, $projectId, $cash, $bonusCash, $bonusRate);

        if( $return['status'] ){

            $returnJson = Logic::callSuccess($return['data']);

        }else{

            $returnJson = Logic::callError($return['msg'], $return['code'], $return['data']);

        }

        self::returnJson($returnJson);
    }

    /**
     * @SWG\Post(
     *   path="/invest/getListByIds",
     *   tags={"Project"},
     *   summary="通过多个id获取投资列表",
     *   @SWG\Parameter(
     *      name="invest_ids",
     *      in="formData",
     *      description="（多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="1,2,3",
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
    public function getListByIds(Request $request){

        $investIds = $request->input('invest_ids');

        $investLogic = new InvestLogic();

        $investList = $investLogic->getInvestListByIds($investIds);

        $return = Logic::callSuccess($investList);

        self::returnJson($return);
    }
    /**
     * @SWG\Post(
     *   path="/invest/getNormalInvestByProjectIds",
     *   tags={"Project"},
     *   summary="通过多个id获取投资列表",
     *   @SWG\Parameter(
     *      name="project_ids",
     *      in="formData",
     *      description="（多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="1,2,3",
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

    public function getNormalInvestListByProjectIds(Request $request){

        $projectIds     =   $request->input('project_ids');

        $investLogic    =   new InvestLogic();

        $investList     =   $investLogic->getNormalInvestListByProjectIds($projectIds);

        $return         =   Logic::callSuccess($investList);

        self::returnJson($return);
    }
    /**
     * @SWG\Post(
     *   path="/invest/getLastInvestTimeByProjectId",
     *   tags={"Project"},
     *   summary="通过多个id获取投资列表",
     *   @SWG\Parameter(
     *      name="project_ids",
     *      in="formData",
     *      description="（多个逗号隔开）",
     *      required=true,
     *      type="string",
     *      default="1,2,3",
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
    public function getLastInvestTimeByProjectId(Request $request){

        $projectIds     = $request->input('project_ids');

        $investLogic    = new InvestLogic();

        $investList     = $investLogic->getLastInvestTimeByProjectId($projectIds);

        $return         = Logic::callSuccess($investList);

        self::returnJson($return);
    }
}