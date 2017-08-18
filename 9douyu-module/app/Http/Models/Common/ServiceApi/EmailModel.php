<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/18
 * Time: 13:46
 *
 */

namespace App\Http\Models\Common\ServiceApi;


use App\Http\Logics\Data\DataStatisticsLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Common\ServiceApiModel;
use Illuminate\Support\Facades\Config;
use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;
use Tests\Http\Logics\Warning\WarningLogic;
use App\Http\Models\Common\ServiceApi\SmsModel;

class EmailModel extends ServiceApiModel{

    private $params     = [];       //参数列表

    private $emailType  = 'sendMail';   //邮件基础接口类型


    public static $codeArr = [

        'formatReceiveEmail'                 => 1,

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_EMAIL;



    /**
     * @desc 检查邮箱格式
     * @author lgh
     * @param $email
     * @return bool
     */
    function checkEmailFormat($email) {
        //首字符字母或数字
        $pattern = '#^[a-z_\d](?:\.?[a-z_\d\-]+)*@[a-z_\d](?:\.?[a-z_\d\-]+)*\.[a-z]{2,3}$#is';

        if(!preg_match($pattern, $email)) {
            return false;
        }

        return true;
    }

    /**
     * @desc 邮件标题\内容处理
     * @param $subject
     * @param $body
     * @return array
     */
    private function formatData($subject,$body){
        //基础数据 收件人,标题,内容
        $data = [

            [
                'name'      => 'subject',
                'contents'  => $subject
            ],
            [
                'name'      => 'body',
                'contents'  => $body,
            ]
        ];
        return $data;
    }

    /**
     * @desc 收件人邮箱处理
     * @param $emails
     * @return array
     * @throws \Exception
     */
    private function formatReceiveEmail($emails){

        $emailArr = [];

        //邮件格式检查
        if(is_array($emails)){

            foreach($emails as $email => $name){

                if($this->checkEmailFormat($email)){
                    $emailArr[] = $email;
                }
            }

        }else{
            if($this->checkEmailFormat($emails)){
                $emailArr[] = $emails;
            }
        }

        if(!$emailArr){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_EMAIL'), self::getFinalCode('formatReceiveEmail'));

        }

        $emailStr = implode(',',$emailArr);

        return [
            [
                'name'      => 'email',
                'contents'  => $emailStr
            ]
        ];

    }

    /**
     * @desc 附件处理
     * @param $attachment
     * @return array
     */
    private function formatAttachment($attachment){

        $fileArr = [];

        //若存在附件则组装数据
        if($attachment){

            foreach($attachment as $file){

                $fileInfo = pathinfo($file);
                $fileArr[] = [
                    'name' => $fileInfo['filename'],
                    'contents' => fopen($file, 'r')
                ];

            }
        }

        return $fileArr;
    }
    /**
     * 合并邮件发送参数
     * @param $to
     * @param $subject
     * @param $body
     * @param $attachment
     */
    private function mergeParams($to,$subject,$body,$attachment){

        //收件人
        $email = $this->formatReceiveEmail($to);
        //发送的标题及内容
        $data = $this->formatData($subject,$body);
        //附件处理
        $attachment = $this->formatAttachment($attachment);

        $this->params['multipart'] = array_merge($email,$data,$attachment);
    }
    /**
     * @desc 发送html的邮件
     * @param       $to   接收邮件者邮箱  或者 array('email_1@9douyu.com' => 'name1', 'email_2@9douyu.com' => 'name2');
     * @param       $subject 邮件主题
     * @param       $body 邮件内容
     * @param       $attachment array 附件列表
     * @return null|void
     */
    public function sendHtmlEmail($to,$subject,$body,$attachment = []){

        if( env('APP_ENV') != 'production' ){

            return Logic::callSuccess();

        }

        $api  = Config::get('serviceApi.moduleEmail.'.$this->emailType.'Html');

        $this->mergeParams($to, 'HostName=['.gethostname().']'.$subject,$body,$attachment);

        $return = HttpQuery::SendEmailPost($api, $this->params);

        if( !$return['status'] ){

            \App\Http\Logics\Warning\WarningLogic::doSmsWarning($subject);

        }

        return $return;

    }

    /**
     * @desc 发送html的邮件
     * @param       $to   接收邮件者邮箱  或者 array('email_1@9douyu.com' => 'name1', 'email_2@9douyu.com' => 'name2');
     * @param       $subject 邮件主题
     * @param       $body 邮件内容
     * @param       $attachment array 附件列表
     * @return null|void
     */
    public function sendEmail($to,$subject,$body,$attachment = []){

        if( env('APP_ENV') != 'production' ){

            return Logic::callSuccess();

        }

        $api  = Config::get('serviceApi.moduleEmail.'.$this->emailType);

        $this->mergeParams($to, 'HostName=['.gethostname().']'.$subject ,$body,$attachment);

        $return = HttpQuery::SendEmailPost($api, $this->params);

        if( !$return['status'] ){

            \App\Http\Logics\Warning\WarningLogic::doSmsWarning($subject);

        }

        return $return;

    }

    /**
     * 邮件命中黑名单词语检测
     * @param $message 邮件内容
     * @return boolean
     */
    public function checkMessageInBlacklist($message) {
        $wordList = $this->getBlacklist();

        foreach ($wordList as $word) {
            if(stripos($message, $word) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @desc 敏感字过滤黑名单
     * @return array
     */
    public function getBlacklist() {

        return SmsModel::getBlacklist();
    }


}

