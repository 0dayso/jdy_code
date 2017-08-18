<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/8/5
 * Time: 下午6:43
 */

namespace App\Http\Controllers\Api\Jdy;


use App\Http\Controllers\Controller;
use App\Http\Logics\Project\ProjectLogic;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    /*-------------- 重构 API 添加项目 ----------------*/
    /**
     * @desc 执行项目创建
     * @param Request $request
     * @return array
     *
     *
     */
    /**
     * @SWG\Post(
     *   path="/project/doCreateApi",
     *   tags={"JDY-Api"},
     *   summary="创建定期项目 [Api\Jdy\ProjectController@apiDoCreate]",
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="项目Id",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="项目名称",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="total_amount",
     *      in="formData",
     *      description="项目融资总额",
     *      required=true,
     *      type="integer",
     *      default="100000",
     *   ),
     *   @SWG\Parameter(
     *      name="invest_days",
     *      in="formData",
     *      description="融资时间",
     *      required=true,
     *      type="integer",
     *      default="10",
     *   ),
     *   @SWG\Parameter(
     *      name="invest_time",
     *      in="formData",
     *      description="投资期限",
     *      required=true,
     *      type="integer",
     *      default="30",
     *   ),
     *   @SWG\Parameter(
     *      name="refund_type",
     *      in="formData",
     *      description="还款方式(10-到期还本息 20-先息后本 30-前置付息)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="10",
     *      enum={"10","20","30"}
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="项目类型(0-XX天 1-一月期 3-三月期 6-六月期 12-十二月期)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="0",
     *      enum={"0","1","3","6","12"}
     *   ),
     *   @SWG\Parameter(
     *      name="product_line",
     *      in="formData",
     *      description="产品线(100-九省心 200-九省心 300-前置付息)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="integer"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","200","300"}
     *   ),
     *   @SWG\Parameter(
     *      name="base_rate",
     *      in="formData",
     *      description="基准利率",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="after_rate",
     *      in="formData",
     *      description="平台加息",
     *      required=false,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="created_by",
     *      in="formData",
     *      description="创建人",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Parameter(
     *      name="publish_time",
     *      in="formData",
     *      description="项目发布时间",
     *      required=true,
     *      type="string",
     *   ),
     *
     *   @SWG\Response(
     *     response=200,
     *     description="定期项目创建成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="定期项目创建失败。",
     *   )
     * )
     */
    public function apiDoCreate(Request $request){

        $logic = new ProjectLogic();

        $data = $request->all();

        $result = $logic->apiDoCreate($data);

        return $result;

    }
    /*-------------- 重构 API 添加项目 ----------------*/

}