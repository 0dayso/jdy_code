<?php

namespace App\Http\Controllers\AppApi\V4_0\Current;
use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Current\CurrentUserLogic;
use App\Http\Logics\RequestSourceLogic;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/2/22
 * Time: 下午4:07
 */
class CurrentController extends AppController
{

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/current_detail",
     *   tags={"APP-Current"},
     *   summary="零钱计划详情 [Current\CurrentController@currentDetail]",
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
     *      name="unique",
     *      in="formData",
     *      description="unique",
     *      required=true,
     *      type="string",
     *      default="1",
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
    public function currentDetail(Request $request){

        $userId = $this->getUserId();

        $logic = new CurrentUserLogic();

        $result = $logic->getAppV4Detail($userId, $this->client);

        return $this->returnJsonData($result);

    }

    /**
     * @param Request $request
     * @return array
     */
    /**
     * @SWG\Post(
     *   path="/current_interest_history",
     *   tags={"APP-Current"},
     *   summary="零钱计划收益[Current\CurrentController@currentInterestHistory]",
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
     *      name="unique",
     *      in="formData",
     *      description="unique",
     *      required=true,
     *      type="string",
     *      default="1",
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
    public function currentInterestHistory(Request $request){

        $userId = $this->getUserId();

        $logic = new CurrentUserLogic();

        $result = $logic->getAppCurrentInterestList($userId);

        return $this->returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/current_able_user_bonus",
     *   tags={"APP-Current"},
     *   summary="零钱计划可用优惠券列表[Current\CurrentController@currentAbleUserBonus]",
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
     *      name="unique",
     *      in="formData",
     *      description="unique",
     *      required=true,
     *      type="string",
     *      default="1",
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
    public function currentAbleUserBonus(Request $request){

        $userId = $this->getUserId();

        $logic = new CurrentUserLogic();

        $result = $logic->getAppV4CurrentUserAbleBonus($userId, $this->client);

        return $this->returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/current_do_invest",
     *   tags={"APP-Current"},
     *   summary="零钱计划转入[Current\CurrentController@currentDoInvest]",
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
     *      name="unique",
     *      in="formData",
     *      description="unique",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="转入金额",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="user_bonus_id",
     *      in="formData",
     *      description="使用优惠券Id",
     *      required=false,
     *      type="string",
     *      default="1",
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
    public function currentDoInvest(Request $request){

        $cash           = $request->input('cash',0);                        //转入金额
        $userId         = $this->getUserId();
        $client         = $this->client;                 //三端来源
        $bonusId        = $request->input('user_bonus_id', 0);

        $logic                  = new CurrentUserLogic();
        $result                 = $logic->currentAppV4Invest($userId, $cash, $client, $bonusId);

        return $this->returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/current_do_invest_out",
     *   tags={"APP-Current"},
     *   summary="零钱计划转出[Current\CurrentController@currentDoInvestOut]",
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
     *      name="unique",
     *      in="formData",
     *      description="unique",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="转出金额",
     *      required=true,
     *      type="number",
     *      default="1",
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
    public function currentDoInvestOut(Request $request){

        $cash           = $request->input('cash',0);                        //转入金额
        $userId         = $this->getUserId();
        $client         = $this->client;                 //三端来源

        $logic                  = new CurrentUserLogic();
        $result                 = $logic->currentAppV4InvestOut($userId, $cash, $client);

        return $this->returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/current_get_interest",
     *   tags={"APP-Current"},
     *   summary="零钱计划预期收益[Current\CurrentController@currentGetInterest]",
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
     *      name="unique",
     *      in="formData",
     *      description="unique",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="转出金额",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="rate",
     *      in="formData",
     *      description="基础利率",
     *      required=true,
     *      type="number",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="add_rate",
     *      in="formData",
     *      description="加息利率",
     *      required=true,
     *      type="number",
     *      default="1",
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
    public function currentGetInterest(Request $request){

        $cash       = $request->input('cash', 0);
        $rate       = $request->input('rate', 0);
        $addRate    = $request->input('add_rate', 0);

        $logic = new CurrentUserLogic();
        $result = $logic->currentAppV4GetInterest($cash, $rate, $addRate);

        return $this->returnJsonData($result);

    }

    /**
     * @SWG\Post(
     *   path="/current_used_user_bonus",
     *   tags={"APP-Current"},
     *   summary="活期使用优惠券[Current\CurrentController@currentUsedUserBonus]",
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
     *      name="unique",
     *      in="formData",
     *      description="unique",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="user_bonus_id",
     *      in="formData",
     *      description="使用优惠券Id",
     *      required=false,
     *      type="string",
     *      default="1",
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
    public function currentUsedUserBonus(Request $request){

        $userId     = $this->getUserId();
        $bonusId    = $request->input('user_bonus_id', 0);
        $client     = $this->client;

        $logic = new CurrentUserLogic();
        $result = $logic->currentAppV4UsedBonus($userId, $bonusId, $client);

        return $this->returnJsonData($result);

    }

}