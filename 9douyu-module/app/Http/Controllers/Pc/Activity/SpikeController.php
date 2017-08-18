<?php

/**  *********************************** 秒杀活动PC端***********************************************
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/13
 * Time: 下午2:06
 */

namespace App\Http\Controllers\Pc\Activity;

use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Activity\SpikeLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;

class SpikeController extends PcController
{
    public function index()
    {
        ToolJump::setLoginUrl('/Spike/activity/');
    }
    /*
     * 秒杀活动 version1.0通过控制项目,控制
     *
     */
    public function activity()
    {
        $spikeLogic     =   new SpikeLogic();

        //活动项目数据
        $spikeProject   =   $spikeLogic->getSecondKillProject();

        //下一个秒杀的时间点
        $nextSpikeTime  =   $spikeLogic->setNextSpikeTime();

        //是否是最后一次秒杀
        $isLastTime     =   $spikeLogic->checkLastSpikeTime();

        //活动的状态
        $spikeStatus    =   $spikeLogic->setSpikeStatus();

        //秒杀的时间点
        $spikeTime      =   $spikeLogic->getSpikeTime();

        $view = [
            'startTime'     =>  SpikeLogic::getStartTime(), //活动开始时间
            'endTime'       =>  SpikeLogic::getEndTime(),   //活动结束时间
            'spikeStatus'   =>  $spikeStatus,               //活动状态
            'isLastTime'    =>  $isLastTime,                //是否最后一次秒杀
            'spikeProject'  =>  $spikeProject,              //秒杀的项目
            'nextSpikeTime' =>  $nextSpikeTime,             //下一次秒杀的活动时间
            //'client'        =>  $client,
            'spikeTime'     =>  implode("、",$spikeTime),    //每天秒杀的时间点
        ];

        //加载数据
        return view('pc.activity.spike.activity', $view);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 项目加息
     */
    public function interest()
    {
        $spikeLogic     =   new SpikeLogic();

        //活动项目数据
        $spikeProject   =   $spikeLogic->getSpikeActivityProject();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/interest');
        $activityTime   =   [
            'start'     =>  SpikeLogic::getStartTime(), //活动开始时间
            'end'       =>  SpikeLogic::getEndTime(),   //活动结束时间
        ];
        $view = [
            'activityTime'  =>  $activityTime,
            'spikeProject'  =>  $spikeProject,              //秒杀的项目

        ];

        //加载数据
        return view('pc.activity.spike.index', $view);
    }

}