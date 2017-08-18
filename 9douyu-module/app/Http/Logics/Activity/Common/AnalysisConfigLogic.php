<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/2/10
 * Time: 下午2:55
 */

namespace App\Http\Logics\Activity\Common;

use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Tools\ToolTime;

class AnalysisConfigLogic extends Logic
{
    protected static $config      =   []; //配置文件内容
    protected static $key         =   '';
    //活动中心参数定义
    protected static $methodConfig = [ ];
    const
        INVEST_ALLOW_WITH_BONUS =   'allow',    //可以使用红包的标示
        ACTIVITY_CYCLE_DAY      =   'day',      //按天统计
        SHOW_RECORD_BLOCK       =   'block',    //默认展示记录
        SHOW_INVEST_GAME_TRUE   =   true,       //是否展示投资排名
        SHOW_PLACE_CYCLE_PRODUCT=   'product',  //按照产品线
        SHOW_PLACE_CYCLE_ALL    =   'all';      //默认按照投资的所有项目



    /*
     * return [
            'MIN_INVEST_CASH'  		=>	'setMinInvestCashDeploy',		    //投资金额配置:单笔投资
            'MIN_RECHARGE_CASH'	    =>	'serMinRechargeCashDeploy',		    //充值金额配置:	最小充值金额
            'INVEST_WITH_BONUS'	    =>	'setInvestWithBonusDeploy',	        //是否使用红包配置:	allow/none允许/禁止
            'DRAW_NUMBER'		    =>	'setDrawNumberDeploy',	            //领取/抽奖次数配置：	周期内领取次数
            'AWARD_GROUP'		    =>  'setAwardGroupDeploy',	    	    //领取奖品配置：奖品，红包的配置lottery_group
            'DRAW_CYCLE'		    =>	'setDrawCycleDeploy',		        //领取/抽奖周期配置：天/活动周期 day/cycle
            'SHOW_AWARD_RECORD'		=>	'setShowAwardRecordDeploy',		     //是否展示奖品记录：展示/不展示 block/none
            'SHOW_PLACE_CYCLE'      =>  'setShowPlaceCycleDeploy',		    //按照项目的排名:按照项目类型/全部投资项目 product/all/none
            'START_TIME'			=>	'setStartTimeDeploy',	            //活动开始时间配置：开始时间 date
            'END_TIME'				=>	'setEndTimeDeploy',	                //活动结束时间配置：结束时间 date
            'MATTER_APP_VERSION	'   =>  'setMatterAppVersionDeploy',	    //有问题的App版本号：版本号	有问题的版本号app_version
            'ACTIVITY_PROJECT'	    =>  'setActivityProjectDeploy',	        //参与的活动项目类型
        ];
     */
    /**
     * AnalysisConfigLogic constructor.
     * @param $key
     */
    private  function __construct($key)
    {
        self::$key          =   self::setKeyToUpper($key);

        self::$config       =   self::config();
    }


    /**
     * @param string | $key
     * @return array| 格式化后的活动配置文件
     * @desc 通过活动配置的key获取参数
     */
    public static function make($key)
    {
         $object = new static($key);

        return  $object->getInstance();
    }

    /**
     * @return mixed |array
     * @desc 解析活动的配置
     */
    protected  function getInstance()
    {
        $matConfig      =   [];

        foreach(  self::$config as $key => $method ){

            $return = $this->getAttribute($key);

            if( !is_null($return) || !empty($return) ){

                $matConfig[$key]    =   $return;
            }else{
                $matConfig[$key]    =   $method;
            }
        }

        $matConfig['PRODUCT_LINE_NOTE']= self::setProjectLineNote();

        $matConfig['PROJECT_SORT_NOTE']= self::setProjectLineToAbbreviation();  //简称

        return $matConfig;
    }

    /**
     * @return string | $name = ''| default null
     * @return result | null
     * @desc 使用函数回调解析配置文件的参数
     */
    protected function getAttribute($name = '')
    {
        if( empty($name)){

            return null;
        }

        $humpName   =   explode("_",$name);

        $methodName=    $this->getHumpName($humpName);

        if(is_null($methodName)|| empty($methodName)){

            return null;
        }

        $method =   'set'.$methodName.'Deploy';

        if( method_exists($this,$method) ){

            return call_user_func_array([$this,$method],[]);
        }

        return null;
    }

