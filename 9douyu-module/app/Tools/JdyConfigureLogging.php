<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/9/23
 * Time: 下午2:28
 */

namespace App\Tools;

use Illuminate\Foundation\Bootstrap\ConfigureLogging;

use Monolog\Handler\SyslogHandler;
use Monolog\Logger as Monolog;

use Illuminate\Log\Writer;

use Illuminate\Contracts\Foundation\Application;

use Monolog\Handler\StreamHandler;

use Monolog\Handler\RotatingFileHandler;
/**
 * 自定义配置
 *
 * Class JdyConfigureLogging
 * @package App\Tools
 */
class JdyConfigureLogging extends ConfigureLogging
{

    
    /**
     * daily 升级版 根据 level 分文件存储
     *
     * @param Application $app
     * @param Writer $log
     */
    protected function configureDailylevelHandler(Application $app, Writer $log)
    {
        $bubble     = false;

        $config     = $app->make('config');

        $maxFiles   = $config->get('app.log_max_files');

        $maxFiles   = is_null($maxFiles) ? 5 : $maxFiles;

        //分类日志【还可以记录异常 以及 区分区分等级后的sql 日志】
        // Stream Handlers
        $infoStreamHandler    = new RotatingFileHandler(storage_path("/logs/laravel_info.log"), $maxFiles, Monolog::INFO, $bubble);
        $warningStreamHandler = new RotatingFileHandler(storage_path("/logs/laravel_warning.log"), $maxFiles, Monolog::WARNING, $bubble);
        $errorStreamHandler   = new RotatingFileHandler(storage_path("/logs/laravel_error.log"), $maxFiles, Monolog::ERROR, $bubble);

        // 获取monolog 实例
        $monolog = $log->getMonolog();

        $monolog->pushHandler($infoStreamHandler);
        $monolog->pushHandler($warningStreamHandler);
        $monolog->pushHandler($errorStreamHandler);
        
        $syslogHandler = ToolSyslog::getSyslogHandler();
        $monolog->pushHandler($syslogHandler);
        /*
        //主日志
        $log->useDailyFiles(
            $app->storagePath().'/logs/laravel.log', is_null($maxFiles) ? 5 : $maxFiles,
            $config->get('app.log_level', 'debug')
        );
        */
    }





}