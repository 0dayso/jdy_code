<?php
/**
 * Created by PhpStorm.
 * User: scofie
 * Date: 7/18/17
 * Time: 10:26 AM
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Activity\Common\ActivityLogic;
use App\Http\Logics\Activity\Common\AnalysisConfigLogic;

class JulyLogic extends ActivityLogic
{
    protected static $config ;//活动配置文件

    /**
     * @return array
     * @desc 构建活动时间
     */
    public static function getActivityTime()
    {
        $config    =   self::config ();

        return self::getTime ($config['START_TIME'] , $config['END_TIME'] ) ;
    }

    /**
     * @return array|mixed
     * @desc build activity project
     */
    public static function getActivityProject()
    {
        $config    =   self::config ();

        $projectList    =   self::getProject($config['ACTIVITY_PROJECT']) ;

        if( empty($projectList) ) {

            return[];
        }
        foreach ($projectList as $key => &$project ) {

            $project['act_token']    =  self::getActToken () . "_" . $project['id'] ;
        }

        return $projectList;
    }

    /**
     * @return string
     * @desc act_token only label
     */
    public static function getActToken()
    {
        return time() . '_' . self::getActivityEventId();
    }

    /**
     * @return int
     * @desc this activity Only event Id
     */
    protected static function getActivityEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_JULY;
    }
    /**
     * @return Common |格式化后的活动配置文件|array
     * @desc analysis activity config
     */
    protected static function config()
    {
        return AnalysisConfigLogic::make('ACTIVITY_JULY_CONFIG');
    }
}