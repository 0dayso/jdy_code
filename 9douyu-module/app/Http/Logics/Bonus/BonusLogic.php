<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\Bonus;

use App\Http\Logics\Logic;

use App\Http\Models\Bonus\BonusModel;

use App\Http\Models\Project\ProjectModel;

use App\Http\Dbs\Bonus\BonusDb;

use Log;

use App\Tools\ToolMoney;
/**
 * 红包加息券逻辑
 * Class BonusLogic
 * @package App\Http\Logics\Bonus
 */
class BonusLogic extends Logic
{

    /**
     * 添加红包加息券
     * @param array $data
     * @return array
     */
    public static function doCreate($data = []){
        try {
            $attributes = self::filterAttributes($data);

            $return     = BonusModel::doCreate($attributes);

        }catch (\Exception $e){
            $attributes['data']           = $data;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$return]);
    }

    /**
     * 后台 添加/编辑 过滤白名单
     * @param array $data
     * @return array
     */
    public static function filterAttributes($data = []){
        $attributes = [
            'id'                =>!empty($data['id']) ? $data['id'] : null,//todo 【重构 API】 测试后移除本行
            'name'              =>$data['name'],
            'type'              =>$data['type'],
            'client_type'       =>!empty($data['client_type']) ? json_encode($data['client_type']) : null,
            'project_type'      =>!empty($data['project_type']) ? json_encode($data['project_type']) : null,
            'rate'              =>isset($data['rate']) ? $data['rate'] : null,
            'money'             =>isset($data['money']) ? $data['money'] : null,
            'use_type'          =>$data['use_type'],
            'min_money'         =>$data['min_money'],
            'effect_type'       =>$data['effect_type'],
            'effect_start_date' =>isset($data['effect_start_date']) && $data['effect_type']==BonusDb::EFFECT_TIME ? $data['effect_start_date'] : '',
            'effect_end_date'   =>isset($data['effect_end_date']) && $data['effect_type']==BonusDb::EFFECT_TIME ? $data['effect_end_date'] : '',
            'expires'           =>isset($data['expires']) && $data['effect_type']==BonusDb::EFFECT_NOW ? $data['expires'] : '',
            'current_day'       =>isset($data['current_day']) ? $data['current_day'] : '',
            'send_start_date'   =>$data['send_start_date'],
            'send_end_date'     =>$data['send_end_date'],
            'using_desc'        =>$data['using_desc'],
            'note'              =>$data['note'],
            'give_type'         =>$data['give_type'],
            'status'            =>isset($data['status']) ? $data['status'] : BonusDb::STATUS_UNPUBLIC,
        ];

        return $attributes;
    }


    /**
     * 编辑红包加息券
     * @param array $data
     * @return array
     */
    public static function doUpdate($data = []){
        try {
            $attributes = self::filterAttributes($data);

            $return     = BonusModel::doUpdate($data['id'], $attributes);

        }catch (\Exception $e){
            $attributes['data']           = $data;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$return]);
    }

    /**
     * 获取指定红包或加息券
     * @param int $id
     * @return array
     */
    public static function findById($id = 0){
        try{

            $obj = BonusModel::findById($id);

        }catch (\Exception $e){
            $data['id']             = $id;
            $data['msg']            = $e->getMessage();
            $data['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $data);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([ 'obj' => $obj]);
    }

    /**
     * 获取类型
     * @return array
     */
    public static function getType(){
        return BonusModel::getType();
    }

    /**
     * 获取使用类型
     * @return array
     */
    public static function getUseType(){
        return BonusModel::getUseType();
    }

    /**
     * 获取客户端类型
     * @return array
     */
    public static function getClientData(){
        return BonusModel::getClientData();
    }

    /**
     * 获取状态
     * @return array
     */
    public static function getStatusData(){
        return BonusModel::getStatusData();
    }

    /**
     * 获取是否允许转让
     * @return array
     */
    public static function getAssignment(){
        return BonusModel::getAssignment();
    }

    /**
     * 获取产品线
     * @return array
     */
    public static function getProductLine(){
        return ProjectModel::getProductLine();
    }

    /**
     * 获取生效类型
     * @return array
     */
    public static function getEffectType(){
        return BonusModel::getEffectType();
    }

    /**
     * 红包加息券列表
     * @param array $condition
     * @return array
     */
    public static function getList($condition = []){
        $classObj = new BonusModel;

        return self::formatOutput($classObj->getList($condition));
    }


    /**
     * 格式列表输出金额
     * @param array $listData
     * @return array
     */
    protected static function formatOutput($listData = []){
        if($listData){
            foreach($listData as $list){
                $list->money     = ToolMoney::formatDbCashDelete($list->money);
                $list->min_money = ToolMoney::formatDbCashDelete($list->min_money);
                $list->max_money = ToolMoney::formatDbCashDelete($list->max_money);
            }
        }
        return $listData;
    }


    /**
     * @param $id
     * @return array
     * 发布红包或加息券
     */
    public static function publishBonus($id){

        $result = self::findById($id);

        if(!$result['status']){
            return $result;
        }
            
        $return     = BonusDb::publishBonusById($id);
        if(!$return){
            return self::callError('红包或加息券发布失败');
        }

        return self::callSuccess([]);




    }

    /**
     * @param $bonusType
     * @param $bonusValue
     * @return array
     * @desc 根据红包类型获取红包或加息券的值
     */
    public function getBonusValueByType($bonusType,$bonusValue){

        if($bonusType == BonusDb::TYPE_CASH){

            $bonusMoney = $bonusValue;

            $bonusRate  = 0;
        }else if($bonusType == BonusDb::TYPE_COUPON_INTEREST){

            $bonusMoney = 0;

            $bonusRate  = $bonusValue;
        }else{

            $bonusMoney = 0;

            $bonusRate  = 0;
        }

        return ['money' => $bonusMoney, 'rate' => $bonusRate];
    }

    /**
     * @param $type
     * @return mixed
     * @desc 根据类型获取可以使用的红包,加息券,
     */
    public function getCanSendListByType( $type )
    {
        $db     =   new BonusDb();

        return $db->getCanSendListByType($type);
    }

    public static function doFormatBonusList( $bonusList = array() )
    {
         if( empty($bonusList) ){

             return [];
         }

        $formatList =   [];

        foreach ($bonusList as $key => $bonus ){

            $formatList[$key]   =   BonusModel::getLable($bonus);

        }
        return $formatList;
    }
    
}


