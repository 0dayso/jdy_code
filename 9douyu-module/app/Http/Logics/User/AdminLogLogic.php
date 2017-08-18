<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/28
 * Time: 上午9:16
 */

namespace App\Http\Logics\User;

use App\Http\Logics\Logic;
use App\Http\Models\User\AdminLogModel;
use Log;

class AdminLogLogic extends Logic
{

    /**
     * @param $data
     * @throws \Exception
     * @return array
     * @desc 添加后台操作记录
     */
    public function createRecord( $data ){

        try{

            $model = new AdminLogModel();
            $model->createRecord($data);
            return self::callSuccess();

        }catch(\Exception $e){

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage()]);
            return self::callError($e->getMessage());

        }

    }
}