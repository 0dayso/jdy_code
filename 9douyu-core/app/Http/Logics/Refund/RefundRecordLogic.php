<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/12
 * Time: 下午4:30
 * Desc: 回款记录相关
 */

namespace App\Http\Logics\Refund;

use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Warning\RefundLogic;
use App\Http\Models\Common\SmsModel;
use App\Http\Models\Refund\ProjectModel;
use App\Http\Logics\Project\ProjectLogic;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use Cache;
use Illuminate\Support\Facades\Lang;
use Log;

class RefundRecordLogic extends Logic
{

    /**
     * @param $userId
     * @param $sDate
     * @param $eDate
     * @return array
     * @desc 获取已回款的记录
     */
    public function getRefundedList($userId, $sDate, $eDate)
    {

        $refundDb = new RefundRecordDb();

        $list = $refundDb->getUserRefundListByTimes($userId, RefundRecordDb::STATUS_SUCCESS, $sDate, $eDate);

        $list = $this->formatList($list);

        $list = $this->formatUserRefundPeriods($userId, $list);

        $list = $this->formatRefundRecordData($list);

        return self::callSuccess($list);

    }

    /**
     * @param $userId
     * @param $sDate
     * @param $eDate
     * @return array
     * @desc 获取回款中的记录
     */
    public function getRefundingList($userId, $sDate, $eDate)
    {

        $refundDb = new RefundRecordDb();

        $list = $refundDb->getUserRefundListByTimes($userId, RefundRecordDb::STATUS_ING, $sDate, $eDate);

        $list = $this->formatList($list);

        $list = $this->formatUserRefundPeriods($userId, $list);

        $list = $this->formatRefundRecordData($list);

        return self::callSuccess($list);

    }

    /**
     * @desc 获取用户某个月的回款记录
     * @author linguanghui
     * @param $useId int
     * @param $date string
     * @return array
     */
    public function getRefundRecordByDay($userId, $date){

        $refundDb = new RefundRecordDb();

        $refundList = $refundDb->getRefundByDay($userId, $date);

        $refundList  = $this->formatUserRefundPeriods($userId, $refundList);

        $refundList = $this->formatRefundRecordData($refundList);

        return self::callSuccess($refundList);
    }

    /**
     * @desc 通过ID获取用户回款详情
     * @author linguanghui
     * @param $id int
     * @return array
     */
    public function getRefundDetailById($id = 0){

        $refundDb = new RefundRecordDb();

        $refundDetail = $refundDb->getRefundDetailById($id);

        //回款详情优惠券收益
        $bonusRefund  = $refundDb->getRefundBonusAward($refundDetail[0]['invest_id'], $refundDetail[0]['times']);
        if(!empty($bonusRefund)){
            $refundDetail[0]['bonus_cash'] = $bonusRefund[0]['cash'];
        }

        $refundDetail = $this->formatUserRefundPeriods($refundDetail[0]['user_id'], $refundDetail);

        $refundDetail = $this->formatRefundRecordData($refundDetail);

        return self::callSuccess($refundDetail);
    }

    /**
     * @desc 格式化回款记录
     * @author linguanghui
     * @param $refundrecord
     * @return array
     */
    public function formatRefundRecordData($refundRecord){

        if(empty($refundRecord)){
            return [];
        }

        $projectLogic = new ProjectLogic();

        $projectIds = implode(',',ToolArray::arrayToIds($refundRecord, 'project_id'));

        $projectLists = ToolArray::arrayToKey($projectLogic->getListByIds($projectIds),'id');

        #print_r($projectLists);exit;

        foreach($refundRecord as $key=>$val){

            if(isset($projectLists[$val['project_id']])){
                $refundRecord[$key]['product_line_note'] = $projectLists[$val['project_id']]['product_line_note'];
                $refundRecord[$key]['invest_time_note'] = $projectLists[$val['project_id']]['invest_time_note'];
                $refundRecord[$key]['invest_type']      = $refundRecord[$key]['type']==RefundRecordDb::TYPE_BONUS_RATE ? 3 : $refundRecord[$key]['type'];
            }
        }

        return $refundRecord;
    }

    /**
     * @desc 格式化用户回款期数
     * @author linguanghui
     * @param $userId int
     * @param $refundList array
     * @return array
     */
    public function formatUserRefundPeriods($userId, $refundList){

        $formatRefundData  = [];
        $refundDb = new RefundRecordDb();
        if(!empty($refundList)){

            $formatRefundData  = $refundList;


            $investIds = ToolArray::arrayToIds($refundList,'invest_id');
            //回款期数
            $refundPeriods = ToolArray::arrayToKey($refundDb->getUserRefundPeriods($userId, $investIds),'invest_id');
            foreach($refundList as $key=>$value){
                if(isset($refundPeriods[$value['invest_id']])){
                    $formatRefundData[$key]['periods'] = $refundPeriods[$value['invest_id']]['periods'];
                }

                //投资id
                $investId = $value['invest_id'];
                $times = $value['times'];

                $currentRefundPeriods = $refundDb->getRefundedCurrentPeriods($userId, $investId, $times);

                $formatRefundData[$key]['current_periods'] = $currentRefundPeriods['current_periods'];
            }
        }

        return $formatRefundData;
    }


