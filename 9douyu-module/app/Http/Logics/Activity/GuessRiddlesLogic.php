<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date: 17/1/23
 * Time:10:57
 * @desc 猜灯谜逻辑处理
 */
namespace App\Http\Logics\Activity;

use App\Http\Models\Activity\GuessRiddlesModel;
use App\Lang\LangModel;
use App\Tools\ToolTime;
use App\Http\Logics\Logic;
use Event;
use Log;
use Cache;

class GuessRiddlesLogic extends Logic{
    protected $model;

    public function __construct()
    {
        $this->model = new GuessRiddlesModel();
    }

    /**
     * @desc 执行猜灯谜操作逻辑处理
     * @param $data
     * @return array
     */
    public function doRiddles($data){

        $userId = $data['user_id'];//用户id
        $riddlesId = $data['riddles_id'];//灯谜配置id
        $type =$data['type'];//活动类型标示
        $answer = $data['answer'];//用户提交答案
        $activityKey = $data['activity_key'];//关联活动的配置

        Log::info("GuessRiddlesData:", $data);
        if(empty($data)){
        return self::callError('提交猜灯谜的数据异常');
        }

        //检测用户是否登录
        if(!isset($data['user_id']) || empty($data['user_id'])){
        return self::callError('您还未登录系统,请先登录');
        }

        //获取活动配置信息
        $activityStatus = $this->getActivityStatus($activityKey);

        //活动状态是否正常
        if($activityStatus == false){
            return self::callError('获取活动配置失败');
        }

        if($activityStatus == GuessRiddlesModel::ACTIVITY_NO_START){
            return self::callError('活动未开始', GuessRiddlesModel::ACTIVITY_NO_START);
        }
        if($activityStatus == GuessRiddlesModel::ACTIVITY_IS_END){
            return self::callError('活动已结束', GuessRiddlesModel::ACTIVITY_IS_END);
        }
       try{

        //检查用户是否猜过此灯谜
        $this->model->checkIsGuessRiddles($userId, $type, $riddlesId);

        //检查用户灯谜答案是否正确
        $this->model->checkAnswerIsTrue($riddlesId, $answer);

        //添加猜灯谜成功纪录
        $this->model->addLantern($data);

        }catch (\Exception $e){
            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage(), $e->getCode());
        }
        //猜灯谜成功
        Event::fire(new \App\Events\Activity\GuessRiddlesEvent(
            ['data'=>$data]
        ));
        return self::callSuccess();

    }

    /**
     * @desc 通过配置活动的key获取当前活动的状态
     */
    public function getActivityStatus($activityKey){

        $activityConfig = $this->model->getActivityConfigByKey($activityKey);

        if(empty($activityConfig)){
            return false;
        }

        //活动开始时间
        $startTime = ToolTime::getUnixTime($activityConfig['START_TIME']);
        //活动结束时间
        $endTime   = ToolTime::getUnixTime($activityConfig['END_TIME'],'end');

        $status = GuessRiddlesModel::ACTIVITY_DOING;
        //未开始
        if(time() < $startTime){
            $status = GuessRiddlesModel::ACTIVITY_NO_START;
        }
        //已结束
        if(time() > $endTime){
            $status = GuessRiddlesModel::ACTIVITY_IS_END;
        }
        return $status;
    }

    /**
     * @desc  获取灯谜配置信息处理列表
     * @param int $userId
     * @param string $type
     * @return mixed
     */
    public function getRiddlesList($userId=0, $type = ''){
        $riddlesList = $this->model->getRiddlesList($userId, $type);
        return $riddlesList;
    }

    /**
     * @desc 检测是否猜过此灯谜
     * @param $data
     * @return mixed
     */
    public function checkIsGuessRiddles($data){

        $userId = $data['user_id'];
        $type = $data['type'];
        $riddlesId = $data['riddles_id'];
        $guessRiddlesModel = new GuessRiddlesModel();

        try{
            $guessRiddlesModel->checkIsGuessRiddles($userId, $type, $riddlesId);
        }catch (\Exception $e){
            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage(), $e->getCode());
        }
        return self::callSuccess();
    }

}

