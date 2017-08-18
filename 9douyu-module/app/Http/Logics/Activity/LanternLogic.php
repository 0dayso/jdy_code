<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/26
 * Time: 下午4:36
 */

namespace App\Http\Logics\Activity;

use App\Http\Logics\Invest\TermLogic;
use App\Http\Models\Activity\ActivityConfigModel;

use App\Http\Logics\Logic;
use App\Http\Models\Activity\GuessRiddlesModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Tools\ToolArray;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use Log;

class LanternLogic extends Logic{

    const
        ACTIVITY_KEY ='ACTIVITY_LANTERN_FESTIVAL';//活动配置key


    /**
     * @desc 获取活动时间格式
     * @return array
     */
    public function getActivityTimesFormat(){
        $timeArr = [];
        //获取活动配置
        $lanternConfig = ActivityConfigModel::getConfig(self::ACTIVITY_KEY);

        $timeArr['start_time'] = date('Y年m月d日', strtotime($lanternConfig['START_TIME']));
        $timeArr['end_middle_time'] = date('m月d日', strtotime($lanternConfig['END_TIME']));
        $timeArr['end_short_time'] = date('d日', strtotime($lanternConfig['END_TIME']));

        return $timeArr;
    }

    /**
     * @return array
     * @desc返回时间戳
     */
    public function getActivityTime()
    {
        $lanternConfig = ActivityConfigModel::getConfig(self::ACTIVITY_KEY);

        return [
            'start' =>  ToolTime::getUnixTime($lanternConfig['START_TIME']),
            'end'   =>  ToolTime::getUnixTime($lanternConfig['END_TIME'],'end'),
        ];
    }
    /**
     * @desc 获取活动期间活动项目
     * @return array
     */
    public function getActivityProject(){

        $projectList = ProjectModel::getNewestProjectEveryType();

        $projectList = $this->getFormatActivityProject($projectList);
        return $projectList;
    }

    /**
     * @desc 格式化活动
     * @param $projectList
     * @return array
     */
    public function getFormatActivityProject($projectList){

        if( empty($projectList) ){

            return [];
        }

        $activityType = self::setActivityProjectType();

        $activityProject       =   [];

        foreach ($projectList as $key => $project ){

            if( in_array($key,$activityType) ){

                $activityProject[$key]=  $project;
            }
        }

        return $activityProject;
    }

    /**
     * 获取后台配置的后台类型
     * @return array
     */
    public static function setActivityProjectType(){

        //$projectType = ['three','six', 'jax'];

        $activityConfig = ActivityConfigModel::getConfig(self::ACTIVITY_KEY);

        $projectType = explode(',', $activityConfig['ACTIVITY_PROJECT']);

        return $projectType;
    }

    /**
     * @desc 获取活动期间内投资排名
     * @param $size
     * @return mixed
     */
    public function getActivityInvestRankData($size){

        $investLogic = new TermLogic();
        
        $lanternConfig = ActivityConfigModel::getConfig(self::ACTIVITY_KEY);

        $startTime = date("Y-m-d H:i:s", ToolTime::getUnixTime($lanternConfig['START_TIME']));
        $endTime   = date("Y-m-d H:i:s", ToolTime::getUnixTime($lanternConfig['END_TIME'],'end'));

        $returnArr      =   [
            'start_time'    =>  $startTime,
            'end_time'      =>  $endTime,
            'size'          =>  $size
        ];
        $rankingList    =    $investLogic->getInvestStatisticsExist($returnArr);

        $rankingList    =    $this->getFormatRank($rankingList);

        return $rankingList;
    }

    /**
     * @desc 格式化投资排名的数据
     * @param $rankingList
     * @return mixed
     */
    public function getFormatRank($rankingList){
        $userIds = implode(',', ToolArray::arrayToIds($rankingList, 'user_id'));

        $userInfo  = ToolArray::arrayToKey(UserModel::getUserListByIds($userIds), 'id');

        foreach($rankingList as $key=>$value){
            if(isset($userInfo[$value['user_id']])){
                $rankingList[$key]['phone'] = ToolStr::hidePhone($userInfo[$value['user_id']]['phone'], 3, 4);
                $rankingList[$key]['real_name'] = $userInfo[$value['user_id']]['real_name'];
            }
        }
        return $rankingList;
    }

}
