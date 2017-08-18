<?php

/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/3
 * Time: 10:53
 * Desc: 发送邮件基础model
 */

namespace App\Http\Models\Common;
use App\Http\Models\Model;
use App\Lang\LangModel;
use GuzzleHttp\Client;
use App\Http\Models\Common\ExceptionCodeModel;


class EmailModel extends  Model{
    

    private $params     = [];       //参数列表

    private $emailType  = 'sendMail';   //邮件基础接口类型

    public static $codeArr = [

        'formatReceiveEmail'                 => 1,

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_EMAIL;


    /**
     * @param $email
     * @return bool
     * 检查邮箱格式
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
     * @param $subject
     * @param $body
     * @return array
     * 邮件标题\内容处理
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
     * @param $emails
     * @return array
     * 收件人邮箱处理
     */
    private function formatReceiveEmail($emails){

        //$emailArr = [];

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

        if( empty($emailArr) ){

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
     * @param $attachment
     * @return array
     * 附件处理
     */
    private function formatAttachement($attachment){

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
     * @param $to
     * @param $subject
     * @param $body
     * @throws \Exception
     * 参数合并
     */
    private function mergeParams($to,$subject,$body,$attachment){

        //收件人
        $email = $this->formatReceiveEmail($to);
        //发送的标题及内容
        $data = $this->formatData($subject,$body);
        //附件处理
        $attachment = $this->formatAttachement($attachment);

        $this->params['multipart'] = array_merge($email,$data,$attachment);
    }

    /**
     * 系统邮件发送函数
     * @param mixed  $to    接收邮件者邮箱  或者 array('email_1@9douyu.com' => 'name1', 'email_2@9douyu.com' => 'name2');
     * @param string $subject 邮件主题
     * @param string $body    邮件内容
     * @param string $attachment 附件列表
     * @return boolean
     */
    public function sendEmail($to,$subject,$body,$attachment = []){
        
        $this->mergeParams($to,$subject,$body,$attachment);

        return $this->send();
    }



    /**
     * 系统邮件发送函数
     * @param mixed  $to    接收邮件者邮箱  或者 array('email_1@9douyu.com' => 'name1', 'email_2@9douyu.com' => 'name2');
     * @param string $subject 邮件主题
     * @param string $body    邮件内容
     * @param string $attachment 附件列表
     * @return boolean
     */
    public function sendHtmlEmail($to,$subject,$body,$attachment = []){

        $this->mergeParams($to,$subject,$body,$attachment);

        return $this->send('Html');
    }

    /**
     * @param $type
     * @return mixed
     * 发送邮件统一出口
     */
    private function send($type = ''){

        $return = HttpQuery::serverPost('/'.$this->emailType.$type,$this->params);

        return $return;
    }


}
