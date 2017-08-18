<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/25
 * Time: 下午6:10
 */

namespace App\Http\Controllers\Weixin\Project;


use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Project\ProjectModel;
use App\Tools\ToolJump;
use Illuminate\Http\Request;
use Session;
use Redirect;


class ProjectSdfController extends WeixinController
{

    /**
     * 闪电付息项目列表页
     */
    public function index(){

        //查询可投项目
        $logic = new ProjectLogic();

        //获取项目详情
        $list = $logic->getSdfProject();

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

        $view = [
            'list'          => $list['data'],
            'title'         => '闪电付息，投资成功秒拿收益！',
            'minInvestCash' => ProjectModel::getInvestMinCashByProductLine(ProjectDb::PROJECT_PRODUCT_LINE_SDF),
            'returnUrl'     => '',
            'status'        => $status,
        ];

        return view('wap.project.sdf', $view);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 投资确认页
     */
    public function investConfirm(Request $request){

        $userId = $this->getUserId();

        if( !$userId ){
            ToolJump::setLoginUrl('/project/sdf/');
        }

        $this->checkLogin(true);

        $id = (int)$request->input('id', 0);

        $logic = new ProjectLogic();

        $info = $logic->getById( $id )['data'];

        $userLogic = new UserLogic();

        $userInfo  = $userLogic->getUser($userId);

        //非前置付息项目直接跳转
        if($info['refund_type'] != ProjectDb::REFUND_TYPE_FIRST_INTEREST){

            return Redirect::to('/project/sdf');

        }

        $view = [

            'project'       => $info,
            'balance'       => $userInfo['balance'],
            'investMinCash' => ProjectModel::getInvestMinCashByProductLine(ProjectDb::PROJECT_PRODUCT_LINE_SDF),

        ];

        return view('wap.invest.project.sdf_confirm', $view);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     * @desc 闪电付息详情页
     */
    public function detail(Request $request){

        $id = $request->input('id', 0);

        if( !$id ){
            return Redirect::to('/project/lists');
        }

        $logic = new ProjectLogic();

        $info = $logic->getById( $id )['data'];

        //非前置付息项目直接跳转
        if(empty($info) || $info['refund_type'] != ProjectDb::REFUND_TYPE_FIRST_INTEREST){

            return Redirect::to('/project/lists');

        }

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
        }else{
            ToolJump::setLoginUrl("/Invest/Project/preDoInvest?id=$id&from=wap");
        }

        $view = [
            'project'       => $info,
            'investMinCash' => ProjectModel::getInvestMinCashByProductLine(ProjectDb::PROJECT_PRODUCT_LINE_SDF),
            'status'        => $status,
        ];

        return view('wap.project.detail_sdf', $view);

    }

}