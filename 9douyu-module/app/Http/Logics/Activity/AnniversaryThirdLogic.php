<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/26
 * Time: 上午11:48
 */
namespace App\Http\Logics\Activity;

use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Logics\Activity\Common\ActivityLogic;
use App\Http\Logics\Activity\Common\AnalysisConfigLogic;
use App\Http\Logics\Bonus\BonusLogic;
use App\Http\Models\Common\IncomeModel;
use App\Lang\LangModel;
use App\Tools\ToolMoney;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use Cache;

class AnniversaryThirdLogic  extends ActivityLogic
{

    const
        LOTTERY_CONFIG_KEY       =   "LOTTERY_LIST_CACHE";   //奖品配置

    /**
     * @return array
     * @desc 活动时间
     */
    public  function getActivityTime()
    {
        $config     =   self::config();

        return $this -> getTime( $config['START_TIME'] , $config['END_TIME'] );
    }
    /**
     * @return array
     * @desc 每天展示的奖品数
     */
    public function getEveryDayLottery()
    {
        $prizeNumber    =   self::setVeryDayPrizeNumber();

        $lotteryList    =   $this->getJnhLottery();

        return isset( $lotteryList[$prizeNumber] )  ? $lotteryList[$prizeNumber] : current( $lotteryList )  ;
    }

    /**
     * @return array
     * @desc 获取 嘉年华的奖品
     */
    public function getJnhLottery()
    {
        $config     =   self::config();

        $groupId    =   $this->getActivityLotteryGroup( ActivityFundHistoryDb::SOURCE_ACTIVITY_ANNIVERSARY_THIRD_JNH );

        if( empty( $groupId) || $groupId == '0') {

            $groupId=   $config['AWARD_GROUP'];
        }

        return $this->setCouponLotteryList( $groupId );
    }
    /**
     * @return float|int|string
     * @desc 定位奖品输出的名称
     */
    protected static function setVeryDayPrizeNumber()
    {
        $config     =   self::config();

        if( $config['START_TIME'] >= time() ) {

            return 1;   //活动未开始，直接锁定首位奖品
        }

        $lastTime      =   time();

        if( $config['END_TIME'] <= time() ) {

            $lastTime  =   $config['END_TIME'];
        }

        return  ToolTime::getDayDiff( date( 'Y-m-d' , $config['START_TIME'] ) ,date('Y-m-d' , $lastTime ) ) + 1;
    }
    /**
     * @param int $userId
     * @param int $groupId
     * @desc 执行抽奖的程序
     */
    public function doLuckDraw($userId = 0 )
    {
        $lotteryLogic           =   new LotteryLogic();

        $lotteryParam           =[
                'activity_id'   =>  self::getEventId() ,
                'group_id'      =>  self::getLotteryGroup() ,
                'user_id'       =>  $userId ,
            ];

        $return     =   $lotteryLogic->doLuckDrawWithRate($lotteryParam);

        if( $return['status'] == false ) {

            return $return;
        }
        if( $return['data']['foreign_id'] != 0 && $return['data']['foreign_id'] >1 ){

            $bonusLogic =   new BonusLogic();

            $bonusInfo  =   $bonusLogic->findById($return['data']['foreign_id']);

            if( $bonusInfo['status'] == true ){

                $formatInfo         =   $bonusLogic->filterAttributes( $bonusInfo['data']['obj'] );
                $return['data']['using_desc']   =   $formatInfo['using_desc'];
                $return['data']['rate']         =   $formatInfo['rate'];
                $return['data']['money']        =   $formatInfo['money'];
                $return['data']['min_money']    =   $formatInfo['min_money'];
            }
        }

        return $return;
    }
    protected function setUseBonusProject( $projectType = array() )
    {
        if( empty($projectType) ) {

            return '';
        }

    }
    /**
     * @return array | lottery record
     * @desc 返回随机中奖的数据
     */
    public function getCouponWinningList()
    {
        $config     =   self::config();

        $winnerList =    $this -> setCouponWinningList( $config['START_TIME'] , $config['END_TIME'] ,  ActivityFundHistoryDb::SOURCE_ACTIVITY_ANNIVERSARY_THIRD_JNH );

        if( empty( $winnerList['list'] ) ) {

            return $winnerList;
        }
        foreach ( $winnerList['list'] as  &$winner ) {

            $winner['phone_hide']   =   ToolStr::hidePhone( $winner['phone'] );

            $winner['time_note']    =   date('Y年m月d日',ToolTime::getUnixTime($winner['created_at']));
        }

        return $winnerList;
    }
    /**
     * @param int $bonusId
     * @return mixed
     * @desc 获取红包奖品的信息 留着待用的名称
     */
    protected static function getLotteryBonusInfo( $bonusId = 0 )
    {
        $bonusDb        =   new BonusDb();

        $bonusInfo      =   $bonusDb -> getById( $bonusId );

        $bonusMessage   =   [
                'rate'      =>  (int) $bonusInfo['rate'],
                'money'     =>  (int) $bonusInfo['money'],
                'min_money' =>  (int) $bonusInfo['min_money'],
                'use_desc'  =>  '<p>满' . (int) $bonusInfo['min_money'] . '可用</p><p>可用于投资九省心</p><p>及九安心项目</p>'
        ];

        if( $bonusInfo['type'] == BonusDb::TYPE_COUPON_CURRENT ) {

            $bonusMessage['use_desc']= '可用于投资零钱计划项目';
        }

        if( $bonusInfo['type'] == BonusDb::TYPE_COUPON_INTEREST ){

            $bonusMessage['use_desc']  =  '<p>定期加息券</p><p>可用于投资九省心</p><p>及九安心项目</p>';
        }

        return $bonusMessage;
    }
    /**
     * @return array
     * @desc 活动时间的判断
     */
    public function getTimeCondition($userId=0)
    {
        $config     =   self::config();

        return $this -> isCanJoinActivity( $config['START_TIME'] , $config['END_TIME'] , self::getEventId() , $userId );
    }

