<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/25
 * Time: 下午6:10
 */

namespace App\Http\Controllers\Weixin\Project;


use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\IncomeModel;
use Illuminate\Http\Request;
use Session;
use Redirect;


class ProjectController extends WeixinController
{

    /**
     * 微信端项目列表页
     */
    public function index(){

        //判断是否是普付宝设备端过来的用户
        if(Session::get('LAST_LOGIN_FROM') == 5){
            Redirect::to('/pfb/projectList');
        }

        //用户状态
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

        //零钱计划数据
        $currentLogic  = new CurrentLogic();
        $current       = $currentLogic->projectDetail($userId,'wap');
        $current['data']['formatFreeAmount'] = round($current['data']['freeAmount']/IncomeModel::TEN_THOUSANDS, 2); //格式化剩余可投金额

        //闪电付息数据
        $logic = new ProjectLogic();//查询可投项目
        //$list = $logic->getSdfProject();//获取项目详情

        //九安心 九省心数据
        //项目数据包
        $projectArr = $logic->getIndexProjectPack();
        unset($projectArr['stat']);

        $view = [
            'current'       => $current['data'],
            //'sdfList'       => $list['data'],
            'projectList'   => $projectArr,
            'title'         => '闪电付息，投资成功秒拿收益！',
            'returnUrl'     => '',
            'status'        => $status,
            'projectActive' => 'active',

        ];

        return view('wap.project.projectList', $view);
    }

    /**
     * @desc 已完结项目列表
     */
    public function more()
    {

        $page              = 1;
        $size              = 5;
        $logic             = new ProjectLogic();
        $fundedProject     = $logic->getFinishedList($page, $size);

        $viewData = [
            'size'      => $size,
            'page'      => $page,
            'projects'  => $fundedProject['list'],
            'title'     => '已售罄列表',
        ];

        return view("wap.project.finishedList", $viewData);

    }

    /**
     * @desc 什么是零钱计划
     */
    public function descriptions(){

        return view('wap.project.descriptions');

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 普付宝项目列表
     */
    public function pfbList(){

        $page  = 1;
        $size  = 10;
        $logic = new ProjectLogic();

        $list  = $logic->getPfbProject($page,$size);
        //dd($list);
        $view  = [
            'project'   => $list['data']
        ];
        return view('wap.project.pfblist',$view);
    }

}