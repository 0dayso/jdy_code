<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/28
 * Time: 下午7:24
 */

namespace App\Http\Controllers\Pc\Activity;

use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\JulyLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;


class JulyController extends PcController
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc load activity views
     */
    public function July( Request $request)
    {
        ToolJump::setLoginUrl ('/activity/July');

        $viewData   =   [
            'activityTime'  =>  JulyLogic::getActivityTime (),
            'projectList'   =>  JulyLogic::getActivityProject (),
        ];

        return view("pc.activity.July.July" ,$viewData);
    }
    
}