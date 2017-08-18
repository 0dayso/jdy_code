<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/6
 * Time: 上午11:09
 */
namespace App\Http\Models\Order;

use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Model;
use Config;

class WithdrawModel extends Model{

    /**
     * 批量发送提现处理消息
     * @return bool
     */
    public function batchSendDoneMsg(){
        $return = false;
        $api = Config::get('coreApi.moduleOrder.doBatchSendWithdrawNoticeSms');
        $res = HttpQuery::corePost($api);
        if($res['code']==Logic::CODE_SUCCESS){
            $return = $res['status'];
        }
        return $return;
    }

    /**
     * 指定发送提现处理消息
     * @param $id
     * @return array
     */
    public function sendDoneMsg($id){
        $return = false;
        $api = Config::get('coreApi.moduleOrder.doSendWithdrawNoticeSms');
        $res = HttpQuery::corePost($api,['order_id'=>$id]);
        if($res['code']==Logic::CODE_SUCCESS){
            $return = $res['status'];
        }
        return $return;
    }

    
}