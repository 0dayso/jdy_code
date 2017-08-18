<?php
/**
 * create by phpstorm
 * @author lgh
 * Date 16/10/10 Time 17:23 PM
 * @desc 每小时发送提现大于5万的用户信息
 */

namespace App\Http\Logics\Data;

use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Tools\ToolTime;
use Illuminate\Support\Facades\Log;

class WithdrawFiveUserLogic extends Logic{

    public function sendWithdrawFiveUserData(){
        $emailModel = new EmailModel();
        //时间处理
        $hour = date('H');
        $startTime = date('Y-m-d').' '.($hour-1).':00:00';
        $endTime = date('Y-m-d').' '.($hour-1).':59:59';
        $all = [
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
        $orderModel = new OrderModel();

        $withdrawFiveUser = $orderModel->getWithdrawUserCashFive($all);

        if(count($withdrawFiveUser)>0){
            //邮件标题
            $title = "九斗鱼提现5万以上鱼客信息记录_{$startTime} 至 {$endTime}";

            //姓名命中黑名单过滤掉
            foreach($withdrawFiveUser as $key=>$value){
                //姓名命中黑名单过滤掉
                $black = $emailModel->checkMessageInBlacklist($value['real_name']);
                if($black === true){
                    unset($withdrawFiveUser[$key]);
                }
            }

            $withdrawFiveUser = $this->formatWithdrawUserInfo($withdrawFiveUser);

            $body = $this->formatWithdrawUserEmailContent($withdrawFiveUser, $title);

            $savePath = $this->formatFailRechargeAttachment($withdrawFiveUser);

            $this->sendWithdrawUserEmail($title, $body, $savePath);
        }else{
            Log::info('每小时发送提现大于5万的用户信息为空');
            return false;
        }
    }

    /**
     * @desc 格式化提现大于5万的用户信息
     * @param $withdrawFiveUser
     * @return mixed
     */
    public function formatWithdrawUserInfo($withdrawFiveUser){

        foreach($withdrawFiveUser as $key => $value){
            $identityCard               = $value["identity_card"];
            $withdrawFiveUser[$key]["sex"]     =  (substr($identityCard, (strlen($identityCard) == 15 ? -1 : -2), 1) % 2) ? '男' : '女';
        }
        return $withdrawFiveUser;
    }

    /**
     * @desc 格式化每日发送提现大于5万的用户信息邮件内容
     * @param $withdrawFiveUser
     * @param $title
     * @return string
     */
    public function formatWithdrawUserEmailContent($withdrawFiveUser, $title){

        $dataStatisticsLogic = new DataStatisticsLogic();
        $body = "<br/>";
        $body  .= $dataStatisticsLogic->getCss();
        $body .="<p><h4>".$title."</h4></p>";
        $body .="<table class='table'><tr><th>用户ID</th><th>手机</th><th>姓名</th><th>性别</th><th>提现金额</th><th>提现时间</th><th>注册时间</th></tr>";
        foreach($withdrawFiveUser as $k=>$val){
            // echo $sex[$val['sex1']];
            $body .="<tr><td>{$val['id']}</td><td>{$val['phone']}</td><td>{$val['real_name']}</td><td>{$val['sex']}</td><td>{$val['cash']}</td><td>{$val['withdraw_time']}</td><td>{$val['created_at']}</td></tr>";
        }
        $body .="</table>";

        return $body;
    }

    /**
     * @return array
     */
    public function formatFailRechargeAttachment($withdrawFiveUser){
        $excelData[] =  "用户ID, 手机, 姓名, 性别, 提现金额,提现时间,注册时间";
        foreach($withdrawFiveUser as $k=>$val) {
            $birthdayArr = array($val["id"], $val["phone"], $val["real_name"], $val["sex"], $val["cash"], $val["withdraw_time"], $val["created_at"]);

            $excelData[] = implode(",", $birthdayArr);
        }
        //写入的文件路径
        $fileName   = "withdrawFiveUser".ToolTime::dbDate(). ".csv";
        //不存在目录时创建目录
        $dirPath = base_path() . '/public/uploads/withdrawFiveUser/';
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
     * @desc 每小时发送提现大于5万的用户信息执行邮件发送
     * @param $title
     * @param $body
     * @param  $savePath
     */
    public function sendWithdrawUserEmail($title,$body, $savePath=''){
        $dataStatisticsLogic = new DataStatisticsLogic();
        $emailModel = new EmailModel();
        //接受者邮箱
        $receiveEmails = $dataStatisticsLogic->getMailTaskEmailConfig('birthday');
        //发送邮件
        $result = $emailModel->sendHtmlEmail($receiveEmails, $title, $body, $savePath);

        if($result['status'] == false){
            Log::info('每小时发送提现大于5万的用户信息执行邮件发送:'.\GuzzleHttp\json_encode($result));
        }else{
            foreach($savePath as $file) {
                @unlink($file);
            }
            Log::info('每小时发送提现大于5万的用户信息发送成功');
        }
    }
}