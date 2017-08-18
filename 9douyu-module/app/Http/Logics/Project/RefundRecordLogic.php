<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/12
 * Time: 下午3:26
 * Desc: 回款相关
 */

namespace App\Http\Logics\Project;

use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\RefundModel;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Common\LoanUserApi\LoanUserCreditApiModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Config;

class RefundRecordLogic extends Logic
{

    /**
     * @param $userId
     * @param string $sDate
     * @param string $eDate
     * @return mixed
     * @desc 获取用户已回款列表
     */
    public function getRefundedListByDate($userId, $sDate='', $eDate='')
    {

        $sDate = $sDate ? $sDate : ToolTime::getMonthFirstDay();

        $eDate = $eDate ? $eDate : ToolTime::getMonthLastDay();

        $model = new RefundModel();

        $data = $model -> getCoreRefundedRecord($userId, $sDate, $eDate);

        return self::callSuccess($data);

    }

    /**
     * @param $userId
     * @param string $sDate
     * @param string $eDate
     * @return mixed
     * @desc 获取用户回款中列表
     */
    public function getRefundingListByDate($userId, $sDate='', $eDate='')
    {

        $sDate = $sDate ? $sDate : ToolTime::getMonthFirstDay();

        $eDate = $eDate ? $eDate : ToolTime::getMonthLastDay();

        $model = new RefundModel();

        $data  = $model -> getCoreRefundingRecord($userId, $sDate, $eDate);

        return self::callSuccess($data);

    }

    /**
     * @desc 获取用户某天的回款记录
     * @author linguanghui
     * @param $userId int
     * @param $day string
     * @return array
     */
    public function getRefundListByDay($userId, $day){

        if(empty($userId)){
            return [];
        }

        $date  = $day ? $day : ToolTime::dbDate();

        $data = RefundModel::getRefundRecordByDay($userId, $date);

        return $data;
    }

    /**
     * @desc 通过id获取用户回款记录详情
     * @author linguanghui
     * @param $id int
     * @return array
     */
    public function getRefundRecordById($id){

        if(empty($id)){
            return [];
        }

        $data = RefundModel::getRefundRecordById($id);

        return $data;
    }

    /**
     * @param $userId
     * @return array
     * @desc 通过用户id获取每月的待回款金额
     */
    public function getRefundPlanByMonthByUserId( $userId ){

        $model = new RefundModel();

        $data = $model -> getCoreRefundPlanByMonthByUserId($userId);

        $result = [];

        if(!empty($data)){

            foreach($data as $key => $value){

                $result[$key]['year']   = date('Y',strtotime($value['months']));
                $result[$key]['date']   = $value['months'];
                $result[$key]['total']  = number_format(ToolMoney::formatDbCashDelete($value['total_cash']),2);
                $result[$key]['projectNum']   = $value['project_num'];
            }

        }

        return self::callSuccess(['data'=>$result]);

    }

    /**
     * 格式化回款记录按年分组
     *
     * @param $data
     * @return array
     */
    public function getRefundPlanFormatYear( $data =[] ){

        $return = [];

        if(!empty($data)){
            foreach($data as $K => $item){
                $return[$item['year']][] = $item;
            }
        }

        return $return;

    }

    /**
     * @param $userId
     * @param $month
     * @return array
     * @desc 当月回款记录
     */
    public function refundPlanByDate( $userId, $month ){

        $sDate = ToolTime::getMonthFirstDay($month);

        $eDate = ToolTime::getMonthLastDay($month);

        $model = new RefundModel();

        $refunded = $model -> getCoreRefundedRecord($userId, $sDate, $eDate);

        $data['refunded'] = $this -> formatRefundPlan($refunded);

        $refund = $model -> getCoreRefundingRecord($userId, $sDate, $eDate);

        $data['refund']   = $this -> formatRefundPlan($refund);

        return self::callSuccess($data);

    }

