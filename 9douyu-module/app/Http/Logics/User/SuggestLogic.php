<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/6
 * Time: 14:33
 */
namespace App\Http\Logics\User;

use App\Http\Dbs\User\SuggestDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;

class SuggestLogic extends Logic{


    /**
     * @param $params
     * 记录用户反馈意见
     */
    public function addSuggest($params){


        try{

            if($params['user_id'] != 0){
                ValidateModel::isUserId($params['user_id']);
            }
            ValidateModel::checkSuggestContent($params['content']);
            
            $data = [
                'user_id'   => $params['user_id'],
                'content'   => $params['content'],
                'dev_info'  => $params['client'].' '.$params['version'].' '.$params['phone_type'].' '.$params['phone_version'].' '.$params['phone_system_version'],
            ];
            $db = new SuggestDb();
            $db->add($data);
            
            return self::callSuccess([]);
            
        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }
       
    }
}