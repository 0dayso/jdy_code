<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/14
 * Time: 下午4:39
 * Desc：订单相关
 */

namespace App\Http\Logics\Warning;
use Illuminate\Support\Facades\Cache;
use App\Http\Models\Common\EmailModel;


class OrderLogic extends WarningLogic
{
    

    /**
     * @param $data
     * @desc 绑定银行卡失败，发送邮件
     */
    public static function bindCardWarning($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】绑定银行卡失败';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 批量修改提现订单状态
     */
    public static function batchCheckAccountWarning($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】批量处理提现订单进入队列失败';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 提现失败的订单
     */
    public static function batchWithdrawError($data){

        $arr['subject'] = $data;

        $arr['title'] = '【Warning】提现失败订单,请确认后手动处理';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @desc 批量提现短信发送失败
     */
    public static function batchWithdrawSubmitToBankWarning($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】批量提现短信发送失败';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }


    /**
     * @desc 未发现提现订单报警
     */
    public static function withdrawOrderEmailWarning($data)
    {

        $arr['subject'] = json_encode($data);

        $arr['title'] = '【Warning】批量提现邮件发送失败';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');

        self::doSend($configData, $arr);

    }

    /**
     * @param $data
     * @desc 充值失败订单处理
     */
    public static function failOrderWarning($data)
    {

        //防止重复发送报警邮件

        $orderId = $data['order_id'];

        $key = 'FAILED_ORDER:'.$orderId;

        $result = Cache::get($key);

        if($result){

            return ;
        }

        $arr['subject'] = implode(',', $data);
        
        $arr['title'] = '【Warning】充值失败订单处理';

        $configData = self::getConfigDataByKey('SYSTEM_WARNING_RECEIVE_ADMIN');
        
        self::doSend($configData, $arr);

        Cache::put($key,1,60);


    }

    /**
     * @param $data
     * @return bool
     * 发送提现邮件
     */
    public static function doSendWithdrawEmail($data,$emails){

        $arr['subject'] = $data['subject'];

        $arr['title'] =  '【通知】'.$data['startTime'].'至'.$data['endTime']. "提现统计数据";
        
        if($emails){

            $emailModel = new EmailModel();

            $result = $emailModel->sendHtmlEmail($emails, $arr['title'], $data['subject'],$data['attachment']);

            return $result;
        }else{

            $configData = self::getConfigDataByKey('WITHDRAW_RECEIVES');

            return self::doSend($configData, $arr,$data['attachment']);
        }

       
        
    }

    /**
     * @param $data
     * 不存在提现用户发送报警邮件
     */
    public static function doSendWithdrawEmailWarning($data){
        
        

    }

}