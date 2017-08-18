<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/7
 * Time: 下午4:48
 */

namespace App\Http\Controllers\App\User;


use App\Http\Controllers\App\UserController;
use App\Http\Logics\Project\RefundRecordLogic;
use Illuminate\Http\Request;

class RefundPlanController extends UserController
{


    /**
     * @SWG\Post(
     *   path="/refund_plan",
     *   tags={"APP-UserRefund:用户回款相关"},
     *   summary="回款计划 [User\RefundPlanController@refundPlan]",
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
     *      default="653030e9f8e4f6559669386dfe4f56d4",
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
    /**
     * @param Request $request
     * @return array
     * @desc 回款计划
     */
    public function refundPlan(Request $request){

        $userId = $this -> getUserId();

        $logic = new RefundRecordLogic();

        $result = $logic -> getRefundPlanByMonthByUserId( $userId );

        if(empty($result['data']['data'])){
            $result['data']['data'] = [[]];
        }

        return self::appReturnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/refund_plan_by_date",
     *   tags={"APP-UserRefund:用户回款相关"},
     *   summary="当月回款具体信息 [User\RefundPlanController@refundPlanByDate]",
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
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="date",
     *      in="formData",
     *      description="年-月 2016-07",
     *      required=true,
     *      type="string",
     *      default="2016-07",
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
    /**
     * @param Request $request
     * @return array
     * @desc 当月回款具体信息
     */
    public function refundPlanByDate(Request $request){

        $userId = $this -> getUserId();

        $date  = $request -> input('date');

        $logic = new RefundRecordLogic();

        $result = $logic -> refundPlanByDate( $userId, $date );

        $result['data']['refunded'] = empty($result['data']['refunded'])?[[]]:$result['data']['refunded'];
        $result['data']['refund'] = empty($result['data']['refund'])?[[]]:$result['data']['refund'];

        return self::appReturnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/android_refund_plan_by_date",
     *   tags={"APP-UserRefund:用户回款相关"},
     *   summary="Android当月回款计划页 [User\RefundPlanController@androidRefundPlanByDate]",
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
     *      default="653030e9f8e4f6559669386dfe4f56d4",
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
    /**
     * @param Request $request
     * @return array
     * @desc Android回款计划页
     */
    public function androidRefundPlanByDate(Request $request){

        $userId = $this -> getUserId();

        $logic = new RefundRecordLogic();

        $result = $logic -> androidRefundPlanByDate( $userId );

        return self::appReturnJson($result);

    }

}