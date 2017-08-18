<?php

/**
 * Created by 9douyu Coder.
 * User: scofie wu.changming@9douyu.com
 * Date: 09/05/2017.
 * Time: 4:16 PM.
 * Desc: 检测活动的状态值
 */

namespace App\Http\Logics\Activity\Statistics;

use App\Http\Logics\Activity\Common\ActivityLogic;
use App\Http\Logics\Activity\Common\AnalysisConfigLogic;
use App\Http\Models\Activity\ActivityConfigModel;

class CheckActLogic extends ActivityLogic
{

    /**
     * @param $productLine
     * @param string $actToken
     * @return array|bool
     * @desc 符号要求,返回配置文件,不符合返回false
     */
    public static function decideStatusByActToken( $productLine, $actToken = '')
    {
        //act_token 是否存在
        if( self::isGetActToken( $actToken ) == false ) return false ;

        $parameterArray =   self::getParameterFromActToken( $actToken ) ;

        $actConfig      =   self::decideActivityThereExist( $parameterArray['activity_id'] ) ;

        if( empty($actConfig) ) return false ;

        //验证活动时间
        if( self::decideActivityTime( $actConfig['START_TIME'] , $actConfig['END_TIME'] ) ==false) return false;

        //是否在有效的时间内
        if( self::decideOperationTimeWhetherEffective( $parameterArray['timestamp'] , StatisticsLogic::getRecommendTimePoint())== false ) return false ;

        if( self::decideProductLineInActivityConfig( $productLine , $actConfig ) ==false ) return false;

        return true;
    }

    /**
     * @param $productLine
     * @param $config
     * @return bool
     * @desc 判断当前项目的产品线是否在活动配置中
     */
    protected static function decideProductLineInActivityConfig( $productLine ,$config )
    {
        $productLineConfig  =   self::getProductLineAbbreviation( $config ) ;

        $productLine        =   self::decideProductLineAbbreviation( $productLine , $config['PROJECT_SORT_NOTE'] );

        return in_array( $productLine , $productLineConfig ) ? true  : false;
    }

    /**
     * @param $productLine
     * @param $systemAbbreviation
     * @return string
     * @desc 当前项目的简称Abbreviation文字
     */
    protected static function decideProductLineAbbreviation( $productLine ,$systemAbbreviation )
    {
        return isset( $systemAbbreviation[$productLine] ) ? $systemAbbreviation[$productLine] : '' ;
    }
    /**
     * @param string $actId
     * @return bool
     * @desc 验证活动是否存在 活动的配置文件
     */
    public static function decideActivityThereExist( $actId = '' )
    {
        //是否有活动配置存在(验证的活动是否存在)
        $actKey         =   self::decideEventWithConfigExistence( $actId ) ;

        if( empty($actKey) ) return [];

        $actConfig      =   self::getActivityConfig( $actKey ) ;

        if( empty( $actConfig ) ) return [] ;

        return $actConfig ;
    }

    /**
     * @param $parameterArray
     * @return array
     * @desc  act_token的配置
     */
    public static function getParameterFromActToken( $actToken = '' )
    {
        $parameterArray =   explode( "_" , trim($actToken) ) ;

        if( empty( $parameterArray ) ) { return [] ; }

        return [
            'project_id'    =>  isset( $parameterArray[2] ) ? $parameterArray[2] : null ,
            'timestamp'     =>  isset( $parameterArray[0] ) ? $parameterArray[0] : null ,
            'activity_id'   =>  isset( $parameterArray[1] ) ? $parameterArray[1] : null ,
        ];
    }
    /**
     * @param string $actToken
     * @return bool
     */
    protected static function isGetActToken( $actToken= '' )
    {
        if( empty($actToken) ) {return false ; }

        return true;
    }

    /**
     * @param int $actId
     * @return mixed|null
     * @desc 判断ActId 是否存在活动配置
     */
    public static function decideEventWithConfigExistence( $actId = 0 )
    {
        $eventConfigList    =   self::getEventIdWithActivityConfig() ;

        return isset( $eventConfigList[$actId] ) ? $eventConfigList[$actId] : null;
    }

    /**
     * @return array|mixed
     * @desc 目前使用的活的配置
     */
    protected static function getEventIdWithActivityConfig( )
    {
        return ActivityConfigModel::getConfig( 'ACTIVITY_EVENT_ID_TO_CONFIG' ) ;
    }

    /**
     * @param string $key
     * @return \App\Http\Logics\Activity\Common\格式化后的活动配置文件|array
     * @desc  获取活动活动的基本配置
     */
    protected static function getActivityConfig( $key = '' )
    {
        return AnalysisConfigLogic::make( $key ) ;
    }
}
