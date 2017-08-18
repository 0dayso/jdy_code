<?php
/**
 * create by Phpstorm
 * User: lgh-dev
 * Date: 16/09/25
 * Time: 14:03 PM
 * Desc: 活动签到Model层
 */

namespace App\Http\Models\Activity;

use App\Http\Models\Model;
use App\Http\Dbs\Activity\ActivitySignDb;
use App\Lang\LangModel;
use App\Tools\ToolTime;

class ActivitySignModel extends Model{
    protected $db;


    public function __construct()
    {
        $this->db = new ActivitySignDb();
    }

    /**
     * @desc 添加签到记录
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function addSign($data){

        $res = $this->db->addSign($data);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_ADD_SIGN'), self::getFinalCode('addSign'));
        }
        return $res;
    }

    /**
     * @desc 更新签到记录
     * @param $where
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function updateSign($where, $data){

        $res = $this->db->updateSign($where,$data);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_ADD_SIGN'), self::getFinalCode('updateSign'));
        }
        return $res;
    }

    /**
     * @desc 连续签到更新记录
     * @param $userId
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function updateContinueSign($userId, $type){

        $res = $this->db->updateContinueSign($userId, $type);
        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_ADD_SIGN'), self::getFinalCode('updateSign'));
        }
        return $res;
    }

    /**
     * @desc 非连续签到更新记录
     * @param $userId
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function updateNoContinueSign($userId, $type){

        $res = $this->db->updateNoContinueSign($userId, $type);

        if(!$res){
            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_ADD_SIGN'), self::getFinalCode('updateSign'));
        }
        return $res;
    }

    /**
     * @desc 检测用户是否已经有签到记录
     * @param $userId
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function checkSignRecord($userId, $type){

        if(empty($userId) || empty($type)){
            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_PARAM_NULL'), self::getFinalCode('checkSignRecord'));
        }
        return $this->db->getUserSign($userId, $type);
    }

    /**
     * @desc 检测当天是否重复签到
     * @param $signDay
     * @return bool
     * @throws \Exception
     * @throws \Exception
     */
    public function checkSignRepeat($signDay){
        if(empty($signDay)){
            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_PARAM_NULL'), self::getFinalCode('checkSignRepeat'));
        }
        $dayNum = ToolTime::getDayDiff($signDay, ToolTime::dbDate());
        if($dayNum == 0){
            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_SIGN_REPEAT'), self::getFinalCode('checkSignRepeat'));
        }
        return true;
    }

    /**
     * @desc 检测是否连续签到
     * @param $signDay
     * @return bool
     * @throws \Exception
     */
    public function checkSignContinue($signDay){
        if(empty($signDay)){
            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_PARAM_NULL'), self::getFinalCode('checkSignContinue'));
        }
        $dayNum =  ToolTime::getDayDiff($signDay, ToolTime::dbDate());

        if($dayNum > 1){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @desc 检测是否连续签到
     * @param $signDay
     * @return bool
     * @throws \Exception
     */
    public function idCheckSignContinue($signDay){
        
        $dayNum =  ToolTime::getDayDiff($signDay, ToolTime::dbDate());

        if($dayNum > 1){

            throw new \Exception(LangModel::getLang('ERROR_SIGN_NOT_CONTINUITY'), self::getFinalCode('checkSignContinue'));
        }

        return true;
    }
    /**
     * @desc 组装用户签到记录
     * @param $signRecord
     * @return string
     */
    public function getMergeSingRecord($signRecord){

        return $signRecord."|".ToolTime::dbDate();
    }

}