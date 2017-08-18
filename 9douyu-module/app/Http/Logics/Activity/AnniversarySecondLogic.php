<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/3/13
 * Time: 下午2:14
 */

namespace App\Http\Logics\Activity;

use App\Http\Dbs\User\InviteDb;
use App\Http\Logics\Invest\InvestLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Models\User\UserModel;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Models\User\InviteModel;
use App\Lang\LangModel;
use App\Http\Logics\Activity\Common\ActivityLogic;
use App\Http\Logics\Activity\Common\AnalysisConfigLogic;
use App\Tools\ToolArray;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use Cache;

class AnniversarySecondLogic extends ActivityLogic
{
    protected  $objectExample;  //数据对象

    const
        INVITE_CACHE    =   'invite_ranking_',  //被邀请人投资排名缓存的cacheKey
        PARTNER_CACHE   =   'partner_ranking',  //邀请人投资排名缓存的cacheKey
        DEFAULT_GROUP   =   16;             //默认的奖品配置


    /**
     * @return array
     */
    public  function setTime()
    {
        $config     =   self::config();

        return $this->getTime($config['START_TIME'],$config['END_TIME']);
    }

    /**
     * @return mixed|int
     * @desc 排名次数
     */
    public  function getInvestTotalRanking()
    {
        $config     =   self::config();

        return isset($config['INVEST_TOTAL_RANKING']) && !empty($config['INVEST_TOTAL_RANKING']) ? (int)$config['INVEST_TOTAL_RANKING']:'5';
    }

    /**
     * @return array
     * @desc 奖品的配置
     */
    public  function getPrizeList()
    {
        $inviteCacheKey =   self::INVITE_CACHE.'LOTTERY';

        $lotteryList    =   Cache::get($inviteCacheKey);

        if( !empty($lotteryList)  ){

            //return json_decode($lotteryList,true);
        }

        $lotteryList     =   $this->setCouponLotteryList(self::getActivityLotteryGroup());

        Cache::put($inviteCacheKey,json_encode($lotteryList), 60);  //加入缓存

        return $lotteryList;
    }

    /**
     * @return array | used Cache | array
     * @desc 获取活动时间内被邀请人的投资数据
     */
    public  function getInviteInvestList()
    {
        $inviteCacheKey =   self::INVITE_CACHE.date('Ymd',time())."_list";

        $inviteList     =   Cache::get($inviteCacheKey);

        if( !empty($inviteList) ) {

            return json_decode($inviteList,true);
        }

        $inviteList =   InviteModel::getInviteInvestList( self::setSearchParam() , InviteDb::USER_TYPE_NORMAL, self::getInvestTotalRanking() );

        if( empty($inviteList) ) {

            return [];
        }

        $inviteList     =   self::formatSearchReturn( $inviteList , array_column($inviteList,'user_id') );

        Cache::put($inviteCacheKey,json_encode($inviteList), 60);  //加入缓存

        return $inviteList;
    }

    /**
     * @return array|mixed
     * @desc 获取被邀请人的投资排名的数据
     */
    public  function getPartnerInvestmentRanking($isCache = true)
    {
        $cacheKey       =   self::PARTNER_CACHE.date('Ymd',time());

        $rankingList    =   Cache::get($cacheKey);

        if( !empty($rankingList) && $isCache== true ) {

            return json_decode($rankingList,true);
        }

        $rankingList    =   InviteModel::getPartnerInviteInfo( self::setSearchParam() ,InviteDb::USER_TYPE_NORMAL );

        if( empty($rankingList) ) {

            return $rankingList;
        }

        $userIds        =   array_column($rankingList,'user_id');

        $investLogic    =   new  TermLogic();

        $investParam    =   [
            'user_id'   =>  $userIds,
            'size'      =>  self::getInvestTotalRanking(),
        ];

        $rankingList    =   $investLogic->getInvestStatisticsExist( array_merge($investParam,self::setSearchParam()));

        $rankingList    =   self::formatSearchReturn($rankingList,$userIds);

        Cache::put($cacheKey,json_encode($rankingList), 60);  //加入缓存

        return $rankingList;
    }

    /**
     * @return array
     * @desc 查询统计的条件
     */
    protected function setSearchParam()
    {
        $activityTime   =   self::setTime();

        $inviteParam    =   [
            'start_time'    =>  date('Y-m-d H:i:s',$activityTime['start']),
            'end_time'      =>  date('Y-m-d H:i:s',$activityTime['end']),
        ];

        return $inviteParam;
    }
    /**
     * @param $rankingList
     * @param $userIds
     * @desc 数据格式
     */
    protected function formatSearchReturn( $formatList,$userIds )
    {
        $userList       =   self::getInvestUserList($userIds);

        foreach ($formatList as $key => &$item ){

            $item['phone']  =   isset( $userList[$item['user_id']] ) ? ToolStr::hidePhone($userList[$item['user_id']]['phone']) : '****';

            $item['name']   =   isset( $userList[$item['user_id']] ) ? $userList[$item['user_id']]['real_name'] : '****';
        }

        return $formatList;
    }
    /**
     * @param  array|$userIds |defautl []
     * @return mixed|array $userList
     */
    private  function getInvestUserList($userIds = [])
    {
        if( empty($userIds) ) return [];

        $userModel      =   new UserModel();

        $userList       =    $userModel->getCoreUserListByIds($userIds);

        return ToolArray::arrayToKey($userList,'id');
    }

    /**
     * @return int
     * @desc   获取奖品分组
     */
    protected  function getLotteryGroup()
    {
        $groupId    =    $this->getActivityLotteryGroup(self::getEventId());

        return !empty($groupId) ? (int)$groupId: self::DEFAULT_GROUP;
    }

    /**
     * @return int EventId
     * @desc 周年庆第二趴的活动事件标示
     */
    public function getEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_ANNIVERSARY_SECOND;
    }
    /**
     * @return object | 周年庆活动第二期
     */
    protected static function config()
    {
        return AnalysisConfigLogic::make('ANNIVERSARY_CONFIG_SECOND');
    }
}
