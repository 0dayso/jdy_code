<?php
/**
 * Created by PhpStorm.
 * User: xialili <xia.lili@9douyu.com>
 * Date: 17/1/20
 * Time: 下午4:24
 */

namespace App\Http\Controllers\Pc\Activity;


use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\RequestSourceLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;
use App\Http\Logics\Activity\LanternLogic;
use App\Http\Logics\Activity\GuessRiddlesLogic;
use App\Http\Models\Activity\GuessRiddlesModel;

class LanternController extends PcController
{
    /**
     * @desc 元宵节活动
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //用户id
        $userId         = $this->getUserId();

        $lanternLogic = new LanternLogic();

        $guessRiddlesLogic = new GuessRiddlesLogic();

        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/lantern');

        //获取活动时间格式
        $timesArr = $lanternLogic->getActivityTimesFormat();

        //获取活动状态
        $activityStatus = $guessRiddlesLogic->getActivityStatus(LanternLogic::ACTIVITY_KEY);

        //获取灯谜的列表信息
        $riddlesList = $guessRiddlesLogic->getRiddlesList($userId, GuessRiddlesModel::ACTIVITY_TYPE_LANTERN);

        //获取活动期间的项目
        $projectList = $lanternLogic->getActivityProject();

        //活动期间内投资定期的排名
        $investRankData  = $lanternLogic->getActivityInvestRankData(5);

        $assign = [
            'user_id' => $userId,
            'time_format' => $timesArr,
            'activityTime'  =>  $lanternLogic->getActivityTime(),
            'activityStatus' => $activityStatus,
            'activity_key' => LanternLogic::ACTIVITY_KEY,
            'type' => GuessRiddlesModel::ACTIVITY_TYPE_LANTERN,
            'riddles_content' => $riddlesList,
            'project_list'    => $projectList,
            'invest_rank'    => $investRankData,
        ];

        return view('pc.activity.lantern.index', $assign);
    }

    /**
     * @desc 猜灯谜操作处理
     * @param Request $request
     * @return mixed
     */
    public function doGuessRiddles(Request $request){

        $data = $request->all();

        $guessRiddlesLogic = new GuessRiddlesLogic();

        $return = $guessRiddlesLogic->doRiddles($data);

        return self::returnJson($return);
    }


}
