<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/11
 * Time: 19:13
 * Desc: 零钱计划利率相关model
 */

namespace App\Http\Models\Current;

use App\Http\Dbs\Current\RateDb;
use App\Http\Models\Model;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Tools\ToolTime;
use App\Http\Models\Common\HttpQuery;

class RateModel extends Model{

    public static $codeArr = [
        'checkRateIsExist'                 => 1,
        'editRateRateLessToday'            => 2,
        'editRateNotExist'                 => 3,
        'editRateFailed'                   => 4,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_CURRENT_RATE;


    /**
     * @param $date
     * 判断当前日期利率是否已存在,若存在抛出异常
     */
    public function checkRateIsExist($date){
        
        
        $db = new RateDb();
        $result = $db->getInfoByDate($date);
        
        if($result){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_RATE_IS_EXIST'), self::getFinalCode('checkRateIsExist'));

        }
    }

    /**
     * @param $date
     * @param $rate
     * @param $profit
     * 添加零钱计划利率
     */
    public function create($date,$rate,$profit){
        
        $db     = new RateDb();
        $data   = $this->formatProfit($rate,$profit);

        $profitPercentage = $data['profit'];
        $rate             = $data['rate'];

        $db->add($date,$rate,$profitPercentage);
    }

    /**
     * @param $id
     * @param $date
     * @param $rate
     * @param $profit
     * 编辑零钱计划利率
     */
    public function edit($id,$date,$rate,$profit){

        //编辑的日期不能小于当前日期
        if($date < ToolTime::dbDate()){
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_RATE_DATE_LESS_TODAY'), self::getFinalCode('editRateRateLessToday'));
        }

        //根据ID获取零钱计划利率
        $db = new RateDb();
        $result = $db->getById($id);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_RATE_NOT_EXIST'), self::getFinalCode('editRateNotExist'));
        }

        //数据格式化
        $data   = $this->formatProfit($rate,$profit);

        $profitPercentage = $data['profit'];
        $rate             = $data['rate'];
        
        $result = $db->edit($id,$date,$rate,$profitPercentage);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_RATE_EDIT_FAILED'), self::getFinalCode('editRateFailed'));

        }


    }

    /**
     * @param $rate
     * @param $profit
     * @return array
     * 零钱计划利率数据格式化
     */
    private function formatProfit($rate,$profit){

        if(!$profit){
            $profitPercentage = $rate;
        }else{
            $profitPercentage = $rate.'+'.$profit;
            $rate += $profit;
        }

        return [
            'profit' => $profitPercentage,
            'rate'    => $rate
        ];
    }

    /**
     * 获取零钱计划利率
     */
    public function getRate(){

        $rateDb = new RateDb();
        $rateInfo       = $rateDb->getShowRate();
        $rateArr = explode('+',$rateInfo['profit_percentage']);

        $rateData = [
            'rate'          => $rateInfo['rate'],   //总利率
            'base_rate'     => $rateArr[0],         //基准利率
            'profit'        => 0,                   //加息利率
        ];

        if(count($rateArr) == 2){
            $rateData['profit'] = $rateArr[1];
            $rateData['profit_percentage'] = $rateArr[1];
        }

        return $rateData;
    }

    /**
     * @param $rate
     * @return array
     * 向核心发起零钱计划计息请求
     */
    public function interestAccrual($rate){


        $result = HttpQuery::corePost('/current/refund/doRefundJob',['rate' => $rate]);

        return $result['status'];
    }

    /**
     * @desc 获取零钱计划利率列表
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getAdminCurrentRateList($page, $pageSize){
        $rateModel = new RateDb();

        $rateList = $rateModel->getRateList($page, $pageSize);

        return $rateList;
    }

}