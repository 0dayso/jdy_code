<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午8:14
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\ActivityBaseController;
//use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\AutumnLogic;
use App\Http\Logics\Activity\GradeLotteryLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class AutumnController extends ActivityBaseController
{

    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 多奖池抽奖活动
     */
    public function index(Request $request)
    {
        ToolJump::setLoginUrl('/activity/Autumn');

        $userId         =   $this->getUserId();

        $activityTime   =   AutumnLogic::getActivityTime();

        $viewData   =   [
            'activityTime'  =>  $activityTime,
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
            'client'        =>  RequestSourceLogic::getSource() ,
            'version'       =>  $request->input('version'),
            'actToken'      =>  AutumnLogic::setActivityActToken(),
            'package'       =>  $this->setChannelInfo()['package'],
        ];

        return view('wap.activity.autumn.index',$viewData);
    }
   
    /**
     * @param Request $request
     * @desc 执行抽奖的程序
     */
    public function doLuckDraw( Request $request)
    {
        $userId         =   $this->getUserId() ;

        $grade          =   $request->input('grade',1 );

        $validGrade     =   AutumnLogic::isValidGradeLevel ( $grade ) ;

        if( $validGrade['status'] == false ) {
            return $validGrade ;
        }

        $validCondition =   AutumnLogic::validTimeCondition($userId) ;

        if( $validCondition['status'] == false ) {

            return $validCondition;
        }

        $lotteryCondition=  AutumnLogic::validUserLotteryCondition( $userId , $grade );

        if( $lotteryCondition['status'] == false ) {

            return $lotteryCondition;
        }

        return AutumnLogic::doLuckDrawUseActSta( $userId , AutumnLogic::getGradeLotteryGroup ($grade) , AutumnLogic::setActivityEventId () , $lotteryCondition['data']['statics_id']);
    }


    /*
     * @param Request $request
     * @return array project
     * @desc 活动页展示的项目信息
     */
    public function getProject(Request $request)
    {
        return AutumnLogic::getActivityProjectList () ;
    }

}
