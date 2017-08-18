<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/12
 * Time: 下午4:21
 * Desc: 模块回款记录相关
 */

namespace App\Http\Controllers\Module\Refund;

use App\Http\Controllers\Controller;
use App\Http\Logics\Refund\RefundRecordLogic;
use App\Http\Models\Common\IncomeModel;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;

class RefundRecordController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/refund/refunded",
     *   tags={"Refund"},
     *   summary="获取已回款的列表",
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="s_date",
     *      in="formData",
     *      description="开始时间",
     *      required=true,
     *      type="string",
     *      default="2016-06-01",
     *   ),
     *  @SWG\Parameter(
     *      name="e_date",
     *      in="formData",
     *      description="结束时间",
     *      required=true,
     *      type="string",
     *      default="2016-06-30",
     *   ),
     *  @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="签名",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取数据成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */
    public function getRefundedList( Request $request )
    {

        $sDate = $request->input('s_date');

        $eDate = $request->input('e_date');

        $userId = $request->input('user_id');

        $logic = new RefundRecordLogic();

        $list = $logic->getRefundedList($userId, $sDate, $eDate);

        return self::returnJson($list);

    }

    /**
     * @SWG\Post(
     *   path="/refund/refunding",
     *   tags={"Refund"},
     *   summary="获取回款中的列表",
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="s_date",
     *      in="formData",
     *      description="开始时间",
     *      required=true,
     *      type="string",
     *      default="2016-06-01",
     *   ),
     *  @SWG\Parameter(
     *      name="e_date",
     *      in="formData",
     *      description="结束时间",
     *      required=true,
     *      type="string",
     *      default="2016-06-30",
     *   ),
     *  @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="签名",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取数据成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */
    public function getRefundingList( Request $request )
    {

        $sDate = $request->input('s_date');

        $eDate = $request->input('e_date');

        $userId = $request->input('user_id');

        $logic = new RefundRecordLogic();

        $list = $logic->getRefundingList($userId, $sDate, $eDate);

        return self::returnJson($list);

    }


    /**
     * @SWG\Post(
     *   path="/refund/getRefundByDay",
     *   tags={"Refund"},
     *   summary="获取用户某天回款记录",
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="date",
     *      in="formData",
     *      description="日期",
     *      required=true,
     *      type="string",
     *      default="2016-06-01",
     *   ),
          *  @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="签名",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取数据成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */

    public function getRefundByDay(Request $request){

        $userId = $request->input('user_id');
        $date = $request->input('date');

        $logic = new RefundRecordLogic();

        $list = $logic->getRefundRecordByDay($userId, $date);

        return self::returnJson($list);


    }

    /**
     * @SWG\Post(
     *   path="/refund/getRefundDetailById",
     *   tags={"Refund"},
     *   summary="通过id获取用户回款记录详情",
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *  @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="签名",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取数据成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */

    public function getRefundDetailById(Request $request){

        $id = $request->input('id',0);

        $refundLogic = new RefundRecordLogic();

        $detail = $refundLogic->getRefundDetailById($id);

        return self::returnJson($detail);
    }


    /**
     * @SWG\Post(
     *   path="/refund/getTotalInterest",
     *   tags={"Refund"},
     *   summary="定期投资已回款收益",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="定期投资已回款收益成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="定期投资已回款收益失败。",
     *   )
     * )
     */
    public function getTotalInterest(){

        $logic         = new RefundRecordLogic();
        $result        = $logic->getTotalInterest();


        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/refund/getRefundAmount",
     *   tags={"Refund"},
     *   summary="获取定期已回款本息",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="定期投资已回款收益成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="定期投资已回款收益失败。",
     *   )
     * )
     */
    public function getRefundAmount(){

        $logic         = new RefundRecordLogic();
        $result        = $logic->getRefundAmount();

        return self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/refund/getRefundPlanByMonthByUserId",
     *   tags={"Refund"},
     *   summary="通过用户id获取每月的待回款金额",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="每月的待回款金额获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="每月的待回款金额获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 通过用户id获取每月的待回款金额(只取当前月之后的12个月的记录)
     */
    public function getRefundPlanByMonthByUserId(Request $request){

        $userId = $request -> input('user_id', 0);

        $logic         = new RefundRecordLogic();

        $result        = $logic->getRefundPlanByMonthByUserId($userId);

        return self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/refund/refundPlanByProjectId",
     *   tags={"Refund"},
     *   summary="根据项目ID和投资金额获取回款信息",
     *   @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="项目ID",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="投资金额",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="invest_time",
     *      in="formData",
     *      description="投资时间",
     *      required=false,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取数据成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取数据失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 根据项目ID和投资金额获取回款信息
     */
    public function getRefundPlanByProjectId( Request $request){

        $projectId = $request->input('project_id',0);

        $cash      = $request->input('cash',0);

        $investTime = $request->input('invest_time','');

        $incomeModel = new IncomeModel();

        $result      = $incomeModel->getIncome($projectId,$cash,$investTime);

        return self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/refund/getRefundTotalByUserIds",
     *   tags={"Refund"},
     *   summary="通过多个用户id获取待回款本金之和",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="string",
     *      default="1,2,3",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="待回款本金获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="待回款本金获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 通过多个用户id获取待回款本金之和
     */
    public function getRefundTotalByUserIds(Request $request){

        $userIds = $request -> input('user_id', []);

        $logic = new RefundRecordLogic();

        $result = $logic->getRefundTotalByUserIds($userIds);

        return self::returnJson($result);

    }
    /**
     * @SWG\Post(
     *   path="/refund/getRefundProjectIdByTimes",
     *   tags={"Refund"},
     *   summary="通过固定时间待回款的项目ID",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *     @SWG\Parameter(
     *      name="times",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="2016-09-01",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="数据获取正常。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="数据获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 活动某天的回款的项目
     */
    public function getRefundProjectIdByTimes(Request $request){

        $times = $request -> input('times', date('Y-m-d'));

        $logic = new RefundRecordLogic();

        $result = $logic->getRefundProjectIdByTimes($times);

        return self::returnJson($result);

    }


    /**
     * @SWG\POST(
     *  path="/refund/getRefundingTotal",
     *  tags={"Refund"},
     *  summary="获取待收本息总额",
     *  @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function getRefundingTotal(){

        $logic = new RefundRecordLogic();

        $total = $logic->getRefundingTotal();

       return self::returnJson(['total'=>$total]);
    }

    /**
     * @SWG\Post(
     *   path="/refund/getRefundByUserIds",
     *   tags={"Refund"},
     *   summary="通过多个用户id获取每个人的待回款本金之和",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="string",
     *      default="1,2,3",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="待回款本金获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="待回款本金获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 通过多个用户id获取待回款本金之和
     */
    public function getRefundByUserIds(Request $request){

        $userIds = $request -> input('user_id', []);

        $logic = new RefundRecordLogic();

        $result = $logic->getRefundByUserIds($userIds);

        return self::returnJson($result);

    }

    public function getArticleNoticeByTimes( Request $request ){

        $times = $request->input('times');

        $logic = new RefundRecordLogic();

        $result = $logic->getArticleNoticeByTimes($times);

        return self::returnJson($result);

    }
    /**
     * @SWG\Post(
     *   path="/refund/getTodayRefundUser",
     *   tags={"Refund"},
     *   summary="获取今日回款的用户",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="今日回款的用户获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="今日回款的用户获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     */
    public function getTodayRefundUser(){

        $refundLogic = new RefundRecordLogic();

        $result = $refundLogic->getTodayRefundUser();

        return self::returnJson($result);
    }

    /**
     * @param Request $request
     * @desc 通过项目ids获取利息
     */
    public function getSumInterestByProjectIds( Request $request ){

        $projectIds = $request->input('project_ids');

        $refundLogic = new RefundRecordLogic();

        $list = $refundLogic->getSumInterestByProjectIds($projectIds);

        self::returnJson($list);

    }

    /**
     * @SWG\Post(
     *   path="/refund/getInterestTypeByProjectIds",
     *   tags={"Refund"},
     *   summary="根据项目id获取回款利息的明细",
     *   @SWG\Parameter(
     *      name="project_ids",
     *      in="formData",
     *      description="项目ID集合",
     *      required=true,
     *      type="string",
     *      default="2003,2510",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="今日回款的用户获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="今日回款的用户获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @desc 通过项目ids获取利息
     */
    public function getInterestTypeByProjectIds( Request $request ){

        $projectIds = $request->input('project_ids');

        $refundLogic= new RefundRecordLogic();

        $list       = $refundLogic->getInterestTypeByProjectIds($projectIds);

        self::returnJson($list);

    }

    /**
     * @SWG\Post(
     *   path="/refund/getRefundTotalGroupByTime",
     *   tags={"Refund"},
     *   summary="根据时间段获取每天回款总额",
     *   @SWG\Parameter(
     *      name="start_time",
     *      in="formData",
     *      description="开始时间",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *    @SWG\Parameter(
     *      name="end_time",
     *      in="formData",
     *      description="结束时间",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="每天回款总额获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="每天回款总额获取失败。",
     *   )
     * )
     */
    public function getRefundTotalGroupByTime(Request $request){

        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        $refundLogic= new RefundRecordLogic();

        $list       = $refundLogic->getRefundTotalGroupByTime($startTime, $endTime);

        self::returnJson($list);

    }

    /**
     * @SWG\Post(
     *   path="/refund/getRefundUserByDate",
     *   tags={"Refund"},
     *   summary="根据时间获取回款的用户",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="date",
     *      in="formData",
     *      description="时间",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     */
    public function getRefundUserByDate( Request $request ){

        $date = $request->input('date');

        $refundLogic = new RefundRecordLogic();

        $result = $refundLogic->getRefundListByDate($date);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/refund/getRefundProjectIdsAndCashByDate",
     *   tags={"Refund"},
     *   summary="根据时间获取还款的项目id和金额",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="date",
     *      in="formData",
     *      description="时间",
     *      required=true,
     *      type="string",
     *      default="",
     *   ),
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     */
    public function getRefundProjectIdsAndCashByDate( Request $request ){

        $date = $request->input('date');

        $refundLogic = new RefundRecordLogic();

        $result = $refundLogic->getRefundProjectIdsAndCashByDate($date);

        return self::returnJson($result);

    }




}
