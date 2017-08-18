<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/8
 * Time: 18:49
 * Desc: 零钱计划利率相关logic
 */

namespace App\Http\Logics\CurrentNew;

use App\Http\Dbs\CurrentNew\RateNewDb as RateDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\CurrentNew\RateModel;
use App\Tools\ToolTime;
use Log;

class RateLogic extends Logic{

    /**
     * @param $date
     * @param $rate
     * @param $profit
     * @return array
     * 添加零钱计划利率
     */
    public function create($date,$rate,$profit){

        try{
            
            ValidateModel::isCurrentRate($rate);
            //验证是否为合法的日期
            ValidateModel::isDate($date);
            //验证是否是有效的零钱计划利率
            ValidateModel::isCurrentProfit($profit);
            
            $model = new RateModel();
            //判断当前日期利率是否已存在
            $model->checkRateIsExist($date);
            //创建零钱计划利息
            $model->create($date,$rate,$profit);
            
        }catch(\Exception $e){

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }


    /**
     * @param $date
     * @param $rate
     * @param $profit
     * @return array
     * 添加零钱计划利率
     */
    public function edit($id,$date,$rate,$profit){

        try{
            //验证是否为合法的日期
            ValidateModel::isDate($date);
            //验证是否是有效的零钱计划利率
            ValidateModel::isCurrentRate($rate);

            $model = new RateModel();
            
            $model->edit($id,$date,$rate,$profit);

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }


    /**
     * 定时生成今日的零钱计划利率
     */
    public function autoCreateRate(){

        //获取今日零钱计划基准利率真
        $db     = new RateDb();
        $result = $db->getInfoByDate();
        //若不存在则创建
        if(!$result){
            //获取昨日的零钱计划利率,以此生成一条今日的记录
            $yesterdayRate = $db->getYesterdayRate();

            $date   = ToolTime::dbDate();
            $rate   = empty($yesterdayRate['rate'])?7:$yesterdayRate['rate'];
            $profit = empty($yesterdayRate['profit_percentage'])?7:$yesterdayRate['profit_percentage'];

            try{
                //创建零钱计划利率
                $db->add($date,$rate,$profit);

                Log::error(__METHOD__.'Success',['msg' => '创建零钱计划利率成功']);

            }catch (\Exception $e){

                Log::error(__METHOD__.'Error',['msg' => $e->getMessage()]);
            }

        }else{

            $log = [
                'msg'   => '今日零钱计划利率已存在,无需重复创建',
            ];
            Log::info(__METHOD__.'Info',$log);
        }

    }

    /**
     * 获取昨日零钱计划利率发起给核心进行计息
     */
    public function interestAccrual(){
        
        $db = new RateDb();
        $yesterdayRate = $db->getYesterdayRate();
        
        if(empty($yesterdayRate)){
            
            Log::error(__METHOD__.'Error',['msg' => '昨日利率不存在,无法计息']);
            
        }else{
            
            $rate = $yesterdayRate['rate'];
            
            $projectModel = new ProjectLogic();
            
            $result = $projectModel->doRefundJob($rate);

            if(!$result){

                Log::error(__METHOD__.'Error',['msg' => '接收零钱计划利率失败']);

            }
            
        }
    }

    /**
     * @desc 获取零钱计划的利率信息
     * @author lgh
     * @param $id
     * @return mixed
     */
    public function getRateById($id){
        $rateInfo =[];
        $rateDb = new RateDb();

        $result = $rateDb->getById($id);

        $rateInfo = $result['attributes'];

        $rateArr = explode('+',$rateInfo['profit_percentage']);
        $rateInfo['rate'] = $rateArr[0];
        $rateInfo['profit'] = empty($rateArr[1])?'':$rateArr[1];


        return $rateInfo;
    }
    /**
     * @desc 获取[管理后台]零钱计划利率列表
     * @author lgh
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getAdminCurrentRateList($page, $pageSize){
        $rateModel = new RateModel();

        $rateList = $rateModel->getAdminCurrentRateList($page, $pageSize);

        return $rateList;
    }
    
}