    /**
     * @param $userId
     * @return array
     * @desc 安卓回款记录
     */
    public function androidRefundPlanByDate( $userId ){

        $sDate = ToolTime::getYearFirstDay();

        $date  = ToolTime::getAfterYearDay();

        $eDate = ToolTime::getYearLastDay($date);


        $model = new RefundModel();

        $data['refunded'] = $model -> getCoreRefundedRecord($userId, $sDate, $eDate);

        $data['refund']   = $model -> getCoreRefundingRecord($userId, $sDate, $eDate);

        $data = $this -> formatAndroidRefundPlan($data,$sDate,$eDate);

        return self::callSuccess($data);

    }

    /**
     * @param $data
     * @return array
     * @desc 格式化安卓回款记录
     */
    public function formatAndroidRefundPlan( $data,$sDate,$eDate ){

        $refunded = $data['refunded'];

        $refund   = $data['refund'];

        $refundedResult   = [];
        $refundResult = [];

        if(!empty($refunded)){

            foreach($refunded as $key => $item){

                //$year   = ToolTime::getYear($item['times']);
                //$month  = ToolTime::getMonth($item['times']);

                $date = date('Y-m',strtotime($item['times']));
                /*
                $refundedResult[$date]['year'] = $year;
                $refundedResult[$date]['month'] = $month;
                */

                /*
                if(!isset($result[$year])){
                    $result[$year] =[];
                }

                if(!isset($result[$year][$month])){
                    $result[$year][$month] =[];
                }

                if(!isset($result[$date]['total'])){
                    $result[$year][$month]['total'] = 0;
                }

                if(!isset($result[$year][$month]['month'])){
                    $result[$year][$month]['month'] = $month;
                }
                */
                if(isset($refundedResult[$date]['total'])){
                    $refundedResult[$date]['total'] += ToolMoney::formatDbCashDelete($item['cash']);

                }else{
                    $refundedResult[$date]['total'] = ToolMoney::formatDbCashDelete($item['cash']);
                }

                $refundedResult[$date]['refunded'][] = [

                    'interest'      => ToolMoney::formatDbCashDelete($item['interest']),
                    'invest_type'   => $item['invest_type'],
                    'cash'          => ToolMoney::formatDbCashDelete($item['cash']),
                    'name'          => $item['project_name'].' '.$item['project_id'],
                    'principal'     => ToolMoney::formatDbCashDelete($item['principal']),
                    'date'          => $item['times'],

                ];

                //$result[$year][$month]['refund'] = [[]];

            }

        }

        if(!empty($refund)){

            foreach($refund as $key => $item){

                /*
                $year   = ToolTime::getYear($item['times']);
                $month  = ToolTime::getMonth($item['times']);

                */
                $date = date('Y-m',strtotime($item['times']));
                /*
                $refundResult[$date]['year'] = $year;
                $refundResult[$date]['month'] = $month;
                */
                /*

                if(!isset($result[$year])){
                    $result[$year] =[];
                }

                if(!isset($result[$year][$month])){
                    $result[$year][$month] =[];
                }

                if(!isset($result[$year][$month]['total'])){
                    $result[$year][$month]['total'] = 0;
                }

                if(!isset($result[$year][$month]['month'])){
                    $result[$year][$month]['month'] = $month;
                }

                 */

                if(isset($refundResult[$date]['total'])){

                    $refundResult[$date]['total'] += ToolMoney::formatDbCashDelete($item['cash']);

                }else{

                    $refundResult[$date]['total'] = ToolMoney::formatDbCashDelete($item['cash']);
                }

                $refundResult[$date]['refund'][] = [

                    'interest'      => ToolMoney::formatDbCashDelete($item['interest']),
                    'invest_type'   => $item['invest_type'],
                    'cash'          => ToolMoney::formatDbCashDelete($item['cash']),
                    'name'          => $item['project_name'].' '.$item['project_id'],
                    'principal'     => ToolMoney::formatDbCashDelete($item['principal']),
                    'date'          => $item['times'],

                ];

            }

        }


        $sTime = strtotime($sDate);
        $i = 0;
        $sDate = date('Y-m',$sTime);
        $eDate =  date('Y-m',strtotime($eDate));

        $result = [];

        while($sDate < $eDate){
            $sDate = date('Y-m',strtotime(" +{$i} month",$sTime));
            $i++;

            $year = date('Y',strtotime($sDate));
            $month  = date('n',strtotime($sDate));


            $data = [
                'month' => $month,
                'total' => 0,
                'refunded' => [[]],
                'refund'    => [[]]
            ];

            if(isset($refundedResult[$sDate]) && !empty($refundedResult[$sDate]['refunded'])){

                $data['refunded'] = $refundedResult[$sDate]['refunded'];

                $data['total'] += $refundedResult[$sDate]['total'];
            }
            if(isset($refundResult[$sDate]) && !empty($refundResult[$sDate]['refund'])){

                $data['refund'] = $refundResult[$sDate]['refund'];

                $data['total'] += $refundResult[$sDate]['total'];

            }


            //$result[$year] = ['year' => $year];

            $result[$year]['data'][] = $data;
        }

        foreach($result as $year => $item){
            $list[] = [
                'year' => $year,
                'data' => $item['data']
            ];
        }

        return $list;

    }

