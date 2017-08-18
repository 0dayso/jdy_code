<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/7
 * Time: 下午3:30
 */

namespace App\Http\Logics\Bonus;

use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\Bonus\UserBonusDb;
use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Batch\BatchListLogic;
use App\Http\Logics\Notice\NoticeLogic;
use App\Http\Logics\Project\RefundRecordLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Project\ProjectModel;
use App\Http\Models\Common\CoreApi\ProjectModel as CoreApiProjectModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;
use App\Tools\AdminUser;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use App\Http\Logics\Logic;
use App\Http\Models\Bonus\BonusModel;
use App\Http\Models\Bonus\UserBonusModel;
use App\Tools\ToolUrl;
use Log;
use Mockery\CountValidator\Exception;
use App\Http\Models\Common\TradingPasswordModel;
use Cache;
use App\Http\Logics\Oss\OssLogic;

class UserBonusLogic extends Logic
{
    private $model;

    const
        DEFAULT_BIRTHDAY_BONUS  =   270,//默认的生日红包配置
        DEFAULT_DIFF_TIME   =   31,  //红包使用数据查询最大的查询区间
        REFUND_THOUSAND_BONUS       =   244,    //回款大于1000的用户发送的红包id
        REFUND_TEN_THOUSAND_BONUS   =   223,   //回款大于1万的用户发送的红包
        REFUND_TEN_THOUSAND         = 10000, //发送1%加息券限制回款金额
        REFUND_THOUSAND             = 1000; //发送0.5%加息券限制回款金额

    public function __construct()
    {
        $this -> model = new UserBonusModel();
    }

    /**
     * @param $data
     * @return array
     * @desc 执行发放优惠券
     */
    public function doSendBonus($data){

        $data = self::filterParams($data);

        try{

            $bonusId = $data['bonus_id'];

            $phone   = $data['phone'];

            $userModel = new UserModel();

            $userInfo = UserModel::getBaseUserInfo($phone);

            //$userIds = $data['user_id'];

            $data['user_id'][] = $userInfo['id'];

            $bonusModel = new BonusModel();

            //验证红包id是否合法
            $this -> model -> checkBonusId($bonusId);

            //验证用户id是否合法
            ValidateModel::checkUserIds($data['user_id']);

            //验证红包是否可以发放
            $bonusInfo = $bonusModel->checkBonus($bonusId);

            //格式化发送数据
            $data   = $this->formatBonusInfo($bonusInfo, $data);

            //执行发放
            $result = $this->model->doSendBonus($data);

            Log::info(__CLASS__.__METHOD__.__LINE__."info",[$data]);


        }catch (\Exception $e){

            Log::error(__CLASS__.__METHOD__.__LINE__."ERROR",[$e->getCode(),$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);

    }


    /**
     * @param $userId
     * @param $bonusIds
     * @throws \Exception
     * 给用户发送多个红包
     */
    public function doSendBonusByUserId($userId,$bonusIds){

        $bonusModel = new BonusModel();

        try{
            //验证用户id是否合法
            ValidateModel::isUserId($userId);

            $data = [];

            foreach($bonusIds as $bonusId){
                //验证红包是否可以发放
                $bonusInfo[] = $bonusModel->checkBonus($bonusId);
                //格式化发送数据
                $data   = $this->formatMultiBonusInfo($bonusInfo, $userId);
            }

            //执行发放
            $result = $this->model->doSendBonus($data);

            return self::callSuccess($result);


        }catch (\Exception $e){

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage()]);

            return self::callError($e->getMessage());

        }


    }

    /**
     * @param $bonusId
     * @param $userIds
     * @return array
     * @desc 后台批量发送用户红包加息券
     */
    public function adminBatchSendBonusByUserIds($bonusId, $userIds)
    {

        $bonusModel = new BonusModel();

        try{

            $bonusInfo = $bonusModel->checkBonus($bonusId);

            $now = ToolTime::dbNow();

            $data = [];

            foreach ($userIds as $userId){

                $data[] = [
                    'bonus_id'     => $bonusId,
                    'send_user_id' => 1,
                    'get_time'     => $now,
                    'use_start_time' => $bonusInfo['effect_start_date'] ? $bonusInfo['effect_start_date'] : '0000-00-00',
                    'use_end_time' => $bonusInfo['effect_type'] == BonusDb::EFFECT_NOW ? $this->getBonusUseEndTimeByExpires($bonusInfo['expires']) : $bonusInfo['effect_end_date'],
                    'from_type'    => UserBonusDb::FROM_TYPE_ADMIN,
                    'memo'         => $bonusInfo['note'],
                    'user_id'      => $userId
                ];

            }

            $this->model->doSendBonus($data);

            return self::callSuccess($bonusId);

        }catch (\Exception $e){

            return self::callError($e->getMessage());

        }

    }

    /**
     * @param $expires
     * @return bool|string
     * @desc
     */
    private function getBonusUseEndTimeByExpires($expires)
    {

        return UserBonusModel::getUseEndTime(ToolTime::dbNow(), $expires);  //优惠券最后使用日期

    }

    /**
     * @param $data
     * @return mixed
     */
    public static function filterParams($data){

        //$params['user_id']      = (array)$data['user_id'];
        $params['phone']        = $data['phone'];
        $params['bonus_id']     = (int) $data['bonus_id'];
        $params['memo']         = $data['memo'];
        $params['from_type']    = $data['from_type'];
        $params['send_user_id'] = $data['send_user_id'];

        return $params;

    }

