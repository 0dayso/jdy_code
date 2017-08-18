<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/7
 * Time: 下午3:31
 */

namespace App\Http\Models\Bonus;


use App\Http\Dbs\Bonus\UserBonusDb;
use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Tools\AdminUser;
use App\Tools\ToolArray;
use App\Tools\ToolTime;

class UserBonusModel extends Model
{

    private $db;

    public static $codeArr            = [
        'doSendBonus'  => 1,
        'checkBonusId' => 2,
        'checkUserId'  => 3,
        'checkIsExits' => 4,
        'addLock'      => 5,
        'delLock'      => 6,
        'checkExpireTime' => 7,
        'checkIsLock' => 8,
        'checkIsUsed' => 9,
        'doRegularUsedBonus' => 10,
        'checkCurrentBonusUsed' => 11,
        'checkUserBonus' => 12,
        'checkIsUnLock' => 13,
        'checkUserCurrentBonus' => 14,
        'checkBonusIsUsed'      => 15,
        'checkIsRefundType'     => 16,

    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_USER_BONUS;

    public function __construct()
    {

        $this -> db = new UserBonusDb();
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 执行红包发放
     */
    public function doSendBonus($data){


        $db = new UserBonusDb();

        $result = $db -> add($data);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_ADD'), self::getFinalCode('doSendBonus'));
        }

        return $result;

    }

    /**
     * @param $bonusId
     * @return bool
     * @throws \Exception
     * @desc 验证用户红包id是否合法
     */
    public function checkBonusId($bonusId){

        if(!$bonusId){
            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_ID'), self::getFinalCode('checkBonusId'));
        }

        return true;

    }

    /**
     * @param $getTime
     * @param $expire
     * @return bool|string
     * @desc 通过获取时间得到最后使用截止时间
     */
    public static function getUseEndTime($getTime ,$expire){

        return date("Y-m-d", strtotime("+".$expire.' days', strtotime($getTime)));

    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * @desc 检测是否存在
     */
    public static function checkIsExits($id){

        $db = new UserBonusDb();

        $result = $db -> getById($id);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_NOT_FIND'), self::getFinalCode('checkIsExits'));
        }


        return $result;

    }

    /**
     * @param $bonusInfo
     * 判断红包或加息券是否已使用
     */
    public static function checkBonusIsUsed($bonusInfo){

        if($bonusInfo['used_time'] != '0000-00-00 00:00:00' && $bonusInfo['lock'] == UserBonusDb::LOCK_TRUE){

            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_IS_USED'), self::getFinalCode('checkBonusIsUsed'));

        }

    }

    /**
     * @param $userBonus
     * @throws \Exception
     * @desc 检测用户红包是否可用,封装一个公共方法,避免Logic重复调用
     */
    public static function checkUserBonusCanUse($userBonus)
    {

        self::checkBonusIsUsed($userBonus);

        self::checkStartTime($userBonus['use_start_time']);

        self::checkExpireTime($userBonus['use_end_time']);

        self::checkIsLock($userBonus['lock']);

    }

    /**
     * @param $id
     * @throws \Exception
     * @desc 数据加锁
     * @return mixed
     */
    public static function addLock($id){

        $db = new UserBonusDb();

        self::checkIsExits($id);

        $result = $db -> getLockById($id);

        if($result){
            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_IS_LOCK'), self::getFinalCode('addLock'));
        }

        $result = $db -> addLock($id);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_ADD_LOCK'), self::getFinalCode('addLock'));
        }

        return $result;

    }

    /**
     * @param $id
     * @throws \Exception
     * @desc 数据加锁
     * @return mixed
     */
    public static function delLock($id){

        $db = new UserBonusDb();

        self::checkIsExits($id);

        $result = $db -> getUnLockById($id);

        if($result){
            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_NU_LOCK'), self::getFinalCode('delLock'));
        }

        $result = $db -> delLock($id);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_DEL_LOCK'), self::getFinalCode('delLock'));
        }

