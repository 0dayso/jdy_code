<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/21
 * Time: 上午10:46
 * Desc: 回款表
 */

namespace App\Http\Dbs;

use App\Tools\ToolTime;
use Illuminate\Support\Facades\DB;

class RefundRecordDb extends JdyDb
{

    const   STATUS_SUCCESS      = 200,  //已还款
            STATUS_ING          = 600,  //还款中

            TYPE_COMMON         = 0,    //默认值
            TYPE_BONUS_RATE     = 1;    //加息券

    protected $table = 'refund_record';


    /**
     * @param string $times
     * @return mixed
     * @desc 根据回款时间获取回款列表
     */
    public function getRefundListByTimes($times='', $size=200)
    {

        if( empty($times) ){

            $times = ToolTime::dbDate();

        }

        return self::where('times',$times)
            ->where('cash', '>', 0)
            ->where('status', self::STATUS_ING)
            ->orderBy('id')
            ->take($size)
            ->get()
            ->toArray();

    }

    /**
     * @desc 通过日期获取用户的回款记录
     * @param $userId int
     * @param $date string
     * @return mixed
     */
    public function getRefundByDay($userId, $date){

        if( empty($date) ){
            $date = ToolTime::dbDate();
        }

        return self::where('times',$date)
            ->where('cash', '>', 0)
            ->where('user_id', $userId)
            ->orderBy('id')
            ->get()
            ->toArray();
    }

    /**
     * @desc 通过id获取用户回款记录详情
     * @param $id int
     * @return mixed
     */
    public function getRefundDetailById($id){

        return self::where('id', $id)
            ->get()
            ->toArray();
    }

    /**
     * @desc 获取用户加息券回款
     * @author linguanghui
     * @param $investId int
     * @param $times
     * @return array
     */
    public function getRefundBonusAward($investId,$times){

        return self::where('invest_id', $investId)
            ->where('times', $times)
            ->where('type', self::TYPE_BONUS_RATE)
            ->get()
            ->toArray();
    }

    /**
     * @desc 通过投资Id获取用户实际回款的期数排除加息券的
     * @param $userId int
     * @param $investId array
     * @return array
     */
    public function getUserRefundPeriods($userId, $investId = []){

        return self::select('invest_id',\DB::raw('count(`id`) as periods'))
            ->whereIn('invest_id', $investId)
            ->where('user_id', $userId)
            ->where('type','<>', self::TYPE_BONUS_RATE)
            ->groupBy('invest_id')
            ->get()
            ->toArray();
    }

    /**
     * @desc 通过投资id和时间获取当前回款期数
     * @param $userId int
     * @param $investId int
     * @param $times string
     * @param array
     */
    public function getRefundedCurrentPeriods($userId, $investId, $times){

        return self::select(\DB::raw('count(`id`) as current_periods'))
            ->where('invest_id', $investId)
            ->where('user_id', $userId)
            ->where('times','<=', $times)
            ->where('type','<>', self::TYPE_BONUS_RATE)
            ->first()
            ->toArray();
    }



    /**
     * @param $userId
     * @return mixed
     * @desc 回款列表用户
     */
    public static function getRefundTotalByUserId( $userId ){

        return self::join('project', 'project.id','=','refund_record.project_id')
            ->select(\DB::raw('round(round(sum(core_refund_record.`principal`)),2) as principal'),\DB::raw('sum(core_refund_record.`interest`) as interest'),'project.product_line')
            ->where('refund_record.status',self::STATUS_ING)
            ->where('refund_record.user_id',$userId)
            ->groupBy('product_line')
            ->get()->toArray();

    }

    /**
     * @param string $times
     * @return mixed
     * @desc 获取当前时间未回款总数
     */
    public function getRefundCountByTimes($times='')
    {

        if( empty($times) ){

            $times = ToolTime::dbDate();

        }

        return self::where('times',$times)
            ->where('cash', '>', 0)
            ->where('status',self::STATUS_ING)
            ->count('id');

    }

    /**
     * @param $ids
     * @return bool
     * @desc 更新回款状态
     */
    public function updateRefundSuccessByIds($ids)
    {

        return self::whereIn('id', $ids)
                ->where('status', self::STATUS_ING)
                ->update(['status' => self::STATUS_SUCCESS]);

    }

