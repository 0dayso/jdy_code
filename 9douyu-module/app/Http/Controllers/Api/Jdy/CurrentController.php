<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/21
 * Time: 16:26
 */


namespace App\Http\Controllers\Api\Jdy;

use App\Http\Controllers\Controller;
use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Requests\Invest\CurrentRequest;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;
use App\Http\Logics\Project\CurrentLogic as CurrentProjectLogic;
use Illuminate\Support\Facades\Redirect;
use App\Http\Logics\Current\RateLogic;

use Cache;

class CurrentController extends Controller
{

    /**
     * @param CurrentRequest $request
     * 零钱计划转出对接九斗鱼
     * todo 上线后可删除
     */

    /**
     * @SWG\Post(
     *   path="/current/doInvestOutApi",
     *   tags={"JDY-Api"},
     *   summary="零钱计划转出 [Api\Jdy\CurrentController@doInvestOutApi]",
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="转出金额",
     *      required=true,
     *      type="integer",
     *      default="100",
     *   ),
     *   @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="string",
     *      default="82692",
     *   ),
     *   @SWG\Parameter(
     *      name="from",
     *      in="formData",
     *      description="三端来源",
     *      type="array",
     *      required=true,
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="pc",
     *      enum={"pc","wap","ios","android"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="用户零钱计划转出成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="用户零钱计划转出失败。",
     *   )
     * )
     */
    public function doInvestOutApi(Request $request)
    {

        $data = $request->all();

        $cash = (float)$request->input('cash', 0);
        $data['cash'] = ToolMoney::formatDbCashAdd($cash);

        $logic = new CurrentLogic();
        $result = $logic->doInvestOut($data);

        return self::returnJson($result);

    }


    /**
     * @param CurrentRequest $request
     * 零钱计划转入九斗鱼对接
     * todo 上线后可删除
     */
    /**
     * @SWG\Post(
     *   path="/current/doInvestApi",
     *   tags={"JDY-Api"},
     *   summary="零钱计划转入 [Api\Jdy\CurrentController@doInvestApi]",
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="投资金额",
     *      required=true,
     *      type="integer",
     *      default="100",
     *   ),
     *   @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="string",
     *      default="82692",
     *   ),
     *   @SWG\Parameter(
     *      name="from",
     *      in="formData",
     *      description="三端来源",
     *      type="array",
     *      required=true,
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="pc",
     *      enum={"pc","wap","ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="bonus_id",
     *      in="formData",
     *      description="加息券ID",
     *      required=false,
     *      type="integer",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="用户零钱计划转入成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="用户零钱计划转入失败。",
     *   )
     * )
     */
    public function doInvestApi(Request $request){

        $data               = $request->all();
        $data['cash']       = ToolMoney::formatDbCashAdd($data['cash']);    //金额处理成分

        $data['bonus_id']   = $request->input('bonus_id',0);

        $logic              = new CurrentLogic();
        $result             = $logic->doInvest($data);

        return $result;

    }

    /**
     * @param Request $request
     * 创建零钱计划项目,与九斗鱼进行对接
     * todo 上线后可删除
     */

    /**
     * @SWG\Post(
     *   path="/current/project/doCreateApi",
     *   tags={"JDY-Api"},
     *   summary="创建零钱计划项目 [Api\Jdy\CurrentController@doCreateProjectApi]",
     *   @SWG\Parameter(
     *      name="total_amount",
     *      in="formData",
     *      description="项目总金额",
     *      required=true,
     *      type="integer",
     *      default="1000000",
     *   ),
     *   @SWG\Parameter(
     *      name="project_name",
     *      in="formData",
     *      description="项目名称",
     *      required=true,
     *      type="string",
     *      default="零钱计划",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="用户零钱计划转出成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="用户零钱计划转出失败。",
     *   )
     * )
     */
    public function doCreateProjectApi(Request $request)
    {

        $totalAmount = (int)$request->input('total_amount', 0);
        $projectName = $request->input('project_name', '');

        $totalAmount = ToolMoney::formatDbCashAdd($totalAmount);

        $logic = new CurrentProjectLogic();
        $result = $logic->create($projectName, $totalAmount);

        return self::returnJson($result);
    }



    /**
     * @param Request $request
     * 后台添加零钱计划利率对接九斗鱼
     * todo 上线后可删除
     */


    /**
     * @SWG\Post(
     *   path="/current/rate/doCreateApi",
     *   tags={"JDY-Api"},
     *   summary="创建零钱计划利率 [Api\Jdy\CurrentController@doCreateRateApi]",
     *   @SWG\Parameter(
     *      name="rate",
     *      in="formData",
     *      description="基准利率",
     *      required=true,
     *      type="integer",
     *      default="7",
     *   ),
     *   @SWG\Parameter(
     *      name="rate_date",
     *      in="formData",
     *      description="日期",
     *      required=true,
     *      type="string",
     *      default="2016-07-04",
     *   ),
     *   @SWG\Parameter(
     *      name="profit",
     *      in="formData",
     *      description="加息利率",
     *      required=false,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="添加零钱计划利率成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="添加零钱计划利率失败。",
     *   )
     * )
     */
    public function doCreateRateApi(Request $request){

        $rate       = $request->input('rate',0);   //利率
        $date       = $request->input('rate_date','');  //日期
        $profit     = $request->input('profit',0);     //加息利率

        $logic      = new RateLogic();
        $logicResult = $logic->create($date,$rate,$profit);

        return self::returnJson($logicResult);

    }

}