<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/19
 * Time: 下午6:16
 */

namespace App\Http\Logics\Activity;


use App\Http\Logics\Logic;
use App\Http\Logics\Project\CurrentLogic;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Common\CoreApi\StatisticsModel;
use App\Tools\ToolTime;

class PromotionLogic extends Logic
{
    protected static $objectExample;  //数据对象


    public static function getTime()
    {
        $config =   self::config();

        return[
            'start'=>ToolTime::getUnixTime ($config['START_TIME']),
            'end'=>ToolTime::getUnixTime ($config['END_TIME'] ,'end')
        ];
    }

    /**
     * @return array
     * @desc 获取需要展示的项目
     */
    public function getFormatProject()
    {
        $projectList    =   self::getNewestProjectEveryType();

        unset($projectList['sdfsix']);

        unset($projectList['sdftwelve']);

        $formatProject  =   self::getFormatProjectType($projectList);

        $formatProject['current']   =   self::getCurrentProject();

        return $formatProject;
    }

    /**
     * @param array $projectList
     * @return array
     * @desc 项目分类
     */
    protected static function getFormatProjectType($projectList = array())
    {
        if( empty($projectList) ){

            return [];
        }

        $showType   =   self::setShowProjectLine();

        $showProject=   [];

        $moreProject=   [];

        foreach ($projectList as $key => $project ){

            if( in_array($key,$showType) ){

                $showProject[$key]=  $project;
            }else{

                $moreProject[$key]= $project;
            }
        }

        return ['show'=>$showProject , 'more'=>$moreProject];
    }
    /**
     * @return mixed
     * @desc 获取一个就随心项目
     */
    public function getCurrentProject()
    {
        $logic      =   new CurrentLogic();

        $current    =   $logic->getShowProject();

        return $current;
    }
    /**
     * @return int
     * @desc 读取所有最新的项目的项目
     */
    protected static function getNewestProjectEveryType()
    {
        return ProjectModel::getNewestProjectEveryType();
    }

    /**
     * @return array
     * @desc 读取九省心的类型
     */
    protected static function setShowProjectLine()
    {
        return['three','six'];
    }

    /**
     * @return array | 9douyu total statistics
     * @desc 返回九斗鱼平台数据汇总，累计投资，定期投资，预期收益等数据
     */
    public static function getStatistics()
    {
        return StatisticsModel::getStatistics();
    }
    /**
     * @return 获取配置文件
     */
    private static function config()
    {
        return ActivityConfigModel::getConfig('NOVICE_ACTIVITY_S11');

    }
    /**
     * @return 解析数据
     */
    private static function getObject()
    {
        return self::getInstance()->getObject();
    }

    /**
     * @return object 读取配置信息，并进行解析
     */
    private static function getInstance()
    {
        if(!(self::$objectExample instanceof self)){

             self::$objectExample = new AnalysisConfigLogic('NOVICE_ACTIVITY_S11');
        }

        return self::$objectExample;
    }
}
