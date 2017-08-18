<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午8:14
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\CanadianLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class CanadianController extends WeixinController
{

    /**
     * @param Request $request
     * @desc 入口
     */
    public function index( Request $request)
    {
        $token              =   $request->input('token');

        $version            =   $request->input('version');

        $client             =   RequestSourceLogic::getSource();
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/canadian');

        $userId         =   $this->getUserId();

        $activityTime   =   CanadianLogic::setTime();

        $projectList    =   CanadianLogic::getProject();

        if( $client == 'android' && $userId ){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);
        }

        $isVersionTrue  =   CanadianLogic::isUnUseAppVersion($version);

        $awardConfig    =   CanadianLogic::getAwardConfig();

        $projectLineNote=   CanadianLogic::getProjectLineNote();

        $viewData   =   [
            'projectList'   =>  $projectList,
            'lineNote'      =>  $projectLineNote,
            'activityTime'  =>  $activityTime,
            'version'       =>  $isVersionTrue,
            'client'        =>  $client,
            'token'         =>  $token,
            'actToken'      =>  CanadianLogic::getActToken(),
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
            'awardConfig'   =>  $awardConfig,
        ];

        return view('wap.activity.canadian.index',$viewData);
    }

}
