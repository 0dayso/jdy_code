<?php

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/3
 * Time: 上午10:42
 *
 */

namespace App\Http\Controllers\Weixin\Project;

use App\Http\Controllers\Weixin\WeixinController;

use App\Http\Dbs\Credit\CreditDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Logics\User\UserLogic;
use Illuminate\Support\Facades\Redirect;

use App\Http\Logics\Invest\CurrentLogic;

/**
 * 项目详情
 * Class ProjectDetailController
 * @package App\Http\Controllers\weixin\Project
 */
class ProjectDetailController extends WeixinController
{
    /**
     * 项目详情逻辑类
     * @var ProjectDetailLogic|null
     */
    protected $ProjectDetailLogic = null;


    public function appendConstruct(){
        $this->ProjectDetailLogic = new ProjectDetailLogic;
    }

    /**
     * 项目详情页面
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get($id = 0){

        //项目信息
        $project     = $this->ProjectDetailLogic->get($id);

        if(empty($project) || $project['status'] < ProjectDb::STATUS_UNPUBLISH)
            return Redirect::to("/project/lists");

        //投资概况
        $investBrief     = $this->ProjectDetailLogic->getInvestBrief($id);

        $userId = $this->getUserId();
        $status = [
            'is_login'          => 'off',
            'name_checked'      => 'off',
            'password_checked'  => 'off',
        ];

        if(!empty($userId)){
            $userLogic = new UserLogic();
            $userInfo  = $userLogic -> getUser($userId);
            $status    = $userLogic -> getUserAuthStatus($userInfo);
        }

        // 债权
        $creditDetail      = $this->ProjectDetailLogic->getCreditBrowserShowData($id, $userId);


        $projectWay        = $creditDetail['projectWay'];


        $assign          = [
            'project'      => $project,
            'investBrief'  => $investBrief,
            'process'      => ($investBrief['cash']/$project['total_amount'])*100,
            'status'       => $status,
            'projectWay'   => $projectWay,
        ];

        return view('wap.project.detail', $assign);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 计算器
     */
    public function calculator(){

        return view('wap.project.calculator');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     * @desc 项目详情
     */
    public function companyDetail( $id = 0){

        //项目信息
        $project           = $this->ProjectDetailLogic->get($id);

        if(empty($project))
            return Redirect::to("/project/lists");

        // 债权
        $creditDetail      = $this->ProjectDetailLogic->getCreditBrowserShowData($id);


        $projectWay        = $creditDetail['projectWay'];

        if(!in_array($projectWay, [CreditDb::SOURCE_FACTORING, CreditDb::SOURCE_HOUSING_MORTGAGE, CreditDb::SOURCE_CREDIT_LOAN])){
            return Redirect::to("/project/lists");
        }

        switch ($projectWay) {
            case CreditDb::SOURCE_CREDIT_LOAN :
                $tpl = "creditdetail";
                break;
            case CreditDb::SOURCE_FACTORING :
                $tpl = "factordetail";
                break;
            case CreditDb::SOURCE_HOUSING_MORTGAGE :
                $tpl = "housedetail";
                break;
        }

        $assign['project']      = $project;
        $assign['creditDetail'] = $creditDetail;

        \Log::info(__METHOD__, [$creditDetail]);

        return view('wap.project.' . $tpl, $assign);
    }

    /*
     * 零钱计划项目投资详情页
     */
    public function getCurrent(){

        $logic = new CurrentLogic();
        $assign = $logic->getCurrentBaseInfo();

        return view('wap.invest.current.detail',$assign);


    }


}
