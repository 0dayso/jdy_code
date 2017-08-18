<?php
/**
 * Created by 9douyu Coder.
 * User: scofie wu.changming@9douyu.com
 * Date: 13/05/2017.
 * Time: 3:23 PM.
 * Desc: ActivityStatisctListener.php.
 */

namespace App\Listeners\Award\Activity;


use App\Events\CommonEvent;
use App\Http\Logics\Activity\Statistics\ActivityStatisticsLogic;
use App\Http\Logics\Activity\Statistics\CheckActLogic;
use Log;

class ActivityStatisticsListener
{
    public function handle( CommonEvent $event)
    {
        $data       =   $event->getDataByKey('activity');
        Log::info('act_Token_record_param', $data ) ;
        $actToken   =   isset( $data['act_token'] ) ? $data['act_token'] : '' ;

        $productLine=   isset( $data['project_line'] ) ? $data['project_line'] : '';

        $return =    CheckActLogic::decideStatusByActToken( $productLine , $actToken );

        if ( $return == true ) {

            $parameterArray =   CheckActLogic::getParameterFromActToken( $actToken ) ;

            $this->implementCheckInRecord( $parameterArray['activity_id'] , $data );
        }

    }
    /**
     * @param $param
     * @desc 记录数据
     */
    protected static function implementCheckInRecord( $activityId, $param )
    {
        $config             =   self::getActConfig($activityId) ;

        Log::info('act_Token_record_config', $config ) ;

        $return['status']   =   true;

        if( self::decideInvestWithBonus($config , $param['bonus_id']) && self::decideMinInvestCash($config , $param['cash']) ) {

            $param['act_id']=   $activityId;

            $return         =   ActivityStatisticsLogic::checkInRecord( $param );
        }

        if( $return['status'] == false ){

            Log::Error('act_Token_record_activity_error', $return ) ;
        } else {

            Log::info('act_Token_record_activity_success', $param ) ;
        }
    }
    /**
     * @param $config
     * @param $cash
     * @return bool
     * @desc 验证活动的单笔投资金额
     */
    protected static function decideMinInvestCash( $config ,$cash )
    {
        if( isset( $config['MIN_INVEST_CASH'] ) && $cash < $config['MIN_INVEST_CASH'] ) {

            return false;
        }

        return true;
    }
    /**
     * @param $config
     * @param int $bonusId
     * @return bool
     * @desc  用户使用投资是否可以使用红包
     */
    protected static function decideInvestWithBonus($config , $bonusId = 0)
    {
        if( isset($config['INVEST_WITH_BONUS']) && $config['INVEST_WITH_BONUS']==false && $bonusId > 0 ) {

            return false ;
        }

        return true;
    }
    /**
     * @param $actToken
     * @return bool
     * @desc 通过Act_token 活动活动对应的配置
     */
    protected static function getActConfig( $activityId )
    {
        return CheckActLogic::decideActivityThereExist( $activityId ) ;
    }


}
