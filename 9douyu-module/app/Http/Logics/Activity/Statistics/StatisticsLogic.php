<?php

/**
 * Created by 9douyu Coder.
 * User: scofie wu.changming@9douyu.com
 * Date: 09/05/2017.
 * Time: 4:16 PM.
 * Desc: StatisticsLogic.php.
 */

namespace App\Http\Logics\Activity\Statistics;
use App\Http\Logics\Activity\ActivityConfigLogic;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\Logic;

class StatisticsLogic extends CheckActLogic
{


    /**
     * @return array
     * @desc  展示推荐的活动信息并格式化返回给项目数据接口
     */
    public static function recommendActivity( $productLine = '',  $actToken ='' )
    {
        if( self::decideStatusByActToken( $productLine,  $actToken ) == false ){

            return  self::formatAdInformation( self::getAdInformation() );
        }

        //需讨论是否需要提示用户当前正常参与的活动
        $parameterArray =   self::getParameterFromActToken( $actToken ) ;

        return [];

        //return ['title' => parent::getActivityNote($parameterArray['activity_id']) ];
    }

    /**
     * @return array|mixed
     * @desc act_token的有效性
     */
    public static function getRecommendTimePoint()
    {
        $recommendConfig    =   self::getRecommendConfig();

        return isset( $recommendConfig['ACT_TOKEN_USEFULL_TIME'] ) ? $recommendConfig['ACT_TOKEN_USEFULL_TIME'] : 3600;
    }

    /**
     * @return array
     * @desc 根据配置的广告位的Id获取广告的信息
     */
    protected static function getAdInformation()
    {
        return AdLogic::getUseAbleListByAdId( self::getFormatActivityWithAdIdArray(self::getRecommendAdConfig()) );
    }

    /**
     * @param array $adInformation
     * @return array
     * @desc 格式化数据
     */
    protected static function formatAdInformation( $adInformation = [] )
    {
        if ( empty($adInformation) ) {

            return [];
        }

        $formatInformation  =   [];

        foreach ( $adInformation as $key => $information ) {

            $param  =   json_decode($information['param'] , true);

            $formatInformation[]  =   [
                'id'    => $information['id'],
                'url'   => $param['url'],
                'title' => $information['title'],
                'shareInfo' => [
                    'share_title'  => !empty($param['share_title']) ? $param['share_title'] : $information['title'] ,
                    'share_img'    => (!empty($param['share_image_name'])&&!empty($param['share_image_path'])) ? assetUrlByCdn($param['share_image_path'].$param['share_image_name']) : '' ,
                    'share_desc'   => !empty($param['share_desc']) ? $param['share_desc'] : $information['title'] ,
                    'share_url'    => !empty($param['share_url']) ? $param['share_url'] : $param['url'] ,
                ]
            ];
        }

        return $formatInformation;
    }

    /**
     * @param string $ids
     * @return array
     * @desc 把需要展示的活动的广告Id拆分
     */
    protected static function getFormatActivityWithAdIdArray( $ids = '' )
    {
        if( empty( $ids ) ) {

            return [];
        }

        return explode( ',' , $ids ) ;
    }
    /**
     * @return array|mixed
     * @desc
     */
    protected static function getRecommendAdConfig()
    {
        $recommendConfig    =   self::getRecommendConfig();

        return isset($recommendConfig['APP_BANNER_INDEX_ADD_ID']) ? $recommendConfig['APP_BANNER_INDEX_ADD_ID'] : "";
    }
    /**
     * @return array|mixed
     * @desc 返回推荐活动的配置的信息
     */
    protected static function getRecommendConfig()
    {
        return ActivityConfigLogic::getConfig('RECOMMEND_ACTIVITY_CONFIG');
    }
}