    /**
     * @param array $list
     * @return array|string
     * @desc 格式化回款列表，项目的回款期数，项目名称
     */
    public function formatList($list=[])
    {

        if( empty($list) ){

            return '';

        }

        //获取项目ids
        $projectIds = ToolArray::arrayToIds($list, 'project_id');

        $refundDb = new RefundRecordDb();

        $projectDb = new ProjectDb();

        //获取项目的期数信息（已回款/未回款）
        $refundList = $refundDb->getRefundTotalByProjectIds($projectIds);

        //获取项目信息，前端项目名称数据显示
        $projectList = $projectDb->getListByProjectIds($projectIds);

        //把项目id作为键名
        $projectList = ToolArray::arrayToKey($projectList);

        $formatRefund = [];

        //按照回款状态格式化回款列表信息，作为期数的统计数据
        foreach( $refundList as $key => $val ){

            if( $val['status'] == RefundRecordDb::STATUS_SUCCESS ){

                $formatRefund[$val['project_id']]['refunded'] = $val['times'];

            }else{

                $formatRefund[$val['project_id']]['refunding'] = $val['times'];

            }

        }

        //组装数据
        foreach( $list as $key1 => $val1 ){

            if( isset($formatRefund[$val1['project_id']]) ){

                $refundedTotal      = isset($formatRefund[$val1['project_id']]['refunded']) ? count($formatRefund[$val1['project_id']]['refunded']) : 0;
                $refundingTotal     = isset($formatRefund[$val1['project_id']]['refunding']) ? count($formatRefund[$val1['project_id']]['refunding']) : 0;
                $refundedCurrent    = $refundedTotal + 1;
                $refundTotal        = $refundedTotal + $refundingTotal;

                $list[$key1]['refunded_total']  = $refundedTotal;   //已回期数
                $list[$key1]['refunding_total'] = $refundingTotal;  //待回期数
                $list[$key1]['refund_current']  = $refundedCurrent; //当前回款期数
                $list[$key1]['refund_total']    = $refundTotal;     //总期数
                $list[$key1]['interest']        = ToolMoney::formatDbCashDelete($list[$key1]['interest']);      //本期所需回款利息
                $list[$key1]['cash']            = ToolMoney::formatDbCashDelete($list[$key1]['cash']);          //本期所需回款总金额
                $list[$key1]['principal']       = ToolMoney::formatDbCashDelete($list[$key1]['principal']);     //本期所需回款本金
                //$list[$key1]['invest_type']     = empty($list[$key1]['type']) ? 0 : 3;
                $list[$key1]['invest_type']     = $list[$key1]['type']==RefundRecordDb::TYPE_BONUS_RATE ? 3 : $list[$key1]['type'];

            }

            $list[$key1]['project_name'] = isset($projectList[$val1['project_id']]['name']) ? $projectList[$val1['project_id']]['name'] : '';

        }

        return $list;

    }

    /**
     * @return mixed
     * 获取定期项目总收益
     */
    public function getTotalInterest(){

        $model          = new RefundRecordDb();
        $totalInterest  = $model->getTotalInterest();

        $totalInterest = ToolMoney::formatDbCashDelete($totalInterest);

        return self::callSuccess(['totalInterest' => $totalInterest]);
    }

    /**
     * @return array
     * 已回款本息总额
     */
    public function getRefundAmount(){

        $model          = new RefundRecordDb();
        $totalCash  = $model->getRefundAmount();

        $totalCash = ToolMoney::formatDbCashDelete($totalCash);

        return self::callSuccess(['totalCash' => $totalCash]);
    }

    /**
     * @param $userId
     * @return array
     * @desc 通过用户id获取每月的待回款金额(只取当前月之后的12个月的记录)
     */
    public function getRefundPlanByMonthByUserId($userId){

        $db = new RefundRecordDb();

        $refundMonth = $db -> getRefundPlanByMonthByUserId($userId);

        $result = $this -> formatRefundMonth($refundMonth);

        return self::callSuccess($result);

    }

    /**
     * @param $data
     * @return array
     * @desc 格式化通过用户id获取每月的待回款金额(只取当前月之后的12个月的记录)
     */
    public function formatRefundMonth( $data ){

        if(empty($data)){
            return [];
        }

        foreach( $data as $key => $value){

            $data[$key]['total_cash'] = ToolMoney::formatDbCashDelete($value['total_cash']);

            $data[$key]['project_num'] = $value['projectNum'];
        }

        return $data;

    }

