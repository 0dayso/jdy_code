<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/18
 * Time: 上午10:32
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Logics\User\IndexLogic;
use Illuminate\Http\Request;
use App\Http\Logics\User\ModifyLogic;
/**
 * 变更接口控制器
 *
 * Class ModifyController
 * @package App\Http\Controllers\User
 */
class ModifyController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/user/modify/phone",
     *   tags={"User"},
     *   summary="根据手机号变更手机号",
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
     *      default="209c02k29"
     *   ),
     *   @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="老手机号",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="new_phone",
     *      in="formData",
     *      description="新手机号",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="变更手机号成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="变更手机号失败。",
     *   )
     * )
     */
    public function modifyPhone(Request $request)
    {
        $modifyLogic = new ModifyLogic();

        $phone       = $request->input('phone');

        $new_phone   = $request->input('new_phone');

        $logicReturn = $modifyLogic->modifyPhone($phone, $new_phone);

        self::returnJson($logicReturn);
    }

    /**
     * @SWG\Post(
     *   path="/user/doIncreaseBalanceToCurrentAccount",
     *   tags={"User"},
     *   summary="活动奖励发送至用户账户余额",
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
     *      default="209c02k29"
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="转至账户金额",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="trade_password",
     *      in="formData",
     *      description="交易密码(加密后的)",
     *      required=true,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="ticket_id",
     *      in="formData",
     *      description="加钱的票据",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="note",
     *      in="formData",
     *      description="备注",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="event_id",
     *      in="formData",
     *      description="event类别",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="转至账户成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="转至账户失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @desc 活动奖励发送至用户账户余额
     */
    public function doIncreaseBalanceToCurrentAccount(Request $request){

        $logic          = new IndexLogic();

        $userId         = $request->input('user_id');

        $cash           = $request->input('cash');

        $tradePassword  = $request->input('trade_password');

        $note           = $request->input('note','');
        
        $ticketId       = $request->input('ticket_id','');

        $eventId        = $request->input('event_id', '');

        $result         = $logic->doIncreaseBalanceToCurrentAccount($userId, $cash, $tradePassword, $note, $ticketId, $eventId);

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/user/doDecreaseBalance",
     *   tags={"User"},
     *   summary="用户账户扣款",
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
     *      default="209c02k29"
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="扣款金额",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="trade_password",
     *      in="formData",
     *      description="交易密码(加密后的)",
     *      required=true,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="ticket_id",
     *      in="formData",
     *      description="扣款的票据",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="note",
     *      in="formData",
     *      description="备注",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="event_id",
     *      in="formData",
     *      description="event类别",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="扣款成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="扣款失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @desc 用户扣款
     */
    public function doDecreaseBalance(Request $request){

        $logic          = new IndexLogic();

        $userId         = $request->input('user_id');

        $cash           = $request->input('cash');

        $tradePassword  = $request->input('trade_password');

        $note           = $request->input('note','');

        $ticketId       = $request->input('ticket_id','');

        $eventId        = $request->input('event_id', '');

        $result         = $logic->doDecreaseBalance($userId, $cash, $tradePassword, $note, $ticketId, $eventId);

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/user/statusBlock",
     *   tags={"User"},
     *   summary="锁定或解初绑定用户账户",
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
     *      default="209c02k29"
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="用户解锁或锁定状态",
     *      required=true,
     *      type="integer",
     *      default="300"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="解锁|锁定账户成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="解锁|锁定账户失败。",
     *   )
     * )
     */
    public function modifyStatusBlock(Request $request){

        $userId         = $request->input('user_id');
        $status         = $request->input('status');

        $modifyLogic  = new ModifyLogic();

        $result = $modifyLogic->doModifyStatusBlock($userId, $status);

        self::returnJson($result);


    }

}