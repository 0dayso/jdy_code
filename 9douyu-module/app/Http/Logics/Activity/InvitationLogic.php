<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/3/13
 * Time: 下午2:14
 */

namespace App\Http\Logics\Activity;

use App\Http\Models\User\UserModel;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Models\User\InviteModel;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Cache;
use Session;

class InvitationLogic extends Logic
{
    protected static $objectExample;  //数据对象

    const
        INVITE_CACHE    =   'invite_ranking_',  //合伙人投资排名缓存的cacheKey
        DEFAULT_GROUP   =   8;              //默认的奖品配置


    /**
     * @return array
     */
    public static function setTime()
    {
        $config     =   self::config();

        return['start'=> $config['START_TIME'],'end'=>$config['END_TIME']];
    }

    /**
     * @return mixed|int
     * @desc 排名次数
     */
    public static function getInvestTotalRanking()
    {
        $config     =   self::config();

        return isset($config['INVEST_TOTAL_RANKING']) && !empty($config['INVEST_TOTAL_RANKING']) ? (int)$config['INVEST_TOTAL_RANKING']:'5';
    }

    /**
     * @return array
     * @desc 格式化数字对文字
     */
    public static function getFormatNumberToWord()
    {
        return [0=>'零',1=>'一',2=>'二',3=>'三',4=>'四',5=>'五',6=>'六',7=>'七',8=>'八',9=>'九',10=>'十'];
    }
    /**
     * @return array
     * @desc 奖品的配置
     */
    public static function getPrizeList()
    {
        $inviteCacheKey =   self::INVITE_CACHE.'LOTTERY';

        $lotteryList    =   Cache::get($inviteCacheKey);

        if( !empty($lotteryList)  ){

            return json_decode($lotteryList,true);
        }

        $prizeGroup      =   self::getActivityLotteryGroup();

        $lotteryList     =   LotteryConfigLogic::getLotteryByGroup($prizeGroup);

        if( !empty($lotteryList) ){

            $lotteryList= ToolArray::arrayToKey($lotteryList,'order_num');
        }

        Cache::put($inviteCacheKey,json_encode($lotteryList), 60);  //加入缓存

        return $lotteryList;
    }

    /**
     * @return array|mixed
     * @desc 获取合伙人投资排名的数据
     */
    public static function getPartnerInvestmentRanking($isCache = true)
    {
        $inviteCacheKey =   self::INVITE_CACHE.date('Ymd',time());

        $rankingList    =   Cache::get($inviteCacheKey);

        if( !empty($rankingList) && $isCache== true ){

            return json_decode($rankingList,true);
        }

        $activityTime   =   self::setTime();

        $inviteParam    =   [
            'start_time'    =>  date('Y-m-d H:i:s',$activityTime['start']),
            'end_time'      =>  date('Y-m-d H:i:s',$activityTime['end']),
            'invest_start_time'    =>  date('Y-m-d H:i:s',$activityTime['start']),
            'invest_end_time'      =>  date('Y-m-d H:i:s',$activityTime['end'])
        ];

        $rankingList    =   InviteModel::getPartnerInvestmentRanking($inviteParam);

        if( empty($rankingList) ){

            return $rankingList;
        }
        $userIds        =   ToolArray::arrayToIds($rankingList,'user_id');

        $inviteModel    =   new InviteModel();

        $inviteCount    =   ToolArray::arrayToKey(($inviteModel->getCountInviteSortByUidsTime($userIds,$inviteParam['start_time'],$inviteParam['end_time'])),'user_id');

        $userList       =   self::getInvestUserList($userIds);

        foreach ($rankingList as $key => &$item ){

            $item['total']  =   isset($inviteCount[$item['user_id']]) ?$inviteCount[$item['user_id']]['total']:'0';

            $item['phone']  =   $userList[$item['user_id']]['phone'];

            $item['name']   =   $userList[$item['user_id']]['real_name'];
        }

        Cache::put($inviteCacheKey,json_encode($rankingList), 60);  //加入缓存

        return $rankingList;
    }

    /**
     * @param  array|$userIds |defautl []
     * @return mixed|array $userList
     */
    private static function getInvestUserList($userIds = [])
    {
        if( empty($userIds)) return [];

        $userModel      =   new UserModel();

        $userList       =    $userModel->getCoreUserListByIds($userIds);

        return ToolArray::arrayToKey($userList,'id');
    }

    /**
     * @return int
     * @desc   获取奖品分组
     */
    protected static function getActivityLotteryGroup()
    {
        $eventId    =   self::setActivityEventId();

        $eventNote  =   self::setActivityEventIdNote();

        return isset($eventNote[$eventId]) ? $eventNote[$eventId]['group'] : self::DEFAULT_GROUP;
    }

    /**
     * @return array
     * @desc 所有的包含抽奖活动的标示
     */
    protected static function setActivityEventIdNote()
    {
        return LotteryRecordLogic::getLotteryActivityEventNote();
    }
    /**
     * @return int
     * @DESC 活动的唯一性标示
     */
    protected static function setActivityEventId()
    {
        return ActivityFundHistoryDb::ACTIVITY_SPRING_TOUR_CONFIG;
    }
    /**
     * @return array|mixed
     * @desc  春风十里活动的配置文件
     */
    private static function config()
    {
        $object =   self::getObject();

        return $object['config'];
    }
    /**
     * @return mixed
     * @desc 获取解析的数据
     */
    private static function getObject()
    {
        return self::getInstance()->getObject();
    }
    /**
     * @return $object
     * @desc 单列模式
     */
    private static function getInstance(){

        if(!(self::$objectExample instanceof self)){

            self::$objectExample = new AnalysisConfigLogic('ACTIVITY_SPRING_TOUR_CONFIG');
        }

        return self::$objectExample;
    }

}
