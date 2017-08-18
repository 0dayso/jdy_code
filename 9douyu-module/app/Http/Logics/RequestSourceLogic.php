<?php

namespace App\Http\Logics;

use Log;
/**
 * 请求来源 用 RequestSourceLogic::getSource(); 获取请求来源
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/29
 * Time: 下午2:33
 */
 class RequestSourceLogic{

     private static $instance         = null;

     const SOURCE_IOS                 = 'ios';
     const SOURCE_WAP                 = 'wap';
     const SOURCE_PC                  = 'pc';
     const SOURCE_ANDROID             = 'android';
     const SOURCE_PUFUBAO             = 'pfb';

     private $source                  = '';


     //5 普付宝
     public static $clientSource      = [1=>'pc', 2=>'wap', 3=>'ios', 4=>'android', 5=>'pfb'];

     private function __construct(){}

     /**
      * 初始化session 验证参数 便于全局调用 不用 分层传递
      * @return RequestSourceLogic|null
      */
     public static function getInstance(){
         if(is_null(self::$instance)) {
             self::$instance = new self;
         }
         return self::$instance;
     }

     /**
      *
      * 判断是不是手机版本
      */
     public static function isMobile() {
         $_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
         $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
         $mobile_browser = '0';
         if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
             $mobile_browser++;
         }
         if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false)) {
             $mobile_browser++;
         }
         if(isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
             $mobile_browser++;
         }
         if(isset($_SERVER['HTTP_PROFILE'])) {
             $mobile_browser++;
         }
         $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
         $mobile_agents = array(
             'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
             'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
             'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
             'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
             'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
             'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
             'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
             'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
             'wapr','webc','winw','winw','xda','xda-'
         );
         if(in_array($mobile_ua, $mobile_agents)) {
             $mobile_browser++;
         }
         if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false) {
             $mobile_browser++;
         }
         // Pre-final check to reset everything if the user is on Windows
         if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false) {
             $mobile_browser=0;
         }
         // But WP7 is also Windows, with a slightly different characteristic
         if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false) {
             $mobile_browser++;
         }
         if($mobile_browser>0)  {
             return true;
         } else {
             return false;
         }
     }

     /**
      * 获取来源标识符
      * @param null $client
      *
      * @return string
      */
     public static function setSource($client = null){

         $request    = app('request');
         $client     = $request->input('client', $client);

         $from       = $request->input('request_source', $client);
         $from       = isset($from) ? strtolower($from) : null;
         $obj        = self::getInstance();
         if(empty($from)){
            if(self::isMobile()){
                 $from = self::SOURCE_WAP;
            }else{
                 $from = self::SOURCE_PC;
            }
         }

         if(!in_array($from, self::$clientSource)){

             Log::error('异常请求来源：'. $from);

             //reset pc
             $from = self::SOURCE_PC;
         }

         $obj->source = $from;
     }

     /**
      * 获取来源标识符
      * @return string
      */
     public static function getSource(){
         return self::getInstance()->source;
     }


     /**
      * @param string $from
      * @return mixed
      * @desc 通过来源字符串获取对应的值
      */
     public static function getSourceKey( $from=self::SOURCE_PC )
     {

         $sourceArr = self::$clientSource;

         return array_search($from, $sourceArr);
     }

     /**
      * @param int $key
      * @return mixed|string
      * @desc 通过key获取来源字符串
      */
     public static function getSourceString( $key=1 )
     {

         $sourceArr = self::$clientSource;

         return isset($sourceArr[$key]) ? $sourceArr[$key] : '';

     }


     /**
      * 是否客户端访问
      *
      * @return bool
      */
     public static function isAppRequest(){
         $requestFrom = self::getSource();
         if($requestFrom == self::SOURCE_ANDROID || $requestFrom == self::SOURCE_IOS || $requestFrom == self::SOURCE_PUFUBAO){
             return true;
         }
         return false;
     }

     /**
      * @return array
      * @desc 获取浏览器的信息
      */
     public static function getSourceBrowserString()
     {
         $userAgent     = $_SERVER['HTTP_USER_AGENT'];
         if(preg_match('/MicroMessenger\/([^\s]+)/i',$userAgent, $pregInfo)){
             $browser   =   'weChat';
             $version   =   $pregInfo[1];
         }elseif(preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $userAgent, $pregInfo)){
             $browser   =   'OmniWeb';
             $version   =   $pregInfo[2];
         }elseif(preg_match('/Netscape([\d]*)\/([^\s]+)/i', $userAgent, $pregInfo)){
             $browser   =   'Netscape';
             $version   =   $pregInfo[2];
         }elseif(preg_match('/MSIE\s([^\s|;]+)/i', $userAgent, $pregInfo)) {
             $browser   =   'Internet Explorer';
             $version   =   $pregInfo[1];
         }elseif(preg_match('/Opera[\s|\/]([^\s]+)/i', $userAgent, $pregInfo)) {
             $browser   =   'Opera浏览器';
             $version   =   $pregInfo[1];
         }elseif(preg_match('/Maxthon/i', $userAgent, $pregInfo)) {
             $browser   =   'Maxthon(傲游)';
             $version   =   '';
         }elseif(preg_match('/360SE/i', $userAgent, $pregInfo)) {
             $browser   =   '360SE';
             $version   =   '';
         }elseif(preg_match('/SE 2.x/i', $userAgent, $pregInfo)) {
             $browser   =   '搜狗';
             $version   =   '';
         }elseif(preg_match('/FireFox\/([^\s]+)/i', $userAgent, $pregInfo)) {
             $browser   =   'FireFox';
             $version   =   $pregInfo[1];
         }elseif(preg_match('/Lynx\/([^\s]+)/i', $userAgent, $pregInfo)) {
             $browser   =   'Lynx';
             $version   =   $pregInfo[1];
         }elseif(preg_match('/Chrome\/([^\s]+)/i', $userAgent, $pregInfo)){
             $browser   =   'Chrome';
             $version   =   $pregInfo[1];
         }elseif(preg_match('/safari\/([^\s]+)/i', $userAgent, $pregInfo)){
             $browser   =   'Safari';
             $version   =   $pregInfo[1];
         }else{
             $browser   = 'unknown browser';
             $version   = 'unknown browser version';
         }
         return ['client'=>self::getUserAgentOs(),'version'=>$browser."/".$version,'message' =>$userAgent];
     }

     /**
      * @return string
      * @desc 获取登录的系统信息
      */
     public static function getUserAgentOs()
     {
         $userAgent   =  $_SERVER['HTTP_USER_AGENT'];
         if(preg_match('/win/i', $userAgent) && preg_match('/nt 6.0/i', $userAgent)){
             $agentOs = 'Windows Vista';
         }elseif(preg_match('/win/i', $userAgent) && preg_match('/nt 6.1/i', $userAgent)){
             $agentOs = 'Windows 7';
         }elseif(preg_match('/win/i', $userAgent) && preg_match('/nt 6.2/i', $userAgent)){
             $agentOs = 'Windows 8';
         }elseif(preg_match('/win/i', $userAgent) && preg_match('/nt 10.0/i', $userAgent)){
             $agentOs = 'Windows 10';
         }elseif(preg_match('/win/i', $userAgent) && preg_match('/nt 5.1/i', $userAgent)){
             $agentOs = 'Windows XP';
         }elseif(preg_match('/win/i', $userAgent) && preg_match('/Phone/i', $userAgent)){
             $agentOs = 'Windows Phone';
         }elseif(preg_match('/iPhone/i', $userAgent) || preg_match('/iPad/i', $userAgent)){
             $agentOs = 'iPhone os';
         }elseif(preg_match('/mac/i', $userAgent)){
             $agentOs = 'Mac OS X';
         }elseif(preg_match('/android/i', $userAgent)){
             $agentOs = 'Android';
         }elseif(preg_match('/Mobile/i', $userAgent)){
             $agentOs = 'unknown Mobile';
         }elseif(preg_match('/unix/i', $userAgent)){
             $agentOs = 'Unix';
         }elseif(preg_match('/PowerPC/i', $userAgent)){
             $agentOs = 'PowerPC';
         }else{
             $agentOs = 'unknown system';
         }
         return $agentOs;
     }
 }