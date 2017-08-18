<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/7/5
 * Time: 11:54
 * Desc: 每小时执行
 */
namespace App\Console\Commands\Hour\AccessToken;
use App\Http\Dbs\SystemConfig\SystemConfigDb;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Console\Command;
use Log;

class Server extends Command{

    //计划任务唯一标识
    protected $signature = 'AccessTokenServer';

    //计划任务描述
    protected $description = 'Every hour 00 minute update access_token from server.';


    public function handle(){
        echo date("Y-m-d H:i:s")." : Start AccessTokenUpdateServer\n";
        Log::info('Start AccessTokenUpdateServer.');

        $authorizationStr = '';

        try {
            $tokenInfo = HttpQuery::serverPost('/oauth/access_token', [
                'grant_type'        => 'client_credentials',
                'client_id'         => env('OAUTH_SERVER_CLIENT_ID'),
                'client_secret'     => env('OAUTH_SERVER_CLIENT_SECRET'),
            ]);
            $authorizationStr = "{$tokenInfo['token_type']} {$tokenInfo['access_token']}"; 
            $systemConfigModel = new SystemConfigModel();
            $systemConfigModel->doUpdateByKey('ACCESS_TOKEN_SERVER', $authorizationStr);


        } catch(\Exception $e) {
            Log::error('Error on update access_token for the server: ' . $e->getMessage() . '[' . $e->getCode() . ']');
        }

        Log::info('End AccessTokenUpdateServer.');

        //更新core保存的service_access_token
        if($authorizationStr){

            $value = $second_des = serialize($authorizationStr);

            $result = \App\Http\Models\Common\CoreApi\SystemConfigModel::doEditConfigByKey('ACCESS_TOKEN_SERVER','Service Access Token',$value,1,SystemConfigDb::STATUS_OPEN,$second_des);

            if(!$result['status']){

                Log::error('Error on update service_access_token for the core: ' . $result['msg'] );

            }

        }
        echo date("Y-m-d H:i:s")." : End AccessTokenUpdateServer\n";


    }
    
    
}
