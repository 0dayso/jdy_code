<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 11:56
 * Desc: 核心回款操作调用model
 */

namespace App\Http\Models\Common\CoreApi;

use App\Http\Models\Common\CoreApiModel;
use Config;
use App\Http\Models\Common\HttpQuery;
use App\Tools\ToolMoney;

class RefundModel extends CoreApiModel{

    const   STATUS_SUCCESS      = 200,  //已还款
            STATUS_ING          = 600,  //还款中

            END = true;
    /**
     * @param $userId       用户ID    必填
     * @param $sDate        开始日期    必填
     * @param $eDate        结束日期    必填
     * @return array
     * 获取已款列表
     */
    public static function getCoreRefundedRecord($userId,$sDate,$eDate)
    {

        $api = Config::get('coreApi.moduleRefund.getCoreRefundedRecord');

        $params = [
            'user_id'   => $userId,
            's_date'    => $sDate,
            'e_date'    => $eDate,
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            foreach ($data as $k => $val) {

                $data[$k]['principal']          = ToolMoney::formatDbCashAdd($val['principal']);
                $data[$k]['interest']           = ToolMoney::formatDbCashAdd($val['interest']);
                $data[$k]['cash']               = ToolMoney::formatDbCashAdd($val['cash']);
                //$data[$k]['before_refund']      = ToolMoney::formatDbCashAdd($val['before_refund']);
                $data[$k]['refunded_total']     = ToolMoney::formatDbCashAdd($val['refunded_total']);
                $data[$k]['refunding_total']    = ToolMoney::formatDbCashAdd($val['refunding_total']);
                $data[$k]['refund_current']     = ToolMoney::formatDbCashAdd($val['refund_current']);
                $data[$k]['refund_total']       = ToolMoney::formatDbCashAdd($val['refund_total']);

            }

            return $data;
        } else {
            return [];
        }

    }