    /**
     * @param $userIds
     * @return array
     * @desc 通过userId获取待收本金
     */
    public function getRefundTotalByUserIds( $userIds ){

        if( empty($userIds) ){
            return self::callSuccess(['total_cash'=>0]);
        }

        $userIds = explode(',', $userIds);

        $db = new RefundRecordDb();

        $result = $db -> getRefundTotalByUserIds( $userIds );

        return self::callSuccess($result);

    }

    /**
     * @param $userIds
     * @return array
     * @desc 通过userId获取每个人的待收本金之和
     */
    public function getRefundByUserIds( $userIds ){

        if( empty($userIds) ){
            return self::callSuccess([]);
        }

        $userIds = explode(',', $userIds);

        $db = new RefundRecordDb();

        $result = $db -> getRefundByUserIds( $userIds );

        return self::callSuccess($result);

    }

    /**
     * @return mixed
     * @desc 获取待收本息总额
     */
    public function getRefundingTotal(){

        $db    = new RefundRecordDb();

        $total = $db->getRefundingTotal();

        return  $total;
    }
    /*
     * @param $userId
     * @param $sDate
     * @param $eDate
     * @return array
     * @desc 获取回款中的记录
     */
    public function getRefundProjectIdByTimes($times)
    {

        $refundDb   = new RefundRecordDb();

        $list       = $refundDb->getRefundProjectIdByTimes($times);

        $list       = array_column($list,"project_id");

        return self::callSuccess($list);

    }


    /**
     * 获取指定项目已经还款的id
     */
    public function getRefundedProjectIdByIds($projectIds){


        $db = new RefundRecordDb();

        $list = $db->getRefundedProjectIdByIds($projectIds);

        return ToolArray::arrayToIds($list,'project_id');
    }

    /**
     * @param $times
     * @desc 发送回款成功邮件
     */
    public function sendRefundSuccessByTime($times)
    {

        $sendRes = Cache::get('REFUND_SUCCESS_'.$times);

        if( $sendRes ){

            return '';

        }

        $db = new RefundRecordDb();

        //获取未回款总数
        $count = $db->getRefundCountByTimes($times);

        if( $count < 1 ){

            //获取今日回款人数/金额总数,
            $result = $db->getTodayRefundSuccessByTimes($times);

            RefundLogic::doRefundSuccessNotice($result[0]);

            Cache::put('REFUND_SUCCESS_'.$times, true, 100);

        }

    }

    /**
     * @param $times
     * @return array
     * @desc 还款公告
     */
    public function getArticleNoticeByTimes($times)
    {

        $refundDb = new RefundRecordDb();

        $refundList = $refundDb->getArticleNoticeByTimes($times);

        $return = [];

        if( !empty($refundList) ){

            $projectIds = ToolArray::arrayToIds($refundList);

            $refundStatusList = $refundDb->getRefundStatusListByProjectIds($projectIds);

            $data = [];

            foreach ($refundStatusList as $refund){


                if( $refund['status'] == RefundRecordDb::STATUS_SUCCESS ){

                    $data[$refund['project_id']]['q'] = isset($data[$refund['project_id']]['q']) ? ($data[$refund['project_id']]['q'] + 1) : 1;

                }

                $data[$refund['project_id']]['n'] = isset($data[$refund['project_id']]['n']) ? ($data[$refund['project_id']]['n'] + 1) : 1;

            }

            foreach ($refundList as $key => $project){

                $return[$key]['project_id'] = $project['id'];

                $return[$key]['q'] = isset($data[$project['id']]['q']) ? $data[$project['id']]['q'] : 0;

                $return[$key]['n'] = isset($data[$project['id']]['n']) ? $data[$project['id']]['n'] : 0;

                $return[$key]['refund_type_note'] = Lang::get('messages.PROJECT.REFUND_TYPE_' . $project['refund_type']);

                $return[$key]['product_line_note'] = Lang::get('messages.PROJECT.PRODUCT_LINE_' . $project['product_line']);

                $return[$key]['invest_time_note'] = $project['invest_time'] . Lang::get('messages.PROJECT.TYPE_' . $project['type']);

                $return[$key]['cash_total'] = $project['cash'];

            }

        }

        return self::callSuccess($return);

    }

    /**
     * @desc 获取今日回款的用户
     * @author lgh
     * @return array
     */
    public function getTodayRefundUser(){

        $projectModel = new ProjectModel();

        try{
            $result =  $projectModel->getTodayRefundUser();

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }
        return self::callSuccess($result);
    }

    /**
     * @desc 获取今日回款的用户
     * @author lgh
     * @return array
     */
    public function getRefundListByDate($date=''){

        $refundRecordDb = new RefundRecordDb();

        try{

            $result =  $refundRecordDb->getRefundListByDate($date);

        }catch(\Exception $e){

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);
    }

