<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/2/27
 * Time: 下午12:07
 */

namespace App\Http\Controllers\AppApi\V4_0\Fund;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Fund\FundHistoryLogic;
use Dingo\Api\Http\Request;

class FundHistoryController extends AppController
{

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/fund_history",
     *   tags={"APP-Fund"},
     *   summary="资金明细 [Fund\FundHistoryController@getList]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
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
     *      description="版本号",
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
     *      name="type",
     *      in="formData",
     *      description="查询类型",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="all",
     *      enum={"all","reward","recharge","withdraw","invest","refund","investCurrent","outCurrent"}
     *   ),
     *   @SWG\Parameter(
     *      name="start_time",
     *      in="formData",
     *      description="开始时间",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="end_time",
     *      in="formData",
     *      description="结束时间",
     *      required=false,
     *      type="string",
     *      default="",
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
     *      default="5",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取数据成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */
    public function getList(Request $request){
        $data = $request->all();
        $data['user_id'] = $this->getUserId();

        $logic      = new FundHistoryLogic();
        $result = $logic->getApp4List($data);

        return $this->returnJsonData($result);
    }

}