    /**
     * @param $userId       用户ID    必填
     * @param $sDate        开始日期    必填
     * @param $eDate        结束日期    必填
     * @return array
     * 获取已款列表
     */
    public static function getCoreRefundingRecord($userId,$sDate,$eDate)
    {

        $api = Config::get('coreApi.moduleRefund.getCoreRefundingRecord');

        $params = [
            'user_id'   => $userId,
            's_date'    => $sDate,
            'e_date'    => $eDate,
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];
            foreach ($data as $k => $val) {
                $data[$k]['project_id']         = $val['project_id'];
                $data[$k]['principal']          = ToolMoney::formatDbCashAdd($val['principal']);
                $data[$k]['interest']           = ToolMoney::formatDbCashAdd($val['interest']);
                $data[$k]['cash']               = ToolMoney::formatDbCashAdd($val['cash']);
                //$data[$k]['before_refund']      = ToolMoney::formatDbCashAdd($val['before_refund']);
                $data[$k]['refunded_total']     = ToolMoney::formatDbCashAdd($val['refunded_total']);
                $data[$k]['refunding_total']    = ToolMoney::formatDbCashAdd($val['refunding_total']);
                $data[$k]['refund_current']     = ToolMoney::formatDbCashAdd($val['refund_current']);
                $data[$k]['refund_total']       = ToolMoney::formatDbCashAdd($val['refund_total']);

            }

            return $data;
        } else {
            return [];
        }

    }

    /**
     * @desc 获取用户的某天的回款记录
     * @param $userId int
     * @param $date string
     * @return array
     */
    public  static function getRefundRecordByDay($userId, $date){

        $api = Config::get('coreApi.moduleRefund.getUserRefundRecordByDay');

        $params = [
            'user_id'   => $userId,
            'date'    => $date,
        ];

        $return = HttpQuery::corePost($api, $params);

        if($return['status'] && !empty($return['data'])){
            $data = $return['data'];
            return $data;
        }else {
            return [];
        }
    }

    /**
     * @desc 通过ID获取用户回款详情
     * @author linguanghui
     * @param $id int
     * @return array
     */
    public static function getRefundRecordById($id){

        $api = Config::get('coreApi.moduleRefund.getUserRefundRecordById');

        $params = [
            'id' => $id,
            ];

        $return =  HttpQuery::corePost($api, $params);

        if($return['status'] && !empty($return['data'])){
            $data = $return['data'];
            return $data;
        }else {
            return [];
        }
    }


    /**
     * @param $userId 用户ID    必填
     * @return array
     * @desc 通过用户id获取每月的待回款金额
     */
    public static function getCoreRefundPlanByMonthByUserId( $userId ){

        $api = Config::get('coreApi.moduleRefund.getCoreRefundPlanByMonthByUserId');

        $params = [
          'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            foreach ($data as $k => $val) {

                $data[$k]['total_cash']         = ToolMoney::formatDbCashAdd($val['total_cash']);

            }

            return $data;
        } else {
            return [];
        }

    }

    /**
     * @param string $userIds 用户ID    必填 1,2,3
     * @return array
     * @desc 通过多个用户id获取待回款本金之和
     */
    public static function getCoreRefundTotalByUserIds( $userIds ){

        $api = Config::get('coreApi.moduleRefund.getRefundTotalByUserIds');

        $userId = is_array($userIds)==false ? $userIds : implode(',', $userIds);

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            $data['total_cash'] = $data['total_cash']?:0;

            return $data;
        } else {

            return ['total_cash'=>0];
        }

    }

    /**
     * @desc 通过多个用户id获取每个人的待回款本金之和列表
     * @author lgh
     * @param $userIds 用户ID 必填项
     * @return array
     */
    public static function getRefundingPrincipalListByUserIds( $userIds ){

        $api = Config::get('coreApi.moduleRefund.getRefundingPrincipalListByUserIds');

        $userId = is_array($userIds)==false ? $userIds : implode(',', $userIds);

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            //$data['total_cash'] = $data['total_cash']?:0;

            return $data;
        } else {

            return [];
        }
    }

    /**
     * @return null|void
     * @desc 获取待收本息总额
     */
    public function getRefundingTotal(){

        $api = Config::get('coreApi.moduleRefund.getRefundingTotal');

        $return = HttpQuery::corePost($api);

        if(!empty($return['data'])){

            return $return['data']['total'];
        }else{

            return 0;
        }
    }

    /*
 * @param  string $times
 * @return array
 * @desc 读取核心当天回款的项目
 */
    public static function getRefundProjectByTime( $times )
    {
        $api        =   Config::get('coreApi.moduleRefund.getRefundProjectByTime');
        $apiData    =   ['times'=>$times];
        $return     =   HttpQuery::corePost($api, $apiData);
        if( !empty($return['data'])){

            return $return['data'];
        }
        return [];
    }

    /**
     * @param $times
     * @return array
     * @desc 项目还款公告数据
     */
    public static function getArticleNoticeByTimes( $times ){

        $api        =   Config::get('coreApi.moduleRefund.getArticleNoticeByTimes');

        $apiData    =   ['times'=>$times];

        $return     =   HttpQuery::corePost($api, $apiData);

        if( !empty($return['data'])){

            return $return['data'];

        }

        return [];

    }

    /**
     * @desc 获取当日回款用户
     * @author lgh
     * @return null|void
     */
    public static function getTodayRefundUser(){

        $api        =   Config::get('coreApi.moduleRefund.getTodayRefundUser');

        $return     =   HttpQuery::corePost($api);

        return $return;
    }

    /**
     * @param string $date
     * @return null|void
     * @desc 根据时间获取回款用户列表信息
     */
    public static function getRefundUserByDate($date=''){

        $api        =   Config::get('coreApi.moduleRefund.getRefundUserByDate');

        $apiData    =   ['date'=>$date];

        $return     =   HttpQuery::corePost($api, $apiData);

        return $return;
    }

    /**
     * @desc 根据时间段获取每天回款总额
     * @param $data
     * @return array
     */
    public static function getRefundTotalGroupByTime($data)
    {
        $api        =   Config::get('coreApi.moduleRefund.getRefundTotalGroupByTime');
        $apiData    =   ['start_time'=>$data['start_time'],'end_time'=>$data['end_time']];
        $return     =   HttpQuery::corePost($api, $apiData);
        if( !empty($return['data'])){

            return $return['data'];
        }
        return [];
    }

    /**
     * @param string $date
     * @return array
     * @desc 根据时间获取还款的项目id和金额
     */
    public static function getRefundProjectIdsAndCashByDate($date=''){

        $api        =   Config::get('coreApi.moduleRefund.getRefundProjectIdsAndCashByDate');

        $apiData    =   ['date'=>$date];

        return HttpQuery::corePost($api, $apiData);

    }

}
