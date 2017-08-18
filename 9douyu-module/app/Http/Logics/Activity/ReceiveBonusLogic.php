<?php
/** ================领取红包的活动================
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/12/26
 * Time: 下午4:39
 */

namespace App\Http\Logics\Activity;



use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\Bonus\UserBonusDb;
use App\Http\Logics\Bonus\BonusLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Cache;
class ReceiveBonusLogic extends Logic
{
    const
        RECEIVE_BONUS_CACHE     =  'RECEIVE_BONUS_KEY_CACHE';   //领取红包的红包缓存配置

    /**
     * @return array
     * @desc 活动时间
     */
    public static function getActivityTime()
    {
        return [
            'start' =>  self::setStartTime(),
            'end'   =>  self::setEndTime(),
        ];
    }
    /**
     * @param int $userId
     * @return array
     * @desc 红包的领取数据
     */
    public static function getUserReceiveBonusTotal( $userId = 0)
    {
        $bonusParam =   self::setBonus();
        
        $userBonusDb=   new UserBonusDb();

        $betweenTime=   self::setReceiveBetweenTime();

        return self::setFormatUserBonusTotal($userBonusDb->getUserBonusUsedTotal($betweenTime['start'],$betweenTime['end'],$bonusParam,$userId));
    }

    /**
     * @return array|mixed
     * @desc 获取红包的数据
     */
    public static function getBonusList()
    {
        $cacheKey   =   md5(json_encode(self::setBonus()));

        $bonusCacheList= Cache::get($cacheKey);

        if( !empty($bonusCacheList) ){

            return json_decode($bonusCacheList,true);
        }

        $bonusParam =   self::setBonus();

        $bonusDb    =   new BonusDb();

        $bonusList  =   $bonusDb->getByIds($bonusParam);

        $logic      =   new BonusLogic();

        $bonusFormat=   $logic->doFormatBonusList($bonusList);

        Cache::put($cacheKey,json_encode($bonusFormat), 10);

        return $bonusFormat;
    }

    /**
     * @param $userId
     * @param $bonusId
     * @return array
     * @desc 执行红包的领取
     */
    public static function doReceiveBonus($userId,$bonusId)
    {
        $cacheKey = 'receive_bonus_lock_'.$userId;

        if(Cache::has($cacheKey)){

            return self::callError('验证失败，请重新抽奖');
        }

        Cache::put($cacheKey,1,0.2);   //0.2为过期时间,单位为分钟

        $userBonusLogic =   new UserBonusLogic();

        $return =   $userBonusLogic->doSendBonusByUserIdWithBonusId($userId,[$bonusId]);

        return $return;
    }
    /**
     * @param int $userId
     * @param int $bonusId
     * @return array
     * @desc 领取红包的条件判断
     */
    public static function isCanReceiveBonus($userId = 0,$bonusId = 0)
    {
        if( empty($userId) || $userId ==0){

            return self::callError('您还没有登录,请登录后后领取');
        }

        $startTime    =   self::setStartTime();

        $nowTime      =   time();

        if( $nowTime < $startTime ){

            return self::callError("领取红包在".date('m.d',$startTime)."号准时开启!<br/>敬请期待!");
        }

        $endTime        =   self::setEndTime();

        if( $nowTime > $endTime ){

            return self::callError("领取红包活动已经结束!<br/>谢谢参与!");
        }

        return self::isCanReceiveBonusTimes($userId,$bonusId);
    }

    /**
     * @param $userId
     * @param $bonusId
     * @return array
     * @desc 判断是否可以领取红包
     */
    protected static function isCanReceiveBonusTimes($userId ,$bonusId)
    {
        if( !in_array($bonusId,self::setBonus()) ){

            return self::callError("红包信息错误,请确认后领取!");
        }

        $userBonusTotalArr  =   self::getUserReceiveBonusTotal($userId);

        $maxCanBonusNumber  =   self::getMaxBonusNumber();

        if( $userBonusTotalArr[$bonusId] >= $maxCanBonusNumber){

            return self::callError("您已经领取过该红包,谢谢参与");
        }

        return self::callSuccess();
    }

    /**
     * @param array $totalList
     * @return array
     * @desc 格式化红包的数量
     */
    protected static function setFormatUserBonusTotal($totalList = array() )
    {
        $bonusParam =   self::setBonus();

        $formatBonusTotal   =   [];

        $totalList  =   ToolArray::arrayToKey($totalList,'bonus_id');

        foreach ($bonusParam as $key => $bonusId ){

            $formatBonusTotal[$bonusId] =   isset($totalList[$bonusId]) ? $totalList[$bonusId]['total'] : 0;
        }

        return $formatBonusTotal;
    }
    /**
     * @return array
     * @desc  根据配置设置统计的时间段
     */
    protected static function setReceiveBetweenTime()
    {
        $isEveryDay =   self::setEveryDayReceiveStatus();

        if( $isEveryDay == false ){

            return [
                'start' =>  date("Y-m-d H:i:s" , self::setStartTime() ),
                'end'   =>  date("Y-m-d H:i:s" , self::setEndTime() ),
            ];
        }

        return [
            'start' =>  date("Y-m-d 00:00:00",time()),
            'end'   =>  date("Y-m-d 23:59:59",time()),
        ];
    }
    /**
     * @return array
     * @desc  活动红包的数据
     */
    protected static function setBonus()
    {
        $config     =   self::config();

        $bonusArr   =   explode(',',$config['BONUS_CONFIG']);

        return array_filter($bonusArr);
    }

    /**
     * @return bool
     * @desc 是否每天都可以领取
     */
    protected static function setEveryDayReceiveStatus()
    {
        $config     =   self::config();
        
        if( $config['IS_EVERY_DAY_RECEIVE'] ==1 ){

            return true;
        }

        return false;
    }
    /**
     * @return int
     * @desc 获取红包的领取的最大次数
     */
    protected static function getMaxBonusNumber()
    {
        $config     =   self::setBonus();

        return isset($config['MAX_RECEIVE_TIMES']) ? (int)$config['MAX_RECEIVE_TIMES'] : 1;
    }
    /**
     * @return int
     * @desc 活动结束时间
     */
    protected static function setEndTime()
    {
        $config     =   self::config();

        return ToolTime::getUnixTime($config['END_TIME'],'end');
    }
    /**
     * @return int
     * @DESC 活动开始时间
     */
    protected static function setStartTime()
    {
        $config     =   self::config();

        return ToolTime::getUnixTime($config['START_TIME']);
    }

    /**
     * @return array|mixed
     * @DESC 活动配置
     */
    private static function config()
    {
        $config =   ActivityConfigModel::getConfig('RECEIVE_BONUS_CONFIG');

        if( empty($config) ){

            return SystemConfigModel::getConfig('RECEIVE_BONUS_CONFIG');
        }

        return $config;
    }


    /**
     * @param $userId
     * @param $bonusId
     * @return array
     * @desc 执行红包的领取
     */
    public static function doReceiveBonusWithGeeks($userId,$bonusId)
    {
        $userBonusLogic =   new UserBonusLogic();
        $userBonus  = $userBonusLogic->getReceivedBonusWithUser($userId , $bonusId);
        if($userBonus){

            return self::callError( '对不起，您已经参与过了' );

        }
        $cacheKey   = "RECEIVED_GEEKS_".$userId;
        if(\Cache::has($cacheKey)){
            return self::callError('请勿大量重复领取~');
        }
        \Cache::put($cacheKey,1,0.2);       //0.2为过期时间,单位为分钟

        $return =   $userBonusLogic->doSendBonusByUserIdWithBonusId($userId,[$bonusId]);

        return $return;
    }

}