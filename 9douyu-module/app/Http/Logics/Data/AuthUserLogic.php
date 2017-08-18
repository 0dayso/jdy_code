<?php
/**
 * create by phpstorm
 * @author lgh
 * Date 16/10/08 Time 18:23 PM
 * @desc 每小时发送实名认证的用户
 */

namespace App\Http\Logics\Data;

use App\Http\Dbs\Identity\CardDb;
use App\Http\Logics\Logic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Illuminate\Support\Facades\Log;

class AuthUserLogic extends Logic{


    public function sendAuthData(){

        $emailModel = new EmailModel();
        $cardDb = new CardDb();
        //时间处理
        $hour = date('H');
        $startTime = date('Y-m-d').' '.($hour-1).':00:00';
        $endTime = date('Y-m-d').' '.($hour-1).':59:59';

        //获取实名认证记录
        $authInfo = $cardDb->getIdentityByAuthTime($startTime, $endTime);
        if(count($authInfo)>0){
            //邮件标题
            $title = "{$startTime} 至 {$endTime} 的鱼客实名认证名单";

            //姓名命中黑名单过滤掉
            foreach($authInfo as $key=>$value){
                //姓名命中黑名单过滤掉
                $black = $emailModel->checkMessageInBlacklist($value['name']);
                if($black === true){
                    unset($authInfo[$key]);
                }
            }

            $authUser = $this->getAuthUserInfo($authInfo);

            $body = $this->formatAuthUserEmailContent($authUser, $title);

            $savePath = $this->formatAuthAttachment($authUser);

            $this->sendAuthUserEmail($title, $body, $savePath);
        }else{
            Log::info('每小时实名认证的用户为空');
            return false;
        }
    }

    /**
     * @desc 获取实名用户信息
     * @param $authInfo
     * @return array
     */
    public function getAuthUserInfo($authInfo){
        $authUser = [];
        $userLogic = new UserLogic();

        $identityCards = implode(',', ToolArray::arrayToIds($authInfo, 'identity_card'));
        $userInfo = $userLogic->getUserByIdCards($identityCards);


        foreach($userInfo as $key=>$value){

            $identityCard               = $value["identity_card"];
            $authUser[$key]['id'] = $value['id'];
            $authUser[$key]['phone'] = $value['phone'];
            $authUser[$key]['name'] = $value['real_name'];
            $authUser[$key]['created_at'] = $value['created_at'];
            foreach($authInfo as $k=>$val){
                if($val["identity_card"] == $identityCard){
                    $authUser[$key]['app_request'] = $val['app_request'];
                }
            }
            $subBirthday                = strlen($identityCard) == 15 ? substr($identityCard, 4, 8) : substr($identityCard, 6, 8);
            $authUser[$key]["birthymd"] = substr($subBirthday, 0, 4)."-".substr($subBirthday, 4, 2)."-".substr($subBirthday, 6, 2);
            $authUser[$key]["sex"]      =  (substr($identityCard, (strlen($identityCard) == 15 ? -1 : -2), 1) % 2) ? '男' : '女';
            $authUser[$key]["age"] = ToolTime::getDateDiff($authUser[$key]["birthymd"])['y'];


        }
        return $authUser;
    }

    /**
     * @desc 格式化实名认证用户邮件的数据
     * @param $authUser
     * @param $title
     * @return string
     */
    public function formatAuthUserEmailContent($authUser,$title){

        $dataStatisticsLogic = new DataStatisticsLogic();
        $body = "<br/>";
        $body  .= $dataStatisticsLogic->getCss();
        $body .="<p><h4>".$title."</h4></p>";
        $body .="<table class='table'><tr><th>用户ID</th><th>手机</th><th>姓名</th><th>性别</th><th>年龄</th><th>注册时间</th><th>注册来源</th></tr>";
        foreach($authUser as $k=>$val){
            // echo $sex[$val['sex1']];
            $body .="<tr><td>{$val['id']}</td><td>{$val['phone']}</td><td>{$val['name']}</td><td>{$val['sex']}</td><td>{$val['age']}</td><td>{$val['created_at']}</td><td>{$val['app_request']}</td></tr>";
        }
        $body .="</table>";

        return $body;

    }

    /**
     * @desc 格式化实名附件内容
     * @param $authUser
     * @return string
     */
    public function formatAuthAttachment($authUser){
        $excelData[] =  "用户ID, 手机, 姓名, 性别, 年龄,注册时间,注册来源";
        foreach($authUser as $k=>$val) {
            $birthdayArr = array($val["id"], $val["phone"], $val["name"], $val["sex"], $val["age"], $val["created_at"], $val["app_request"]);

            $excelData[] = implode(",", $birthdayArr);
        }
        //写入的文件路径
        $fileName   = "identity_card.csv";
        //不存在目录时创建目录
        $dirPath = base_path() . '/public/uploads/authUser/';
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
     * @desc 每小时实名认证的用户执行邮件发送
     * @param $title
     * @param $body
     * @param  $savePath
     */
    public function sendAuthUserEmail($title,$body, $savePath=''){
        $dataStatisticsLogic = new DataStatisticsLogic();
        $emailModel = new EmailModel();
        //接受者邮箱
        $receiveEmails = $dataStatisticsLogic->getMailTaskEmailConfig('birthday');
        //发送邮件
        $result = $emailModel->sendHtmlEmail($receiveEmails, $title, $body, $savePath);

        if($result['status'] == false){
            Log::info('每小时实名认证的用户执行邮件发送:'.\GuzzleHttp\json_encode($result));
        }else{
            foreach($savePath as $file) {
                @unlink($file);
            }
            Log::info('每小时实名认证的用户执行邮件发送成功');
        }
    }
}