    /**
     * @param $projectIds
     * @return array|mixed
     * @desc 通过项目id获取利息
     */
    public function getSumInterestByProjectIds($projectIds)
    {

        $projectIds = explode(',', $projectIds);

        $refundDb = new RefundRecordDb();

        $list = $refundDb->getSumInterestByProjectIds($projectIds);

        if( !empty($list) ){

            $data = [];

            foreach ($list as $value){

                if( !isset($data[$value['project_id']]) ){

                    $data[$value['project_id']]['total_interest'] = $value['interest'];

                    $data[$value['project_id']]['bonus_interest'] = 0;

                }else{

                    if( $value['type'] == RefundRecordDb::TYPE_BONUS_RATE ){

                        $data[$value['project_id']]['bonus_interest'] = $value['interest'];

                    }

                    $data[$value['project_id']]['total_interest'] += $value['interest'];

                }

            }

            return self::callSuccess($data);

        }

        return self::callSuccess([]);

    }

    /**
     * @param $projectIds
     * @param $times
     * @return array|bool
     * @desc 项目提前还款卡的短信通知
     */
    public function sendBeforeNoticeListByProjectIdTimes($projectIds, $times){

        if( empty($projectIds) || empty($times) ){

            Log::Error(__METHOD__.'getBeforeListByProjectIdTimesEmpty', ['project_ids' => $projectIds, 'times'=> $times]);

            return false;

        }

        $refundList = $this->getBeforeListByProjectIdsTimes($projectIds, $times);

        if( empty($refundList) ){

            return [];

        }


        $userIds = ToolArray::arrayToIds($refundList, 'user_id');

        $userDb = new UserDb();

        $userList = $userDb->getUserListByUserIds($userIds);

        $userList = ToolArray::arrayToKey($userList);

        $smsModel = new SmsModel();

        foreach ($refundList as $key => $item){

            if( isset($userList[$item['user_id']]) ){

                $msgTpl = Lang::get('messages.SMS_MESSAGE.BEFORE_REFUND_NOTICE');

                $msg = sprintf($msgTpl, $userList[$item['user_id']]['real_name'], $item['project_id'], $item['cash']);

                $res = $smsModel->sendNotice($userList[$item['user_id']]['phone'], $msg);

                if( !isset($res['status']) || !$res['status'] ){

                    Log::Error(__METHOD__.'SendError', ['phone' => $userList[$item['user_id']]['phone'], 'msg' => $msg]);

                }

            }else{

                unset($refundList[$key]);

            }

        }

    }

    /**
     * @param $projectIds
     * @param $times
     * @return array
     * @desc 根据项目id 还款时间获取列表信息
     */
    public function getBeforeListByProjectIdsTimes($projectIds, $times){

        $db = new RefundRecordDb();

        return $db->getBeforeListByProjectIdTimes($projectIds, $times);

    }

    /**
     * @desc    通过多个id获取项目需要还款金额
     * @param   $ids
     * @return  mixed
     *
     */
    public function getProjectNeedRefundById($ids)
    {

        $ids    = explode(',', $ids);

        $refundDb   = new RefundRecordDb();

        $refundList = $refundDb->getProjectNeedFundByProjectIds($ids);

        return  $refundList;

    }

    /**
     * @param array $projectIds
     * @return array
     * @desc 获取回款记录中的各项加息数据
     */
    public static function getInterestTypeByProjectIds( $projectIds =array() )
    {
        $projectIds = explode(',', $projectIds);

        if( empty($projectIds)){

            return self::callSuccess([]);
        }

        $interestList   =   RefundRecordDb::getInterestTypeByProjectIds($projectIds);

        if( empty($interestList) ){

            return self::callSuccess([]);
        }

        $interestList   =   ToolArray::arrayToKey($interestList,'project_id');

        return self::callSuccess($interestList);
    }

    /**
     * @desc  根据时间段获取每天回款总额
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public function getRefundTotalGroupByTime($startTime, $endTime)
    {

        if( empty($startTime) || empty($endTime)){

            return self::callSuccess([]);
        }
        $refundDb = new RefundRecordDb();

        $refundTotalList   =   $refundDb->getRefundTotalGroupByTime($startTime, $endTime);

        if( empty($refundTotalList) ){

            return self::callSuccess([]);
        }

        return self::callSuccess($refundTotalList);
    }

    /**
     * @param string $date
     * @return array
     * @desc 根据时间获取还款的项目id和金额
     */
    public function getRefundProjectIdsAndCashByDate($date=''){

        $refundDb = new RefundRecordDb();

        $refundTotalList   =   $refundDb->getRefundProjectIdsAndCashByDate($date);

        return self::callSuccess($refundTotalList);

    }


}
