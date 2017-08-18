<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 11:40
 * Desc: 核心订单调用model
 */

namespace App\Http\Models\Common\CoreApi;

use App\Http\Models\Common\CoreApiModel;
use App\Tools\ToolMoney;
use App\Http\Models\Common\HttpQuery;
use Config;

class OrderModel extends CoreApiModel{

    
    /**
     * @param array $params
     * @return array
     * $params 包含字段如下
     * order_id         订单号         必填
     * user_id          用户ID         必填
     * cash             充值金额       必填
     * bank_id          银行ID         必填
     * card_no          银行卡号        必填
     * type             支付通道类型     必填
     * from             三端来源        必填
     * version          app版本号      非必填
     * 创建充值订单
     */
    public static function doCreateRechargeOrder(array $params){

        $api  = Config::get('coreApi.moduleOrder.doCreateRechargeOrder');

        $params['cash']  = ToolMoney::formatDbCashDelete($params['cash']);

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }


    /**
     * @param $orderId          订单号     必填
     * @param string $trade_no  交易流水号   非必填
     * @return array
     * 支付成功,修改订单为成功状态
     */
    public static function doSuccRechargeOrder($orderId,$tradeNo = ''){

        $api  = Config::get('coreApi.moduleOrder.doSuccRechargeOrder');

        $params = [
            'order_id'  => $orderId,
            'trade_no'  => $tradeNo,
        ];

        $return = HttpQuery::corePost($api,$params);

        //支付成功修改用户充值成功记录
        if($return['status']){

            $params = [
                'event_name'    => 'App\Events\Pay\RechargeSuccessEvent',
                'event_desc'    => '充值成功事件',
                'order_id'      => $orderId,                    //红包ID
            ];

            \Event::fire(new \App\Events\Pay\RechargeSuccessEvent($params));

        }
        return $return;
    }


