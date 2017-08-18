<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/15
 * Time: 下午5:26
 * Desc: 时间工具类
 */

namespace App\Tools;

use Carbon\Carbon;


class ToolTime{


    /**
     * @return bool|string
     * @desc 获取时分秒时间
     */
    public static function dbNow()
    {

        return date('Y-m-d H:i:s',time());

    }

    /**
     * @return bool|string
     * @desc 获取日期时间
     */
    public static function dbDate()
    {

        return date('Y-m-d', time());

    }

    /**
     * @param int $day
     * @return bool|string
     * @desc 获取当前时间前几天，默认返回昨天
     */
    public static function getDateBeforeCurrent($day=1)
    {
        return date("Y-m-d",strtotime("-".$day." day"));
    }

    /**
     * @param int $day
     * @return bool|string
     * @desc 获取当前时间后几天,默认返回明天
     */
    public static function getDateAfterCurrent($day=1)
    {
        return date("Y-m-d",strtotime("+".$day." day"));
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return float
     * @desc 比较2个时间相差天数
     */
    public static function getDayDiff($startTime, $endTime)
    {

        $startTime = date('Y-m-d',strtotime($startTime));

        $endTime = date('Y-m-d',strtotime($endTime));

        $startTime = Carbon::parse($startTime);

        $endTime = Carbon::parse($endTime);

        $diffRes = $startTime->diffInDays($endTime);

        return $diffRes;

    }

    /**
     * @param $dateTime
     * @return bool|string
     * @desc 获取某个时间的日期,返回无时间日期
     */
    public static function getDate( $dateTime ){

        return date("Y-m-d",strtotime($dateTime));

    }

    /**
     * @param $dateTime
     * @return bool|string
     * @desc 获取某个完整时间的小时:分钟,返回无日期时间
     */
    public static function getHourMinute($dateTime){
        return date("H:i",strtotime($dateTime));
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return bool|string
     * @desc 下个月还款日跨月（如1.31 加上一个月会跨入到3月) 去掉跨出部分
     */
    public static function getNextMonthDate($startTime, $endTime){

        $startTime  = strtotime($startTime);
        $endTime    = strtotime($endTime);

        //月末可能导致跨月的处理
        if(date('d', $startTime) != date('d', $endTime)) {   //下个月还款日跨月（如1.31 加上一个月会跨入到3月）
            $endTime = strtoTime(sprintf('-%s day', date('d', $endTime)), $endTime);   //去掉跨出部分
        }

        return date('Y-m-d', $endTime);

    }

    /**
     * @param $date
     * @param $days
     * @return bool|string
     */
    public static function getAfterDayByDate($date, $days){

        $date = date('Y-m-d', $date);

        $times = strtotime($date) + 3600*24*$days;

        return date("Y-m-d", $times);

    }



}