    /**
     * @param $data
     * @return array
     * @desc 格式化当月回款记录
     */
    public function formatRefundPlan( $data ){

        if(empty($data)){
            return [];
        }

        $result = [];

        foreach($data as $key => $item){

            $result[$key] = [
                'id'            => $item['project_id'],
                'record_id'     => $item['id'],
                'invest_id'     => $item['invest_id'],
                'user_id'       => $item['user_id'],
                'interest'      => ToolMoney::formatDbCashDelete($item['interest']),
                'invest_type'   => $item['invest_type'],
                'cash'          => ToolMoney::formatDbCashDelete($item['cash']),
                'name'          => $item['project_name'].' '.$item['project_id'],
                'product_line_note' => $item['product_line_note'],
                'invest_time_note' => $item['invest_time_note'],
                'principal'     => number_format(ToolMoney::formatDbCashDelete($item['principal']),2),
                'principal_amount'     => ToolMoney::formatDbCashDelete($item['principal']),//本金用于计算本金和
                'date'          => $item['times'],
                'times'         => $item['times'],
                'periods'       => $item['periods'],
                'current_periods' => $item['current_periods'],

            ];

        }

        return $result;

    }

    /**
     * @return null|void
     * @desc 获取待收本息总额
     */
    public function getRefundingTotal(){

        $model = new RefundModel();

        $total = $model->getRefundingTotal();

        return $total;
    }

    /**
     * @desc 获取用户代收本息
     * @param $userId
     * @return array
     */
    public function getRefundingTotalByUserId($userId){

        $model = new RefundModel();

        $total = $model->getCoreRefundTotalByUserIds($userId);

        return $total;

    }

    /**
     * @desc 获取今日回款用户
     * @author lgh-dev
     * @return null|void
     */
    public function getTodayRefundUser(){

        $return = RefundModel::getTodayRefundUser();

        if($return['status']){
            return $return['data'];
        }else{
            return $return;
        }
    }

    /**
     * @param string $date
     * @return null|void
     * @desc 根据时间获取回款用户列表
     */
    public function getRefundUserByDate($date=''){

        $return = RefundModel::getRefundUserByDate($date);

        if($return['status']){
            return $return['data'];
        }else{
            return $return;
        }
    }

    /**
     * @param string $date
     * @return array
     * @desc 发送债权还款通知到债权借款人系统
     */
    public function doNoticeLoanUserRefund($date=''){

        $return = RefundModel::getRefundProjectIdsAndCashByDate($date);

        if($return['status']){

            $loanResult = LoanUserCreditApiModel::sendRefundNotice($return['data']);

            if( !$loanResult['status'] ){

                //执行邮件报警
                $receiveEmails = \Config::get('email.monitor.accessToken');

                $emailModel = new EmailModel();

                $subject = '【Error】发送债权还款通知到债权借款人系统出错,出错原因:'.$loanResult['msg'];

                $emailModel->sendHtmlEmail($receiveEmails, $subject, json_encode($loanResult['data']));

            }

        }else{

            return $return;

        }

    }

    /*#############################   App4.0   #######################################*/

