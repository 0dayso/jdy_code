<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:41
 * Desc: 投资零钱计划
 */

namespace App\Http\Controllers\App\Current;

use App\Http\Controllers\App\UserController;
use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;

class InvestController extends UserController{

    /**
     * @SWG\Post(
     *   path="/current_invest",
     *   tags={"APP-Current"},
     *   summary="零钱计划投资页面 [Current\InvestController@investDetail]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划投资页面成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划投资页面失败。",
     *   )
     * )
     */
    public function investDetail(Request $request){

        $client     = $request->input('client','');
        $version    = $request->input('version','');
        $projectId  = $request->input('project_id','');

        $userId     = $this->getUserId();
        
        $logic = new CurrentLogic();
        $result = $logic->projectInfo($userId);

        return self::appReturnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/current_doinvest",
     *   tags={"APP-Current"},
     *   summary="零钱计划投资 [Current\InvestController@doInvest]",
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
     *      name="bonus_id",
     *      in="formData",
     *      description="加息券ID",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="零钱计划投资成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="零钱计划投资失败。",
     *   )
     * )
     */
    public function doInvest(Request $request){

        $data                   = $request->all();
        $cash                   = $request->input('cash',0);           //转入金额
        $data['cash']           = ToolMoney::formatDbCashAdd($cash);

        $data['user_id']        = $this->getUserId();
        $data['from']           = RequestSourceLogic::getSource();                 //三端来源

        //app 3.1.0 版本以上(包括3.1.0) 零钱计划投资取消交易密码的验证
        $isTrade            = true;

        if($this->compareVersion($data['version'], self::THIS_NEW_FIX_VERSION)){

            $isTrade        = false;

        }

        $logic                  = new CurrentLogic();
        $result                 = $logic->doAppInvest($data, $isTrade);

        return self::appReturnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/current_invest_out",
     *   tags={"APP-Current"},
     *   summary="零钱计划转出页面 [Current\InvestController@investOutDetail]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划转出页面成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划转出页面失败。",
     *   )
     * )
     */
    public function investOutDetail(Request $request){

        $data       = $request->all();
        
        $userId     = $this->getUserId();

        //app 3.1.0 版本以上(包括3.1.0) 零钱计划转出取消交易密码的验证
        $isTrade            = true;

        if($this->compareVersion($data['version'], self::THIS_NEW_FIX_VERSION)){

            $isTrade        = false;

        }

        $logic      = new CurrentLogic();
        $result     = $logic->investOutDetail($userId, $isTrade);

        return self::appReturnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/current_doinvest_out",
     *   tags={"APP-Current"},
     *   summary="零钱计划转出 [Current\InvestController@doInvestOut]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="零钱计划转出成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="零钱计划转出失败。",
     *   )
     * )
     */
    public function doInvestOut(Request $request){

        $data               = $request->all();
        $cash               = (float)$request->input('cash',0);

        $data['cash']       = ToolMoney::formatDbCashAdd($cash);
        $data['user_id']    = $this->getUserId();

        $data['from']       = RequestSourceLogic::getSource();

        //app 3.1.0 版本以上(包括3.1.0) 零钱计划转出取消交易密码的验证
        $isTrade            = true;

        if($this->compareVersion($data['version'], self::THIS_NEW_FIX_VERSION)){

            $isTrade        = false;

        }

        $logic              = new CurrentLogic();

        $result         = $logic->doAppInvestOut($data, $isTrade);

        return self::appReturnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/invest_current_agreement",
     *   tags={"APP-Current"},
     *   summary="零钱计划转入协议 [Current\InvestController@getAgreement]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取零钱计划转入协议成功。",
     *   ),
     * )
     */
    public function getAgreement(){

        $userId = $this->getUserId();

        $currentLogic = new CurrentLogic();
        $viewData = $currentLogic->getAgreementInfo($userId);

        $content['data']['info'] =  view('app.agreement.current', $viewData['data'])->render();
        $content['status'] = true;

        return self::appReturnJson($content);

    }
}