    /**
     * @param $bonusInfo
     * @param $data
     * @return array
     * @desc 格式化发送红包数据
     */
    public function formatBonusInfo($bonusInfo, $data){

        $getTime = ToolTime::dbNow();

        $userBonusBase = [

            'bonus_id'     => $bonusInfo['id'],   // 优惠券id
            'send_user_id' => AdminUser::getAdminUserId(), // manager发送人id
            'get_time'     => $getTime,           // 优惠券获取时间
            'use_start_time' => $bonusInfo['effect_start_date'] ? $bonusInfo['effect_start_date'] : '0000-00-00',
            'use_end_time' => $bonusInfo['effect_type'] == BonusDb::EFFECT_NOW ? UserBonusModel::getUseEndTime($getTime, $bonusInfo['expires']) : $bonusInfo['effect_end_date'],  //优惠券最后使用日期
            'from_type'    => $data['from_type'], // 优惠券发送来源
            'memo'         => $data['memo'],      // 备注

        ];

        foreach($data['user_id'] as $key => $item){

            $userBonus[$key] = $userBonusBase;
            $userBonus[$key]['user_id'] = $item;

        }

        return $userBonus;

    }


    /**
     * @param $bonusInfo
     * @param $userId
     * @param $memo
     * @return mixed
     * 给用户批量发送红包
     */
    public function formatMultiBonusInfo($bonusInfo,$userId,$memo=''){

        $getTime = ToolTime::dbNow();

        $sendManage = AdminUser::getAdminUserId();

        foreach($bonusInfo as $k => $bonus){

            $userBonus[] = [

                'bonus_id'     => $bonus['id'],   // 优惠券id
                'send_user_id' => $sendManage,        // manager发送人id
                'get_time'     => $getTime,           // 优惠券获取时间
                'use_start_time' => $bonus['effect_start_date'] ? $bonus['effect_start_date'] : '0000-00-00',
                'use_end_time' => $bonus['effect_type'] == BonusDb::EFFECT_NOW ? UserBonusModel::getUseEndTime($getTime, $bonus['expires']) : $bonus['effect_end_date'],  //优惠券最后使用日期
                'from_type'    => UserBonusDb::FROM_TYPE_USER, // 优惠券发送来源
                'memo'         => $memo,      // 备注
                'user_id'      => $userId

            ];
        }

        return $userBonus;
    }

