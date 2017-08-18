<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/13
 * Time: 上午10:42
 */
namespace App\Http\Logics\User;

use App\Http\Dbs\InvestDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Dbs\UserDb;
use App\Http\Logics\Logic;

use App\Http\Logics\Order\WithdrawLogic;
use App\Http\Logics\Recharge\OrderLogic;
use App\Http\Models\Common\UserModel;

use App\Http\Models\Current\CurrentInterestHistoryModel;
use App\Http\Models\Invest\CurrentModel;
use App\Http\Models\Refund\ProjectModel;
use App\Http\Models\User\TermModel;
use App\Tools\ToolMoney;
use Log;
/**
 * 获取用户数据logic
 * Class RegisterLogic
 * @package App\Http\Logics\User
 */
class GetLogic extends Logic
{

    /**
     * @param null $phone 手机号
     * @return array
     */
    public function getBaseUserInfo($phone = null){
        try {
            UserModel::phoneLength($phone);

            $recordObj = UserModel::getBaseUserInfo($phone);
        }catch (\Exception $e){
            $data['phone']   = $phone;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }

        return self::callSuccess($recordObj);
    }

    /**
     * @param $user_id
     * @return array
     */
    public function getUserInfo($user_id){
        try {
            UserModel::isUserId($user_id);
            $recordObj = UserModel::getUserInfo($user_id);
        }catch (\Exception $e){
            $data['user_id']   = $user_id;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }
        return self::callSuccess($recordObj);
    }

    /**
     * @param $userId
     * @return array
     * @desc 获取零钱计划定期信息
     */
    public function getUserInterest($userId){

        try {
            UserModel::isUserId($userId);
            $recordObj['current'] = CurrentModel::getTotalInterestByUserId($userId);
            $recordObj['current']['seven_interest'] = CurrentInterestHistoryModel::getFundHistoryList($userId);
            $recordObj['project'] = ProjectModel::getRefundInterestByUserId($userId);
            $recordObj['project']['product_line'] = RefundRecordDb::getRefundTotalByUserId($userId);
            $recordObj['project']['no_full_at'] = InvestDb::getUserNoFullAtProjectPrincipal($userId);
        }catch (\Exception $e){
            $data['user_id']   = $userId;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            Log::error(__METHOD__.'Error', $data);
            return self::callError($e->getMessage());
        }
        return self::callSuccess($recordObj);

    }

    /**
     * @param $userIds
     * @return array
     * @desc 通过ids获取用户列表
     */
    public function getListByUserIds($userIds)
    {

        $userIds = explode(',', $userIds);

        try {

            if( is_array($userIds) ){

                foreach( $userIds as $userId ){

                    UserModel::isUserId($userId);

                }

            }else{

                UserModel::isUserId($userIds);

            }

            $userDb = new UserDb();

            $list = $userDb->getUserListByUserIds($userIds);

        }catch (\Exception $e){

            $data['user_id'] = $userIds;

            $data['msg']     = $e->getMessage();

            $data['code']    = $e->getCode();

            Log::error(__METHOD__.'Error', $data);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($list);

    }

    /**
     * @param $param
     * @return array
     * @author lgh
     * @desc 获取用户列表[管理后台]
     */
    public function getAdminUserListAll($param){

        $page  = $param['page'];
        $pageSize  = $param['size'];
        $condition  = $this->formatSearchInput($param);
        try{
            $userDb = new UserDb();
            $userList = $userDb->getAdminUserListAll($pageSize, $condition);
        }catch(\Exception $e){
            Log::error(__METHOD__.'Error', $param);

            return self::callError($e->getMessage());
        }

        return self::callSuccess($userList);

    }

    /**
     * @desc 格式化搜索条件
     * @param $param
     * @return array
     */
    public function formatSearchInput($param){

        //获取用户列表的条件
        $condition  = [];
        //手机号
        if(!empty($param['phone'])){
            $phone = $param['phone'];
            $condition['phone'] =   $phone;
        }
        //时间区间
        if(!empty($param['startTime'])){
            $startTime = $param['startTime'];
            $condition[]  = ['created_at','>=', $startTime];
        }
        if(!empty($param['endTime'])){
            $endTime = $param['endTime'];
            $condition[]  = ['created_at','<=', $endTime." 23:59:59"];
        }
        //姓名查询
        if(!empty($param['real_name'])){
            $realName = $param['real_name'];
            $condition[]  = ['real_name','like', "%".$realName."%"];
        }
        //身份证查询
        if(!empty($param['identity_card'])){
            $idCard = $param['identity_card'];
            $condition[]  = ['identity_card','like', "%".$idCard."%"];
        }
        return $condition;
    }

    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 根据开始结束日期获取用户注册数
     */
    public function getUserAmountByDate($start = '',$end = ''){

        $db   = new UserDb();

        $list = $db->getUserAmountByDate($start,$end);

        return $list;
    }

    /**
     * @return mixed
     * @desc 获取总注册数
     */
    public function getUserTotal(){

        $db    = new UserDb();

        $total = $db->getUserTotal();

        return $total;
    }

    /**
     * @desc 获取用户数据
     * @param $all
     * @return array
     */
    public function getUserStatistics($all){

        $termModel = new TermModel();

        try{
            $where = $this->formatGetUserInput($all);
            $result = $termModel->getUserStatistics($where);
        }catch(\Exception $e){
            Log::error(__METHOD__.'Error', $all);

            return self::callError($e->getMessage());
        }

        return self::callSuccess($result);
    }

    /**
     * @desc 格式化用户条件输入
     * @param $data
     * @return array
     */
    public function formatGetUserInput($data){
        $attribute                     = [];

        $attribute['start_time']       = isset($data['start_time']) ? $data['start_time'] : null;
        $attribute['end_time']         = isset($data['end_time']) ? $data['end_time'] : null;

        return $attribute;
    }

    /**
     * @desc 获取当天生日的用户
     * @return array
     */
    public function getBirthdayUser(){
        $userModel = new UserModel();
        try{
            $result = $userModel->getBirthdayUser();
        }catch(\Exception $e){
            return self::callError($e->getMessage());
        }
        return self::callSuccess($result);
    }

    /**
     * @desc 通过多个身份证号获取用户数据
     * @param $idCards
     * @return array
     */
    public function getUserByIdCards($idCards){

        $idCards = explode(',', $idCards);

        $userModel = new UserModel();
        try{
            $result = $userModel->getUserByIdCards($idCards);
        }catch(\Exception $e){
            return self::callError($e->getMessage());
        }
        return self::callSuccess($result);
    }

    /**
     * @desc 通过多个手机号获取用户数据
     * @param $phones
     * @return array
     */
    public function getUserByPhones($phones){
        $phones = explode(',', $phones);

        $userModel = new UserModel();
        try{
            $result = $userModel->getUserByPhones($phones);
        }catch(\Exception $e){
            return self::callError($e->getMessage());
        }
        return self::callSuccess($result);
    }
}