<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/03/04
 * Time: 下午 17:16
 */

namespace App\Http\Controllers\AppApi\V4_0\User;

use Illuminate\Http\Request;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\AppLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Logics\Project\RefundRecordLogic;


class RefundRecordController extends AppController
{
    /**
     * @SWG\Post(
     *   path="/refund_record",
     *   tags={"APP-UserRefund:用户回款相关"},
     *   summary="App4.0回款计划-日历页面 [User\RefundRecordController@refundRecord]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="6f6b568f305d49a65cedb1bf3625c380167f645d",
     *   ),
     *   @SWG\Parameter(
     *      name="month",
     *      in="formData",
     *      description="月份",
     *      required=true,
     *      type="string",
     *      default="2017-01",
     *   ),
     *   @SWG\Parameter(
     *      name="date",
     *      in="formData",
     *      description="日期",
     *      required=true,
     *      type="string",
     *      default="2017-01-03",
     *   ),

     *   @SWG\Response(
     *     response=200,
     *     description="回款计划获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="回款计划获取失败。",
     *   )
     * )
     */

    public function refundRecord(Request $request){

        $refundRecordData = [];

        $userId = $this->getUserId();

        $refundRecordLogic = new RefundRecordLogic();

        $month =  $request->input('month', '');
        $date =  $request->input('date', '');

        //获取本月的回款数据
        $refundRecordData = $refundRecordLogic->refundPlanByDate($userId, $month);
        #print_r($refundRecordData);exit;


        if($refundRecordData['status'] == true){

            $refundMonthAmount  = $refundRecordLogic->getRefundMonthAmount($refundRecordData['data']); //获取本月回款金额统计数据
            $monthAmount  = $refundRecordLogic->formatAppV4RefundAmountData($refundMonthAmount); //格式化本月回款统计数据

            //本月回款金额数据统计金额
            $refundMonthAmountData =[
                'refunded_cash_note' =>$monthAmount['refunded_cash_note'],
                'refunded_cash' =>$monthAmount['refunded_cash'],
                'refund_cash_note' =>$monthAmount['refund_cash_note'],
                'refund_cash' =>$monthAmount['refund_cash'],
                'refund_amount_unit' =>$monthAmount['refund_amount_unit'],
                'month_all_button' =>$monthAmount['month_all_button'],
                ];

            //回款时间格式化
            $refundDate = $refundRecordLogic->formatAppV4RefundDate($refundRecordData['data']);

            //用户当日回款记录列表
            $dayRefundList = $refundRecordLogic->getRefundListByDay($userId, $date);
            $dayRefundList = $refundRecordLogic->formatAppV4RefundDayList($dayRefundList);

            $refundRecordData = [
                'refund_amount_data' => $refundMonthAmountData,
                'refund_date' => isset($refundDate['refund_date']) ? $refundDate['refund_date'] : [],
                'refunded_date' => isset($refundDate['refunded_date']) ? $refundDate['refunded_date'] : [],
                'day_refund_list' => $dayRefundList,
                ];
            return AppLogic::callSuccess($refundRecordData);
        }

    }

    /**
     * @SWG\Post(
     *   path="/month_refund_record",
     *   tags={"APP-UserRefund:用户回款相关"},
     *   summary="App4.0用户本月全部回款 [User\RefundRecordController@monthRefundRecord]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="4.0.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="6f6b568f305d49a65cedb1bf3625c380167f645d",
     *   ),
     *   @SWG\Parameter(
     *      name="month",
     *      in="formData",
     *      description="月份",
     *      required=true,
     *      type="string",
     *      default="2017-01",
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="类型",
     *      required=true,
     *      type="string",
     *      default="refund",
     *   ),

     *   @SWG\Response(
     *     response=200,
     *     description="回款计划获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="回款计划获取失败。",
     *   )
     * )
     */

    public function monthRefundRecord(Request $request){

        $monthRefundRecordData = [];

        $userId = $this->getUserId();

        $refundRecordLogic = new RefundRecordLogic();

        $month =  $request->input('month', '');
        $type  =  $request->input('type', 'refund');

        //获取本月的回款数据
        $monthRefundRecordData = $refundRecordLogic->refundPlanByDate($userId, $month);

        //格式化用户本月全部回款记录
        $monthRefundRecordData = $refundRecordLogic->formatAppV4MonthRefundList($monthRefundRecordData, $month);

        $monthRefundRecordData = isset($monthRefundRecordData[$type]) ?  $monthRefundRecordData[$type] : [];
        return AppLogic::callSuccess($monthRefundRecordData);
    }

    /**
     * @SWG\Post(
     *   path="/refund_detail",
     *   tags={"APP-UserRefund:用户回款相关"},
     *   summary="App4.0用户回款详情 [User\RefundRecordController@refundRecordDetail]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="2.2.3",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="6f6b568f305d49a65cedb1bf3625c380167f645d",
     *   ),
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="回款记录id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="回款计划获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="回款计划获取失败。",
     *   )
     * )
     */

    public function refundRecordDetail(Request $request){

        $userId = $this->getUserId();

        if(empty($userId)){
            return AppLogic::callError(AppLogic::CODE_NO_USER_ID);
        }

        $id = $request->input('id',0);

        $refundRecordLogic = new RefundRecordLogic();

        $refundDetail = $refundRecordLogic->getRefundRecordById($id);

        $refundDetail = $refundRecordLogic->formatAppV4RecordDetail($refundDetail);

        return AppLogic::callSuccess($refundDetail);
    }
}
