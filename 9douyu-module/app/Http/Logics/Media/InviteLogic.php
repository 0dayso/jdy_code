<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/1
 * Time: 13:47
 */

namespace App\Http\Logics\Media;

use App\Http\Dbs\Media\InviteDb;
use App\Http\Logics\Logic;

class InviteLogic extends Logic{


    /**
     * @param $userId
     * @param $channelId
     * @param $appRequest
     * 添加渠道注册信息
     */
    public function addRecord($userId,$channelId,$appRequest,$version=''){

        $db = new InviteDb();

        try{

            $result = $db->addRecord($userId,$channelId,$appRequest);

            if($result){

                return self::callSuccess();
            }else{

                return self::callError('添加渠道用户信息失败');
            }

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

    }
}