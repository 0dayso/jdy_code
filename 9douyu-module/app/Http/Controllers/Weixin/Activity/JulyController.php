<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午8:14
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\ActivityBaseController;
use App\Http\Logics\Activity\JulyLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class JulyController extends ActivityBaseController
{

    /**
     * @param Request $request
     * @desc 入口
     */
    public function index( Request $request)
    {
        ToolJump::setLoginUrl ('/activity/July');

        $viewData   =   [
            'activityTime'  =>  JulyLogic::getActivityTime (),
            'projectList'   =>  JulyLogic::getActivityProject (),
            'client'        =>  RequestSourceLogic::getSource() ,
            'version'       =>  $request->input('version'),
            'package'       =>  $this->setChannelInfo()['package'],
            'actToken'      =>  JulyLogic::getActToken(),
        ];

        return view('wap.activity.july.index',$viewData);
    }

}