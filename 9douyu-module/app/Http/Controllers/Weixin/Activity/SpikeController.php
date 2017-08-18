<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/5
 * Time: 下午2:35
 */

namespace App\Http\Controllers\Weixin\Activity;


use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\SpikeLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class SpikeController extends WeixinController
{

    public function index()
    {
        ToolJump::setLoginUrl('/Spike/activity/');
    }
    /*
     * 秒杀活动 version1.0通过控制项目,控制
     *
     */
    public function activity(Request $request)
    {
        $spikeLogic     =   new SpikeLogic();

        $client         =   $request->input('client','wap');
        //活动项目数据
        $spikeProject   =   $spikeLogic->getSecondKillProject();
        //dump($spikeProject);die;
        //下一个秒杀的时间点
        $nextSpikeTime  =   $spikeLogic->setNextSpikeTime();

        //是否是最后一次秒杀
        $isLastTime     =   $spikeLogic->checkLastSpikeTime();
        
        //活动的状态
        $spikeStatus    =   $spikeLogic->setSpikeStatus();

        //秒杀的时间点
        $spikeTime      =   $spikeLogic->getSpikeTime();
        //dump($spikeProject);die;
        $view = [
            'startTime'     =>  SpikeLogic::getStartTime(), //活动开始时间
            'endTime'       =>  SpikeLogic::getEndTime(),   //活动结束时间
            'spikeStatus'   =>  $spikeStatus,               //活动状态
            'isLastTime'    =>  $isLastTime,                //是否最后一次秒杀
            'spikeProject'  =>  $spikeProject,              //秒杀的项目
            'nextSpikeTime' =>  $nextSpikeTime,             //下一次秒杀的活动时间
            'client'        =>  $client,
            'spikeTime'     =>  implode("、",$spikeTime),    //每天秒杀的时间点
        ];

        //加载数据
        return view('wap.activity.spike.activity', $view);
    }

     /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 项目加息
     */
    public function interest(Request $request)
    {
        $spikeLogic     =   new SpikeLogic();

        $client         =   RequestSourceLogic::getSource();

        $token          =   strtolower($request->input('token'));
        $version          =   strtolower($request->input('version'));
        //活动项目数据
        $spikeProject   =   $spikeLogic->getSpikeActivityProject();

        $userId             =   $this->getUserId();

        if( $client == 'android' && $userId ){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);

        }
        $activityTime   =   [
            'start'     =>  SpikeLogic::getStartTime(), //活动开始时间
            'end'       =>  SpikeLogic::getEndTime(),   //活动结束时间
        ];
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/interest');

        $view = [
            'activityTime'  =>  $activityTime,
            'spikeProject'  =>  $spikeProject,              //秒杀的项目
            'client'        =>  $client,
            'token'         =>  $token,
            'version'         =>  $version,
            'userStatus'    => (!empty($userId)||$userId!=0) ? true : false,
        ];

        //加载数据
        return view('wap.activity.spike.index', $view);
    }

}