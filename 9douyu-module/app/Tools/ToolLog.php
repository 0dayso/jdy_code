<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 2016/12/2
 * Time: 13:24
 */



namespace App\Tools;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ToolLog{

    /**
     * @param $name
     * @return Logger
     * 获取Monolog实例
     */
    public static function getLogger($name) {
        
        $logger = new Logger($name);
        $date = date('Y-m-d', time());
        $file_name = $name . '-' . $date . '.log';
        $path = storage_path() . '/logs/' . $file_name;
        $stream = new StreamHandler($path, Logger::INFO);
        $logger->pushHandler($stream);
        return $logger;
        
    }
}

