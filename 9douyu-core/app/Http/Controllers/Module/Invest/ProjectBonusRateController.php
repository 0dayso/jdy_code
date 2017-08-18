<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/6
 * Time: 下午3:47
 * Desc: 投资使用加息券模块相关接口
 */

namespace App\Http\Controllers\Module\Invest;

use App\Http\Controllers\Controller;
use App\Http\Logics\Module\Invest\RateLogic;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;

class ProjectBonusRateController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/project/invest/createRateRecord",
     *   tags={"Project"},
     *   summary="创建加息券的回款记录",
     *   @SWG\Parameter(
     *      name="profit",
     *      in="formData",
     *      description="加息券利率",
     *      required=true,
     *      type="string",
     *      default="2",
     *   ),
     *   @SWG\Parameter(
     *      name="invest_id",
     *      in="formData",
     *      description="投资成功id",
     *      required=true,
     *      type="integer",
     *   ),
     *  @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="创建回款记录成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="创建回款记录失败。",
     *   )
     * )
     */
    public function createRateRecord(Request $request)
    {

        $profit = $request->input('profit',0);

        $investId = $request->input('invest_id');

        $rateLogic = new RateLogic();

        $result = $rateLogic->createBonusRateRecord($investId, $profit);

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/project/invest/getPlanInterest",
     *   tags={"Project"},
     *   summary="投资确认页面预期收益",
     *   @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="项目id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="金额",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="profit",
     *      in="formData",
     *      description="加息券利率",
     *      required=true,
     *      type="integer",
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
     *     description="创建回款记录成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="创建回款记录失败。",
     *   )
     * )
     */
    public function getPlanInterest(Request $request)
    {

        $projectId = $request->input('project_id');

        $cash = $request->input('cash');

        $cash = ToolMoney::formatDbCashAdd($cash);

        $cash = abs($cash);

        $profit = $request->input('profit', 0);

        $logic = new RateLogic();

        $list = $logic->getPlanInterest($projectId, $cash, $profit);

        self::returnJson($list);


    }

}