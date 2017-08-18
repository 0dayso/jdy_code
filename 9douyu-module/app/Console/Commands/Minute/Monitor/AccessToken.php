<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/20
 * Time: 16:51
 */

namespace App\Console\Commands\Minute\Monitor;
use App\Http\Logics\Monitor\AccessTokenLogic;
use Illuminate\Console\Command;
use Log;

class AccessToken extends Command{


    //计划任务唯一标识
    protected $signature = 'monitor:AccessToken';

    //计划任务描述
    protected $description = '每五分钟检查token是否及时更新';


    public function handle(){
        Log::info('Start AccessTokenMonitor.');

        $logic = new AccessTokenLogic();
        $logic->accessTokenMonitor();

        Log::info('End AccessTokenMonitor.');
    }

}