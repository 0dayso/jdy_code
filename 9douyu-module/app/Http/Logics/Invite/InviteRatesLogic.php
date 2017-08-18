<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/11/10
 * Time: 下午4:48
 * Desc: 邀请加息券相关
 */

namespace App\Http\Logics\Invite;

use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Invite\InviteRatesModel;
use App\Tools\ToolTime;

class InviteRatesLogic extends Logic{

    /**
     * @param $data
     * @return array
     * @desc 执行添加
     */
    public function doAdd($data){

        $model = new InviteRatesModel();

        if( $data['use_expire_time'] < ToolTime::dbDate() ){

            return self::callError('过期时间必须大于当前时间');

        }

        $data['use_expire_time'] = date('Y-m-d H:i:s', strtotime($data['use_expire_time']) + 86400 - 1);

        try{

            $model->doAdd($data);

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }

        return self::callSuccess($data);

    }

    /**
     * @param $id
     * @param $userId
     * @return array
     * @desc 执行使用
     */
    public function doUse($id, $userId){

        $model = new InviteRatesModel();

        try{

            $model->doUse($id, $userId);

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }

        return self::callSuccess($id);

    }

    /**
     * @param $userId
     * @return int
     * @desc 获取用户正在使用的加息利率
     */
    public function getUsingRateByUserIds($userIds){

        $model = new InviteRatesModel();

        $res = $model->getUsingRateByUserIds($userIds);

        return self::callSuccess($res);

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取可用列表
     */
    public function getCanUseListByUserId($userId){

        $model = new InviteRatesModel();

        $list = $model->getCanUseListByUserId($userId);

        return self::callSuccess($list);

    }

    /**
     * @param string $phone
     * @return mixed|string
     * @desc 通过用户id获取列表信息
     */
    public function getListByPhone($phone=''){

        if( empty($phone) ){

            return '';

        }

        $userInfo = UserModel::getBaseUserInfo($phone);

        if( !isset($userInfo['id']) || !$userInfo['id'] ){

            return '';

        }

        $model = new InviteRatesModel();

        return $model->getListByUserIds([$userInfo['id']]);

    }

    /**
     * @param $data
     * @return mixed
     * @desc 执行删除
     */
    public function doDel($data){

        $model = new InviteRatesModel();

        return $model->doDel($data['id'], $data['user_id']);

    }



}