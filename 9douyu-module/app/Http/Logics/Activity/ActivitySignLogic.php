<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date: 16/09/25
 * Time:14:30
 * @desc 签到逻辑层
 */
namespace App\Http\Logics\Activity;

use App\Http\Dbs\Activity\ActivitySignDb;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivitySignModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;
use App\Tools\ToolTime;
use Event;
use Log;
use Cache;

class ActivitySignLogic extends Logic{
    protected $model;

    public function __construct()
    {
        $this->model = new ActivitySignModel();
    }

    /**
     * @desc 执行签到逻辑
     * @param $data
     * @return array
     */
    public function doSign($data){

        if(empty($data)){
            return self::callError(LangModel::ERROR_ACTIVITY_ADD_SIGN);
        }
        //活动未开始
        if($data['activity_status'] == NationalDayLogic::NATIONAL_NO_START){
            return self::callError(LangModel::ERROR_ACTIVITY_NO_START);
        }
        //活动已结束
        if($data['activity_status'] == NationalDayLogic::NATIONAL_END){
            return self::callError(LangModel::ERROR_ACTIVITY_END);
        }
        $activitySignModel = new ActivitySignModel();
        //签到逻辑
        try{
            self::beginTransaction();
            //检测用户是否登录
            if(!isset($data['user_id'])){
                return self::callError(LangModel::ERROR_ACTIVITY_NOT_LOGIN);
            }
            //检测是否已存在当前活动的签到记录
            $res = $activitySignModel->checkSignRecord($data['user_id'], $data['type']);
            //存在
            if($res){
                //检测当天是否重复签到
                $this->model->checkSignRepeat($res['last_sign_day']);
                //检测是否连续签到
                $continue = $this->model->checkSignContinue($res['last_sign_day']);

                if($continue){//连续签到
                    $this->model->updateContinueSign($data['user_id'], $data['type']);
                }else{
                    $this->model->updateNoContinueSign($data['user_id'], $data['type']);
                }
            }else{
                $addSignData = $this->formatAddSignData($data);
                //添加签到记录
                $this->model->addSign($addSignData);
            }
           self::commit();
        }catch (\Exception $e){
            self::rollback();
            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }
        //签到成功
        Event::fire(new \App\Events\Activity\SignEvent(
            ['data'=>$data]
        ));
        return self::callSuccess();
    }

    /**
     * @desc 格式化添加签到记录的的数据
     * @param $data
     * @return array
     */
    public function formatAddSignData($data){

        $formatSignData = [];

        $formatSignData['user_id']           = isset($data['user_id']) ? $data['user_id'] : 0; //用户id
        $formatSignData['sign_continue_num'] = ActivitySignDb::ACTIVITY_CONTINUE_ONE; //连续签到次数
        $formatSignData['last_sign_day']     = ToolTime::dbDate(); //最新签到日期
        $formatSignData['note']              = isset($data['note']) ? $data['note'] : '活动签到';//备注信息
        $formatSignData['type']              = isset($data['type']) ? $data['type'] : 0;//签到活动类型
        $formatSignData['sign_record']       = isset($data['sign_record']) ? $data['sign_record'] : ToolTime::dbDate()."|";//签到记录

        return $formatSignData;
    }

    /**
     * @desc 获取连续签到次数
     * @author lgh-dev
     * @param $userId
     * @param $type
     * @return int
     */
    public function getContinueSignNum($userId, $type){

        $activitySignDb = new ActivitySignDb();

        $signNum = $activitySignDb->getSignNum($userId, $type);

        if($signNum){
            return $signNum['sign_num'];
        }
        return 0;
    }

    /**
     * @param $userId
     * @param $type
     * @return mixed
     * @desc 获取用户在某个签到活动中的记录
     */
    public function getUserSign( $userId , $type)
    {
        $signDb     =   new ActivitySignDb();

        $signList   =   $signDb->getUserSign($userId , $type);

        return $this->doFormatSignMessage($signList);
    }

    /**
     * @param array $list
     * @return array
     * @desc 格式化用户的签到数据
     */
    protected function doFormatSignMessage($list = array() )
    {
        if( empty($list) ){

            return [];
        }
        $signRecord     =   [];

        if( !empty($list['sign_record']) ){

            $signRecord =   array_filter(explode("|",$list['sign_record']));
        }
        return [
            'sign_num'      =>  $list['sign_continue_num'],
            'new_sign_day'  =>  $list['last_sign_day'],
            'activity_id'   =>  $list['type'],
            'sign_note'     =>  isset($list['note']) ? $list['note'] : '签到活动',
            'sign_record'   =>  $signRecord,
        ];
    }
    /**
     * @param array $data
     * @return array
     * @desc 签到记录(需要连续签到)
     */
    public  function doRecordSign( $data = [])
    {
        $cacheKey = 'sign_lock_'.$data['user_id'];

        if(Cache::has($cacheKey)){

            return self::callError('验证失败,请不要重复提交!');
        }

        Cache::put($cacheKey,1,0.2);   //0.2为过期时间,单位为分钟
        
        $signModel      =   new ActivitySignModel();

        $userId         =   $data['user_id'];

        $activityId     =   $data['type'];

        //签到逻辑
        try{
            
            self::beginTransaction();

            //检测是否已存在当前活动的签到记录
            $isSignRecord = $signModel->checkSignRecord($userId, $activityId);

            if($isSignRecord){

                //检测当天是否重复签到
                $signModel->checkSignRepeat($isSignRecord['last_sign_day']);

                //检测是否连续签到
                $signModel->idCheckSignContinue($isSignRecord['last_sign_day']);

                $signModel->updateContinueSign($userId, $activityId);

            }else{

                $signData    = $this->formatAddSignData($data);
                //添加签到记录
                $signModel->addSign($signData);
            }

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }
}