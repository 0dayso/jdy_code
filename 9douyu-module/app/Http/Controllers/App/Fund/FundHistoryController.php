<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午3:08
 * Desc: 资金流水
 */
namespace App\Http\Controllers\App\Fund;

use App\Http\Controllers\App\UserController;
use App\Http\Logics\Fund\FundHistoryLogic;
use App\Http\Logics\RequestSourceLogic;
use Illuminate\Http\Request;

class FundHistoryController extends UserController{


    /**
     * @SWG\Post(
     *   path="/current_invest_history",
     *   tags={"APP-Current"},
     *   summary="零钱计划投资明细 [Fund\FundHistoryController@getCurrentInvestList]",
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
     *      name="p",
     *      in="formData",
     *      description="页码",
     *      required=false,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页记录条数",
     *      required=false,
     *      type="integer",
     *      default="10",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划投资明细成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划投资明细失败。",
     *   )
     * )
     */
    public function getCurrentInvestList(Request $request){

        $page       = $request->input('p',1);
        $size       = $request->input('size',10);
        $userId     = $this->getUserId();
        
        $logic      = new FundHistoryLogic();
        $result     = $logic->getCurrentInvestList($userId,$page,$size);

        return self::appReturnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/fund_history",
     *   tags={"APP-User"},
     *   summary="用户交易明细(资金流水) [Fund\FundHistoryController@getList]",
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
     *      name="p",
     *      in="formData",
     *      description="页码",
     *      required=false,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页记录条数",
     *      required=false,
     *      type="integer",
     *      default="10",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户交易明细(资金流水)成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户交易明细(资金流水)失败。",
     *   )
     * )
     */
    public function getList(Request $request){

        $page       = $request->input('p',1);
        $size       = $request->input('size',10);
        
        $userId     = $this->getUserId();
        
        $logic      = new FundHistoryLogic();

        $client = RequestSourceLogic::getSource();
        
        $result = $logic->getAppList($userId,$page,$size,$client);

        return self::appReturnJson($result);
    }
}