    /**
     * @param $id
     * @param $investId
     * @return array
     * @desc 定期使用
     */
    public function doRegularUsedBonus($id, $investId=0){

        try{

            $model = new UserBonusModel();

            //验证是否存在
            $userBonusInfo = $model -> checkIsExits($id);

            //验证当前红包或加息券是否已使用
            $model->checkBonusIsUsed($userBonusInfo);

            //验证开始使用时间
            $model -> checkStartTime($userBonusInfo['use_start_time']);

            //验证是否已过期
            $model -> checkExpireTime($userBonusInfo['use_end_time']);

            //验证是否已锁定
            $model -> checkIsLock($userBonusInfo['lock']);

            //验证是否已使用
            $model -> checkIsUsed($userBonusInfo['used_time']);

            $data = $model -> doRegularUsedBonus($id, $investId);

            Log::info(__CLASS__.__METHOD__.__LINE__."info",[$data]);

        }catch (\Exception $e){

            Log::error(__CLASS__.__METHOD__.__LINE__."ERROR",[$e->getCode(),$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($data);

    }

    /**
     * @param $id
     * @param $userId
     * @param $investId
     * @return array
     * @desc 零钱计划使用
     */
    public function doCurrentUsedBonus($userId, $id, $investId=0){

        try{

            $model = new UserBonusModel();

            $bonusDb = new BonusDb();

            //验证是否存在
            $userBonusInfo = $model -> checkIsExits($id);

            //验证当前红包或加息券是否已使用
            $model->checkBonusIsUsed($userBonusInfo);

            $bonusId = $userBonusInfo['bonus_id'];

            $bonusInfo = $bonusDb -> getById($bonusId);

            //验证开始使用时间
            $model -> checkStartTime($userBonusInfo['use_start_time']);

            //验证是否已过期
            $model -> checkExpireTime($userBonusInfo['use_end_time']);

            //验证是否已锁定
            $model -> checkIsLock($userBonusInfo['lock']);

            //验证是否已使用
            $model -> checkIsUsed($userBonusInfo['used_time']);

            //验证当前是否有在使用的加息券
            $model -> checkCurrentBonusUsed($userId);

            $data = $model -> doCurrentUsedBonus($id, $bonusInfo['current_day'], $investId);

            Log::info(__CLASS__.__METHOD__.__LINE__."info",[$data]);

        }catch (\Exception $e){

            Log::error(__CLASS__.__METHOD__.__LINE__."ERROR",[$e->getCode(),$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($data);

    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户可用红包加息券
     */
    public function getBonus($userId){
        $result = [];
        $userBonusModel = new UserBonusModel();
        $bonus = $userBonusModel->getAbleUseListByUserId($userId);
        //格式化money
        foreach($bonus as $key=>$val){
            $result[$key] = $val;
            $result[$key]['bonus_info']['money'] = ToolMoney::formatDbCashDelete($val['bonus_info']['money']);
            $result[$key]['bonus_info']['min_money'] = ToolMoney::formatDbCashDelete($val['bonus_info']['min_money']);
        }
        return $result;
    }

    /**
     * @desc 获取用户可用红包列表
     * $userId int 用户id
     * $return max
     */
    public function getAbleUserBonusCount($userId){
        $ableUserBonus = $this->getBonus($userId);

        $count = 0;

        if(!empty($ableUserBonus)){
            foreach( $ableUserBonus as  $value )
            {
                //排除可用未生效的
                if( ToolTime::dbNow()  < $value['use_start_time'] )
                {
                    continue;
                }

                $count++;
            }
        }
        return $count;
    }

    /**
     * @desc 获取用户已过期的优惠券列表
     * @param $userId
     * @return array
     */
    public function getExpireListByUserId($userId){

        $result = [];
        $userBonusModel = new UserBonusModel();
        $bonus = $userBonusModel->getExpireListByUserId($userId);

        //格式化money
        foreach($bonus as $key=>$val){
            $result[$key] = $val;
            $result[$key]['bonus_info']['money'] = ToolMoney::formatDbCashDelete($val['bonus_info']['money']);
            $result[$key]['bonus_info']['min_money'] = ToolMoney::formatDbCashDelete($val['bonus_info']['min_money']);
        }
        return $result;
    }

    /**
     * @desc   获取用户所有未使用的的红包
     * @author lgh
     * @param  $userId
     * @return array
     */
    public function getNoUsedUserBonus($userId){
        $result = [];
        $userBonusModel = new UserBonusModel();
        $ableUserBonus  = $userBonusModel->getAbleUseListByUserId($userId); //获取用户优惠券可用列表
        $expireUserBonus  = $userBonusModel->getExpireListByUserId($userId);//获取用户优惠券过期列表
        $bonus = array_merge($ableUserBonus, $expireUserBonus);

        //格式化money
        if(!empty($bonus)){
            foreach($bonus as $key=>$val){
                $result[$key] = $val;
                $result[$key]['user_status'] = $this->getBonusStatus($val);
                $result[$key]['bonus_info']['money'] = ToolMoney::formatDbCashDelete($val['bonus_info']['money']);
                $result[$key]['bonus_info']['min_money'] = ToolMoney::formatDbCashDelete($val['bonus_info']['min_money']);
            }
        }
        return $result;
    }

    /**
     * @desc 获取用户所有的红包
     * @author lgh
     * @param $userId
     * @return array
     */
    public function getAllUserBonus($userId){
        $result = [];
        $userBonusModel = new UserBonusModel();
        $ableUserBonus  = $userBonusModel->getAbleUseListByUserId($userId); //获取用户优惠券可用列表
        $expireUserBonus  = $userBonusModel->getExpireListByUserId($userId);//获取用户优惠券过期列表
        $usedUserBonus    = $userBonusModel->getUsedListByUserId($userId); //获取已使用的优惠券
        $bonus = array_merge($ableUserBonus, $expireUserBonus, $usedUserBonus);

        //格式化money
        if(!empty($bonus)){
            foreach($bonus as $key=>$val){
                $result[$key] = $val;
                $result[$key]['user_status'] = $this->getBonusStatus($val);
                $result[$key]['bonus_info']['money'] = ToolMoney::formatDbCashDelete($val['bonus_info']['money']);
                $result[$key]['bonus_info']['min_money'] = ToolMoney::formatDbCashDelete($val['bonus_info']['min_money']);
            }
        }
        return $result;
    }

    /**
     * @param array $userBonusList
     * @return array
     * @desc 对用户红包状态进行分类
     */
    public function getFormatUserBonusStatus( $userBonusList = array() )
    {
        if( empty($userBonusList))
            return [];

        $formatList['used'] =   []; //已使用

        $formatList['past'] =   []; //已过期

        $formatList['useful']=  []; //可使用

        foreach ($userBonusList  as $key    =>  $item ){

            if($item['used_time'] != '0000-00-00 00:00:00'){

                $formatList['used'][]   =   $item;

            }elseif($item['use_end_time']< ToolTime::dbNow() && $item['used_time'] == '0000-00-00 00:00:00'){

                $formatList['past'][]   =   $item;

            }else{

                $formatList['useful'][]  =   $item;

            }
        }

        return $formatList;
    }
    /********************重构 API start 发送红包 ***********************/
    /**
     * @param $data
     * @return array
     * @desc 平台接口红包信息接口对接
     */
    public function doSendBonusApi($data)
    {

        $data = [
            'id'            => $data['id'],
            'bonus_id'      => $data['bonus_id'],
            'user_id'       => $data['user_id'],
            'send_user_id'  => $data['send_user_id'],
            'get_time'      => $data['get_time'],
            'use_end_time'  => $data['use_end_time'],
            'from_type'     => $data['from_type'],
            'memo'          => isset($data['memo']) ? $data['memo'] : '',
        ];

        try{

            $result = $this->model->doSendBonus($data);

        }catch (\Exception $e){

            $result = [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage(),
                'data'  => $data
            ];

            Log::Error($result);

            return self::callError($result);

        }

        return self::callSuccess($result);

    }
    /********************重构 API end 发送红包 ***********************/

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @param int $type
     * @return array
     * @desc app获取用户优惠券
     */
    public function getAppBonus($userId, $page, $size, $type = 1){

        $userBonusModel = new UserBonusModel();
        if($type == 1){

            $bonus = $userBonusModel->getAbleBonusByUserId($userId, $page, $size);

        }elseif($type == 2){

            $bonus = $userBonusModel->getExpireBonusByUserId($userId, $page, $size);

        }
        if(empty($bonus['list'])) {
            $bonus['list'] = [[]];
            $bonus['total']= 0;
            $bonus['url'] = ToolUrl::getAppBaseUrl() . "/app/topic/bonusDesc";
            return self::callSuccess($bonus);
        }

        $bonus['list'] = $this->formatAppBonusList( $bonus['list'] );
        $bonus['url'] = ToolUrl::getAppBaseUrl() . "/app/topic/bonusDesc";

        return self::callSuccess($bonus);

    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @param int $type
     * @return array
     * @desc App4.0获取用户优惠券
     */
    public function getUserBonusList($userId, $page, $size, $type = 1){

        $userBonusModel = new UserBonusModel();

        $bonus = [];

        //未使用优惠券(正在生效+未生效)
        if($type == 1){
            $bonus = $userBonusModel->getBonusList($userId, $page, $size);
        }

        //已使用优惠券
        if($type == 2){
            $bonus = $userBonusModel->getUsedBonusByUserId($userId, $page, $size);
        }

        //已过期优惠券(上一版本过期优惠券接口中包括了已使用优惠券,4.0版本进行拆分)
        if($type == 3){
            $bonus = $userBonusModel->getOutTimeBonusByUserId($userId, $page, $size);
        }

        $bonusList = $this->formatApp4BonusList( $bonus );

        return self::callSuccess($bonusList);
    }

    /**
     * @param $userId
     * @param $client
     * @return array
     * @desc 获取app活动期可用加息券
     */
    public function getAppUserCurrentAbleBonus($userId, $client){

        $clientArr      = BonusModel::getClientArr();

        $appRequest     = $clientArr[$client];

        $userBonusModel = new UserBonusModel();

        //正在使用中的加息券
        $usingBonus     = $userBonusModel -> getUsingCurrentBonusList($userId);

        $res            = $this -> formatCurrentUsingBonusInfo($usingBonus);

        //可用加息券信息
        $bonusList      = $userBonusModel -> getCurrentAbleUserBonusList($userId, $appRequest);

        $res['bonus']   = $this -> formatAppCurrentAbleBonusList($bonusList);
        $bonus          = array_filter($res['bonus']);
        $res['total']   = count($bonus);
        return self::callSuccess([$res]);

    }

    /**
     * @param $userId
     * @param $projectId
     * @param $client
     * @return array
     * @desc 获取本项目可用加息券
     */
    public function getAppUserUsableBonus($userId, $projectId, $client){

        $clientArr      = BonusModel::getClientArr();

        $appRequest     = $clientArr[$client];

        $projectInfo    = CoreApiProjectModel::getProjectDetail($projectId);

        if( empty($projectInfo) ){

            return self::callError('项目不存在');

        }

        $userBonusModel = new UserBonusModel();

        $bonusList      = $userBonusModel -> getAbleUserBonusListByProject($userId, $projectInfo['product_line'], $projectInfo['type'], $appRequest, $projectInfo['refund_type']);

        $data['list']   = $this -> formatAppBonusList($bonusList)?:[];

        $data['count']  = count($data['list']);

        return self::callSuccess($data);

    }

    /**
     * @param $data
     * @return array
     * @desc 零钱计划加息券列表数据格式化
     */
    public function formatAppCurrentAbleBonusList($data){
        $bonusDb = new BonusDb();
        if(empty($data)){
            return [[]];
        }
        //获取红包id的集合
        $bonusIds = ToolArray::arrayToIds($data, 'bonus_id');

        $bonusInfoList  = $bonusDb->getByIds($bonusIds);
        $bonusInfoList  = ToolArray::arrayToKey($bonusInfoList, 'id');

        foreach($data as $key => $value){
            //$bonusInfo = $bonusDb->getById($value['bonus_id']);
            $bonusInfo = isset($bonusInfoList[$value['bonus_id']]) ? $bonusInfoList[$value['bonus_id']] : '';

            $data[$key] = [
                'id'                => $value['id'],
                'name'              => $bonusInfo['name'],
                'rate_used_time'    => $value['rate_used_time'],
                'period'            => $bonusInfo['current_day'],
                'bonus_id'          => $value['bonus_id'],
                'rate'              => $bonusInfo['rate'],
                'user_id'           => $value['user_id'],
                'get_time'          => $value['get_time'],
                'used_time'         => $value['used_time'],
                'from_type'         => $value['from_type'],
                'use_end_time'      => date("Y-m-d H:i:s",strtotime($value['use_end_time'])-1),
                'memo'              => $value['memo'],
                'note1'             => "仅限零钱计划产品使用",
                'note2'             => "连续加息".$bonusInfo['current_day']."天",
                'note3'             => "有效期至".date("Y-m-d",strtotime($value['use_end_time'])-1),
            ];

        }

        return $data;

    }

    /**
     * @param $bonus
     * @return mixed
     * @desc 正在使用的零钱计划加息券数据格式化
     */
    public function formatCurrentUsingBonusInfo($bonus){

        if( empty($bonus) ){
            $data['is_use_bonus']   = 0;
            $data['use_bonus_info'] = '';
            return $data;
        }

        $bonusDb = new BonusDb();
        $bonusInfo = $bonusDb->getById($bonus['bonus_id']);

        $data['is_use_bonus']   = 1;
        $data['use_bonus_info'] = '+'.$bonusInfo['rate'].'%';

        return $data;

    }

    /**
     * @param $data
     * @param int $type
     * @return array
     * @desc 格式化app显示数据
     */
    public function formatAppBonusList( $data , $type = 1 ){

        if(empty($data)){
            return [];
        }
        $bonusDb = new BonusDb();
        //获取红包id的集合
        $bonusIds = ToolArray::arrayToIds($data, 'bonus_id');

        $bonusInfoList  = $bonusDb->getByIds($bonusIds);
        $bonusInfoList  = ToolArray::arrayToKey($bonusInfoList, 'id');

        foreach ($data as $key => $value) {


            //$bonusInfo = $bonusDb->getById($value['bonus_id']);
            $bonusInfo = isset($bonusInfoList[$value['bonus_id']]) ? $bonusInfoList[$value['bonus_id']] : '';
            //格式化红包数据
            $bonusInfo = BonusModel::getLable($bonusInfo);
            $bonusInfo['money'] = ToolMoney::formatDbCashDelete($bonusInfo['money']);
            $bonusInfo['min_money'] = ToolMoney::formatDbCashDelete($bonusInfo['min_money']);

            $cash = $bonusInfo['type'] != BonusDb::TYPE_CASH ? "0.0" : (string)round($bonusInfo['money']);

            $childName = '';
            if ($type) {
                $nameStr = $bonusInfo['type'] == BonusDb::TYPE_CASH ? $cash . '元红包' : $bonusInfo['rate'] . '%加息券';
            } else {
                $nameArr = explode('-', $bonusInfo['name']);
                if (count($nameArr) > 1) {
                    $nameStr = $nameArr[0];
                    $childName = $nameArr[1];
                } else {
                    $nameStr = $bonusInfo['name'];
                }
            }

            if ($bonusInfo['type'] == BonusDb::TYPE_CASH) {
                $bonus_type = 1;
            } else {
                $bonus_type = 2;
            }

            if ($bonusInfo['type'] == BonusDb::TYPE_COUPON_CURRENT) {
                $typeBonus = 2;
            } else {
                $typeBonus = 1;
            }

            if($value['used_time'] == UserBonusDb::USED_TIME){
                $value['used_time'] = 0;
            };

            $result[] = [
                'id' => $value['bonus_id'],
                'name' => $bonusInfo['name'],
                'nameTile' => $nameStr,
                'childName' => $childName,
                'cash' => $cash,
                'rate' => $bonusInfo['rate'],
                'bonus_type' => $bonus_type,
                'min' => $bonusInfo['min_money'],
                'end_time'=>date("Y-m-d H:i:s",strtotime($value['use_end_time'])-1),
                'using_range' => $bonusInfo['using_desc'],
                'used_time' => $value['used_time'],
                'user_bonus_id' => $value['id'],
                'use_type_description' => $bonusInfo['project_name'],
                'coupon_type' => $bonus_type,
                'type_bonus' => $typeBonus,
            ];

        }

        return $result;

    }

    /**
     * @param $data
     * @return array
     * @desc 格式化app4.0显示数据
     */
    public function formatApp4BonusList( $data ){

        if(empty($data)){
            return [];
        }

        $effectBonus    = [];
        $notEffectBonus = [];

        foreach($data as $item){

            $projectLine = BonusModel::getApp4Lable($item);

            $info = [
                'id'                => $item['id'],
                'name'              => $item['name'],
                'bonus_type'        => $item['bonus_type'],
                'min_money'         => (float)$item['min_money'],
                'min_money_note'    => $item['min_money']>0?sprintf('满%s元可用',(float)$item['min_money']):'无金额门槛',
                'using_desc'        => $projectLine['project_name'],
                'use_start_time'    => $item['effect_type'] == BonusDb::EFFECT_TIME ? $item['use_start_time'] : ToolTime::getDate($item['get_time']),
                'use_end_time'      => ToolTime::getAddDateByDays(-1,$item['use_end_time']),
                'current_day'       => !empty($item['current_day']) ? $item['current_day'] : '',
                'bonus_cash'        => !empty($item['money']) ? $item['money'] : '',
                'bonus_rate'        => (float)$item['rate'],
                //'bonus_rate_note'   => '%',
                //'bonus_cash_note'   => '￥',
                'bonus_value_note'  => $item['bonus_type'] == BonusDb::TYPE_CASH?'￥':'%',
                'bonus_value'       => $item['bonus_type'] == BonusDb::TYPE_CASH?(float)$item['money']:(float)$item['rate'],
            ];

            if($item['bonus_type'] == BonusDb::TYPE_COUPON_CURRENT){
                $info['min_money_note'] = '连续加息'.$item['current_day'].'天';
            }

            if($info['use_start_time'] > ToolTime::dbNow()){
                $info['not_effect'] = 'on';
                $notEffectBonus[]   = $info;
            }else{
                $effectBonus[] = $info;
            }


        }

        $bonusInfo = array_merge($effectBonus,$notEffectBonus);

        return $bonusInfo;

    }

    /**
     * @param $userId
     * @param $bonusId
     * @param $tradingPassword
     * @param $data
     * @param $isTrade
     * 使用零钱计划加息券
     */
    public function doUserCurrentBonus($data, $isTrade=true){

        try{

            $userId             = $data['user_id'];
            $tradingPassword    = empty($data['trading_password'])?'':$data['trading_password'];
            $bonusId            = $data['bonus_id'];
            $from               = $data['from'];

            ValidateModel::isUserId($userId);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);

            if($isTrade){

                //验证交易密码是否正确
                TradingPasswordModel::checkPassword($tradingPassword,$userInfo['trading_password']);

            }

            self::beginTransaction();
            //检测加息券是否可用
            $bounsLogic = new UserBonusModel();
            $bounsLogic->checkUserBonus($userId,$bonusId,$from);
            //锁定加息券
            UserBonusModel::addLock($bonusId);

            //使用加息券
            $result         = $this->doCurrentUsedBonus($userId,$bonusId);

            if($result['status']){

                self::commit();
                //获取加息券信息
                $bonusLogic = new UserBonusModel();
                $bonusInfo  = $bonusLogic->getUserBonusById($bonusId);
                //零钱计划加息券利率
                $addRate    = $bonusInfo['bonus_info']['rate'];

                $currentDay = $bonusInfo['bonus_info']['current_day'];
                if($currentDay == 1){
                    $return = [
                        'addRate'   => $addRate,
                        'note'      => date('m月d日'),
                    ];
                }else{
                    $endDate = ToolTime::getAddDateByDays($currentDay-1,ToolTime::dbNow());
                    $return = [
                        'addRate'   => $addRate,
                        'note'      => date('m月d日').'至'.date('m月d日',strtotime($endDate)),
                    ];
                }

            }else{

                self::rollback();

                return self::callError($result['msg']);
            }

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess($return);
    }

    /**
     * @desc  [管理后台] 红包延期处理操作
     * @param $userBonusId
     * @param $endTime
     * @return array
     */
    public function doDelayUserBonus($userBonusId, $endTime){

        $userBonusModel = new UserBonusModel();
        try{
            if(empty($userBonusId)){
                return self::callError('用户红包id为空');
            }
            if(empty($endTime)){
                return self::callError('红包使用截至时间为空');
            }
            $return = $userBonusModel->doDelayUserBonus($userBonusId, $endTime);
        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess($return, '红包延期成功');
    }

    /**
     * @param string $userId
     * @return array|mixed
     * @desc 获取新用户是否有指定的加息券,用户首页显示
     */
    public function getAppNewUserCurrentBonus( $userBonus=[] )
    {

        if( empty($userBonus) ){

            return 0;

        }

        $bonusConfig = SystemConfigModel::getConfig('CURRENT_BONUS_RATE_ID');

        $userBonus = ToolArray::arrayToIds($userBonus, 'bonus_id');

        return in_array($bonusConfig, $userBonus) ? 1 : 0;

    }

    /**
     * @desc 获取用户的红包状态
     * @param $userInfo
     * @return string
     */
    public function getBonusStatus($userInfo){
        $userStatus     = "可使用";
        if($userInfo['used_time'] != '0000-00-00 00:00:00'){
            $userStatus = "已使用";
        }
        if($userInfo['use_end_time']< ToolTime::dbNow() && $userInfo['used_time'] == '0000-00-00 00:00:00'){
            $userStatus = "已过期";
        }
        return $userStatus;
    }

    /**
     * @desc 每天自动给当天生日的用户发送红包逻辑
     * @desc lgh-dev
     * @return array|bool
     */
    public function sendBirthdayBonus(){

        $userLogic = new UserLogic();
        $batchListLogic = new BatchListLogic();
        $emailModel = new EmailModel();
        //获取当日生日的用户
        $birthdayData = $userLogic->getBirthdayUser();

        //姓名命中黑名单过滤掉
        foreach($birthdayData as $key=>$value){
            //姓名命中黑名单过滤掉
            $black = $emailModel->checkMessageInBlacklist($value['real_name']);
            if($black === true){
                unset($birthdayData[$key]);
            }
        }

        if (empty($birthdayData)) {

            return false;
        }

        Log::info(__CLASS__.__METHOD__."当日生日用户-BirthdayUser:",$birthdayData);

        //发送站内信
        $userIdArr = ToolArray::arrayToIds($birthdayData);

        $msg = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_BONUS_BIRTHDAY);

        NoticeLogic::batchSend(NoticeDb::TYPE_BONUS_BIRTHDAY, $userIdArr, $msg, NoticeDb::TYPE_BONUS_BIRTHDAY);

        //组装当日生日的用户id
        $userIdStr = ToolArray::arrayToStr($userIdArr, "\n");
        //写入的文件路径
        $filePath = '/uploads/' . ToolTime::dbDate() . '/' . strtolower(ToolStr::getRandStr(13)) . ".txt";


        //不存在目录时创建目录
        //$dirPath = base_path() . '/public/uploads/'.ToolTime::dbDate();
        //if (!is_dir($dirPath)) @mkdir($dirPath);
        //写入文件
        //@file_put_contents(base_path() . '/public/' . $filePath, $userIdStr);

        //改为Oss上传处理
        $oss = new OssLogic();
        $oss->writeFile( $userIdStr , $filePath );

        //读取系统中的生日红包配置
        $bonusArr   =   SystemConfigModel::getConfig('SYSTEM_SEND_USER_AWARD');
        $data = [
            'type' => 'bonus',
            'admin_id' => 1, //todo 计划任务
            'content' => isset($bonusArr['BIRTHDAY_BONUS'])?$bonusArr['BIRTHDAY_BONUS']:self::DEFAULT_BIRTHDAY_BONUS,
            'note' => LangModel::USER_BIRTHDAY_BONUS.ToolTime::dbDate(),
            'file_path' => $filePath,
        ];
        Log::info(__CLASS__.__METHOD__."batchData:",$birthdayData);
        try{
            $res = $batchListLogic->doAdd($data);
            if($res['status']){
                $batchListLogic->doAuditById($res['data']);
            }
        }catch(\Exception $e){
            Log::info(__CLASS__.__METHOD__.__LINE__."sendBirthday", [$e->getMessage()]);
            return self::callError($e->getMessage());
        }
        return self::callSuccess();
    }

    /**
     * @desc 发送今日回款用户加息券
     * @author lgh-dev
     * @return array|bool
     */
    public function sendTodayRefundUserBonus(){

        $refundRecordLogic = new RefundRecordLogic();
        $batchListLogic    = new BatchListLogic();

        $todayRefundUser = $refundRecordLogic->getRefundUserByDate(ToolTime::getDateAfterCurrent());
        if (empty($todayRefundUser)) {

            return false;
        }
        //$userIdArr = ToolArray::arrayToIds($todayRefundUser, 'user_id');
        try{
             //回款发送加息券的配置
            $refundConfig   =   SystemConfigModel::getConfig('REFUND_SEND_BONUS_RATE');

            $maxBaseCash    =   isset($refundConfig['REFUND_TEN_THOUSAND']) ? $refundConfig['REFUND_TEN_THOUSAND'] : self::REFUND_TEN_THOUSAND;

            $minBaseCash    =   isset($refundConfig['REFUND_THOUSAND']) ? $refundConfig['REFUND_THOUSAND'] : self::REFUND_THOUSAND;
            ################################回款金额大于等于一万用户ID发送1%加息券###########################
            $tenThousandUserIdArr = $this->getRefundTenThousandUser($todayRefundUser);

            if(!empty($tenThousandUserIdArr)){
                Log::info(__CLASS__.__METHOD__."回款大于".$maxBaseCash."用户Id:",$tenThousandUserIdArr);
                //组装当日生日的用户id
                $userIdStr = ToolArray::arrayToStr($tenThousandUserIdArr,"\n");

                //文件路径
                $filePath = '/uploads/' . ToolTime::dbDate() . '/' . strtolower(ToolStr::getRandStr(13)) . "_ten_thousand.txt";
                //不存在目录时创建目录
                //$dirPath = base_path() . '/public/uploads/'.ToolTime::dbDate();
                //if (!is_dir($dirPath)) @mkdir($dirPath);
                //写入文件
                //@file_put_contents(base_path() . '/public/' . $filePath, $userIdStr);

                //改为Oss上传处理
                $oss = new OssLogic();
                $oss->writeFile( $userIdStr , $filePath );

                $data = [
                    'type' => 'bonus',
                    'admin_id' => 1, //todo 计划任务
                    'content' => isset($refundConfig['REFUND_TEN_THOUSAND_BONUS']) ? $refundConfig['REFUND_TEN_THOUSAND_BONUS'] :self::REFUND_TEN_THOUSAND_BONUS,
                    'note' => LangModel::USER_REFUND_BONUS."1%加息券".ToolTime::dbDate(),
                    'file_path' => $filePath,
                ];
                $res = $batchListLogic->doAdd($data);
                if($res['status']){
                    $batchListLogic->doAuditById($res['data']);
                }
            }

            ##############回款金额介于1000～10000的用户发送0.5%的加息券###############################
            $thousandUserIdArr = $this->getRefundThousandUser($todayRefundUser);

            if(!empty($thousandUserIdArr)){
                Log::info(__CLASS__.__METHOD__."回款介于".$minBaseCash."~".$maxBaseCash."用户Id:",$thousandUserIdArr);
                //组装当日生日的用户id
                $userIdStr = ToolArray::arrayToStr($thousandUserIdArr,"\n");

                //文件路径
                $filePath = '/uploads/' . ToolTime::dbDate() . '/' . strtolower(ToolStr::getRandStr(13)) . "_thousand.txt";
                //不存在目录时创建目录
                //$dirPath = base_path() . '/public/uploads/'.ToolTime::dbDate();
                //if (!is_dir($dirPath)) @mkdir($dirPath);
                //写入文件
                //@file_put_contents(base_path() . '/public/' . $filePath, $userIdStr);

                //改为Oss上传处理
                $oss = new OssLogic();
                $oss->writeFile( $userIdStr , $filePath );

                $data = [
                    'type' => 'bonus',
                    'admin_id' => 1, //todo 计划任务
                    'content' => isset($refundConfig['REFUND_THOUSAND_BONUS']) ? $refundConfig['REFUND_THOUSAND_BONUS'] : self::REFUND_THOUSAND_BONUS,
                    'note' => LangModel::USER_REFUND_BONUS."0.5%加息券".ToolTime::dbDate(),
                    'file_path' => $filePath,
                ];
                $res = $batchListLogic->doAdd($data);
                if($res['status']){
                    $batchListLogic->doAuditById($res['data']);
                }
            }
        }catch(\Exception $e){
            Log::info(__CLASS__.__METHOD__.__LINE__."sendTodayRefundUserBonus", [$e->getMessage()]);
            return self::callError($e->getMessage());
        }
        return self::callSuccess();
    }

    /**
     * @desc 获取回款金额大于一万的用户信息列表
     * @param        $todayRefundUser
     * @param string $keys
     * @return array
     */
    public function getRefundTenThousandUser($todayRefundUser, $keys = 'user_id'){

        $tenThousandUser = [];

        $refundConfig   =   SystemConfigModel::getConfig('REFUND_SEND_BONUS_RATE');

        $maxBaseCash    =   isset($refundConfig['REFUND_TEN_THOUSAND']) ? $refundConfig['REFUND_TEN_THOUSAND'] : self::REFUND_TEN_THOUSAND;

        foreach($todayRefundUser as $key=>$value){
            if($value['total_cash'] >= $maxBaseCash){
                $tenThousandUser[] = $value[$keys];
            }
        }
        return $tenThousandUser;
    }

    /**
     * @desc 获取回款金额介于(>=)1000～10000(<)之间的用户
     * @param        $todayRefundUser
     * @param string $keys
     * @return array
     */
    public function getRefundThousandUser($todayRefundUser, $keys='user_id'){
        $thousandUser = [];
        $refundConfig   =   SystemConfigModel::getConfig('REFUND_SEND_BONUS_RATE');

        $minBaseCash    =   isset($refundConfig['REFUND_THOUSAND']) ? $refundConfig['REFUND_THOUSAND'] : self::REFUND_THOUSAND;

        $maxBaseCash    =   isset($refundConfig['REFUND_TEN_THOUSAND']) ? $refundConfig['REFUND_TEN_THOUSAND'] : self::REFUND_TEN_THOUSAND;

        foreach($todayRefundUser as $key=>$value){
            if($value['total_cash'] >= $minBaseCash && $value['total_cash'] < $maxBaseCash){
                $thousandUser[] = $value[$keys];
            }
        }
        return $thousandUser;
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return array
     * @desc 控制查询的时间段
     */
    public static function setDiffSearchTime( $startTime , $endTime )
    {
        $diffNumber =   ToolTime::getDayDiff($startTime,$endTime);

        if( $diffNumber > self::DEFAULT_DIFF_TIME){

            return self::callError("建议查询的时间区间控制在一个月内");
        }

        return self::callSuccess();
    }

    /**
     * @param $parameter
     * @return mixed
     * @desc 红包的状态
     */
    public static function getUserBonusStatus($startTime,$endTime)
    {
        $parameter          =   self::setFormatParameter( $startTime , $endTime);

        //获取指定时间内发放的红包
        $allBonus           =   UserBonusDb::getUserBonusUsedSituation($parameter);

        //获取指定时间内已经使用的红包
        $usedBonus          =   UserBonusDb::getUserBonusUsedSituation($parameter,true);

        $allBonusList       =   array_merge($allBonus,$usedBonus);

        $return['list']     =   self::doFormatUserBonusStatus($allBonusList ,$allBonus,$usedBonus);

        $return['total_money']= self::doCalculationBonusTotal($usedBonus);

        return $return;
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return array
     * @desc 定义搜索的时间
     */
    protected static function setFormatParameter($startTime , $endTime )
    {
        return [
            'start_time'    =>  isset($startTime) ? date("Y-m-d H:i:s",ToolTime::getUnixTime($startTime)) : null,
            'end_time'      =>  isset($endTime) ? date("Y-m-d H:i:s",ToolTime::getUnixTime($endTime,"end")) : null,
        ];
    }

    /**
     * @param array $bonusList
     * @return int|number
     * @desc 红包总额
     */
    protected static function doCalculationBonusTotal($bonusList = array())
    {
        if( empty($bonusList) ){

            return 0;
        }

        return array_sum( array_column($bonusList,"total_cash"));
    }

    /**
     * @param array $allBonusList
     * @param array $usedBonus
     * @return array
     * @desc 格式化红包数据
     */
    protected static function doFormatUserBonusStatus( $allBonusList = array() , $allBonus =array(),$usedBonus = array())
    {
        if( empty($allBonusList) && empty($usedBonus) ){

            return [];
        }

        $formatList =   [];

        $usedBonus  =   ToolArray::arrayToKey($usedBonus, "bonus_id");

        $allBonus   =   ToolArray::arrayToKey($allBonus, "bonus_id");

        foreach ( $allBonusList as $key => $item ){

            $rateMoney      =   $item['type'] == BonusDb::TYPE_CASH ? $item['money']."元" : $item['rate']."%";

            $usedTotal      =   isset($usedBonus[$item['bonus_id']]) ? $usedBonus[$item['bonus_id']]['total_number'] : "0";

            $sendTotal      =   isset($allBonus[$item['bonus_id']]) ? $allBonus[$item['bonus_id']]['total_number'] : "0";

            $formatList[$key]   = [
                'bonus_id'      =>  $item['bonus_id'] ,
                'name'          =>  $item['name'],
                'type'          =>  $item['type'],
                'total'         =>  $sendTotal,
                'rate_money'    =>  $rateMoney,
                'used_total'    =>  $usedTotal,

            ];
        }

        return $formatList;
    }

    /**
     * @param $userId
     * @param $bonusId
     * @return array
     * @desc  通过红包id,用户id 执行用户领取红包
     */
    public function doSendBonusByUserIdWithBonusId($userId,$bonusId){

        $cacheKey = 'get_bonus_lock_'.$userId.'_'.$bonusId;

        if(Cache::has($cacheKey)){

            return self::callError('验证失败,请不要重复提交!');
        }
        Cache::put($cacheKey,1,0.2);   //0.2为过期时间,单位为分钟

        $bonusModel = new BonusModel();

        try{
            //验证用户id是否合法
            ValidateModel::isUserId($userId);

            //验证红包是否可以发放
            $bonusInfo[] = $bonusModel->checkBonus($bonusId);
            //格式化发送数据
            $data   = $this->formatMultiBonusInfo($bonusInfo, $userId);
            //执行发放
            $this->model->doSendBonus($data);

        }catch (\Exception $e){
            Log::error(__METHOD__.'Error',['msg' => $e->getMessage()]);

            return self::callError( $e->getMessage());
        }

        return self::callSuccess();

    }

    /**
     * @desc    用户是否以获取红包
     * @param   $userId     用户ID
     * @param   $bonusId    红包ID
     **/
    public function getReceivedBonusWithUser($userId, $bonusId ){

        $model = new UserBonusModel();

        $userBonus  = $model->getReceivedBonusWithUser($userId,$bonusId);

        return $userBonus;
    }
}