        return $result;

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户优惠券已使用列表
     */
    public function getUsedListByUserId($userId){

        $db = new UserBonusDb();

        $result = $db -> getUsedListByUserId($userId);

        $result = $this -> formatUserBonusInfo($result);

        return $result;

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户优惠券可用列表
     */
    public function getAbleUseListByUserId($userId){

        $db = new UserBonusDb();

        $result = $db -> getAbleUseListByUserId($userId);

        $result = $this -> formatUserBonusInfo($result);

        return $result;

    }

    /**
     * @param $bonusId
     * @return mixed
     * 根据加息券ID,查询相应的信息
     */
    public function getUserBonusById($id){

        $db = new UserBonusDb();

        $result = $db->getById($id);
        if(!empty($result)){
            $result = $this -> formatUserBonusInfo([$result]);

            return $result[0];
        }

        return $result;

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户优惠券过期列表
     */
    public function getExpireListByUserId($userId){

        $db = new UserBonusDb();

        $result = $db -> getExpireListByUserId($userId);

        $result = $this -> formatUserBonusInfo($result);

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * @desc 格式化用户优惠券数据
     */
    public function formatUserBonusInfo($data){

        if(is_array($data) && !empty($data)){

            $bonusDb    = new BonusDb();

            //获取红包id的集合
            $bonusIds = ToolArray::arrayToIds($data, 'bonus_id');

            $bonusInfoList  = $bonusDb->getByIds($bonusIds);
            $bonusInfoList  = ToolArray::arrayToKey($bonusInfoList, 'id');

            foreach($data as $key => $item){
                //$bonusInfo  = $bonusDb -> getById($item['bonus_id']);
                $bonusInfo = isset($bonusInfoList[$item['bonus_id']]) ? $bonusInfoList[$item['bonus_id']] : '';
                if(empty($bonusInfo)){
                    unset($data[$key]);
                    continue;
                }
                // todo lqh 调用接口格式化优惠券信息方法
                $data[$key]['bonus_info'] = BonusModel::getLable($bonusInfo);
                $data[$key]['end_time'] = date("Y-m-d H:i:s",strtotime($item['use_end_time'])-1);
            }
        }

        return $data;

    }

    /**
     * @param $userId
     * @param $productLine
     * @param $type
     * @param $appRequest
     * @desc 获取定期优惠券
     * @return mixed
     */
    public function getAbleUserBonusListByProject($userId, $productLine, $type, $appRequest, $refundType=''){

        $data = $this -> db -> getRegularAbleBonusList($userId);

        if(empty($data)) return [];

        foreach($data as $key => $item){
            try{

                $this->checkIsRefundType($item['type'], $refundType);

                //验证项目类型是否存在
                $projectType = $productLine + $type;
                BonusModel::checkProjectType($projectType, $item['project_type']);

                if(!is_numeric($appRequest)){
                    $appRequest = RequestSourceLogic::getSourceKey($appRequest);
                }

                //验证来源是否存
                if($appRequest >= BonusDb::CLIENT_TYPE_APP) {

                    $appRequest = BonusDb::CLIENT_TYPE_APP;

                }
                BonusModel::checkClient($appRequest, $item['client_type']);

            }catch(\Exception $e){
                unset($data[$key]);
                continue;
            }


        }

        return $data;

    }

    /**
     * @param $userId
     * @param $appRequest
     * @desc 获取零钱计划优惠券
     * @return mixed
     */
    public function getCurrentAbleUserBonusList($userId, $appRequest){

        $data = $this -> db -> getCurrentAbleBonusList($userId);

        if(empty($data)) return [];

        foreach($data as $key => $item){
            try{

                //验证来源是否存

                if(!is_numeric($appRequest)){
                    $appRequest = RequestSourceLogic::getSourceKey($appRequest);
                }

                if($appRequest >= BonusDb::CLIENT_TYPE_APP) {

                    $appRequest = BonusDb::CLIENT_TYPE_APP;

                }

                BonusModel::checkClient($appRequest, $item['client_type']);

            }catch(\Exception $e){
                unset($data[$key]);
                continue;
            }

        }

        return $data;

    }

    /**
     * @param $userId
     * @param $userBonusId
     * @param $productLine
     * @param $type
     * @param $appRequest
     * @param $cash
     * @param $bonusType
     * @return bool
     * @throws \Exception
     * @desc 定期使用优惠券检测
     */
    public function checkUserBonus($userId, $userBonusId,$appRequest,$cash = 0,$productLine=0,$projectType=0,$bonusType=''){

        $userBonusInfo = self::checkIsExits($userBonusId);

        self::checkBonusIsUsed($userBonusInfo);

        //验证开始使用时间
        $this -> checkStartTime($userBonusInfo['use_start_time']);

        //验证是否已过期
        $this -> checkExpireTime($userBonusInfo['use_end_time']);

        //验证是否已锁定
        $this -> checkIsUnLock($userBonusInfo['lock']);

        //验证是否已使用
        $this -> checkIsUsed($userBonusInfo['used_time']);

        if($userId != $userBonusInfo['user_id']){

            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_NOT_FIND'), self::getFinalCode('checkUserBonus'));

        }

        $bonusId = $userBonusInfo['bonus_id'];

        $model = new BonusModel();

        $appRequest = RequestSourceLogic::getSourceKey($appRequest);

        if($appRequest >= BonusDb::CLIENT_TYPE_APP) {

            $appRequest = BonusDb::CLIENT_TYPE_APP;

        }

        $bonusInfo = [
            'id' => $bonusId,
            'client_type'  => $appRequest,
            'project_type' => $productLine + $projectType,
            'invest_money' => $cash,
        ];

        $result = $model -> isCanUseBonus($bonusInfo);

        return $result;

    }

    /**
     * @param $startTime
     * @return bool
     * @throws \Exception
     * @desc 检测红包开始使用时间
     */
    public static function checkStartTime($startTime){

        if($startTime > ToolTime::dbNow()){

            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_START'), self::getFinalCode('checkStartTime'));

        }

        return true;

    }

    /**
     * @param $expireTime
     * @return bool
     * @throws \Exception
     * @desc 检测红包是否过期
     */
    public static function checkExpireTime($expireTime){

        if($expireTime < ToolTime::dbNow()){

            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_EXPIRE'), self::getFinalCode('checkExpireTime'));

        }

        return true;

    }

    /**
     * @param $isLock
     * @return bool
     * @throws \Exception
     * @desc 检测红包是否为锁定状态
     */
    public static function checkIsLock($isLock){

        if($isLock == UserBonusDb::LOCK_FALSE){

            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_NU_LOCK'), self::getFinalCode('checkIsLock'));

        }

        return true;

    }

    /**
     * @param $isLock
     * @return bool
     * @throws \Exception
     * @desc 检测红包是否为未锁定状态
     */
    public static function checkIsUnLock($isLock){

        if($isLock == UserBonusDb::LOCK_TRUE){

            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_ADD_LOCK'), self::getFinalCode('checkIsUnLock'));

        }

        return true;

    }

    /**
     * @param $usedTime
     * @throws \Exception
     * @return bool
     * @desc 检测是否已使用
     */
    public static function checkIsUsed($usedTime){

        if($usedTime != UserBonusDb::USED_TIME){

            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_IS_USED'), self::getFinalCode('checkIsUsed'));

        }

        return true;

    }

    /**
     * @param $id
     * @param $investId
     * @return mixed
     * @throws \Exception
     * @desc 使用定期优惠券
     */
    public function doRegularUsedBonus($id, $investId=0){

        $result = $this -> db -> doRegularUsedBonus($id, $investId);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_USED_FAIL'), self::getFinalCode('doRegularUsedBonus'));

        }

        return $result;

    }

    /**
     * @param $id
     * @param $currentDay
     * @param $investId
     * @return mixed
     * @throws \Exception
     * @desc 使用零钱计划优惠券
     */
    public function doCurrentUsedBonus($id , $currentDay, $investId=0){

        $result = $this -> db -> doCurrentUsedBonus($id, $currentDay, $investId);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_USED_FAIL'), self::getFinalCode('doRegularUsedBonus'));

        }

        return $result;

    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     * @desc 检测零钱计划优惠券是否有可用中的
     */
    public function checkCurrentBonusUsed( $userId ){

        $result = $this -> db -> getCurrentIngUsedBonus( $userId );

        if($result){

            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_CURRENT_USED'), self::getFinalCode('checkCurrentBonusUsed'));

        }

        return $result;

    }

    /**
     * @param $userId
     * 获取使用中的零钱计划加息券列表
     */
    public function getUsingCurrentBonusList($userId){

        $bonusDb = new UserBonusDb();
        $bonusList = $bonusDb->getCurrentIngUsedBonus($userId);
        return $bonusList;
    }
    
    /**
     * @param $userId
     * @param string $bonusId
     * @throws \Exception
     * 检查加息券使用情况,若有使用的零钱计划红包,直接抛出异常
     */
    public function checkUserCurrentBonus($userId,$bonusId = ''){

        if($bonusId){

            $bonusList = $this->getUsingCurrentBonusList($userId);
            //当前有正在使用的零钱计划加息券,直接抛出异常
            if($bonusList){

                throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_BONUS_IS_USING'), self::getFinalCode('checkUserCurrentBonus'));

            }
        }

    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * @desc 分页列表可用优惠券数据
     */
    public function getAbleBonusByUserId($userId, $page, $size){

        $db = new UserBonusDb();

        $list = $db -> getAbleBonusByUserId($userId, $page, $size);

        return $list;

    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * @desc 分页列表过期优惠券数据
     */
    public function getExpireBonusByUserId($userId, $page, $size){

        $db = new UserBonusDb();

        $list = $db -> getExpireBonusByUserId($userId, $page, $size);

        return $list;

    }

    /**
     * @param $userId
     * @return array
     * @desc 分页列表4.0未使用优惠券数据
     */
    public function getBonusList($userId, $page, $size){

        $db = new UserBonusDb();

        $list = $db -> getBonusList($userId, $page, $size);

        return $list;

    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * @desc 4.0分页列表过期优惠券数据
     */
    public function getOutTimeBonusByUserId($userId, $page, $size){

        $db = new UserBonusDb();

        $list = $db -> getOutTimeBonusByUserId($userId, $page, $size);

        return $list;

    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * @desc 分页列表4.0已用优惠券数据
     */
    public function getUsedBonusByUserId($userId, $page, $size){

        $db = new UserBonusDb();

        $list = $db -> getUsedBonusByUserId($userId, $page, $size);

        return $list;

    }

    /**
     * @desc [管理后台]用户红包延期操作
     * @author lgh
     * @param $userBonusId
     * @param $endTime
     * @return mixed
     */
    public function doDelayUserBonus($userBonusId, $endTime){

        $data['use_end_time'] = $endTime;

        $data['send_user_id'] = AdminUser::getAdminUserId();

        $res = UserBonusDb::doUpdate($userBonusId, $data);

        return $res;
    }

    /**
     * @desc    通过用户ID，红包ID，获取用户红包信息
     * @param   $userId
     * @param   $bonusId
     * @return  array
     *
     **/
    public function getReceivedBonusWithUser($userId, $bonusId){

        $db = new UserBonusDb();

        $userBonusInfo  = $db->getReceivedBonusWithUser($userId, $bonusId);

        return $userBonusInfo;
    }

    /**
     * @param $bonusType
     * @param $refundType
     * @desc
     */
    public function checkIsRefundType($bonusType, $refundType){

        $db = new BonusDb();

        $refundTypeArr = $db->projectRefundType($bonusType);

        if(is_array($refundTypeArr) && !in_array($refundType, $refundTypeArr)){

            throw new \Exception(LangModel::getLang('该还款方式暂不支持使用加息券'), self::getFinalCode('checkIsRefundType'));

        }

        return false;

    }


}