<?php
/**
 * User: changming
 * Date: 16/4/19
 * Time: 10:39
 * Desc: 数据统一验证Model
 */
namespace App\Http\Models\Common;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Cache;
class CheckLimitModel extends Model{


    public static $codeArr = [
        'isMinute'              => 1,   //分钟内的限制
        'isTenMinute'           => 2,
        'isHour'                => 3,
        'isDay'                 => 4,
    ];

    const
            IN_ONE_MINTUE_MAX_LIMIT =   1,  //一分钟内默认最大的次数
            IN_TEN_MINUTE_MAX_LIMIT =   3,
            IN_ONE_HOUR_MAX_LIMIT   =   5,  //一个小时内最大的次数
            IN_ONE_DAY_MAX_LIMIT    =   10; //24小时内最大的次数

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_CHECK_LIMIT;

    /**
     * @param $key  当前操作的唯一的键
     * @return throw || true
     * @desc 逐级验证limit 次数
     */
    public static function checkLimit($key)
    {
        $countArr   =   self::getCount(self::getDailyKey($key));

        if( empty($countArr) || is_null($countArr)) { return true; }

        $limit      =   $countArr['limit'];

        $time       =   $countArr['time'];

        self::validateDayCount($limit);

        self::validateHourCount($limit,$time);

        self::validateTenMinCount($limit,$time);

        self::validateMintueCount($limit,$time);

        return true;
    }

    /**
     * @param $limit int  | 错误的次数
     * @param $time  生效的时间点
     * @return throw Exception error
     * @desc 一分钟内的次数验证
     */
    private static function validateMintueCount($limit,$time)
    {
        if( $limit >= self::getMintueLimit() && time()-$time <=60){

            throw new \Exception(LangModel::getLang('ERROR_SEND_CODE_MINUTE_LIMIT'), self::getFinalCode('isMinute'));
        }
    }

    /**
     * @param $limit int  | 错误的次数
     * @param $time  生效的时间点
     * @return throw Exception error
     * @desc 十分钟内的次数验证
     */
    private static function validateTenMinCount($limit,$time)
    {
        if($limit >= self::getHourLimit() && time()-$time <=60*10 ){

            throw new \Exception(LangModel::getLang('ERROR_SEND_CODE_TEN_MINUTE_LIMIT'), self::getFinalCode('isTenMinute'));
        }
    }
    /**
     * @param $limit int  | 错误的次数
     * @param $time  生效的时间点
     * @return throw Exception error
     * @desc 十分钟内的次数验证
     */
    private static function validateHourCount($limit,$time)
    {
        if($limit >= self::getHourLimit() && time()-$time <=60*60 ){

            throw new \Exception(LangModel::getLang('ERROR_SEND_CODE_HOUR_LIMIT'), self::getFinalCode('isHour'));
        }
    }
    /**
     * @param $limit int  | 错误的次数
     * @param $time  生效的时间点
     * @return throw Exception error
     * @desc 24小时内最大次数验证
     */
    private static function validateDayCount($limit)
    {
        if( $limit >= self::getDayLimit()){

            throw new \Exception(LangModel::getLang('ERROR_SEND_CODE_DAY_LIMIT'), self::getFinalCode('isDay'));
        }
    }

    /**
     * @param $key cache_key
     * @param $time cache_expiry
     * @param $add 记录值
     */
    public static function setCount($key,$add = 1)
    {
        $key    =   self::getDailyKey($key);

        $count  =   self::getCount($key);

        $limit  =   isset($count['limit']) ? $count['limit'] : 0;

        $addArr =   ['time'=>time(),'limit'=>(int)$limit+$add];

        return Cache::put($key,json_encode($addArr),60*24);
    }
    /**
     * @return limit count
     * @desc 获取limit count
     */
    private static function getCount($key)
    {
        return json_decode(Cache::get($key),true);
    }

    /**
     * @return int | get one mintue max limit times
     * @desc一分钟内的最大次数
     */
    private static function getMintueLimit()
    {
        $config =   self::getLimitConfig();

        return isset($config['IN_MINTUE_MAX_LIMIT']) ? (int)$config['IN_MINTUE_MAX_LIMIT']: self::IN_ONE_MINTUE_MAX_LIMIT;
    }

    /**
     * @return int | get one mintue max limit times
     * @desc一分钟内的最大次数
     */
    private static function getTenMinLimit()
    {
        $config =   self::getLimitConfig();

        return isset($config['IN_TEN_MINTUE_MAX_LIMIT']) ? (int)$config['IN_TEN_MINTUE_MAX_LIMIT']: self::IN_TEN_MINUTE_MAX_LIMIT;
    }
    /**
     * @return int | get one hour max limit times
     * @desc 一个小时内的最大次数
     */
    private static function getHourLimit()
    {
        $config =   self::getLimitConfig();

        return isset($config['IN_HOUR_MAX_LIMIT']) ? (int)$config['IN_HOUR_MAX_LIMIT']: self::IN_ONE_HOUR_MAX_LIMIT;
    }

    /**
     * @return int | get 24 hours max limit times
     * @desc 24小时内的次数
     */
    private static function getDayLimit()
    {
        $config =   self::getLimitConfig();

        return isset($config['IN_DAY_MAX_LIMIT']) ? (int)$config['IN_DAY_MAX_LIMIT']: self::IN_ONE_DAY_MAX_LIMIT;
    }
    /**
     * @desc 格式化天key
     * @exed 按照24小时计算
     */
    private static function getDailyKey($key)
    {
        return strtoupper($key);
    }
    /**
     * @param check limit max times
     * @return 系统设置的验证码最大次数配置
     */
    private static function getLimitConfig()
    {
        return SystemConfigModel::getConfig('CHECK_PHONE_CODE_LIMIT');
    }
}