    /**
     * @param $userId
     * @return array
     * @desc 判断用户是否可以抽奖
     */
    public  function getUserCondition( $userId )
    {
        $config                 =   self::config();

        $lotteryEdNumber        =   $this -> getUserLotteryNumber( $userId );

        $maxLotteryNumber       =   $this -> getDrawNumber( $config );

        if( $config['DRAW_CYCLE'] && $lotteryEdNumber >= $maxLotteryNumber ) {

            return self::callError( sprintf( LangModel::getLang('ERROR_ACTIVITY_LOTTERY_EVERY_DAY_TRAVEL') , $maxLotteryNumber ) );

        } elseif ( $lotteryEdNumber >= $maxLotteryNumber  ) {

            return self::callError( sprintf(LangModel::getLang('ERROR_ACTIVITY_LOTTERY_ONLY_ONCE') , $maxLotteryNumber ) );
        }

        return self::callSuccess();
    }

    /**
     * @param int $userId
     * @return int|mixed
     * @desc 获取用户在某个活动中的领取或者抽奖次数
     */
    protected function getUserLotteryNumber( $userId = 0 )
    {
        $config         =   self::config();

        $timeArr        =   $this -> setReceiveBetweenTime( $config['START_TIME'] , $config['END_TIME'] , $config['DRAW_CYCLE']);

        return $this -> getUserLotteryInfo( strtotime($timeArr['start']) , strtotime($timeArr['end']), self::getEventId() ,$userId );
    }
    /**
     * @return int | groupId
     * @desc  奖品分组
     */
    protected  function getLotteryGroup()
    {
        $groupId    =    $this -> getActivityLotteryGroup( self::getEventId() );

        return !empty( $groupId ) ? (int)$groupId : self::DEFAULT_GROUP;
    }
    /**
     * @return int | eventId
     * @desc 周年庆活动第三趴的活动标示
     */
    protected static function getEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_ANNIVERSARY_THIRD;
    }

    /**
     * @return object | 第三波活动的的配置
     */
    protected static function config()
    {
       return AnalysisConfigLogic::make( 'ANNIVERSARY_CONFIG_THIRD' );
    }
}
