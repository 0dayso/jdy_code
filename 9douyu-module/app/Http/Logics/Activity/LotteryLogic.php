<?php
/****************** 执行抽奖的逻辑程序****************************/
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/25
 * Time: 下午6:15
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\LotteryConfigDb;
use App\Http\Logics\Logic;
use Cache;

class LotteryLogic extends Logic
{

    /**
     * @param $data
     * @return array
     * @desc 带有概率的抽奖
     */
    public function doLuckDrawWithRate($data)
    {
        $cacheKey = 'lottery_lock_'.$data['user_id'];

        if(Cache::has($cacheKey)){

            return self::callError('验证失败,请不要重复提交!');
        }

        Cache::put($cacheKey,1,0.2);   //0.2为过期时间,单位为分钟
        
        $lotteryList    =   LotteryConfigLogic::getLotteryByGroup($data['group_id']);

        $lotteryList    =   LotteryConfigLogic::doGetFormatLottery($lotteryList);

        $formatLottery  =   self::doFormatLotteryParams($lotteryList);

        $lotteryId      =   self::doLotteryRandomWithRate($formatLottery);

        $return         =   self::doNoteDownLottery($lotteryId,$data);

        if( $return['status']==false ){

            return self::callError($return['msg']);
        }

        return self::callSuccess($return);
    }
    /**
     * @param $data
     * @return array
     * @desc 带有概率的抽奖
     */
    public function doLuckDrawWithRateUseActSta($data)
    {
        $cacheKey = 'lottery_lock_'.$data['user_id'];

        if(Cache::has($cacheKey)){

            return self::callError('验证失败,请不要重复提交!');
        }

        Cache::put($cacheKey,1,0.2);   //0.2为过期时间,单位为分钟

        $lotteryList    =   LotteryConfigLogic::getLotteryByGroup($data['group_id']);

        $lotteryList    =   LotteryConfigLogic::doGetFormatLottery($lotteryList);

        $formatLottery  =   self::doFormatLotteryParams($lotteryList);

        $lotteryId      =   self::doLotteryRandomWithRate($formatLottery);

        $return         =   self::doNoteDownLotteryUseActStat($lotteryId,$data);

        if( $return['status']==false ){

            return self::callError($return['msg']);
        }

        return self::callSuccess($return);
    }

    /**
     * @param $data
     * @return array
     * @desc 没有概率的中奖
     */
    public function doLuckDrawWithoutRate($data)
    {
        $cacheKey = 'lottery_lock_'.$data['user_id'];

        if(Cache::has($cacheKey)){

            return self::callError('验证失败,请不要重复提交!');
        }

        Cache::put($cacheKey,1,0.2);   //0.2为过期时间,单位为分钟

        $lotteryList    =   LotteryConfigLogic::getLotteryByGroup($data['group_id']);

        $lotteryList    =   LotteryConfigLogic::doGetFormatLottery($lotteryList);

        $formatLottery  =   self::doFormatLotteryParams($lotteryList);

        $lotteryId      =   self::doLotteryRandomWithoutRate($formatLottery);

        $return         =   self::doNoteDownLottery($lotteryId,$data);

        if( $return['status']==false ){

            return self::callError($return['msg']);
        }
        unset($return['status']);
        
        return self::callSuccess($return);
    }

    /**
     * @param $randArr
     * @return int|string
     * @desc 通过配置概率返回结果
     * @data   array("id"=>"rate","id"=>"rate");,id为奖品的Id,rate为奖品的概率
     */
    protected static function doLotteryRandomWithRate( $randLottery )
    {
        $returnRand   =   '';     //用来存储结果

        $randSum      =   array_sum($randLottery);

        foreach ( $randLottery as $key  =>  $randMon ){

            $randNum  = mt_rand(1, $randSum);

            if( $randNum <= $randMon){

                $returnRand = $key;

                break;

            }else{

                $randSum    -= $randMon;
            }
        }

        unset($randLottery);

        return $returnRand;
    }


    /**
     * @param $randLottery
     * @return int
     * @desc 百分百中奖,平均值
     */
    public static function doLotteryRandomWithoutRate( $randLottery )
    {

        $returnRand     =   array_keys($randLottery);

        $minRand        =   min($returnRand);

        $maxRand        =   max($returnRand);

        $randNumber     =   mt_rand($minRand,$maxRand);

        unset($returnRand);

        return $randNumber;
    }