    /**
     * @desc 获取本月回款金额数据统计金额
     * @author linguanghui
     * @param $refundData
     * @return array
     */
    public function getRefundMonthAmount($refundData){

        $refundMonthAmounts = [];

        //初始化数据
        $refundedCash = $refundCash = $refundInterest = $refundedInterest = $refundPrincipal = $refundedPrincipal = 0;

        //已回款
        if(!empty($refundData['refunded'])){

            foreach($refundData['refunded'] as $value){
                //本月已回款金额
                $refundedCash += (float)$value['cash'];
                //本月已回款本金
                $refundedPrincipal += (float)$value['principal_amount'];
                //本月已回款利息
                $refundedInterest += (float)$value['interest'];
            }
        }

        //待回款
        if(!empty($refundData['refund'])){
            foreach($refundData['refund'] as $value){
                //本月待回款金额
                $refundCash += (float)$value['cash'];
                //本月待回款本金
                $refundPrincipal += (float)$value['principal_amount'];
                //本月待回款利息
                $refundInterest += (float)$value['interest'];
            }
        }

        $refundMonthAmounts = [
            'refund_cash' => $refundCash,
            'refunded_cash' => $refundedCash,
            'refund_principal' => $refundPrincipal,
            'refunded_principal' => $refundedPrincipal,
            'refund_interest' => $refundInterest,
            'refunded_interest' => $refundedInterest,
            ];

        return $refundMonthAmounts;

    }
    /**
     * @desc 格式化App4.0回款相关的统计数据
     * @author linguanghui
     * @param $refundAmountData array
     * $return $formatRefundAmount array
     */
    public function formatAppV4RefundAmountData($refundAmountData){
        $formatRefundAmount = [];

        if(!empty($refundAmountData)){
            foreach($refundAmountData as $key=>$value){
                $formatRefundAmount[$key] = number_format($value,2);
            }
            $formatRefundAmount['refund_cash_note'] = '本月未回款';
            $formatRefundAmount['refunded_cash_note'] = '本月已回款';
            $formatRefundAmount['refund_amount_unit'] = '元';
            $formatRefundAmount['month_all_button'] = '本月全部';
            $formatRefundAmount['refund_principal_note'] = '待回款本金';
            $formatRefundAmount['refund_interest_note'] = '待回款收益';
            $formatRefundAmount['refunded_principal_note'] = '已回款本金';
            $formatRefundAmount['refunded_interest_note']  = '已回款收益';
        }

        return $formatRefundAmount;
    }

    /**
     * @desc 获取App本月回款日期
     * @author linguanghui
     * @param $refundRecordData array
     * @return array
     */
    public function formatAppV4RefundDate($refundRecordData){

        $formatRefundDate = [];

        //已回款日期
        if(!empty($refundRecordData['refunded'])){

            foreach($refundRecordData['refunded'] as $key => $value){
                $formatRefundDate['refunded_date'][$key] = $value['date'];
            }
        }

        //待回款日期
        if(!empty($refundRecordData['refund'])){

            foreach($refundRecordData['refund'] as $key => $value){
                $formatRefundDate['refund_date'][$key] = $value['date'];
            }
        }

        return $formatRefundDate;
    }


    /**
     * @desc 格式化用户当日回款列表数据
     * @author linguanghui
     * @param $dayRefundList
     * @return array
     */
    public function formatAppV4RefundDayList($dayRefundList){
        $formatUserRefundList = [];

        #print_r($dayRefundList);exit;
        if(!empty($dayRefundList)){

            foreach($dayRefundList as $key=>$value){

                $formatUserRefundList[$key]['id'] = $value['id'];
                $formatUserRefundList[$key]['project_id'] = $value['project_id'];
                $formatUserRefundList[$key]['project_name'] = $value['product_line_note'];
                $formatUserRefundList[$key]['invest_time_note'] = $value['invest_time_note'];
                $formatUserRefundList[$key]['invest_id'] = $value['invest_id'];
                $formatUserRefundList[$key]['user_id'] = $value['user_id'];
                $formatUserRefundList[$key]['cash'] = number_format($value['cash'],2);
                $formatUserRefundList[$key]['cash_unit'] = '元';
                $formatUserRefundList[$key]['periods'] = $value['periods'];
                $formatUserRefundList[$key]['current_periods'] = $value['current_periods'];
                $formatUserRefundList[$key]['periods_note'] = '[第'.sprintf("%02d",$value['current_periods']).'/'.sprintf("%02d",$value['periods']).'期]';
            }
        }

        return $formatUserRefundList;
    }


