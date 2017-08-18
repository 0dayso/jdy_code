<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午8:14
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\AnniversaryThirdLogic;
use App\Http\Logics\Activity\AutumnLogic;
use App\Http\Logics\Activity\GradeLotteryLogic;
use App\Http\Logics\Activity\AnniversaryLogic;
use App\Http\Logics\Activity\AnniversarySecondLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class AutumnController extends PcController
{

    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 多奖池抽奖活动
     */
    public function index()
    {
        ToolJump::setLoginUrl('/activity/Autumn');

        $activityTime   =   AutumnLogic::getActivityTime();

        $viewData   =   [
            'activityTime'  =>  $activityTime,
        ];

        return view('pc.activity.autumn.index',$viewData);
    }
    

    /**
     * @param Request $request
     * @desc 执行抽奖记录
     */
    public function doLuckDraw( Request $request )
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

       return AutumnLogic::doLuckDrawUseActSta( $userId , AutumnLogic::getGradeLotteryGroup ($grade) , AutumnLogic::setActivityEventId () , $lotteryCondition['data']['statics_id'] );
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
