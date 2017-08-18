<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午3:35
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\ActivityVoteDb;
use App\Http\Logics\Logic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\Activity\ActivityVoteModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Models\User\UserModel;
use App\Lang\LangModel;
use App\Tools\ToolTime;
use Cache;


class ActivityVoteLogic extends Logic
{
    /**
     * @return array
     * @desc 投票时间端
     */
    public static function getVoteTime()
    {
        return [
            'start' =>  self::setVoteStartTime(),
            'end'   =>  self::setVoteEndTime(),
        ];
    }

    /**
     * @param $userId
     * @param $activityId
     * @return array
     * @desc 使用可以投票的逻辑入口
     */
    public static function isCanVote( $userId ,$activityId )
    {
        $timeStatus     =   self::isInVoteTime();

        if( $timeStatus['status'] == false ){

            return $timeStatus;
        }
        
        $logicStatus    =   self::isLogin($userId);

        if( $logicStatus['status'] == false ){

            return $logicStatus;
        }

        $voteTimesStatus=   self::isCanVoteTimes($userId ,$activityId);

        if( $voteTimesStatus['status'] ==false ){

            return $voteTimesStatus;
        }

        return self::callSuccess();
    }

    /**
     * @param $teamId
     * @return array
     * @desc 判断是否在同一个团队里
     */
    public static function isInVoteTeam( $teamId )
    {
        $teamNumber =   self::setVoteTeamNumber();

        if( !in_array($teamId ,$teamNumber ) ){

            return self::callError("请选择正确的投票团队");
        }

        return self::callSuccess();
    }

    /**
     * @return array
     * @desc 投票的团队
     */
    protected static function setVoteTeamNumber()
    {
        return ["10" , "11" , "20" , "21" , "30" , "40" , "50" , "60"];
    }
    /**
     * @param $userId
     * @return array
     * @desc 判断是否登录
     */
    public static function isLogin( $userId )
    {
        if( empty($userId) || $userId == 0 ){

            return self::callError("请登录后重新进行投票");
        }

        return self::callSuccess();
    }
    /**
     * @return array
     * @desc 活动时间状态
     */
    public static function isInVoteTime()
    {
        $startTime      =   self::setVoteStartTime();
        
        $endTime        =   self::setVoteEndTime();
        
        $nowTime        =   time();
        
        if( $startTime  >  $nowTime ){

            $msg        =   sprintf(LangModel::getLang('ERROR_ACTIVITY_TIME_NOT_OPEN') , "投票",date('m月d',$startTime)."日,");

            return self::callError($msg);
        }

        if( $endTime  < $nowTime ){

            $msg        =   sprintf(LangModel::getLang('ERROR_ACTIVITY_TIME_NOT_CLOSED') , "投票");

            return self::callError($msg);

        }

        return self::callSuccess();
    }
    /**
     * @param $userId
     * @param $activityId
     * @return array
     * @desc 判断用户是否可以投票
     */
    public static function isCanVoteTimes( $userId ,$activityId )
    {
        $isEveryDayVote =   self::isEveryDayVote();

        $voteTimes      =   self::getVoteTimes($userId ,$activityId);

        $maxVoteTimes   =   self::setMaxVoteTimes();

        if( $isEveryDayVote == true  && $voteTimes >= $maxVoteTimes ){

            $msg        =   sprintf(LangModel::getLang('ERROR_ACTIVITY_VOTE_EVERY_DAY_TRAVEL'),$maxVoteTimes);

            return self::callError($msg);

        }elseif( $voteTimes >= $maxVoteTimes ){

            $msg        =   sprintf(LangModel::getLang('ERROR_ACTIVITY_VOTE_ONLY_ONCE'),$maxVoteTimes);

            return self::callError($msg);

        }

        return self::callSuccess();

    }
    /**
     * @param $userId
     * @param $activityId
     * @param $note
     * @return array
     * @desc 添加数据
     */
    public static function doAddVoteTimes( $userId ,$choices ,$activityId ,$note )
    {
        $cacheKey = 'lottery_lock_'.$userId;

        if(Cache::has($cacheKey)){

            return self::callError('验证失败，不可以重复提交投票');
        }

        Cache::put($cacheKey,1,0.2);   //0.2为过期时间,单位为分钟

        try{
            self::beginTransaction();

            $userInfo   =   UserModel::getUserInfo($userId);

            $voteParam  =   self::setFormatVoteRecord($activityId,$choices,$userInfo,$note );

            ActivityVoteModel::doAddVote($voteParam);
            
            self::commit();

        }catch (\Exception $e){

            self::rollback();
            
            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @param $activityId
     * @param $userInfo
     * @param $note
     * @return array
     * @desc  格式化记录数据
     */
    protected static function setFormatVoteRecord( $activityId ,$choices ,$userInfo ,$note )
    {
        return [
            'user_id'       =>  $userInfo['id'] ,
            'phone'         =>  $userInfo['phone'],
            'activity_id'   =>  $activityId,
            'choices'       =>  $choices,
            'note'          =>  !empty($note) ? $note :  "投票活动",
        ];
    }
    /**
     * @param $userId
     * @param $activityId
     * @return mixed
     * @desc 获取区间投票数据
     */
    protected static function getVoteTimes( $userId ,$activityId )
    {
        $betweenTime    =   self::setVoteBetweenTime();
        
        $voteTimes      =   ActivityVoteDb::getActivityVoteByTime($userId ,$activityId ,$betweenTime['start'] ,$betweenTime['end']);

        return $voteTimes;
    }

    /**
     * @return array
     * @desc  根据配置设置统计的时间段
     */
    protected static function setVoteBetweenTime()
    {
        $isEveryDayVote =   self::isEveryDayVote();

        if( $isEveryDayVote == false ){

            return [
                'start' =>  date("Y-m-d H:i:s" , self::setVoteStartTime() ),
                'end'   =>  date("Y-m-d H:i:s" , self::setVoteEndTime() ),
            ];
        }

        return [
            'start' =>  date("Y-m-d 00:00:00",time()),
            'end'   =>  date("Y-m-d 23:59:59",time()),
        ];
    }
    /**
     * @return int
     * @desc 开始时间
     */
    protected static function setVoteStartTime()
    {
        $config     =   self::setVoteConfig();

        return ToolTime::getUnixTime($config['START_TIME']);
    }

    /**
     * @return int
     * @desc 结束时间
     */
    protected static function setVoteEndTime()
    {
        $config     =   self::setVoteConfig();

        return ToolTime::getUnixTime($config['END_TIME'],'end');
    }
    /**
     * @return bool
     * @desc 是否每天可以投票(全局)
     * 值为1 每天都可以投票,否则为只有一组投票机会
     */
    protected static function isEveryDayVote()
    {
        $config     =   self::setVoteConfig();

        if( $config['IS_EVERY_DAY_VOTE'] == "1" ){

            return true;
        }

        return false;
    }

    /**
     * @return int|mixed
     * @desc 获取全局的最大投票次数
     */
    public static function setMaxVoteTimes()
    {
        $config     =   self::setVoteConfig();

        return $config['MAX_VOTE_TIMES'] ? $config['MAX_VOTE_TIMES']  : ActivityVoteModel::VOTE_MAX_TIME;
    }
    /**
     * @return array|mixed
     * @desc 投票活动的全局配置
     */
    protected static function setVoteConfig()
    {
        $config     =   ActivityConfigModel::getConfig("ACTIVITY_VOTE_GLOBAL_CONFIG");

        if( empty($config) ){

            return SystemConfigModel::getConfig("ACTIVITY_VOTE_GLOBAL_CONFIG");
        }

        return $config;

    }
}