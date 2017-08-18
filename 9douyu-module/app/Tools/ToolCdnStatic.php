<?php
namespace App\Tools;
/**
 +------------------------------------------------------------------------------
 * 基于CDN 应用类扩展
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Util
 * @author    wangxuesong <wang.xuesong@9douyu.com>
 * @version     2015-08-19 04:59:14  wangxuesong $
 +------------------------------------------------------------------------------
 */
  
class ToolCdnStatic{
   
      /**
     * Tab statics
     *
     * @var    string
     * @since  11.1
     */
   
  public static function statics($params=null,$version=null)
    {
        $switch=env('TMPL_PARSE_STRING.__switch__');
        if($switch!='on' || env('APP_ENV', 'production') != 'production'){
          return $params;
          exit;
         }
        if(isset($_SERVER['HTTPS']))
        {
            if ($_SERVER['HTTPS'] != "on" )
            {
                $_cdnurl="http://".env('TMPL_PARSE_STRING.__CDNURL2__');
            }else{
                //当协议为http时显示方式
                $serverhttps=env('TMPL_PARSE_STRING.__SERVERHTTPS__');

                if($serverhttps=="https"){
                    $_cdnurl="https://".env('TMPL_PARSE_STRING.__CDNURL__');
                }elseif($serverhttps=="http"){
                    $_cdnurl="http://".env('TMPL_PARSE_STRING.__CDNURL__');
                }else{
                    return $params;
                    exit;
                }

            }

        }else{
            $_cdnurl="http://".env('TMPL_PARSE_STRING.__CDNURL2__');
        }

	    $key=env('TMPL_PARSE_STRING.__key__'); 
		/*if(empty($version)){
			$version=env('STATIC_VERSION').env('TOKEN_VERSION');
		}
		*/
        $version=env('STATIC_VERSION').env('TOKEN_VERSION');
         if(strpos($params,"www.9douyu.com")!==false ||strpos($params,"api.9douyu.com")!==false ){
             $paramser=parse_url($params);
             $params=$paramser['path'];
         }
		$arr=explode("/",$params); 
        if(empty($cdnurl)){
          $url=$_cdnurl.$params;
        }else{
          $url=$cdnurl.$params;
        } 
		
       
       if($version){
		  
		 $nome=explode(".",$arr[count($arr)-1]);
		 $encrypt='s'.substr(md5($version.$key.$arr[count($arr)-1]),0,8);
		 $md5keyname=$nome[0].'.';
		 if(count($nome)>2){
			for($i=1;$i<count($nome)-1;$i++){
				$md5keyname.=$nome[$i].".";
			 }
		 }
		 $md5keyname.=$encrypt.'.'.$nome[count($nome)-1];  
         $url=str_replace($arr[count($arr)-1],$md5keyname,$url);
	   }  
        return $url;     
    }

}