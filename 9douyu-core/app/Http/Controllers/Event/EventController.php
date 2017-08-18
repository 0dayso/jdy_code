<?php
/**
 * User: Zjmainstay
 * Date: 16/4/21
 * Desc: 事件通知（注册成功、实名成功、投资成功等）
 */

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Logics\Event\EventNotifyLogic;
use Illuminate\Http\Request;

class EventController extends Controller{

    /**
     * @SWG\Post(
     *   path="/event/list",
     *   tags={"Event"},
     *   summary="内核可用事件列表",
     *   @SWG\Response(
     *     response=200,
     *     description="内核可用事件列表"
     *   )
     * )
     */
    public function getEventList()
    {

        //获取事件列表
        $logic      = new EventNotifyLogic();
        $result     = $logic->getEventList();

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/event/register",
     *   tags={"Event"},
     *   summary="注册事件监听",
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
     *      name="event_name",
     *      in="formData",
     *      description="事件名",
     *      required=true,
     *      type="string",
     *   ),
     *    @SWG\Parameter(
     *      name="notify_url",
     *      in="formData",
     *      description="事件触发时的回调地址",
     *      required=true,
     *      type="string",
     *   ), 
     *   @SWG\Response(
     *      response=200,
     *      description="提交内核事件监听回调，当该事件触发时，会通过异步curl通知该回调地址。",
     *      @SWG\Schema(
     *          @SWG\Property(
     *              property="status",
     *              type="boolean",
     *              description="状态"
     *          ),
     *          @SWG\Property(
     *              property="code",
     *              type="integer",
     *              format="int32"
     *          ),
     *          @SWG\Property(
     *              property="message",
     *              type="string"
     *          ),
     *          @SWG\Property(
     *              property="data",
     *              type="array"
     *          )
     *      )
     *   )
     * )
     */
    public function register(Request $request)
    {
        $eventName = $request->input('event_name');
        $notifyUrl = $request->input('notify_url');
        
        $logic = new EventNotifyLogic();
        $result     = $logic->register(self::$authId, $eventName, $notifyUrl);

        return self::returnJson($result);
    }
}