    /**
     * @desc 格式化本月全部回款的数据[待回款+已回款]
     * @author linguanghui
     * @param $monthRefundRecord array
     * @param $month string
     * @return array
     */
    public function formatAppV4MonthRefundList($monthRefundRecord, $month = ''){

        $formatMonthRefund = [];
        //格式化回款时间
        $month = $month ? $month : ToolTime::dbDate();
        $month_note = date('Y年m月', strtotime(ToolTime::getYearMonth($month)));

        if(!empty($monthRefundRecord) && $monthRefundRecord['status'] == true){
            $refundMonthAmount  = $this->getRefundMonthAmount($monthRefundRecord['data']);
            $monthAmountData    = $this->formatAppV4RefundAmountData($refundMonthAmount);//格式化本月的金额统计数据
        }

        #print_r($monthAmountData);exit;
        //待回款
        if(!empty($monthRefundRecord['data']['refund'])){
            $formatMonthRefund['refund']['refund_principal_note'] = $monthAmountData['refund_principal_note'];
            $formatMonthRefund['refund']['refund_principal'] = $monthAmountData['refund_principal'];
            $formatMonthRefund['refund']['refund_interest_note'] = $monthAmountData['refund_interest_note'];
            $formatMonthRefund['refund']['refund_interest'] = $monthAmountData['refund_interest'];
            $formatMonthRefund['refund']['refund_amount_unit'] = '元';
            $formatMonthRefund['refund']['month_note'] = $month_note;

            $refundMonthData = $this->formatMonthRefundListData($monthRefundRecord['data']['refund']);
            $formatMonthRefund['refund']['refund_list'] = $refundMonthData;

        }

        //已回款
        if(!empty($monthRefundRecord['data']['refunded'])){

            $formatMonthRefund['refunded']['refund_principal_note']  = $monthAmountData['refunded_principal_note'];
            $formatMonthRefund['refunded']['refund_principal']       = $monthAmountData['refunded_principal'];
            $formatMonthRefund['refunded']['refund_interest_note']  = $monthAmountData['refunded_interest_note'];
            $formatMonthRefund['refunded']['refund_interest']  = $monthAmountData['refunded_interest'];
            $formatMonthRefund['refunded']['refund_amount_unit'] = '元';
            $formatMonthRefund['refunded']['month_note'] = $month_note;

            $refundedMonthData  = $this->formatMonthRefundListData($monthRefundRecord['data']['refunded']);
            $formatMonthRefund['refunded']['refund_list'] = $refundedMonthData;


        }
        return $formatMonthRefund;
    }


    /**
     * @desc 格式化用户本月全部回款列表数据
     * @author linguanghui
     * @param $monthRefundList array
     * @return array
     */
    public function formatMonthRefundListData($monthRefundList){
        $formatUserRefundList = [];

        if(!empty($monthRefundList)){

            foreach($monthRefundList as $key=>$value){

                $formatUserRefundList[$key]['id'] = $value['record_id'];
                $formatUserRefundList[$key]['project_id'] = $value['id'];
                $formatUserRefundList[$key]['project_name'] = $value['name'];
                $formatUserRefundList[$key]['product_line_note'] = $value['product_line_note'];
                $formatUserRefundList[$key]['invest_time_note'] = $value['invest_time_note'];
                $formatUserRefundList[$key]['invest_id'] = $value['invest_id'];
                $formatUserRefundList[$key]['user_id'] = $value['user_id'];
                $formatUserRefundList[$key]['cash'] = number_format($value['cash'],2);
                $formatUserRefundList[$key]['cash_unit'] = '元';
                $formatUserRefundList[$key]['periods'] = $value['periods'];
                $formatUserRefundList[$key]['current_periods'] = $value['current_periods'];
                $formatUserRefundList[$key]['periods_note'] = '[第'.sprintf("%02d",$value['current_periods']).'/'.sprintf("%02d",$value['periods']).'期]';
            }
        }

        return $formatUserRefundList;
    }

