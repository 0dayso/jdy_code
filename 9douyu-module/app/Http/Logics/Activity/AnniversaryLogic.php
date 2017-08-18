<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 2017/2/21
 * Time: 下午2:36
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Activity\Common\ActivityLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Tools\ToolArray;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use App\Http\Logics\Activity\Common\AnalysisConfigLogic;
use Cache;
use App\Http\Dbs\Project\ProjectDb;

class AnniversaryLogic extends ActivityLogic
{
    protected static $objectExample;  //数据对象
    const DEFAULT_GROUP =   5;  //默认的奖品配置

    /*******************************展示数据********************************/

    public function getActivityTime()
    {
        $config  =   self::config();

        return  $this->getTime( $config['START_TIME'] , $config['END_TIME'] );
    }
    /**
     * @return act_token 唯一标示
     */
    public  function getActToken()
    {
        return time() . '_' . self::getEventId();
    }
    /**
     * @return mixed
     * @desc  活动的项目
     */
    public function getShowProject()
    {
        $config         =   self::config();

        $projectList    =   $this->getProject( $config['ACTIVITY_PROJECT'] );

        if( empty($projectList) ) {

            return $projectList;
        }

        $termLogic      =   new TermLogic();

        foreach ( $projectList as $key => &$project ) {

            $project['income'] = $this->getProfitByProject($project['refund_type'] , $project['profit_percentage'] , $project['invest_time'] , 100000);

            $project['income_note']= "出借10万元预计可获得收益{$project['income']}元";
        }

        return $projectList;
    }
    protected function getProfitByProject( $refundType , $rate , $investTime,$cash='100000' )
    {
        if( $refundType == ProjectDb::REFUND_TYPE_BASE_INTEREST ) {

            return round( $cash * $rate / 365 * $investTime /100 , 2 ) ;
        }

        return round( $cash * $rate / 12 *$investTime/100 , 2 ) ;
    }
    /**
     * @return array | summation && percentage
     * @desc 总和和百分比
     */
    public function getInvestPercentage()
    {
        $config     =   self::config();

        $return['summation']    =   self::gitInvestTotal();

        $return['diffDay']      =   self::getLastTimeNote( $config['START_TIME'] , $config['END_TIME']);

        $firstLevel             =   $config['INVEST_AWARD_BONUS_FIRST'] * 10000;

        $secondLevel            =   $config['INVEST_AWARD_BONUS_SECOND'] * 10000;

        $thirdLevel             =   $config['INVEST_AWARD_BONUS_THIRD'] * 10000;

        if( $return['summation'] <= $firstLevel || $return['summation'] < $secondLevel ) {

            $return['percentage']=   '1';
        }

        if( $return['summation'] >= $thirdLevel ) {

            $return['percentage']=   '3';
        }

        if( $return['summation'] < $thirdLevel && $return['summation'] >= $secondLevel ) {

            $return['percentage']=   '2';
        }

        return $return;
    }
    public function getLastTimeNote($startTime , $endTime )
    {
        if( $startTime > time() ) {

            return '周年庆活动在' . date('m月d日' , $startTime ) . '零点开始！';
        }

        $lastTime   =   $endTime - time();

        if( $lastTime/3600 >=24 ){

            return '距周年庆结束还有' . ToolTime::getDayDiff(date('Y-m-d',time()),date('Y-m-d',$endTime)) . '天';
        }

        if( $lastTime >0 && $lastTime/3600 < 24 ){

            return '距周年庆结束还有' . (int) ($lastTime/3600) . '小时';
        }

        if( $lastTime >0 && $lastTime/60 < 60 ) {

            return '距周年庆结束还有' . (int) ($lastTime/60) . '分钟';
        }

        if( $lastTime < 0 ) {

            return '周年庆活动在' . date('m月d日',$endTime+2) . '零点结束！';
        }
    }
    /*
     * @return mixed | int | investSumCash
     * @desc 获取投资定期的总金额
     */
   protected function gitInvestTotal()
    {
        $config =   self::config();

        return $this->getSatisfyInvestSummation( $config['START_TIME'] , $config['END_TIME'] , true );
    }

    /*
     * @return array | lotteryList
     * @desc 获取活动的奖品数据
     */
    public function getLottery()
    {
        $config     =   self::config();

        $groupId    =   $this->getActivityLotteryGroup( self::getEventId() );

        if( empty( $groupId) || $groupId == '0') {

            $groupId=   $config['AWARD_GROUP'];
        }

        return $this->setCouponLotteryList( $groupId );
    }


    /*
     * @erturn array | lottery Record List
     * @desc 获取中奖的数据（每日的三名中奖数据）
     *
     */
    public function getWinnerList()
    {
        $config     =   self::config();

        $winnerList =   $this->setCouponWinningList( $config['START_TIME'] , $config['END_TIME'] , self::getEventId());

        if( empty( $winnerList['list'] ) ) {

            return $winnerList;
        }
        foreach ( $winnerList['list'] as  &$winner ) {

            $winner['phone_hide']   =   ToolStr::hidePhone( $winner['phone'] );

            $winner['time_note']    =   date('Y年m月d日',ToolTime::getUnixTime($winner['created_at']));
        }

        $formatList     =   [];

        foreach ( $winnerList['list'] as $key => $item ) {

            $formatList[$item['time_note']][] =   $item;
        }
        return [ 'list' => $formatList , 'lotteryNum' => $winnerList['lotteryNum'] ];
    }

    /**
      * @今日头条的统计代码标识
      */
    public function getJrttStatisitis($channel = '')
    {
        if( empty( $channel ) ) {

            return '';
        }
        $jrttArr    =   [
            'jrtt_thirdAnniversary'  =>  '61966603836',
            'jrtt_thirdAnniversary2' =>  '61966728238',
            'jrtt_thirdAnniversary3' =>  '61966805465',
            'jrtt_thirdAnniversary4' =>  '61966840133',
            'jrtt_thirdAnniversary5' =>  '61966826132',
        ];

        return isset( $jrttArr[$channel] ) ? $jrttArr[$channel] : '' ;
     }
    /**
     * @return int
     * @desc 活动的唯一标示
     */
    protected  function getEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_THIRD_ANNIVERSARY;
    }
    /**
    * @return array|mixed
    * @desc  加币活动的配置文件
    */
    protected function config()
    {
        return AnalysisConfigLogic::make('THREE_ANNIVERSARY_CONFIG');
    }
}
