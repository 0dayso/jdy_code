<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/8
 * Time: 15:20
 */
namespace App\Http\Dbs\Current;

use App\Http\Dbs\JdyDb;

class UserCreditDb extends JdyDb{

    protected $table = 'current_user_credit';

    /**
     * @return mixed
     * 获取用户匹配零钱计划债权的记录总条数
     */
    public static function getCount(){

        return self::count();
    }

    /**
     * @return mixed
     * 添空用户匹配的零钱计划债权信息
     */
    public static function clear(){

        return \DB::table('current_user_credit')->truncate();
    }

    /**
     * @param $userId
     * @return mixed
     * 获取用户匹配的债权信息
     */
    public static function getByUserId($userId){

        return self::select('user_id','cash','credit')
            ->where('user_id',$userId)
            ->get()
            ->toArray();
    }


    public static function add($attributes){

        $model = new static($attributes, array_keys($attributes));

        $model->save();
    }
}