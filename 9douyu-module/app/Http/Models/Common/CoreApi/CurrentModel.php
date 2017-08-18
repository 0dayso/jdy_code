<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 11:41
 * Desc: 核心零钱计划操作调用model
 */

namespace App\Http\Models\Common\CoreApi;

use App\Http\Models\Common\CoreApiModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Config;
use App\Http\Models\Common\HttpQuery;
use App\Tools\ToolMoney;

class CurrentModel extends CoreApiModel{


    /**
     * @param $userId  用户ID         必填
     * @param $cash    转入金额        必填
     * @return array
     * 零钱计划转入
     */
    public static function doCurrrentInvest($userId,$cash){

        $api = Config::get('coreApi.moduleCurrent.doInvest');

        $cash =     ToolMoney::formatDbCashDelete($cash);
        $params = [
            'user_id' => $userId,
            'cash'    => $cash
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;
    }


    /**
     * @param $userId  用户ID         必填
     * @param $cash    转入金额        必填
     * @return array
     * 零钱计划转出
     */
    public static function doCurrrentInvestOut($userId,$cash){

        $api = Config::get('coreApi.moduleCurrent.doInvestOut');

        $cash =     ToolMoney::formatDbCashDelete($cash);
        $params = [
            'user_id' => $userId,
            'cash'    => $cash
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;
    }



    /**
     * @param $userId  用户ID         必填
     * @return array
     * 零钱计划计息请求
     */
    public static function doCurrentInterestAccrual($rate){

        $api = Config::get('coreApi.moduleCurrent.doInterestAccrual');

        $params = [
            'rate' => $rate,
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;
    }
    /**
     * @param $userId       用户ID    必填
     * @return array
     * 获取零钱计划用户债权金额
     */
    public static function getCurrentCreditAmount($userId)
    {

        $api = Config::get('coreApi.moduleCurrent.getCreditAmount');

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;

        /*
        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            $data['cash']   = ToolMoney::formatDbCashAdd($data['cash']);

            return $data;
        } else {
            return [];
        }
        */

    }


    /**
     * @param $userId       用户ID    必填
     * @return array
     * 获取零钱计划用户信息
     */
    public static function getCurrentUserInfo($userId)
    {

        $api = Config::get('coreApi.moduleCurrent.getUserInfo');

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            return $return['data'];
            
        } else {
            return [];
        }

    }

    /**
     * @return int
     * 获取零钱计划用户今日已转出的总金额
     */
    public static function getTodayCurrentInvestOutAmount(){


        $api = Config::get('coreApi.moduleCurrent.getTodayCurrentInvestOutAmount');

        $params = [
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

           return $return['data']['amount'];

        } else {
            return 0;
        }
    }

    /**
     * @param $userId       用户ID    必填
     * @return array
     * 获取零钱计划投资人数
     */
    public static function getCurrentInvestUserNum()
    {

        $api = Config::get('coreApi.moduleCurrent.getInvestUserNum');

        $params = [];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            return $return['data'];
        } else {
            return [];
        }

    }


    /**
     * @param $userId   用户ID    必填
     * @return array
     * 获取零钱计划用户今日转出金额
     */
    public static function getCurrentUserTodayInvestOutAmount($userId)
    {

        $api = Config::get('coreApi.moduleCurrent.getTodayInvestOutAmount');

        $params = [
            'user_id'   => $userId
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];
            
            return $data;

        } else {
            return [];
        }

    }

