<?php

namespace App\Http\Logics\User;

use App\Http\Logics\AppLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\User\UserInfoModel;
use Illuminate\Support\Facades\Request;
use Log;
use Cache;

class UserInfoLogic extends Logic
{

    /**
     * @param int $userId
     * @param array $data
     * @return array|mixed
     * @desc  计算风险承受能力
     */
    public function doSickAssessment( $userId, $data )
    {
        try{
            //验证用户ID
            ValidateModel::isUserId($userId);

            Cache::put('sickAssessment'.$userId, $data, 60);

            return self::callSuccess();

        }catch(\Exception $e){

            $attributes['data'] = $data;
            $attributes['msg'] = $e->getMessage();
            $attributes['code'] = $e->getCode();

            Log::error(__METHOD__ . 'Error', $attributes);

            return self::callError('提交失败,请稍后再试');
        }

    }

    /**
     * @param int $userId
     * @param array $data
     * @return array|mixed
     * @desc  计算风险承受能力
     */
    public function doSickAssessmentSecond( $userId, $data )
    {
        try{
            //验证用户ID
            ValidateModel::isUserId($userId);

            $cache = Cache::get('sickAssessment'.$userId);

            if(is_null($cache)){

                return self::callError('评估失败,请稍后再试');

            }

            $param = array_merge($cache,$data);

            $count = $this->doScore($param);

            $module     =   new UserInfoModel();

            $userInfo   =  $module->getUserInfo($userId);

            if( empty($userInfo ) ) {

                $data   =   [
                    'userId'            =>  $userId,
                    'assessment_score'  =>  $count,
                    'source_code'       =>  '',
                    'invite_code'       =>  '',
                    'ip'                =>  isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : Request::getClientIp(),
                ];

                $result =   $module -> doCreate($data);

            } else {

                $result = UserInfoModel::doAssessmentScore($userId,$count);

            }

            if($result){

                $type = UserInfoModel::assessmentType($count);

                Cache::forget('sickAssessment'.$userId);

                return self::callSuccess($type);
            }

            return self::callError($result);

        }catch(\Exception $e){

            $attributes['data'] = $data;
            $attributes['msg'] = $e->getMessage();
            $attributes['code'] = $e->getCode();

            Log::error(__METHOD__ . 'Error', $attributes);

            return self::callError($e->getMessage());
        }

    }

    /**
     * @param array $data
     * @return int
     * @desc  计算风险承受能力
     */
    public function doScore($data){

        $scoreArr = UserInfoModel::$scoreArr;

        $score  = 0;

        foreach($data as $key=>$value){
            $score += $scoreArr[$value];
        }

        return $score;

    }

    /**
     * @param int $userId
     * @return string
     * @desc  获取用户承受风险能力级别
     */
    public function getAssessmentType($userId){

        $model = new UserInfoModel();
        $info = $model->getUserInfo($userId);

        $type = '';
        if(!empty($info) && !is_null($info['assessment_score']) && $info['assessment_score'] !=0 ){
            $type = $model->assessmentType($info['assessment_score']);
        }

        return $type;

    }

    /**
     * @param $userId
     * @param $email
     * @return array
     * @desc 设置个人邮箱地址信息
     */
    public function setUserEmail( $userId , $email ){

        if(!((int)$userId)){
            return self::callError(trans('api.CODE_' . AppLogic::CODE_NO_USER_ID),AppLogic::CODE_NO_USER_ID);
        }

        try{
            $data['email'] = empty($email) ? '' : $email;

            if(!empty($data['email'])){
                //验证邮箱格式
                ValidateModel::isEmail($email);
            }

            //修改邮箱信息
            $userInfoModel = new UserInfoModel();

            $userInfoModel->updateUserInfo( $userId, $data );

        }catch(\Exception $e){
            \Log::error(__METHOD__, [$e->getMessage()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();

    }

    /**
     * @param $userId
     * @param $address
     * @return array
     * @desc 设置详细地址信息
     */
    public function setUserAddress( $userId , $address ){

        if(!((int)$userId)){
            return self::callError(trans('api.CODE_' . AppLogic::CODE_NO_USER_ID),AppLogic::CODE_NO_USER_ID);
        }

        try{
            //修改详细地址
            $userInfoModel = new UserInfoModel();

            $data['address_text'] = empty($address) ? '' : $address;
            $userInfoModel->updateUserInfo( $userId, $data );

        }catch(\Exception $e){
            \Log::error(__METHOD__, [$e->getMessage()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();

    }

    public function doWapScore( $userId, $data){

        try{
            //验证用户ID
            ValidateModel::isUserId($userId);

            $count = $this->doScore($data);

            $module     =   new UserInfoModel();

            $userInfo   =  $module->getUserInfo($userId);

            if( empty($userInfo ) ) {

                $data   =   [
                    'userId'            =>  $userId,
                    'assessment_score'  =>  $count,
                    'source_code'       =>  '',
                    'invite_code'       =>  '',
                    'ip'                =>  isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : Request::getClientIp()
                ];

                $result =   $module -> doCreate($data);
            } else {
                $result = UserInfoModel::doAssessmentScore($userId,$count);
            }

            if($result){
                $type = UserInfoModel::assessmentType($count);
                return self::callSuccess($type);
            }
        }catch(\Exception $e){

            $attributes['data'] = $data;
            $attributes['msg'] = $e->getMessage();
            $attributes['code'] = $e->getCode();

            Log::error(__METHOD__ . 'Error', $attributes);

            return self::callError($e->getMessage());
        }

    }

}
