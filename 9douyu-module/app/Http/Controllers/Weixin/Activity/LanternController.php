<?php
/**
 * Created by PhpStorm.
 * User: tianxiaoyan
 * Date: 16/12/27
 * Time: 下午5:00
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Http\Request;
use App\Tools\ToolJump;

use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\Activity\LanternLogic;
use App\Http\Logics\Activity\GuessRiddlesLogic;
use App\Http\Models\Activity\GuessRiddlesModel;

class LanternController extends WeixinController{

    public function __construct()
    {
        parent::__construct();
        $this->checkLogin(false);
    }

    public function lantern(Request $request)
    {

        $client     =   RequestSourceLogic::getSource();

        $token      =   $request->input('token','');

        $userId     =   $this->getUserId();

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
            'client' => $client,
            'token_status' => $token,
            'time_format' => $timesArr,
            'activityTime'  =>  $lanternLogic->getActivityTime(),
            'activityStatus' => $activityStatus,
            'activity_key' => LanternLogic::ACTIVITY_KEY,
            'type' => GuessRiddlesModel::ACTIVITY_TYPE_LANTERN,
            'riddles_content' => $riddlesList,
            'project_list'    => $projectList,
            'invest_rank'    => $investRankData,
        ];

        return view('wap.activity.lantern.lantern', $assign);
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
