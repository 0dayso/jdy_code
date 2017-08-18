<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/3
 * Time: 下午5:38
 * Desc: 活动资金明细
 */

namespace App\Http\Models\Activity;

use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class ActivityFundHistoryModel extends Model
{


    public static $codeArr = [
        'doIncrease'             => 1,
        'doDecrease'             => 1,
    ];


    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_ACTIVITY_FUND_HISTORY;

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 转入方法
     */
    public function doIncrease($data)
    {

        $db = new ActivityFundHistoryDb();

        $data['type'] = $db::TYPE_IN;

        $res = $db->inRecord($data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_FUND_HISTORY'), self::getFinalCode('doIncrease'));

        }

        return $res;

    }

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 转出
     */
    public function doDecrease($data)
    {

        $db = new ActivityFundHistoryDb();

        $data['type'] = $db::TYPE_OUT;

        $res = $db->inRecord($data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_FUND_HISTORY'), self::getFinalCode('doDecrease'));

        }

        return $res;

    }

    /**
     * 获取指定日期的转入的数据汇总
     *
     * @param string $date
     */
    public static function getListsByDate($date = ''){
        $afterDate = date('Y-m-d', (strtotime($date) + 3600*24));
        $condition= [
            'source'=> ActivityFundHistoryDb::SOURCE_YIMAFU
        ];
        $condition[] = ['created_at', '>=', $date];
        $condition[] = ['created_at', '<', $afterDate];

        $list = ActivityFundHistoryDb::select('user_id', \DB::raw('SUM(balance_change) as balance_change'))->where(
            $condition
        )->groupBy('user_id')->get()->toArray();

        return $list;
    }

    #

    /**
     * @desc    获取时间段内，活动资金明细
     * @param   $startTime  开始时间
     * @param   $endTime    结束时间
     * @param   $type       数据类型  1、转入， 2、转出
     * @param   $source     数据来源
     *
     * @param string $date
     */
    public static function activityFundHistoryStat($startTime,$endTime,$type=1,$source=1){

        $condition= [
            'source'=> $source,
            'type'  => $type,
        ];
        if($startTime){
            $condition[] = ['created_at', '>=', $startTime];
        }
        if($endTime){
            $condition[] = ['created_at', '<=',  $endTime];
        }
        $condition[] = ['balance_change', '>',  0];

        $list = ActivityFundHistoryDb::select('user_id', \DB::raw('SUM(balance_change) as balance_change'))->where(
            $condition
        )->groupBy('user_id')->get()->toArray();

        return $list;
    }

    /**
     * @param $startTime
     * @param $endTime
     * @param int $type
     * @param int $source
     * @return mixed
     * @desc 求和
     */
    public static function getActivityFundHistorySumCashByStat($startTime,$endTime,$type=1,$source=1)
    {
        $condition= [
            'source'=> $source,
            'type'  => $type,
        ];
        if($startTime){

            $condition[] = ['created_at', '>=', $startTime];
        }
        if($endTime){

            $condition[] = ['created_at', '<=',  $endTime];
        }

        $condition[] = ['balance_change', '>',  0];

        $data['cash'] = ActivityFundHistoryDb::where(
            $condition
        )->sum("balance_change");

        return $data;
    }

    /**
     * @return array
     * @desc 统一module处的活动标示
     */
    public static function getActivityEventNote()
    {
        return [
        ActivityFundHistoryDb::SOURCE_PARTNER                   =>  "合伙人",    //合伙人
        ActivityFundHistoryDb::SOURCE_YIMAFU                    =>  "一码付",    //一码付
        ActivityFundHistoryDb::SOURCE_ADMIN_ADD_BALANCE         =>  "后台给用户加扣款",    //后台给用户加扣款
        ActivityFundHistoryDb::SOURCE_ACTIVITY                  =>  "加息奖励",    //加息奖励  (加息奖励的默认标示)
        ActivityFundHistoryDb::SOURCE_ACTIVITY_NATIONAL         =>  "国庆活动",     //国庆活动标示
        ActivityFundHistoryDb::SOURCE_ACTIVITY_HALLOWEEN        =>  "万圣节活动",    //万圣节活动
        ActivityFundHistoryDb::SOURCE_ACTIVITY_VOTE             =>  "蓝筹投票",    //蓝筹投票
        ActivityFundHistoryDb::SOURCE_SEVEN_DAT                 =>  "七夕",     //七夕   (旧的event_id标示)
        ActivityFundHistoryDb::SOURCE_FATHER_DAY                =>  "父亲节" ,    //父亲节 (旧的event_id标示)
        ActivityFundHistoryDb::SOURCE_ACTIVITY_DOUBLE_FESTIVAL  =>  '双诞活动',    //双诞活动
        ActivityFundHistoryDb::SOURCE_ACTIVITY_SPRING_FESTIVAL  =>  '春节活动',    //春节活动
        ActivityFundHistoryDb::SOURCE_ACTIVITY_SPRING_COUPON    =>  '春风十里',    //春风十里
        ActivityFundHistoryDb::SOURCE_ACTIVITY_INVEST_GAME      =>  '全民争霸赛',        //全面争霸赛
        ActivityFundHistoryDb::SOURCE_ACTIVITY_INVEST_MATCH     =>  '投资PK',        //投资PK活动
        ActivityFundHistoryDb::SOURCE_ACTIVITY_LABOR_DAY        =>  '五一劳动',         //五一活动
        ActivityFundHistoryDb::SOURCE_ACTIVITY_MOTHER_DAY       =>  '母亲节活动',       //母亲节活动
        ActivityFundHistoryDb::SOURCE_ACTIVITY_GRADE_LOTTERY    => '奖池抽奖',    //第一趴的活动
        ActivityFundHistoryDb::SOURCE_ACTIVITY_THIRD_ANNIVERSARY    => '三周年伴手礼',    //伴手礼
        ActivityFundHistoryDb::SOURCE_ACTIVITY_ANNIVERSARY_SECOND   => '三周年投资赢豪礼',    //第二趴
        ActivityFundHistoryDb::SOURCE_ACTIVITY_ANNIVERSARY_THIRD_JNH=> '三周年嘉年华',    //第三趴
        ActivityFundHistoryDb::SOURCE_ACTIVITY_ANNIVERSARY_THIRD    => '三周年红包雨',    //第三趴
        ActivityFundHistoryDb::SOURCE_ACTIVITY_HONGKONG_DAY     =>  '香港回归20周年',
        ActivityFundHistoryDb::SOURCE_ACTIVITY_GOLD_CHEST       =>  '小金库活动',
        ActivityFundHistoryDb::SOURCE_ACTIVITY_JULY             =>  '夏利活动',
        ActivityFundHistoryDb::SOURCE_ACTIVITY_AUTUMN_LOTTERY   =>  '立秋活动',
        ];
    }

    /**
     * @return array
     * @desc 带有抽奖活动EventId
     */
    public static function getLotteryActivityEventNote()
    {
        return [
            ActivityFundHistoryDb::SOURCE_ACTIVITY_NATIONAL     =>  ['name' => '国庆活动',   'group' =>1],   //国庆活动标示
            ActivityFundHistoryDb::SOURCE_ACTIVITY_HALLOWEEN    =>  ['name' => '万圣节活动', 'group' =>2],    //万圣节活动
            ActivityFundHistoryDb::SOURCE_ACTIVITY_DOUBLE_FESTIVAL  =>  ['name'=> '双诞活动','group' =>3],    //双诞活动
            ActivityFundHistoryDb::SOURCE_ACTIVITY_SPRING_FESTIVAL  =>  ['name'=> '春节活动','group' =>4],     //春节活动
            ActivityFundHistoryDb::SOURCE_ACTIVITY_SPRING_COUPON    =>  ['name'=> '春风十里','group' =>5]     //春风十里
        ];
    }

}
