<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午8:14
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\CanadianLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class CanadianController extends PcController
{


    public function index()
    {
        ToolJump::setLoginUrl('/activity/canadian');

        $activityTime   =   CanadianLogic::setTime();

        $projectList    =   CanadianLogic::getProject();

        $awardConfig    =   CanadianLogic::getAwardConfig();

        $userId         =   $this->getUserId();

        $projectLineNote=   CanadianLogic::getProjectLineNote();

        $viewData   =   [
            'projectList'   =>  $projectList,
            'lineNote'      =>  $projectLineNote,
            'activityTime'  =>  $activityTime,
            'awardConfig'   =>  $awardConfig,
            'actToken'      =>  CanadianLogic::getActToken(),
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
        ];

        return view('pc.activity.canadian.index',$viewData);
    }

}
