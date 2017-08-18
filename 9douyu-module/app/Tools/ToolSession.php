<?php
namespace App\Tools;
/**
 +------------------------------------------------------------------------------
 * 兼容ThinkPHP U
 +------------------------------------------------------------------------------
 */

use Session;
  
class ToolSession{
    /**
     * 取完即销
     * @param $path
     * @param $vars
     *
     * @return string
     */
    public static function getAndForget($key) {
        $res = Session::get($key);
        
        Session::forget($key);
        
        return $res;
    }
}