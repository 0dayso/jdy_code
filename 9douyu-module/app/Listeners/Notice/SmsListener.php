<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/3/8
 * Time: 上午9:23
 * Desc: 家庭账户事件监听
 */

namespace App\Listeners\Notice;

use App\Events\CommonEvent;
use App\Http\Models\Common\SmsModel;
use App\Lang\LangModel;

class SmsListener{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param CommonEvent $event
     * @return bool
     * @desc 授权成功发送短信去完成页
     */
    public function handle(CommonEvent $event)
    {

        $data = $event->getDataByKey('sms');

        try{

            //授权成功发送短信去完成页
            SmsModel::verifySms($data['phone'], LangModel::getLang('PHONE_ADD_FAMILY_ACCOUNT_SUCCESS'));

            \Log::info("PHONE_ADD_FAMILY_ACCOUNT_SUCCESS", ['phone' => $data['phone'], 'msg' => LangModel::getLang('PHONE_ADD_FAMILY_ACCOUNT_SUCCESS')]);

            \Session::put("FAMILY_ROLE_SUCCESS", $data['family_role']);

            return true;

        }catch (\Exception $e){

            \Log::Error(__METHOD__.__CLASS__.__LINE__.'Error', ['code' => $e->getCode(), 'msg' => $e->getMessage(), 'data' => $data]);

            return false;

        }


    }

}