    /**
     * @param $orderId              订单号         必填
     * @param string $trade_no      交易流水号      非必填
     * @param string $note          失败原因        非必填
     * @return array
     * 支付失败,修改订单状态为失败
     */
    public static function doFailedRechargeOrder($orderId,$tradeNo = '',$note = ''){

        $api  = Config::get('coreApi.moduleOrder.doFailedRechargeOrder');

        $params = [
            'order_id'  => $orderId,
            'trade_no'  => $tradeNo,
            'note'      => $note
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }


    /**
     * @param $orderId  必填
     * @return array
     * 支付超时,修改订单状态为超时
     */
    public static function doTimeoutRechargeOrder($orderId,$note = ''){

        $api  = Config::get('coreApi.moduleOrder.doTimeoutRechargeOrder');

        $params = [
            'order_id'  => $orderId,
            'note'      => $note,
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param array $params
     * @return array
     * $params 包含字段如下
     * order_id         订单号         必填
     * user_id          用户ID         必填
     * cash             充值金额       必填
     * handing_fee      手续费金额      必填,若没有手续费传0即可
     * bank_id          银行ID         必填
     * card_no          银行卡号        必填
     * type             支付通道类型     必填
     * from             三端来源        必填
     * version          app版本号      非必填
     * 创建提现订单
     */
    public static function doCreateWithdrawOrder(array $params){

        $api  = Config::get('coreApi.moduleOrder.doCreateWithdrawOrder');

        //金额转化为元
        $params['cash']         = ToolMoney::formatDbCashDelete($params['cash']);
        $params['handing_fee']  = ToolMoney::formatDbCashDelete($params['handing_fee']);

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }


    /**
     * @param $orderId          订单号     必填
     * @param string $trade_no  交易流水号   非必填
     * @return array
     * 提现成功,修改订单为成功状态
     */
    public static function doSuccWithdrawOrder($orderId,$tradeNo = ''){

        $api  = Config::get('coreApi.moduleOrder.doSuccWithdrawOrder');

        $params = [
            'order_id'  => $orderId,
            'trade_no'  => $tradeNo,
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }


    /**
     * @param $orderId              订单号         必填
     * @param string $trade_no      交易流水号      非必填
     * @param string $note          失败原因        非必填
     * @return array
     * 提现失败,修改订单状态为失败
     */
    public static function doFailedWithdrawOrder($orderId,$tradeNo = '',$note = ''){

        $api  = Config::get('coreApi.moduleOrder.doFailedWithdrawOrder');

        $params = [
            'order_id'  => $orderId,
            'trade_no'  => $tradeNo,
            'note'      => $note
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }


    /**
     * @param $orderId      订单号     必填
     * @param string $note  取消原因   必填
     * @return array
     * 取消提现
     */
    public static function doCancelWithdrawOrder($orderId,$note = ''){

        $api  = Config::get('coreApi.moduleOrder.doCancelWithdrawOrder');

        $params = [
            'order_id'  => $orderId,
            'note'      => $note
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param $orderId      订单号     必填
     * @return array
     * 提现提交至银行,修改订单状态为处理中,通知用户提现申请已提交至银行(单个订单号)
     */
    public static function doSendWithdrawNoticeSms($orderId){

        $api  = Config::get('coreApi.moduleOrder.doSendWithdrawNoticeSms');

        $params = [
            'order_id'  => $orderId
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @return array
     * 提现批量提交至银行,修改订单状态为处理中,通知用户提现申请已提交至银行(批量发送)
     */
    public static function doBatchSendWithdrawNoticeSms($params){

        $api  = Config::get('coreApi.moduleOrder.doBatchSendWithdrawNoticeSms');

        //$params = [];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param $order_data   json数据
     * 包含 order_id 订单号
     *      status   状态
     *      note     原因
     * @return array
     * 后台批量对账
     */
    public static function doBatchWithdrawCheckAmount($order_data){

        $api  = Config::get('coreApi.moduleOrder.doBatchWithdrawCheckAmount');

        $params = [
            'order_data'    => $order_data
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param $userId       用户ID    必填
     * @param $type         支付通道    必填
     * @return array
     * 获取某个用户今日无效的充值次数
     */
    public static function getTodayInvalidRechargeNum($userId,$type){

        $api  = Config::get('coreApi.moduleOrder.getTodayInvalidRechargeNum');

        $params = [
            'user_id'   => $userId,
            'type'      => $type
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }

    }


    /**
     * @param $orderId  订单号     必填
     * @return array
     * 根据订单号获取订单信息
     */
    public static function getOrderInfo($orderId){

        $api  = Config::get('coreApi.moduleOrder.getOrderInfo');

        $params = [
            'order_id'  => $orderId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }

    }


    /**
     * @param $userId       用户ID    必填
     * @return array
     * 获取用户本月有效的提现次数,用于计息提现手续费
     */
    public static function getUserMonthWithdrawNum($userId){

        $api  = Config::get('coreApi.moduleOrder.getUserMonthWithdrawNum');

        $params = [
            'user_id'   => $userId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }

    }


    /**
     * 获取项目列表
     *
     * @param array $data
     * @return array
     */
    public static function getOrderList($data = []){

        $api  = Config::get('coreApi.moduleOrder.getList');

        $params = $data;

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }

    /**
     * @param $userId
     * @return array
     * 获取用户非网银支付成功的充值渠道列表
     */
    public static function getSuccPayChannelList($userId){

        $api  = Config::get('coreApi.moduleOrder.getSuccPayChannelList');

        $params = [
            'user_id' => $userId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }

    /**
     * @param $userId
     * @return array
     * 获取用户除网银支付外的成功充值订单数量
     */
    public static function getSuccOrderNum($userId){

        $api  = Config::get('coreApi.moduleOrder.getSuccOrderNum');

        $params = [
            'user_id' => $userId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }

    /**
     * [获取所有提现订单]
     * @return [type] [description]
     */
    public static function getWithdrawOrders($params){

        $api  = Config::get('coreApi.moduleOrder.getWithdrawOrders');

        //$params = ['page'=>$page,'size'=>$size];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }
    
    
    public static function getAdminOrderList($params){

        $api  = Config::get('coreApi.moduleOrder.getAdminOrderList');
        
        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }

    /**
     * @desc 获取充值记录的统计数据
     * @auThor lgh
     * @param $params
     * @return array|null|void
     */
    public static function getRechargeStatistics($params){

        $api  = Config::get('coreApi.moduleOrder.getRechargeStatistics');

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }

    /**
     * @desc 获取订单记录的统计数据
     * @param $params
     * @return array
     */
    public static function getWithdrawStatistics($params){
        $api  = Config::get('coreApi.moduleOrder.getWithdrawStatistics');

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }

    /**
     * @param $params
     * 获单处理加币
     */
    public static function doMissOrderHandle($params){

        $api  = Config::get('coreApi.moduleOrder.missOrderHandle');

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }

    /**
     * @desc 获取提现大于5万用户信息
     * @param $params
     * @return array
     */
    public static function getWithdrawUserCashFive($params){

        $api  = Config::get('coreApi.moduleOrder.getWithdrawUserCashFive');

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }

    /**
     * @desc 获取某段时间内充值失败订单
     * @param $params
     * @return array
     */
    public function getFailRechargeOrderByTime($params){

        $api  = Config::get('coreApi.moduleOrder.getFailRechargeOrderByTime');

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }


    /**
     * @param $page
     * @param $size
     * 获取T+0提现处理列表
     */
    public static function getWithdrawRecord($params){

        $api  = Config::get('coreApi.moduleOrder.getWithdrawRecord');

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }


    /**
     * @param $params
     * @return null|void
     * T+0发送指定时间段的邮件
     */
    public static function sendWithdrawEmail($params){
        
        $api  = Config::get('coreApi.moduleOrder.sendWithdrawEmail');

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }

    /**
     * @desc    提现请求-发邮件
     * @param   $params
     * @return  null|void
     * T+0发送指定时间段的邮件
     */
    public static function sendWithdrawEmailNew($params){

        $api  = Config::get('coreApi.moduleOrder.sendWithdrawEmailNew');

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param $params
     * @return array
     * @desc 充值统计
     */
    public static function getRechargeOrderTotal( $params )
    {
        $api    =   Config::get('coreApi.moduleOrder.getRechargeOrderTotal');

        $return =   HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }
    }
}