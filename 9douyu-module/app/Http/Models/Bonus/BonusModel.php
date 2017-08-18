<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/6
 * Time: 下午8:57
 */

namespace App\Http\Models\Bonus;

use App\Http\Models\Model;

use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Tools\ToolTime;
use Log;

use App\Http\Models\Project\ProjectModel;

/**
 * 红包\加息券 model
 * Class BonusModel
 * @package App\Http\Models\Bonus
 */
class BonusModel extends Model
{

    public static $codeArr            = [
        'doCreate' => 1,
        'doUpdate' => 2,
        'findById' => 3,
        'isCanUseRedEnvelopeType'=>4,
        'checkClient' =>5,
        'checkProjectType' => 6,
        'isCanUseRedEnvelopeStatus'=>7,
        'isCanUseRedEnvelopeClient'=>8,
        'isCanUseRedEnvelopeMoney'=>9,
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_CREDIT_GROUT;


    /**
     * 创建保理债权
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function doCreate($data){

        $return = BonusDb::addRecord($data);
        if(!$return)
            throw new \Exception(LangModel::getLang('ERROR_BONUS_CREATE'), self::getFinalCode('doCreate'));

        return $return;
    }


    /**
     * 编辑债权
     * @param $id
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public static function doUpdate($id, $data = []){

        $return = BonusDb::doUpdate($id, $data);
        if(!$return)
            throw new \Exception(LangModel::getLang('ERROR_BONUS_UPDATE'), self::getFinalCode('doUpdate'));

        return $return;
    }

    /**
     * 根据ID获取对象
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function findById($id){
        $obj = BonusDb::findById($id);
        if(!$obj)
            throw new \Exception(LangModel::getLang('ERROR_BONUS_GET'), self::getFinalCode('findById'));

        return $obj;
    }

    /**
     * 格式化红包加息券数据数据
     * @param array $bonusRecord
     * @return array
     */
    public static function getLable($bonusRecord = []){

        if($bonusRecord['type'] == BonusDb::TYPE_CASH){
            $bonusRecord['label_name'] = '红包 ';
        }
        else{
            $bonusRecord['label_name'] = '加息券 ';
        }

        $client_type_array   = json_decode($bonusRecord['client_type'], true);

        $bonusRecord['client_name'] =  $bonusRecord['project_name'] = '';

        // 客户端
        if(!array_search(BonusDb::CLIENT_TYPE_ALL, $client_type_array) !== false){
            foreach(self::getClientData() as $k => $name){
                if(array_search($k, $client_type_array) !== false){
                    $bonusRecord['client_name'] .=  $name . ',';
                }
            }
        }

        $bonusRecord['client_name'] = rtrim($bonusRecord['client_name'], ',');
        //零钱计划加息券没有产品线
        if($bonusRecord['type'] != BonusDb::TYPE_COUPON_CURRENT){
            // 项目类型
            $project_type_array  = json_decode($bonusRecord['project_type'], true);
            $productLine         = ProjectModel::getProductLine();
            if(count($productLine) != count($project_type_array)) {
                foreach ($productLine as $lineCode => $lineName) {
                    if (array_search($lineCode, $project_type_array) !== false) {
                        $bonusRecord['project_name'] .= $lineName . ',';
                    }
                }
            }
            $bonusRecord['project_name'] = rtrim($bonusRecord['project_name'], ',');

        }

        if(!empty($bonusRecord['client_name']))
            $bonusRecord['client_name'] = '客户端：' . $bonusRecord['client_name'];
        if(!empty($bonusRecord['project_name']))
            $bonusRecord['project_name'] = '项目类型：' . $bonusRecord['project_name'];

        return $bonusRecord;
    }

    /**
     * APP4.0格式化红包加息券数据数据
     * @param array $bonusRecord
     * @return array
     */
    public static function getApp4Lable($bonusRecord){

        $bonusLine['project_name'] = '零钱计划可用';

        //零钱计划加息券没有产品线
        if($bonusRecord['bonus_type'] != BonusDb::TYPE_COUPON_CURRENT){

            $bonusLine['project_name'] = '';

            // 项目类型
            $project_type_array  = json_decode($bonusRecord['project_type'], true);
            $productLine         = ProjectModel::getAppBonusProductLine();

            foreach ($productLine as $lineCode => $lineName) {
                if (array_search($lineCode, $project_type_array) !== false) {
                    $bonusLine['project_name'] .= $lineName . '/';
                }
            }

            $bonusLine['project_name'] = rtrim($bonusLine['project_name'], '/');

            if($bonusLine['project_name'] == $productLine[ProjectDb::PRODUCT_LINE_FACTORING]){
                $bonusLine['project_name'] = '优选项目 '.$bonusLine['project_name'].' 可用';
            }else{
                $bonusLine['project_name'] = '优选项目 '.$bonusLine['project_name'].' 月期可用';
            }
        }

        return $bonusLine;
    }

    /**
     * 获取类型
     * @return array
     */
    public static function getType(){
        return [
            BonusDb::TYPE_COUPON_INTEREST   => '定期加息券',
            BonusDb::TYPE_COUPON_CURRENT    => '零钱计划加息券',
            BonusDb::TYPE_CASH              => '红包',
        ];
    }

    /**
     * @param $BonusId
     * @return mixed
     * @throws \Exception
     * @desc 检测红包是否可用,可用返回红包信息
     */
    public static function checkBonus($BonusId)
    {

        $bonusDb = new BonusDb();

        $bonusInfo = $bonusDb->getCanSendById($BonusId);

        if (empty($bonusInfo) || !is_array($bonusInfo)) {

                throw new \Exception('红包加息券不可发放', self::getFinalCode('checkBonus'));

        }

//        if($bonusInfo['effect_end_date'] != '0000-00-00' && $bonusInfo['effect_end_date']<=ToolTime::dbDate()){
//
//            throw new \Exception('红包加息券已过期', self::getFinalCode('checkBonus'));
//
//        }
//
        if( $bonusInfo['effect_type'] ==BonusDb::EFFECT_TIME && $bonusInfo['effect_end_date']<=ToolTime::dbDate())
        {
            throw new \Exception('红包加息券已过期', self::getFinalCode('checkBonus'));
        }

        return $bonusInfo;
    }

    /**
     * 检测优惠券是否可用
     *
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public static function isCanUseBonus($data = []){

        $id             = $data['id'];
        $client_type    = $data['client_type'];
        $project_type   = $data['project_type'];
        $money          = $data['invest_money'];
        $recordObj      = self::findById($id);

        $bonusTypeArr = self::getType();
        //状态判断
        if( !array_key_exists( $recordObj->type, $bonusTypeArr )){
            Log::info('不是优惠券可用类型'. json_encode($data));
            throw new \Exception(LangModel::getLang('ERROR_BONUS_IS_CAN_USE'), self::getFinalCode('isCanUseRedEnvelopeType'));
        }

        //状态判断
        if($recordObj->status != BonusDb::STATUS_PUBLIC){
            Log::info('状态不为发布'. json_encode($data));
            throw new \Exception(LangModel::getLang('ERROR_BONUS_IS_CAN_USE'), self::getFinalCode('isCanUseRedEnvelopeStatus'));
        }

        // 客户端类型 || 项目类型 为空
        if(empty($recordObj->client_type) || (empty($recordObj->project_type) && $recordObj->type != BonusDb::TYPE_COUPON_CURRENT)){
            Log::info('数据表客户端 或 项目类型为空'. json_encode($data));
            throw new \Exception(LangModel::getLang('ERROR_BONUS_IS_CAN_USE'), self::getFinalCode('isCanUseRedEnvelopeClient'));
        }

        // 客户端检测
        self::checkClient($client_type, $recordObj->client_type);

        if($recordObj->type != BonusDb::TYPE_COUPON_CURRENT){
            // 项目类型检测
            self::checkProjectType($project_type, $recordObj->project_type);
        }

        // 投资金额最小金额验证
        if($money < $recordObj->min_money && $recordObj->type != BonusDb::TYPE_COUPON_CURRENT){
            Log::info('投资金额最小验证失败：'. json_encode($data));
            throw new \Exception(LangModel::getLang('ERROR_BONUS_IS_CAN_USE'), self::getFinalCode('isCanUseRedEnvelopeMoney'));
        }

        return true;
    }


    /**
     * 验证 项目类型
     * @param string $project_type 传入的项目类型
     * @param string $table_project_type 数据表 项目类型
     * @return bool
     * @throws \Exception
     */
    public static function checkProjectType($project_type, $table_project_type){
        $project_type_array = json_decode($table_project_type, true);

        //项目类型验证失败
        if(array_search($project_type, $project_type_array) === false){
            throw new \Exception(LangModel::getLang('ERROR_BONUS_PROJECT_TYPE'), self::getFinalCode('checkProjectType'));
        }
        return true;
    }

    /**
     * 客户端验证
     *
     * @param string $client_type 传入的客户端类型
     * @param string $table_client_type 数据表的client
     * @return bool
     * @throws \Exception
     */
    public static function checkClient($client_type, $table_client_type){
        $client_type_array  = json_decode($table_client_type, true);
        // 客户端验证失败
        if(array_search(BonusDb::CLIENT_TYPE_ALL, $client_type_array) === false){
            if(array_search($client_type, $client_type_array) === false)
            {
                throw new \Exception(LangModel::getLang('ERROR_BONUS_CLIENT'), self::getFinalCode('checkClient'));
            }
        }
        return true;
    }
    /**
     * 获取使用类型
     * @return array
     */
    public static function getUseType(){
        return [
            BonusDb::TYPE_USE_INVEST => '投资使用',
        ];
    }

    /**
     * 获取客户端类型
     * @return array
     */
    public static function getClientData(){
        return [
            BonusDb::CLIENT_TYPE_ALL=>'全部',
            BonusDb::CLIENT_TYPE_APP=>'App',
            BonusDb::CLIENT_TYPE_WAP=>'wap',
            BonusDb::CLIENT_TYPE_WEB=>'web',
        ];
    }

    /**
     * 获取状态
     * @return array
     */
    public static function getStatusData(){
        return [
            BonusDb::STATUS_LOCK     => '锁定',
            BonusDb::STATUS_PUBLIC   => '已发布',
            BonusDb::STATUS_UNPUBLIC => '未发布',
        ];
    }

    /**
     * 获取是否允许转让
     * @return array
     */
    public static function getAssignment(){
        return [
            BonusDb::ASSIGNMENT_ON  => '允许转让',
            BonusDb::ASSIGNMENT_OFF => '不允许转让',
        ];
    }
    /**
     * 列表显示数量
     */
    protected function getAdminListPageSize(){
        return 20;
    }

    /**
     * 获取红包加息券列表
     * @param array $condition
     * @return mixed
     */
    public function getList($condition = []){
        $size  = $this->getAdminListPageSize();

        return BonusDb::where($condition)->orderBy('id', 'desc')->paginate($size);
    }

    /**
     * @return array
     * @desc 来源对应值
     */
    public static function getClientArr(){

        return [
            'pc'        => BonusDb::CLIENT_TYPE_WEB,
            'wap'       => BonusDb::CLIENT_TYPE_WAP,
            'ios'       => BonusDb::CLIENT_TYPE_APP,
            'android'   => BonusDb::CLIENT_TYPE_APP,
        ];

    }

    /**
     * 获取生效类型
     * @return array
     */
    public static function getEffectType(){
        return [
            BonusDb::EFFECT_NOW  => '即时生效',
            BonusDb::EFFECT_TIME => '选择生效时间',
        ];
    }
}
