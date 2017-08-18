<?php
/**
 * Created by PhpStorm.
 * User: lgh－dev
 * Date: 16/10/18
 * Time: 17:03
 */

namespace App\Listeners\User\LoginSuccess;
use App\Events\User\LoginSuccessEvent;
use App\Http\Logics\User\LoginLogic;
use App\Http\Models\User\UserInfoModel;

/**
 * @desc 登录成功后添加登录登录历史纪录
 * Class AddLoginHistoryListener
 * @package App\Listeners\User\LoginSuccess
 */
class AddLoginHistoryListener
{
    /**
     * AddLoginHistoryListener constructor.
     */
    public function __construct()
    {
        //
    }

    public function handle(LoginSuccessEvent $event){
        //登录成功后，添加登录成功的记录
        if($event->getUserId()){

            $clientSource       =   $event->getBrowserMessage();

            $data = [
                'user_id'       =>  $event->getUserId(),
                'login_ip'      =>  $event->getLoginIp(),
                'app_request'   =>  $event->getLoginSource(),
                'client_type'   =>  $clientSource['client'],
                'client_version'=>  $clientSource['version'],
                'client_note'   =>  $clientSource['message'],
            ];

            $loginLogic = new LoginLogic();

            $loginLogic->createUserLoginHistory($data);

            if( !empty($clientSource['uuid']) ){

                try{

                    $userInfoModel = new UserInfoModel();

                    $userInfoModel->updateUserInfo($data['user_id'], ['uuid' => $clientSource['uuid']]);

                }catch (\Exception $e){

                    \Log::Error(__METHOD__.'Error', ['msg' => $e->getMessage(), 'data' => $data]);

                }

            }

        }
    }
}