    /**
     * @desc 格式化用户回款记录详情
     * @author linguanghui
     * @param $recordDetail array
     * @return array
     */
    public function formatAppV4RecordDetail($recordDetail){

        $formatRecordDetail  = [];

        if(!empty($recordDetail)){

            foreach($recordDetail as $value){

                $formatRecordDetail['project_id']  =  $value['project_id'];
                $formatRecordDetail['user_id']  =  $value['user_id'];
                $formatRecordDetail['invest_id']  =  $value['invest_id'];
                $formatRecordDetail['project_id']  =  $value['project_id'];
                $formatRecordDetail['product_line_note'] = $value['product_line_note'];
                $formatRecordDetail['invest_time_note'] = $value['invest_time_note'];
                $formatRecordDetail['cash'] = number_format($value['cash'],2);
                $formatRecordDetail['refund_detail_button'] = '回款明细';
                $formatRecordDetail['cash_unit'] = '元';
                $formatRecordDetail['refund_amount_note'] = '回款金额';
                $formatRecordDetail['principal_note'] = '本金';
                $formatRecordDetail['principal'] = number_format($value['principal'],2);
                $formatRecordDetail['interest_note'] = '利息';
                $formatRecordDetail['interest'] = number_format($value['interest'],2);

                if( isset($value['bonus_cash']) ){
                $formatRecordDetail['bonus_award_note'] = '优惠券奖励';
                $formatRecordDetail['bonus_cash'] = number_format($value['bonus_cash'],2);
                }
                $formatRecordDetail['periods'] = $value['periods'];
                $formatRecordDetail['current_periods'] = $value['current_periods'];
                $formatRecordDetail['periods_note'] = '[第'.sprintf("%02d",$value['current_periods']).'/'.sprintf("%02d",$value['periods']).'期]';
            }

        }
        return $formatRecordDetail;
    }


    /*########################## Appv4.0回款数据接口end  ######################################*/

    /*################################# Appv4.1数据接口 ######################################*/
    /**
     * @desc 4.1格式化用户当日回款列表数据
     * @author jinzhuotao
     * @param $dayRefundList
     * @return array
     */
    public function formatAppV41RefundDayList($dayRefundList){
        $formatUserRefundList = [];

        if(!empty($dayRefundList)){

            foreach($dayRefundList as $key=>$value){

                $formatUserRefundList[$key]['id'] = $value['id'];
                $formatUserRefundList[$key]['project_id'] =isset($value['project_id']) ? $value['project_id'] : $value['id'];
                $formatUserRefundList[$key]['project_name'] = $value['product_line_note'];
                $formatUserRefundList[$key]['invest_time_note'] = $value['invest_time_note'];
                $formatUserRefundList[$key]['invest_id'] = $value['invest_id'];
                $formatUserRefundList[$key]['user_id'] = $value['user_id'];
                $formatUserRefundList[$key]['cash'] = number_format($value['cash'],2);
                $formatUserRefundList[$key]['cash_unit'] = '(元)';
                $formatUserRefundList[$key]['periods'] = $value['periods'];
                $formatUserRefundList[$key]['refund_day'] = date('d',strtotime($value['times']));
                $formatUserRefundList[$key]['current_periods'] = $value['current_periods'];
                $formatUserRefundList[$key]['principal'] = $value['principal'];
                $formatUserRefundList[$key]['interest'] = $value['interest'];
                $formatUserRefundList[$key]['invest_type'] = $value['invest_type'];
                $formatUserRefundList[$key]['periods_note'] = '第'.sprintf("%02d",$value['current_periods']).'/'.sprintf("%02d",$value['periods']).'期';
            }
        }

        return $formatUserRefundList;
    }
}
