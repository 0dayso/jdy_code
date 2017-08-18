<?php

namespace App\Http\Controllers\App\Topic;

use App\Http\Controllers\App\AppController;

use  App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Models\Common\IncomeModel;

class AppTopicController extends AppController
{


    public function appendConstruct(){
        \Debugbar::disable();
    }
    /**
     * @SWG\Get(
     *   path="app/topic/safe",
     *   tags={"APP-Project"},
     *   summary="资产安全 [Topic\AppTopicController@safe]",
     *   @SWG\Response(
     *     response=200,
     *     description="投资零钱计划成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="投资零钱计划失败。",
     *   )
     * )
     */
    public function safe(){
        return view('app.topic.safe');
    }

    /**
     * 理财介绍
     * @param int $projectLineKey
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function financingDesc($appSubDomain =null, $projectId = 0){

        $projectLogic   =   new ProjectDetailLogic();

        $project        =   $projectLogic->getCoreProjectInfo($projectId);

        $projectLineKey =   $project['type']+$project['product_line'];

        $view           =   ProjectDetailLogic::getFinancePage($projectLineKey);

        $project['hundred_thousand_income']=IncomeModel::getInterestPlan($project['profit_percentage'], $project['invest_time'],$project['refund_type']) *10;

        $viewData       =   ['project'=>$project];
        
        return view('app.topic.' . $view,$viewData);
    }



    /**
     * @SWG\Get(
     *   path="/app/topic/current",
     *   tags={"APP-Current"},
     *   summary="零钱计划介绍 [Topic\AppTopicController@currentDesc]",
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
     *     description="获取零钱计划介绍成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取零钱计划介绍失败。",
     *   )
     * )
     */
    public function currentDesc(){


        return view('app.topic.current', []);

    }


    /**
     * 优惠券使用介绍
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bonusDesc(){

        return view('app.topic.bonus', []);

    }

}