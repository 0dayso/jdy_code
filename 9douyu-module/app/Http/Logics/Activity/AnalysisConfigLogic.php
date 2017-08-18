<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/2/10
 * Time: 下午2:55
 */

namespace App\Http\Logics\Activity;


use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Tools\ToolTime;

class AnalysisConfigLogic
{
    private static $config      =   []; //配置文件内容
    private static $key         =   '';

    public  function __construct( $key )
    {
        self::$key      =   self::formatConfigKey($key);
        self::$config   =   self::config();
    }

    /**
     * @return mixed
     * @desc 返回解析后的数据
     */
    public static function getObject()
    {
        //$formatConfig['project']                    =   self::setActivityProject();

        $formatConfig['config']                     =   self::$config;

        $formatConfig['config']['START_TIME']       = self::setStartTime();

        $formatConfig['config']['END_TIME']         = self::setEndTime();

        $formatConfig['config']['ACTIVITY_PROJECT'] = self::setProjectLine();

        $formatConfig['config']['IS_CAN_USED_BONUS']= self::usedBonusStatus();

        $formatConfig['config']['UNUSED_APP_VERSION']= self::setUnUseAppVersion();

        $formatConfig['config']['PROJECT_LINE_NOTE'] = self::setProjectLineNote();

        return $formatConfig;
    }
    /**
     * @return int
     * @desc 活动开始时间
     * @desc 如果没有配置活动时间则返回空
     */
    protected static function setStartTime()
    {
        if(isset(self::$config['START_TIME'])){

            return ToolTime::getUnixTime(self::$config['START_TIME']);
        }

        return "";
    }

    /**
     * @return array
     * @desc 活动页需要展示的项目
     */
    protected static function setProjectLine()
    {
        $projectConfig   =   isset(self::$config['ACTIVITY_PROJECT']) ? self::$config['ACTIVITY_PROJECT'] : '';

        if( empty($projectConfig) ){

            return [];
        }

        return explode(',',$projectConfig);
    }
    /**
     * @return array
     * @desc 这里是这里是设置异常的App版本
     */
    protected static function setUnUseAppVersion()
    {
        $config =   self::config();

        if(isset($config['UNUSED_APP_VERSION']) && !empty($config['UNUSED_APP_VERSION']) ){

            return  explode(",",$config['UNUSED_APP_VERSION']);
        }

        return [];
    }
    /**
     * @return array
     * @desc 展示活动项目
     */
    protected static function setActivityProject()
    {
        $projectConfig  =   self::setProjectLine();

        if( empty($projectConfig) ){

            return [];
        }

        $projectList    =   self::getNewestProjectEveryType();

        $activityProject=   [];

        foreach ($projectList as $key   => $project ){

            if( in_array($key,$projectConfig) ){

                $activityProject[$key]= $project;
            }
        }

        return $activityProject;
    }

    /**
     * @return array
     * @desc 自定义的文字标示
     */
    protected static function setProjectLineNote()
    {
        return [
            'one'       =>  '1月期',
            'three'     =>  '3月期',
            'six'       =>  '6月期',
            'twelve'    =>  '12月期',
            'jax'       =>  '九安心',
            'sdfsix'    =>  '闪电付息6月期',
            'sdftwelve' =>  '闪电付息12月期',
        ];
    }
    /**
     * @return bool
     * @desc 判断用户投资使用可以使用红包
     */
    protected static function usedBonusStatus()
    {
        if(isset(self::$config['IS_CAN_USED_BONUS']) && self::$config['IS_CAN_USED_BONUS'] == 1){

            return true;
        }

        return false;
    }
    /**
     * @return array
     * @desc 获取每一个产品线最新的产品
     */
    protected static function getNewestProjectEveryType()
    {
        return ProjectModel::getNewestProjectEveryType();
    }
    /**
     * @return int
     * @desc 活动结束时间
     * @desc 如果没有配置活动时间则返回空
     */
    protected static function setEndTime()
    {
        if(isset(self::$config['END_TIME'])){

            return ToolTime::getUnixTime(self::$config['END_TIME'],'end');
        }

        return "";
    }
    /**
     * @param $key
     * @return string
     * @desc 配置文件内的Key确保为答谢
     */
    private static function formatConfigKey($key)
    {
        return strtoupper($key);
    }

    /**
     * @return array|mixed
     * @desc 读取配置文件的原始文件
     */
    private static function config()
    {
        return ActivityConfigModel::getConfig(self::$key);
    }
}