    /**
     * @param array| $humpName 需要格式化的变量
     * @return string | 格式化后首字母大写的字符串
     * @desc 把配置文件内的key 格式成首字母大写
     */
    protected static function getHumpName($humpName = [])
    {
        if(empty($humpName) || $humpName ==[]){

            return null;
        }

        foreach($humpName as $key => &$name){

            $humpName[$key] = ucwords(strtolower($name));
        }

        return implode($humpName);
    }
    /**
     * @return mixed | int
     * @desc 投资金额配置:单笔投资 default 0 表示不显示单笔投资额度
     */
    protected static function setMinInvestCashDeploy()
    {
        return isset(self::$config['MIN_INVEST_CASH']) && !empty(self::$config['MIN_INVEST_CASH']) ? (int)self::$config['MIN_INVEST_CASH'] :100;
    }
    /**
     * @return mixed | int
     * @desc 充值金额配置: 最小充值金额 default 0 标示为不限制充值金额
     */
    protected static function setMinRechargeCashDeploy()
    {
        return isset(self::$config['MIN_RECHARGE_CASH']) && !empty(self::$config['MIN_RECHARGE_CASH']) ? (int)self::$config['MIN_RECHARGE_CASH'] :100;
    }
    /**
     * @return mixed|int default 0
     * @desc //领取/抽奖次数配置：  周期内领取次数
     */
    protected static function setDrawNumberDeploy()
    {
        return isset(self::$config['DRAW_NUMBER'])&& !empty(self::$config['DRAW_NUMBER']) ?self::$config['DRAW_NUMBER'] :1;
    }
    /**
     * @return mixed|int default 0
     * @desc 领取奖品配置：奖品，红包的配置lottery_group
     */
    protected static function setAwardGroupDeploy()
    {
        return isset(self::$config['AWARD_GROUP'])&& !empty(self::$config['AWARD_GROUP']) ?self::$config['AWARD_GROUP'] :0;
    }
    /*
     * @return mixed|bool
     * @desc 领取/抽奖周期配置：天/活动周期 day/cycle
     */
    protected static function setDrawCycleDeploy()
    {
        $cycleConfig    =   isset(self::$config['DRAW_CYCLE']) ? self::$config['DRAW_CYCLE'] : '';

        if( !empty($cycleConfig) && $cycleConfig == self::ACTIVITY_CYCLE_DAY ){

            return true;
        }

        return false;
    }
    /*
     * @return mixed|bool
     * @desc 是否展示奖品记录：展示/不展示 block/none
     */
    protected static function setShowAwardRecordDeploy()
    {
        $showConfig =   isset(self::$config['SHOW_AWARD_RECORD']) ? self::$config['SHOW_AWARD_RECORD'] : '';

        if( !empty($showConfig) && $showConfig == self::SHOW_RECORD_BLOCK ){

            return true;
        }

        return false;
    }
    /**
     * @return bool|true
     * @desc  按照项目的排名:按照项目类型/全部投资项目 product/all
     */
    protected static function setShowPlaceCycleDeploy()
    {
        $cycleConfig    =   isset(self::$config['SHOW_PLACE_CYCLE']) ? self::$config['SHOW_PLACE_CYCLE'] : '';

        if(  $cycleConfig == self::SHOW_PLACE_CYCLE_PRODUCT || $cycleConfig ==self::SHOW_PLACE_CYCLE_ALL){

            return $cycleConfig;
        }

        return 'none';
    }
    /**
     * @return array
     * @desc 活动页需要展示的项目
     */
    protected static function setActivityProjectDeploy()
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
    protected static function setMatterAppVersionDeploy()
    {
        $config =   self::config();

        if(isset($config['MATTER_APP_VERSION']) && !empty($config['MATTER_APP_VERSION']) ){

            return  explode(",",$config['MATTER_APP_VERSION']);
        }

        return [];
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
     * @return array
     * @desc 对应的项目文字简称
     */
    protected static function setProjectLineToAbbreviation()
    {
        return [
                ProjectDb::PRODUCT_LINE_ONE_MONTH       => 'one', //九省心一月期
                ProjectDb::PRODUCT_LINE_THREE_MONTH     => 'three',  //九省心三月期
                ProjectDb::PRODUCT_LINE_SIX_MONTH       => 'six',  //九省心六月期
                ProjectDb::PRODUCT_LINE_TWELVE_MONTH    => 'twelve', //九省心十二月期
                ProjectDb::PRODUCT_LINE_FACTORING       => 'jax', //保理
                ProjectDb::PRODUCT_LINE_LIGHTNING_SIX_MONTH => 'sdfsix',  //闪电付息六月期
                ProjectDb::PRODUCT_LINE_LIGHTNING_TWELVE_MONTH => 'sdftwelve',  //闪电付息十二月期
        ];
    }
    /**
     * @return bool
     * @desc 判断用户投资使用可以使用红包
     */
    protected static function setInvestWithBonusDeploy()
    {
        if(isset(self::$config['INVEST_WITH_BONUS']) && self::$config['INVEST_WITH_BONUS'] == 1){

            return true;
        }

        return false;
    }
    /**
     * @return int
     * @desc 活动开始时间
     * @desc 如果没有配置活动时间则返回空
     */
    protected static function setStartTimeDeploy()
    {
        if(isset(self::$config['START_TIME'])){

            return ToolTime::getUnixTime(self::$config['START_TIME']);
        }

        return null;
    }
    /**
     * @return int
     * @desc 活动结束时间
     * @desc 如果没有配置活动时间则返回空
     */
    protected static function setEndTimeDeploy()
    {
        if(isset(self::$config['END_TIME'])){

            return ToolTime::getUnixTime(self::$config['END_TIME'],'end');
        }

        return null;
    }
    /**
     * @param $key
     * @return string
     * @desc 配置文件内的Key确保为答谢
     */
    protected static function setKeyToUpper($key)
    {
        return strtoupper($key);
    }
    /**
     * @return array|mixed
     * @desc 读取配置文件的原始文件
     */
    protected static function config()
    {
        return ActivityConfigModel::getConfig(self::$key);
    }
}