    /**
     * @param $lotteryList
     * @return array
     * @desc 格式化抽奖的随机数据结构
     */
    protected static function doFormatLotteryParams( $lotteryList )
    {
        if( empty($lotteryList) ) return [];

        $returnList     =   [];

        foreach ( $lotteryList  as $key  =>  $lottery ){

            $returnList[$key]  =   $lottery['rate'];

        }

        return $returnList;
    }

    /**
     * @param $lotteryId
     * @param $userId
     * @return array|mixed
     * @desc  记录抽奖的记录
     */
    protected static function doNoteDownLottery( $lotteryId,$data)
    {
        $lotteryLogic   =   new LotteryConfigLogic();

        $lottery        =   $lotteryLogic->getById($lotteryId);

        $record         =   [
            'prizes_id' =>  $lotteryId,
            'user_id'   =>  $data['user_id'],
            'bonus_id'  =>  $lottery['foreign_id'],
            'activity_id'=> isset($data['activity_id']) ? $data['activity_id'] : 0,
        ];

        $logic  =   new LotteryRecordLogic();

        switch ($lottery['type']){

//            case LotteryConfigDb::LOTTERY_TYPE_ENVELOPE://红包
//
//                $return =   $logic->doAddVirtual($record);
//
//                break;
//            case LotteryConfigDb::LOTTERY_TYPE_TICKET: //加息券
//
//                $return =   $logic->doAddVirtual($record);
//
//                break;
            case LotteryConfigDb::LOTTERY_TYPE_ENVELOPE://红包
            case LotteryConfigDb::LOTTERY_TYPE_TICKET: //加息券
            case LotteryConfigDb::LOTTERY_TYPE_CURRENT: //零钱计划加息券

                $return =   $logic->doAddVirtual($record);

                break;
            case LotteryConfigDb::LOTTERY_TYPE_EMPTY: //谢谢参与
            case LotteryConfigDb::LOTTERY_TYPE_ENTITY: //实物奖励

                $return =   $logic->doAdd($record);

                break;
            default:    //默认为异常状态

                $return =   ['status'=>false,"msg" =>'网络异常,请稍后重试!'];

                break;
        }

        if( $return['status'] ==false ){

            return $return;
        }

        return [
            "status"    =>  true,
            'name'      =>  $lottery['name'] ,
            "type"      =>  $lottery['type'] ,
            "foreign_id"=>  $lottery['foreign_id'],
            'order_num' =>  $lottery['order_num']
        ];

    }


    /**
     * @param $lotteryId
     * @param $userId
     * @return array|mixed
     * @desc  记录抽奖的记录
     */
    protected static function doNoteDownLotteryUseActStat( $lotteryId, $data )
    {
        $lotteryLogic   =   new LotteryConfigLogic();

        $lottery        =   $lotteryLogic->getById($lotteryId);

        $record         =   [
            'prizes_id' =>  $lotteryId,
            'user_id'   =>  $data['user_id'],
            'bonus_id'  =>  $lottery['foreign_id'],
            'activity_id'=> isset($data['activity_id']) ? $data['activity_id'] : 0,
            'statics_id'=>  $data['statics_id']
        ];

        $logic  =   new LotteryRecordLogic();

        switch ($lottery['type']){
            case LotteryConfigDb::LOTTERY_TYPE_ENVELOPE://红包
            case LotteryConfigDb::LOTTERY_TYPE_TICKET: //加息券
            case LotteryConfigDb::LOTTERY_TYPE_CURRENT: //零钱计划加息券
                $return =   $logic->doAddVirtualUseStatistics($record);
                break;
            case LotteryConfigDb::LOTTERY_TYPE_EMPTY: //谢谢参与
            case LotteryConfigDb::LOTTERY_TYPE_ENTITY: //实物奖励
                $return =   $logic->doAddUseStatistics($record);
                break;
            default:    //默认为异常状态
                $return =   ['status'=>false,"msg" =>'网络异常,请稍后重试!'];
                break;
        }

        if( $return['status'] ==false ){
            return $return;
        }
        return [
            "status"    =>  true,
            'name'      =>  $lottery['name'] ,
            "type"      =>  $lottery['type'] ,
            "foreign_id"=>  $lottery['foreign_id'],
            'order_num' =>  $lottery['order_num']
        ];

    }
}