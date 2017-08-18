<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/22
 * Time: 下午4:36
 */

namespace App\Http\Logics\CurrentNew;


use App\Http\Logics\Logic;
use App\Http\Models\CurrentNew\AccountModel;
use App\Http\Models\CurrentNew\UserModel;
use App\Lang\LangModel;
use App\Tools\ToolTime;

class UserLogic extends Logic
{

    /**
     * @param int $userId
     * @return array
     * 用户余额
     */
    public static function getUserAmount( $userId=0 ){

        if(!$userId){

            return self::callSuccess(LangModel::getLang('ERROR_PARAMS'));

        }

        try{

            $balance = UserModel::getUserAmount( $userId );

        }catch(\Exception $e){

            return self::callError($e->getMessage());

        }

        return $balance;

    }

    public static function doUserInvestOut( $date ){

        $date = empty($date) ? ToolTime::getDateBeforeCurrent() : $date;

        try{

            $model = new AccountModel();


        }catch(\Exception $e){



        }

        return self::callSuccess();

    }

}