<?php
/**
 * create by phpstorm
 * @author lgh
 * Date 16/10/11 Time 18:16 PM
 * @desc 每小时发送充值失败的用户信息
 */

namespace App\Http\Logics\Data;


use App\Http\Logics\Logic;
use App\Http\Logics\Recharge\PayLimitLogic;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Tools\ToolTime;
use Illuminate\Support\Facades\Log;

class FailRechargeOrderLogic extends Logic{

    public function sendFailRechargeData(){
        $emailModel = new EmailModel();

        //时间处理
        $hour = date('H');
        $startTime = date('Y-m-d').' '.($hour-1).':00:00';
        $endTime = date('Y-m-d').' '.($hour-1).':59:59';
        $params = [
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];

        $orderModel = new OrderModel();

        $failRecharge = $orderModel->getFailRechargeOrderByTime($params);
        if(count($failRecharge)>0){
            //邮件标题
            $title = "九斗鱼充值失败记录_{$startTime} 至 {$endTime}";

            //姓名命中黑名单过滤掉
            foreach($failRecharge as $key=>$value){
                //姓名命中黑名单过滤掉
                $black = $emailModel->checkMessageInBlacklist($value['real_name']);
                if($black === true){
                    unset($failRecharge[$key]);
                }
            }
            $formatFailRechargeInfo = $this->formatFailRechargeOrder($failRecharge);

            $body = $this->formatFailRechargeEmailContent($formatFailRechargeInfo, $title);

            $savePath = $this->formatFailRechargeAttachment($formatFailRechargeInfo);

            $this->sendFailRechargeEmail($title, $body, $savePath);
        }else{
            Log::info('每小时发送充值失败的用户信息为空');
            return false;
        }

    }

    /**
     * @desc 格式化充值失败订单数据
     * @param $failRecharge
     * @return mixed
     */
    public function formatFailRechargeOrder($failRecharge){

        $payLimitLogic = new PayLimitLogic();
        //充值渠道
        $payType = $payLimitLogic->getPayTypeName() + $payLimitLogic->getOnlinePayTypeName();

        foreach($failRecharge as $key => $value){
            $failRecharge[$key]['channel_name'] = $payType[$value['channel']]['name'];
        }
        return $failRecharge;
    }

    /**
     * @desc  格式化充值失败邮件内容
     * @param $formatFailRechargeInfo
     * @param $title
     * @return string
     */
    public function formatFailRechargeEmailContent($formatFailRechargeInfo, $title){
        $dataStatisticsLogic = new DataStatisticsLogic();
        $body = "<br/>";
        $body  .= $dataStatisticsLogic->getCss();
        $body .="<p><h4>".$title."</h4></p>";
        $body .="<table class='table'><tr><th>订单号</th><th>手机</th><th>姓名</th><th>充值银行卡</th><th>充值金额</th><th>充值时间</th><th>渠道</th></tr>";
        foreach($formatFailRechargeInfo as $k=>$val){
            $body .="<tr><td>{$val['order_id']}</td><td>{$val['phone']}</td><td>{$val['real_name']}</td><td>{$val['card_number']}</td><td>{$val['cash']}</td><td>{$val['recharge_time']}</td><td>{$val['channel_name']}</td></tr>";
        }
        $body .="</table>";

        return $body;
    }

    /**
     * @return array
     */
    public function formatFailRechargeAttachment($formatFailRechargeInfo){
        $excelData[] =  "订单号, 手机, 姓名, 充值银行卡, 充值金额,充值时间,渠道";
        foreach($formatFailRechargeInfo as $k=>$val) {
            $birthdayArr = array($val["order_id"], $val["phone"], $val["real_name"], $val["card_number"], $val["cash"], $val["recharge_time"], $val["channel_name"]);

            $excelData[] = implode(",", $birthdayArr);
        }
        //写入的文件路径
        $fileName   = "failRecharge".ToolTime::dbDate(). ".csv";
        //不存在目录时创建目录
        $dirPath = base_path() . '/public/uploads/failRecharge/';
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777);
            chmod($dirPath, 0777);
        };
        //写入文件
        $savePath = $dirPath.$fileName;
        @file_put_contents($savePath, implode("\n", $excelData));
        return [$savePath];
    }

    /**
     * @desc 发送充值失败的邮件
     * @param        $title
     * @param        $body
     * @param string $savePath
     */
    public function sendFailRechargeEmail($title,$body, $savePath=''){

        $dataStatisticsLogic = new DataStatisticsLogic();
        $emailModel = new EmailModel();
        //接受者邮箱
        $receiveEmails = $dataStatisticsLogic->getMailTaskEmailConfig('birthday');
        //发送邮件
        $result = $emailModel->sendHtmlEmail($receiveEmails, $title, $body, $savePath);

        if($result['status'] == false){
            Log::info('每小时发送充值失败的用户信息执行邮件发送:'.\GuzzleHttp\json_encode($result));
        }else{
            foreach($savePath as $file) {
                @unlink($file);
            }
            Log::info('每小时发送充值失败的用户信息成功');
        }
    }




}