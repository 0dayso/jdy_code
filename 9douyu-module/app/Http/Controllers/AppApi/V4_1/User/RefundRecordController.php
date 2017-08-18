<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/5/10
 * Time: 上午9:45
 */

namespace App\Http\Controllers\AppApi\V4_1\User;

use App\Http\Models\Common\CoreApi\RefundModel;
use Illuminate\Http\Request;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\AppLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Logics\Project\RefundRecordLogic;


class RefundRecordController extends AppController
{
    /**
     * @SWG\Post(
     *   path="/refund_record_day",
     *   tags={"APP-UserRefund:用户回款相关"},
     *   summary="App4.1回款计划-用户指定日期回款 [User\RefundRecordController@dayRefundRecord]",
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
     *      default="4.1.0",
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
     *      default="2017-06",
     *   ),
     *   @SWG\Parameter(
     *      name="date",
     *      in="formData",
     *      description="日期",
     *      required=true,
     *      type="string",
     *      default="2017-06-10",
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

    public function dayRefundRecord(Request $request){

        $monthRefundRecordData = [];

        $userId = $this->getUserId();

        $refundRecordLogic = new RefundRecordLogic();

        $month =  $request->input('month', '');
        $date =  $request->input('date', '');

        //获取本月的回款数据
        $monthRefundRecordData = $refundRecordLogic->refundPlanByDate($userId, $month);
        //回款时间格式化
        $refundDate = $refundRecordLogic->formatAppV4RefundDate($monthRefundRecordData['data']);

        if($monthRefundRecordData['status'] == true){

            $refundMonthAmount  = $refundRecordLogic->getRefundMonthAmount($monthRefundRecordData['data']); //获取本月回款金额统计数据
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

            //用户当日回款记录列表
            $dayRefundList = $refundRecordLogic->getRefundListByDay($userId, $date);

            $refund   = [];
            $refunded = [];

            if(!empty($dayRefundList)){

                foreach($dayRefundList as $k => $v){
                    if($v['status'] == RefundModel::STATUS_ING){
                        $refund[] = $v;
                    }elseif($v['status'] == RefundModel::STATUS_SUCCESS){
                        $refunded[] = $v;
                    }
                }

            }

            $refundList = $refundRecordLogic->formatAppV41RefundDayList($refund);
            $refundedList = $refundRecordLogic->formatAppV41RefundDayList($refunded);

            $refundRecordData = [
                'refund_amount_data' => $refundMonthAmountData,
                'refund_date' => isset($refundDate['refund_date']) ? $refundDate['refund_date'] : [],
                'refunded_date' => isset($refundDate['refunded_date']) ? $refundDate['refunded_date'] : [],
                'refund_list' => $refundList,
                'refunded_list' => $refundedList,
            ];
            return AppLogic::callSuccess($refundRecordData);
        }

    }

    /**
     * @SWG\Post(
     *   path="/refund_record_month",
     *   tags={"APP-UserRefund:用户回款相关"},
     *   summary="App4.1用户本月全部回款 [User\RefundRecordController@monthRefundRecord]",
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
     *      default="4.1.0",
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
     *      default="2017-06",
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

        $userId = $this->getUserId();

        $refundRecordLogic = new RefundRecordLogic();

        $month =  $request->input('month', '');

        //获取本月的回款数据
        $monthRefundRecordData = $refundRecordLogic->refundPlanByDate($userId, $month);

        $refundRecordData = [];

        if($monthRefundRecordData['status'] == true){

            $refundMonthAmount  = $refundRecordLogic->getRefundMonthAmount($monthRefundRecordData['data']); //获取本月回款金额统计数据
            $monthAmount  = $refundRecordLogic->formatAppV4RefundAmountData($refundMonthAmount); //格式化本月回款统计数据

            //本月回款金额数据统计金额
            $refundMonthAmountData =[
                'refunded_cash_note' =>$monthAmount['refunded_cash_note'],
                'refunded_cash' =>$monthAmount['refunded_cash'],
                'refund_cash_note' =>$monthAmount['refund_cash_note'],
                'refund_cash' =>$monthAmount['refund_cash'],
                'refund_amount_unit' =>$monthAmount['refund_amount_unit'],
            ];

            //回款时间格式化
            $refundDate = $refundRecordLogic->formatAppV4RefundDate($monthRefundRecordData['data']);

            //回款数据格式化
            $refundList = $refundRecordLogic->formatAppV41RefundDayList($monthRefundRecordData['data']['refund']);
            $refundedList = $refundRecordLogic->formatAppV41RefundDayList($monthRefundRecordData['data']['refunded']);

            $refundRecordData = [
                'refund_amount_data' => $refundMonthAmountData,
                'refund_date' => isset($refundDate['refund_date']) ? $refundDate['refund_date'] : [],
                'refunded_date' => isset($refundDate['refunded_date']) ? $refundDate['refunded_date'] : [],
                'refund_list' => $refundList,
                'refunded_list' => $refundedList,
            ];

        }

        return AppLogic::callSuccess($refundRecordData);

    }

}
