<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 11:54
 * Desc: 每月1号00:05分清空上月充值记录
 */
namespace App\Console\Commands\Hour\AccessToken;
use App\Http\Logics\Pay\RechargeLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Console\Command;
use Log;

class Core extends Command{

    //计划任务唯一标识
    protected $signature = 'AccessTokenCore';

    //计划任务描述
    protected $description = 'Every hour 00 minute update access_token from core.';


    public function handle(){
        echo date("Y-m-d H:i:s")." : Start AccessTokenUpdateCore\n";
        Log::info('Start AccessTokenUpdateCore.');

        try {
            $tokenInfo = HttpQuery::corePost('/oauth/access_token', [
                'grant_type'        => 'client_credentials',
                'client_id'         => env('OAUTH_CORE_CLIENT_ID'),
                'client_secret'     => env('OAUTH_CORE_CLIENT_SECRET'),
            ]);
            $authorizationStr = "{$tokenInfo['token_type']} {$tokenInfo['access_token']}"; 
            $systemConfigModel = new SystemConfigModel();
            $systemConfigModel->doUpdateByKey('ACCESS_TOKEN_CORE', $authorizationStr);
            
        } catch(\Exception $e) {
            Log::error('Error on update access_token for the core: ' . $e->getMessage() . '[' . $e->getCode() . ']');
        }

        Log::info('End AccessTokenUpdateCore.');
        echo date("Y-m-d H:i:s")." : End AccessTokenUpdateCore\n";

    }
}
