<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 18:41
 * Desc: 提现订单操作类  批量发送短信,自动对账等
 */

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Logics\Order\OperateLogic;
use App\Http\Logics\Order\OrderLogic;
use Illuminate\Http\Request;

class OperateController extends Controller{

    private $logic;

    public function __construct(Request $request){

        parent::__construct($request);

        $this->logic = new OperateLogic();
    }

    /**
     * @SWG\Post(
     *   path="/withdraw/order/batchSubmitToBank",
     *   tags={"Order"},
     *   summary="提现批量发送短信",
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
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="T+0提现未处理的ID",
     *      required=true,
     *      type="integer",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="批量发送提现短信成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="批量发送提现短信失败。",
     *   )
     * )
     */
    public function batchWithdrawSubmitToBank(Request $request){

        $id = $request->input('id',0);
        
        $result = $this->logic->batchWithdrawSubmitToBank($id);

        return self::returnJson($result);

    }


    /**
     * @SWG\Post(
     *   path="/withdraw/order/batchCheckAccount",
     *   tags={"Order"},
     *   summary="提现批量对账",
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
     *   @SWG\Parameter(
     *      name="order_data",
     *      in="formData",
     *      description="自动对账批量订单数据(json格式)",
     *      required=true,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="提现批量对账成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="提现批量对账失败。",
     *   )
     * )
     */
    public function batchCheckAccount(Request $request){

        $data = $request->input('order_data','');
        $result = $this->logic->batchCheckAccount($data);

        return self::returnJson($result);
    }



    /**
     * @SWG\Post(
     *   path="/withdraw/order/getWithdrawList",
     *   tags={"Order"},
     *   summary="T+0提现列表",
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
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=true,
     *      type="integer",
     *      default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="每页量",
     *      required=true,
     *      type="integer",
     *      default="20"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="提现邮件处理列表。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="提现邮件处理列表。",
     *   )
     * )
     */
    public function getWithdrawList(Request $request){

        $page = $request->input('page',1);
        $size = $request->input('size',20);

        $result = $this->logic->getWithdrawList($page,$size);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/withdraw/order/sendWithdrawEmail",
     *   tags={"Order"},
     *   summary="T+0提现列表",
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
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="ID",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="email",
     *      in="formData",
     *      description="接收提现邮件的邮箱地址",
     *      required=true,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="发送提现邮件入队成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="发送提现邮件入队失败。",
     *   )
     * )
     */
    public function sendWithdrawEmail(Request $request){

        $id     = $request->input('id',0);
        $email  = $request->input('email','');

        $result = $this->logic->sendWithdrawEmailById($id,$email);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/withdraw/order/sendWithdrawEmailNew",
     *   tags={"Order"},
     *   summary="提现邮件-T+0提现列表",
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
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="ID",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="email",
     *      in="formData",
     *      description="接收提现邮件的邮箱地址",
     *      required=true,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="提现方式",
     *      required=true,
     *      type="string",
     *      default="suma"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="发送提现邮件入队成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="发送提现邮件入队失败。",
     *   )
     * )
     */
    public function sendWithdrawEmailNew(Request $request){

        $id     = $request->input('id',0);
        $email  = $request->input('email','');
        $type   = $request->input('type','');
        $result = $this->logic->sendWithdrawEmailNewById($id,$email,$type);

        return self::returnJson($result);
    }


}