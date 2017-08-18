<?php
/**
 * create by phpstorm
 * @author lgh
 * Date 16/10/08 Time 18:23 PM
 * @desc 每日生日用户信息Logic
 */

namespace App\Http\Logics\Data;

use App\Http\Logics\Logic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Invest\InvestModel;
use App\Http\Models\User\UserLoginModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Illuminate\Support\Facades\Log;

class BirthdayDataLogic extends Logic{
    /**
     * @desc 发送生日邮件数据组装
     * @author lgh-dev
     */
     public function sendBirthdayData(){
         $userLogic = new UserLogic();
         $investModel = new InvestModel();
         $userLoginModel = new UserLoginModel();
         $emailModel  = new  EmailModel();

         $birthdayUser = $userLogic->getBirthdayUser();
         $userIds = ToolArray::arrayToIds($birthdayUser,'id');

         //获取用户投资金额列表
         $investCashList = $investModel->getUserInvestCashTotal($userIds);

         //获取用户登录次数
         $loginNumList   = $userLoginModel->getLoginNumByUserIds($userIds);

         foreach($birthdayUser as $key=>$value){
             $identityCard               = $value["identity_card"];
             $subBirthday                = $value['birthday'];
             $birthdayUser[$key]["birthymd"] = substr($subBirthday, 0, 4)."-".substr($subBirthday, 4, 2)."-".substr($subBirthday, 6, 2);
             $birthdayUser[$key]["sex"]      =  (substr($identityCard, (strlen($identityCard) == 15 ? -1 : -2), 1) % 2) ? '男' : '女';
             $birthdayUser[$key]["age"] = ToolTime::getDateDiff($birthdayUser[$key]["birthymd"])['y'];
             //用户投资金额数据
             $birthdayUser[$key]["investAmount"] = 0;
             foreach($investCashList as $k=>$val){
                 if($birthdayUser[$key]["id"] == $val['user_id']){
                     $birthdayUser[$key]["investAmount"] = $val['investAmount'];
                 }
             }
             //用户登录次数
             $birthdayUser[$key]["loginNum"] = 0;
             foreach($loginNumList as $k=>$val){
                 if($birthdayUser[$key]["id"] == $val['user_id']){
                     $birthdayUser[$key]["loginNum"] = $val['num'];
                 }
             }

             //姓名命中黑名单过滤掉
             $black = $emailModel->checkMessageInBlacklist($value['real_name']);
             if($black === true){
                 unset($birthdayUser[$key]);
             }

         }
         //邮件标题
         $date  = date("Y年m月d日",strtotime(ToolTime::dbNow()));
         $title = "九斗鱼{$date}生日用户";
         $body = $this->formatBirthdayEmailContent($birthdayUser, $title);
         $savePath = $this->formatAttachment($birthdayUser);
         $this->sendBirthdayEmail($title, $body, $savePath);
     }

    /**
     * @desc 格式化生日用户邮件的数据
     * @param $birthdayUser
     * @param $title
     * @return string
     */
     public function formatBirthdayEmailContent($birthdayUser, $title){

        $sex = ['女', '男'];
        $dataStatisticsLogic = new DataStatisticsLogic();
        $body = "<br/>";
        $body  .= $dataStatisticsLogic->getCss();
        $body .="<p><h4>".$title."</h4></p>";
        $body .="<table class='table'><tr><th>ID</th><th>手机</th><th>姓名</th><th>性别</th><th>出生年月</th><th>年龄</th><th>注册时间</th><th>登录次数</th><th>投资金额</th></tr>";
        foreach($birthdayUser as $k=>$val){
            $body .="<tr><td>{$val['id']}</td><td>{$val['phone']}</td><td>{$val['real_name']}</td><td>{$val['sex']}</td><td>{$val['birthymd']}</td><td>{$val['age']}</td><td>{$val['created_at']}</td><td>{$val['loginNum']}</td><td>{$val['investAmount']}</td></tr>";
        }
        $body .="</table>";

        return $body;
     }

    /**
     * @desc 格式化附件内容
     * @param $birthdayUser
     * @return string
     */
     public function formatAttachment($birthdayUser){
         $excelData[] =  "ID, 手机, 姓名, 性别, 出生年月, 年龄,注册时间,登录次数,投资金额";
         foreach($birthdayUser as $k=>$val) {
             $birthdayArr = array($val["id"], $val["phone"], $val["real_name"],$val["sex"], $val["birthymd"], $val["age"], $val["created_at"], $val["loginNum"], $val["investAmount"]);

             $excelData[] = implode(",", $birthdayArr);
         }
         //写入的文件路径
         $fileName   = "birthday".ToolTime::dbDate(). ".csv";
         //不存在目录时创建目录
         $dirPath = base_path() . '/public/uploads/birthday/';
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
     * @desc 生日用户执行邮件发送
     * @param $title
     * @param $body
     * @param  $savePath
     */
     public function sendBirthdayEmail($title,$body, $savePath=''){
         $dataStatisticsLogic = new DataStatisticsLogic();
         $emailModel = new EmailModel();
         //接受者邮箱
         $receiveEmails = $dataStatisticsLogic->getMailTaskEmailConfig('birthday');
         //发送邮件
         $result = $emailModel->sendHtmlEmail($receiveEmails, $title, $body, $savePath);

         if($result['status'] == false){
             Log::info('每日生日用户邮件发送:'.\GuzzleHttp\json_encode($result));
         }else{
             foreach($savePath as $file) {
                 @unlink($file);
             }
             Log::info('每日生日用户邮件发送成功');
         }
     }
}