    /**
     * @param $userId   用户ID    必填
     * @return array
     * 用户中心零钱计划页面
     */
    public static function getCurrentUserFund($userId){


        $api = Config::get('coreApi.moduleCurrent.getUserFund');

        $params = [
            'user_id'   => $userId,
            'size'      => 10,
            'page'      => 1,
        ];

        $return = HttpQuery::corePost($api, $params);
        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            $fundList = $data['fund_list'];

            ////零钱计划资金流水金额转化
            if(!empty($fundList)){

                foreach($fundList['data'] as $k=>$val){

                    $fundList['data'][$k]['balance_change'] = abs(ToolMoney::formatDbCashAdd($val['balance_change']));
                    $fundList['data'][$k]['balance_before'] = ToolMoney::formatDbCashAdd($val['balance_before']);
                    $fundList['data'][$k]['balance']        = ToolMoney::formatDbCashAdd($val['balance']);

                }

                $data['fund_list'] = $fundList;
            }


            $interestList = $data['interest_list'];
            if(!empty($interestList)){

                foreach($interestList as $k=>$val){

                    $interestList[$k]['principal']  = ToolMoney::formatDbCashAdd($val['principal']);
                    $interestList[$k]['interest']   = ToolMoney::formatDbCashAdd($val['interest']);

                }
                $data['interest_list']    = $interestList;

            }

            $accountInfo = $data['account_info'];

            if($accountInfo){

                $accountInfo['cash']                    = ToolMoney::formatDbCashAdd($accountInfo['cash']);
                $accountInfo['interest']                = ToolMoney::formatDbCashAdd($accountInfo['interest']);
                $accountInfo['yesterday_interest']      = ToolMoney::formatDbCashAdd($accountInfo['yesterday_interest']);

                $data['account_info']   = $accountInfo;
            }


            return $data;
        }else{

            return [];
        }

    }


    /**
     * @return array
     * 获取零钱计划投资总额
     */
    public static function getCurrentInvestAmount()
    {

        $api = Config::get('coreApi.moduleCurrent.getInvestAmount');

        $params = [];

        $return = HttpQuery::corePost($api, $params);
        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            $data['amount'] = ToolMoney::formatDbCashAdd($data['amount']);
            
            return $data['amount'];
        } else {
            return 0;
        }

    }


    /**
     * @return int
     * 获取今日零钱计划转入总金额
     */
    public static function getTodayInvestAmount(){

        $api = Config::get('coreApi.moduleCurrent.getTodayCurrentInvestAmount');

        $params = [];

        $return = HttpQuery::corePost($api, $params);
        if ($return['status'] && !empty($return['data'])) {
            
            return $return['data']['amount'];

        } else {
            return 0;
        }
    }

    /**
     * @return int
     * 获取今日用户零钱计划自动转入总金额
     */
    public static function getTodayAutoInvestAmountByUserId($userId){

        $api = Config::get('coreApi.moduleCurrent.getTodayCurrentAutoInvestAmountByUserId');

        $params = [
            'user_id'   => $userId
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            return $return['data']['amount'];

        } else {

            return 0;

        }

    }

    /**
     * @param string $date
     * @return int
     * @desc 根据时间获取平台自动转入零钱计划的金额总数
     */
    public static function getPlatformTodayAutoInvestCurrentTotal($date=''){

        $api = Config::get('coreApi.moduleCurrent.getPlatformTodayAutoInvestCurrentTotal');

        $params = [
            'date'   => $date
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            return $return['data']['amount'];

        } else {

            return 0;

        }

    }


    /**
     * @return array
     * 获取零钱计划用户近一周收益
     */
    public static function getInterestList($userId){

        $api = Config::get('coreApi.moduleCurrent.getInterestList');

        $params = [
            'user_id'   => $userId
        ];
        $return = HttpQuery::corePost($api, $params);
        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            $total_interest = ToolMoney::formatDbCashAdd($data['total_interest']);

            $interestList   = $data['interest_list'];

            foreach($interestList as $k=>$val){

                $interestList[$k]['interest']  = ToolMoney::formatDbCashAdd($val['interest']);
                $interestList[$k]['principal'] = ToolMoney::formatDbCashAdd($val['principal']);
            }


            return [
                'total_interest'    => $total_interest,
                'interest_list'     => $interestList
            ];
        } else {
            return [];
        }
    }
    
    
    public static function getInvestList($userId){

    }

    /**
     * 获取零钱计划昨日转入转出记录
     */
    public static function getYesterdayFund(){

        $api = Config::get('coreApi.moduleCurrent.getYesterdayFund');

        $params = [
        ];
        $return = HttpQuery::corePost($api, $params);

        if($return['status'] && !empty($return['data'])){

            return ToolArray::arrayToKey($return['data'],'event_id');
        }else{

            return [];
        }
    }


    /**
     * 获取零钱计划昨日转入转出记录
     */
    public static function getYesterdayInterest(){

        $api = Config::get('coreApi.moduleCurrent.getYesterdayInterest');

        $params = [
        ];
        $return = HttpQuery::corePost($api, $params);

        if($return['status'] && !empty($return['data'])){

            return ToolArray::arrayToKey($return['data'],'type');
        }else{

            return [];
        }
    }

    /**
     * @desc 获取活期计息历史记录列表【管理后台】
     * @param $page
     * @param $size
     * @param $param
     * @return array
     */
    public static function getAdminCurrentInterestHistory($page, $size, $param){

        $api = Config::get('coreApi.moduleCurrent.getAdminCurrentInterestHistory');

        $params = array_merge(['page'=>$page,'size'=>$size], $param);

        $return = HttpQuery::corePost($api, $params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return array
     * @desc 每天凌晨3点批量获取自动投资活期的记录
     */
    public static function getAutoInvestCurrentListByDate( $startDate='', $endDate='' ){

        $api = Config::get('coreApi.moduleCurrent.getAutoInvestCurrentListByDate');

        $startDate = empty($startDate) ? ToolTime::dbDate() : $startDate;

        $endDate = empty($endDate) ? ToolTime::getDateAfterCurrent() : $endDate;

        $params = ['start_date' => $startDate, 'end_date' => $endDate];

        $return = HttpQuery::corePost($api, $params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];

        }
    }

    /**
     * @return array
     * @desc 获取当前的零钱计划的账号留存
     */
    public static function getCurrentAccountAmount()
    {
        $coreApi = Config::get('coreApi.moduleCurrent.getCurrentAccountAmount');

        $return = HttpQuery::corePost($coreApi, []);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];

        }
    }


}