    /**
     * @param $data
     * @return mixed
     * @desc 创建回款记录
     */
    public function addRefundRecord($data)
    {

        return self::insert(
            $data
        );

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 用户已回款总金额
     */
    public static function getRefundInterestByUserId( $userId )
    {

        return self::where('user_id', $userId)
            ->where('status', self::STATUS_SUCCESS)
            ->select(\DB::raw('sum(interest) as refund_interest'), \DB::raw('sum(principal) as refund_principal'))
            ->first();
    }

    /**
     * @param   $userId
     * @return  mixed
     * @desc    用户回款中总金额
     */
    public static function getNoRefundInterestByUserId( $userId )
    {

        return self::where('user_id', $userId)
            ->where('status', self::STATUS_ING)
            ->select(\DB::raw('sum(interest) as refund_interest'), \DB::raw('sum(principal) as refund_principal'))
            ->first();
    }

    /**
     * @param $userId
     * @param $status
     * @param $sDate
     * @param $eDate
     * @return mixed
     * @desc 获取用户的回款列表
     */
    public function getUserRefundListByTimes($userId, $status, $sDate, $eDate)
    {

        return self::where('user_id', $userId)
            ->select('*')
            ->where('status', $status)
            ->where('times', '>=', $sDate)
            ->where('times', '<=', $eDate)
            ->orderBy('times')
            ->get()
            ->toArray();

    }

    /**
     * @param $projectIds
     * @param int $status
     * @return mixed
     * @desc 获取项目的回款总数
     */
    public function getRefundTotalByProjectIdsStatus($projectIds, $status=self::STATUS_SUCCESS)
    {

        return self::whereIn('project_id', $projectIds)
            ->where('status', $status)
            ->groupBy('project_id','times')
            ->get()
            ->toArray();

    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 获取项目的总回款期数
     */
    public function getRefundTotalByProjectIds($projectIds)
    {

        return self::whereIn('project_id', $projectIds)
            ->groupBy('project_id','times')
            ->get()
            ->toArray();

    }

    /**
     * 获取定期项目总收益
     */
    public function getTotalInterest(){

        return self::where('status',self::STATUS_SUCCESS)
            ->sum('interest');
    }

    /**
     * @return mixed
     * 获取已回款总本息金额
     */
    public function getRefundAmount(){

        return self::where('status',self::STATUS_SUCCESS)
            ->sum('cash');
    }

    /**
     * @return mixed
     * 获取今日用户已还款列表
     */
    public function getTodayRefundList(){

        $date = ToolTime::dbDate();

        return self::select('user_id', DB::raw('SUM(cash) as total_cash'), DB::raw('count(distinct project_id) as project_id_total'))
            ->where('times',$date)
            ->where('status',self::STATUS_SUCCESS)
            ->groupBy('user_id')
            ->get()
            ->toArray();

    }

    /**
     * @param string $date
     * @return mixed
     * @desc 获取还款的项目id和金额
     */
    public function getRefundProjectIdsAndCashByDate($date=''){

        $date = $date ? $date : ToolTime::dbDate();

        return self::select('project_id', DB::raw('SUM(cash) as total_cash'))
            ->where('times',$date)
            ->where('status',self::STATUS_SUCCESS)
            ->groupBy('project_id')
            ->get()
            ->toArray();

    }

    /**
     * @param string $date
     * @param string $status
     * @return mixed
     * @desc 通过时间和状态条件获取回款列表
     */
    public function getRefundListByDate($date='', $status=''){

        $date = $date ? $date : ToolTime::dbDate();

        $status = $status ? $status : self::STATUS_ING;

        return self::select('user_id', DB::raw('SUM(cash) as total_cash'), DB::raw('count(distinct project_id) as project_id_total'))
            ->where('times', $date)
            ->where('status', $status)
            ->groupBy('user_id')
            ->get()
            ->toArray();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 格式化通过用户id获取每月的待回款金额(只取当前月之后的12个月的记录)
     */
    public function getRefundPlanByMonthByUserId( $userId ){

        $result = self::select(DB::raw("SUM(cash) as total_cash, DATE_FORMAT(times, '%Y-%m') as months"), DB::raw("count(distinct(project_id)) as projectNum"))
            ->where('user_id', $userId)
            ->where('status', self::STATUS_ING)
            ->groupBy('months')
            ->get()
            ->toArray();

        return $result;
    }

    /**
     * @param $userIds
     * @return array
     * @desc 通过userId获取待收本金
     */
    public function getRefundTotalByUserIds( $userIds ){

        $result = self::select(DB::raw("SUM(principal) as total_cash"))
            ->whereIn('user_id', $userIds)
            ->where('status', self::STATUS_ING)
            ->first();

        $result = empty($result)?['total_cash'=>0]:$result->toArray();

        return $result;

    }

    /**
     * @param $userIds
     * @return array
     * @desc 通过userId获取待每个人的待收本金之合
     */
    public function getRefundByUserIds( $userIds ){

        $result = self::select(DB::raw("user_id, SUM(principal) as total_cash"))
            ->whereIn('user_id', $userIds)
            ->where('status', self::STATUS_ING)
            ->groupBy('user_id')
            ->get()
            ->toArray();

        return $result;

    }

    /**
     * @param $investId
     * @param $userId
     * @param $projectId
     * @return array
     * @desc 闪电付息投资回款利息
     */
    public function getSdfRefundInterestByInvestId( $investId, $userId, $projectId ){

        $result = self::where('invest_id', $investId)
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->where('status', self::STATUS_ING)
            ->where('interest', '>', 0)
            ->first();

        $result = empty($result)?[]:$result->toArray();

        return $result;

    }
    /**
     * @param $times
     * @return mixed
     * @desc 获取某天回款的项目
     */
    public function getRefundProjectIdByTimes($times)
    {
        return self::where('times', $times)
                    ->groupBy('project_id')
                    ->get()
                    ->toArray();

    }

    /**
     * @return mixed
     * @desc 获取待收本息总额
     */
    public function getRefundingTotal(){

        $result = self::where('status', self::STATUS_ING)
                    ->sum('cash');

        return $result;
    }

    /**
     * @param $investId
     * @return mixed
     * @desc 通过invest 获取加息券的利息信息
     */
    public function getRateInfoByInvestId($investId)
    {

        return $this->where('invest_id', $investId)
            ->where('type', self::TYPE_BONUS_RATE)
            ->first();

    }

    /**
     * @param $investId
     * @return mixed
     * @desc 通过invest id 获取回款信息
     */
    public function getCommonInfoByInvestId($investId)
    {

        return $this->where('invest_id', $investId)
            ->where('type', self::TYPE_COMMON)
            ->first();

    }

    /**
     * @param $investId
     * 根据投资ID获取还款计划列表
     */
    public static function getByInvestId($investId){

        return self::where('invest_id',$investId)
            ->orderBy('times','asc')
            ->get()
            ->toArray();

    }

    /**
     * @param $investId
     * 根据投资ID获取还款计划列表
     */
    public static function getByInvestIds($investIds){

        return self::select(\DB::raw('distinct invest_id'))
            ->whereIn('invest_id',$investIds)
            ->orderBy('times','asc')
            ->get()
            ->toArray();

    }

    /**
     * @param $projectIds
     * @return mixed
     * 获取给定项目生成还款计划的id
     */
    public function getRefundedProjectIdByIds($projectIds){

        return self::select(\DB::raw('distinct project_id'))
            ->whereIn('project_id',$projectIds)
            ->get()
            ->toArray();
    }

    /**
     * @desc 获取
     * @param $userId
     * @param $investIds
     * @return mixed
     */
    public function getUserNextRefundByInvests($userId,$investIds){
        $date = date('Y-m-d');
        return self::select('invest_id','times','cash')
            ->where('user_id',$userId)
            ->whereIn('invest_id',$investIds)
            ->where('times','>',$date)
            ->groupBy('invest_id')
            ->get()
            ->toArray();
    }

    /**
     * @param $times
     * @return mixed
     * @desc 根据时间获取今日回款成功的用户数,回款金额
     */
    public function getTodayRefundSuccessByTimes($times)
    {

        return self::select(DB::raw("SUM(cash) as cash, times"), DB::raw("count(distinct(user_id)) as user_id_num"))
            ->where('times', $times)
            ->where('status', self::STATUS_SUCCESS)
            ->get()
            ->toArray();

    }

    /**
     * @desc 获取某一时间段内每天的回款总额
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getRefundTotalGroupByTime($startTime, $endTime){

        return self::select('times',DB::raw("SUM(cash) as cash"), DB::raw("count(distinct(user_id)) as user_id_num"), \DB::raw("COUNT(id) as totalNum"))
            ->where('times', '>=', $startTime)
            ->where('times', '<', $endTime)
            ->groupBy('times')
            ->get()
            ->toArray();
    }


    /**
     * @param $times
     * @return mixed
     * @desc 获取回款公告
     */
    public function getArticleNoticeByTimes($times)
    {

        return self::join('project', 'project.id', '=', 'refund_record.project_id')
            ->select(\DB::raw('sum(core_refund_record.`cash`) as cash'), 'project.product_line', 'project.id', 'project.invest_time', 'project.refund_type', 'project.type')
            ->where('refund_record.status', self::STATUS_SUCCESS)
            ->where('refund_record.times', $times)
            ->groupBy('project.id')
            ->get()
            ->toArray();
    }

    /*
     * @param $ids
     * @return mixed
     * 删除还款计划
     */
    public static function deleteRefund($ids)
    {

        return self::whereIn('id', $ids)
            ->delete();

    }

    /**
     * @param $investId
     * @return mixed
     * @desc 通过投资Id获取已回款期
     */
    public static function getRefundedTimes($investId)
    {

        return self::select(\DB::raw('count(id) as num'), \DB::raw('sum(principal) as refunded_principal'), 'invest_id')
            ->whereIn('invest_id', $investId)
            ->where('status', self::STATUS_SUCCESS)
            ->groupBy('invest_id')
            ->get()
            ->toArray();
    }

    /**
     * @param $projectIds
     * @param $times
     * @return mixed
     * @desc
     */
    public function getRefundStatusListByProjectIds($projectIds)
    {

        return self::select('id', 'project_id', 'status', 'times')
            ->whereIn('project_id', $projectIds)
            ->groupBy('times')
            ->groupBy('project_id')
            ->get()
            ->toArray();
    }

    /**
     * @param $projectId
     * @param $investId
     * @param $userId
     * @param $createTime
     * @return mixed
     * @desc 债转后的利息
     */
    public static function getCreditAssignInterest($projectId, $investId, $userId,$createTime)
    {

        return self::where('invest_id', $investId)
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->where('times', '>=', ToolTime::getDate($createTime))
            ->sum('interest');
    }


    /**
     * @param $investId
     * @return array
     * @desc 预计首期回款时间
     */
    public function getFirstRefundingDateByInvestId($investId){

        $result = self::select('times')
            ->where('invest_id', $investId)
            ->where('status', RefundRecordDb::STATUS_ING)
            ->orderBy('times')
            ->first();

        return $this->dbToArray($result);

    }

    /**
     * @param $investId
     * @return mixed
     * @desc 总利息
     */
    public function getInterestRefundIngByInvestId($investId){

        return self::where('invest_id', $investId)
            ->where('status', RefundRecordDb::STATUS_ING)
            ->sum('interest');

    }

    /**
     * @param $investId
     * @return mixed
     * @desc 获取还款中的列表信息
     */
    public function getRefundingListByInvestId($investId){

        return self::where('invest_id', $investId)
            ->where('status', RefundRecordDb::STATUS_ING)
            ->get()
            ->toArray();
    }

    /**
     * @desc 获取当日回款的用户信息
     * @return mixed
     */
    public function getTodayRefundUser(){

        return self::join('user', 'user.id', '=', 'refund_record.user_id')
            ->select('refund_record.user_id', 'user.phone',\DB::raw('sum(cash) as refund_cash'))
            ->where('times',ToolTime::getDateAfterCurrent())
            ->where('status_code', UserDb::STATUS_ACTIVE)
            ->groupBy('refund_record.user_id')
            ->get()
            ->toArray();
    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 获取利息总和
     */
    public function getSumInterestByProjectIds($projectIds){

        return self::select(\DB::raw('sum(interest) as interest'), 'project_id', 'type')
            ->whereIn('project_id', $projectIds)
            ->groupBy('project_id')
            ->groupBy('type')
            ->get()
            ->toArray();

    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 获取需要还款的项目列表
     */
    public function getNeedBeforeRefundListByProjectIds( $projectIds ){

        $sql = "select DISTINCT r.invest_id,r.project_id,i.`created_at` as invest_time,i.`cash` from `core_refund_record` r left join `core_invest` i on r.invest_id=i.id and r.user_id=i.user_id and r.project_id=i.project_id where r.project_id in ({$projectIds}) and r.`status`=".self::STATUS_ING;

        return app('db')->select($sql);

    }

    /**
     * @param $projectIds
     * @param $times
     * @return array
     * @desc 获取提前还款的列表信息
     */
    public function getBeforeListByProjectIdTimes($projectIds, $times)
    {

        return self::select(\DB::raw('sum(cash) as cash'), 'project_id', 'user_id')
            ->where('before_refund', ProjectDb::BEFORE_REFUND)
            ->where('times', $times)
            ->whereIn('project_id', $projectIds)
            ->groupBy('user_id')
            ->groupBy('project_id')
            ->get()
            ->toArray();

    }

    /**
     * @param $projectId
     * @return mixed
     * @desc 项目回款计划
     */
    public function getRefundPlanByMonthByProjectId($projectId){

        return self::select(\DB::raw('DISTINCT times as refund_time'),'project_id')
            ->where('project_id', $projectId)
            ->orderBy('times', 'asc')
            ->get()
            ->toArray();

    }

    /**
     * @desc    定期项目-在投中项目 资金统计，利息统计
     * @author  @llper
     */

    public function getFundStatisticsRefund(){

        $result = self::select(\DB::raw('sum(`principal`) as principal'),\DB::raw('sum(`interest`) as interest') )
            ->where('status',self::STATUS_ING)->first();

        return self::dbToArray($result);

    }

    /**
     * @desc    获取还款中的项目总额
     * @param   $projectIds
     * @return  mixed
     *
     */
    public function getProjectNeedFundByProjectIds($projectIds){

        return self::select(\DB::raw('sum(principal) as principal'), 'project_id', 'type')
            ->whereIn('project_id', $projectIds)
            ->where('status', RefundRecordDb::STATUS_ING)
            ->groupBy('project_id')
            ->get()
            ->toArray();

    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 获取回款记录中的各项加息数据
     */
    public static function getInterestTypeByProjectIds( $projectIds )
    {
        $dbObj  = self::select(
        'project_id',\DB::raw('sum(if(status='.self::STATUS_SUCCESS.', interest,0)) as refunded_cash ,
         sum(if(status='.self::STATUS_ING.', interest, 0)) as refunding_cash ,
         sum(if(type='.self::TYPE_COMMON.', interest, 0)) as invest_cash,
         sum(if(type='.self::TYPE_BONUS_RATE.', interest, 0)) as rate_cash ,
         sum(interest) as total_cash')
        );

        if( !empty($projectIds) ){

            $dbObj  =   $dbObj->whereIn('project_id',$projectIds);
        }

        $return =   $dbObj->whereIn('status',[self::STATUS_ING,self::STATUS_SUCCESS])
                        ->groupBy('project_id')
                        ->get()
                        ->toArray();

        return $return;
    }

    /**
     * @desc    获取出借人数
     *    去重（还款中的定期投资人数，活期账户余额大于0的用户数）
     **/
    public function getInvestUserNum(){
        $sql    = "select count(user_id) AS investUserNum from
                (
                  select DISTINCT user_id from core_refund_record   where status = ".self::STATUS_ING."
                  UNION
                  select DISTINCT user_id from core_current_account where cash > 10
                ) AS t1
                ";

        return app('db')->select($sql);
    }

    /**
     * @desc    获取还款中的项目
     * @author  @linglu
     *
     */
    public function getRefundProject(){

        return self::select(\DB::raw('sum(principal) as principal'), 'project_id')
            ->where('status',   RefundRecordDb::STATUS_ING)
            ->groupBy('project_id')
            ->get()
            ->toArray();

    }

}
