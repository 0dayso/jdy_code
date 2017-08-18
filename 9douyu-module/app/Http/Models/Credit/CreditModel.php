<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Models\Credit;

use App\Http\Dbs\Credit\CreditDisperseDb;
use App\Http\Models\Model;

use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Http\Dbs\Credit\CreditDb;

use App\Http\Dbs\Credit\CreditViewAllDb;

use App\Http\Dbs\Credit\CreditOwnerDb;

use Log;

/**
 * 债权模型
 * Class CreditModel
 * @package App\Http\Models\Credit
 */
class CreditModel extends Model
{

    public static $codeArr            = [
        'getPageType'          => 1,
        'getDetailByIds'       => 2,
        'compareCash'          => 3,
        'resetHadMatchFullByIds' => 4
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_CREDIT;


    /**
     * 获取债权状态
     * @return array
     */
    public static function getStatusCode(){
        return[
            CreditDb::STATUS_CODE_UNUSED => '未使用',
            CreditDb::STATUS_CODE_ACTIVE => '已使用',
        ];
    }

    /**
     * 获取回款类型
     * @return array
     */
    public static function refundType($key = null){
        $return = [
            CreditDb::REFUND_TYPE_BASE_INTEREST  => '到期还本息',
            CreditDb::REFUND_TYPE_ONLY_INTEREST  => '按月付息，到期还本',
            CreditDb::REFUND_TYPE_FIRST_INTEREST => '投资当日付息，到期还本',
            CreditDb::REFUND_TYPE_WITH_BASE      => '等额本息',       //等额本息 老系统导入新系统暂时不用
            CreditDb::REFUND_TYPE_BASE_PRINCIPAL => '等额本金',       //等额本金 老系统导入新系统暂时不用
            CreditDb::REFUND_TYPE_CYCLE_INVEST   => '循环投资',       //循环投资 老系统导入新系统暂时不用
        ];
        if($key === null)
            return $return;
        if(isset($return[$key])){
            return $return[$key];
        }
        return null;
    }

    /**
     * 获取回款类型
     * @return array
     */
    public static function refundAppType($key = null){
        $return = [
            CreditDb::REFUND_TYPE_BASE_INTEREST  => '到期还本息',
            CreditDb::REFUND_TYPE_ONLY_INTEREST  => '按月付息，到期还本',
            CreditDb::REFUND_TYPE_FIRST_INTEREST => '投资当日付息，到期还本',
            CreditDb::REFUND_TYPE_WITH_BASE      => '等额本息',       //等额本息 老系统导入新系统暂时不用
            CreditDb::REFUND_TYPE_BASE_PRINCIPAL => '等额本金',       //等额本金 老系统导入新系统暂时不用
            CreditDb::REFUND_TYPE_CYCLE_INVEST   => '循环投资',       //循环投资 老系统导入新系统暂时不用
        ];
        if($key === null)
            return $return;
        if(isset($return[$key])){
            return $return[$key];
        }
        return null;
    }

    /**
     * 获取回款类型【用于 添加和 编辑】
     * @return array
     */
    public static function refundTypeForOperation(){
        $return = [
            CreditDb::REFUND_TYPE_BASE_INTEREST  => '到期还本息',
            CreditDb::REFUND_TYPE_ONLY_INTEREST  => '按月付息，到期还本',
            CreditDb::REFUND_TYPE_FIRST_INTEREST => '投资当日付息，到期还本',
            CreditDb::REFUND_TYPE_WITH_BASE      => '等额本息',
        ];

        return $return;
    }

    /**
     * 获取来源
     * @return array
     */
    public static function getSource(){
        return  [
            CreditDb::SOURCE_FACTORING          =>'耀盛保理',
            CreditDb::SOURCE_CREDIT_LOAN        =>'耀盛信贷',
            CreditDb::SOURCE_HOUSING_MORTGAGE   =>'房产抵押',
            CreditDb::SOURCE_THIRD_CREDIT       =>'第三方',
            CreditDb::SOURCE_TAO_SHOP           =>'淘当铺',
        ];
    }

    /**
     * @param string $key
     * @return mixed|string
     * @desc 通过key,获取来源名称
     */
    public static function getSourceByKey($key=''){

        $sourceArr = self::getSource();

        return isset($sourceArr[$key]) ? $sourceArr[$key] : '未知';

    }



    /**
     * 获取类型
     * @return array
     */
    public static function getType(){
        return [
            CreditDb::TYPE_BASE             =>'常规',
            CreditDb::TYPE_PROJECT_GROUP    =>'项目集',
            CreditDb::TYPE_NINE_CREDIT      =>'九省心',
            CreditDb::TYPE_CAR_LOAN         =>'车贷',
            CreditDb::TYPE_HOUSE_LOAN       =>'房贷',
            CreditDb::TYPE_CREDIT_LOAN      =>'信用贷',
        ];
    }



    /**
     * 获取来源
     * @return array
     */
    public static function getSourceType(){
        return  [
            CreditDb::SOURCE_FACTORING          =>'耀盛保理',
            CreditDb::SOURCE_CREDIT_LOAN        =>'耀盛信贷',
            CreditDb::SOURCE_HOUSING_MORTGAGE   =>'房产抵押',
            CreditDb::SOURCE_THIRD_CREDIT       =>'第三方',
            CreditDb::TYPE_PROJECT_GROUP        =>'项目集',
            CreditDb::TYPE_NINE_CREDIT          =>'九省心'
        ];
    }



    /**
     * 路由请求类型封装
     * @return array
     */
    public static function getRequestType(){
        return [
            CreditDb::SOURCE_FACTORING        =>'factoring',
            CreditDb::SOURCE_CREDIT_LOAN      =>'creditLoan',
            CreditDb::SOURCE_HOUSING_MORTGAGE =>'housingMortgage',
            CreditDb::SOURCE_THIRD_CREDIT     =>'thirdCredit',
            CreditDb::TYPE_BASE               =>'base',
            CreditDb::TYPE_PROJECT_GROUP      =>'projectGroup',
            CreditDb::TYPE_NINE_CREDIT        =>'nineCredit'
        ];
    }


    /**
     * 星级
     * @param bool|false $key
     * @return array
     */
    public static function getStar($key = false){
        $arr   = array(
            '5'     => '5星',
            '4'     => '4星',
            '3'     => '3星',
            '2'     => '2星',
            '1'     => '1星',
        );
        if($key) {
            return $arr[$key];
        }
        return $arr;
    }

    /**
     * 获取riskcale 评级
     */
    public static function getRiskcalcLevel(){
        $arr = [
            1=>'A+',
            2=>'A',
            3=>'A-',
            4=>'B+',
            5=>'B',
            6=>'B-',
            7=>'C',
        ];
        return $arr;
    }

    /**
     * 获取回款类型对应期限【单位】
     * @return array
     */
    public static function loanDeadlineDayOrMonth(){
        return [
            CreditDb::REFUND_TYPE_BASE_INTEREST  => '天',
            CreditDb::REFUND_TYPE_ONLY_INTEREST  => '月',
            CreditDb::REFUND_TYPE_FIRST_INTEREST => '月',

            CreditDb::REFUND_TYPE_WITH_BASE      => '月',       //等额本息 老系统导入新系统暂时不用
            CreditDb::REFUND_TYPE_BASE_PRINCIPAL => '月',       //等额本金 老系统导入新系统暂时不用
            CreditDb::REFUND_TYPE_CYCLE_INVEST   => '月',       //循环投资 老系统导入新系统暂时不用
        ];
    }

    /**
     * 获取 已方债权人信息 数组
     * @return array
     */
    public static function getCreditor(){
        return [
            1 => '北京耀盛小额贷款有限公司,91110229MA0051RL0D',
            2 => '池洪英,110222195008065720',
        ];
    }

    /**
     * 获取有效的请求类型
     *
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public static function getPageType($type){
        $requestType = self::getRequestType();
        if(($key = array_search($type, $requestType)) && $type != $requestType[CreditDb::TYPE_BASE]) {
            return $key;
        }
        throw new \Exception(LangModel::getLang('ERROR_CREDIT_VALID_TYPE'), self::getFinalCode('getPageType'));
    }

    /**
     * 所有保理：后台列表页面每页显示几条 子类可复写此方法更改自定义配置
     */
    protected function getAdminListPageSize(){
        return 10;//todo log 可配置项取
    }


    /**
     * 债权实际控制人 性别数组
     * @return array
     */
    public static function getOwnerSexData(){
        return [
            CreditOwnerDb::SEX_BOY  => '男',
            CreditOwnerDb::SEX_GIRL  => '女',
        ];
    }

    /**
     * 获取指定债权类
     * @param int $code
     * @return string
     */
    public static function getClass($code = 0){
        $class = null;
        switch($code){
            case CreditDb::SOURCE_FACTORING:
                $class = '\App\Http\Dbs\Credit\CreditFactoringDb';
                break;
            case CreditDb::SOURCE_CREDIT_LOAN:
                $class = '\App\Http\Dbs\Credit\CreditLoanDb';
                break;
            case CreditDb::SOURCE_THIRD_CREDIT:
                $class = '\App\Http\Dbs\Credit\CreditThirdDb';
                break;
            case CreditDb::SOURCE_HOUSING_MORTGAGE:
                $class = '\App\Http\Dbs\Credit\CreditHousingDb';
                break;
            case CreditDb::TYPE_NINE_CREDIT:
                $class = '\App\Http\Dbs\Credit\CreditNineDb';
                break;
            case CreditDb::TYPE_PROJECT_GROUP:
                $class = '\App\Http\Dbs\Credit\CreditGroupDb';
                break;
        }
        return $class;
    }

    /**
     * 获取债权详情
     * @param null $class 可用的债权class
     * @param array $idData id
     */
    public static function getDetailByIds($class = null, $idData = []){
        $return = $class::whereIn('id', $idData)->get()->toArray();
//        if(empty($return))
//            throw new \Exception(LangModel::getLang('ERROR_CREDIT_CREATE_FIND_BY_ID'), self::getFinalCode('getDetailByIds'));
        return $return;
    }


    /**
     * 获取所有数据
     * @param array $condition
     * @return mixed
     */
    public function getAllList($condition = []){
        $size  = $this->getAdminListPageSize();
        $lists = CreditViewAllDb::where($condition)->orderBy('created_at', 'desc')->paginate($size);
        //CreditViewAllDb::getSql();
        return $lists;
    }

    /**
     * @desc 通过ID获取债权合集的详情
     * @param $creditId
     * @return mixed
     */
    public static function getCreditDetailById($creditId){
        $return = CreditViewAllDb::where('credit_id', $creditId)->get()->toArray();
        return $return;
    }

    /**
     * 获取符合条件的债权Id集合【从视图中】
     * @param array $condition
     * @return mixed
     */
    public function geAllCreditIds($condition = []){
        $lists = CreditViewAllDb::where($condition)->orderBy('created_at', 'desc')->get();
        return $lists;
    }

    /**
     * @desc 获取指定条数的债权
     * @param  $size
     * @return mixed
     */
    public function getCreditArrayBySize($size){

        $lists = CreditViewAllDb::orderBy('created_at', 'desc')->take($size)->get()->toArray();

        return $lists;
    }

    /**
     * @desc 通过借款人姓名获取债权信息
     * @param $loanName
     * @return mixed
     */
    public function getCreditByLoanName($loanName){

        $lists = CreditViewAllDb::where('loan_username', 'like', '%'.$loanName.'%')->get()->toArray();

        return $lists;
    }

    /**
     * 比较 总额 和 可用金额
     */
    public static function compareCash($loan_amounts=0, $can_use_amounts =0){
        if(empty($can_use_amounts))
            return;
        if($can_use_amounts > $loan_amounts){
            throw new \Exception('可用金额不能大于借款金额', self::getFinalCode('compareCash'));
        }
    }


    /**
     * @desc    获取借款用户的借款信息
     * @param   $idNo   用户身份证号
     * @return  array
     **/
    public static function getCreditWithUser($idNo=""){

        $resData = [];
        if($idNo){
            $resData = CreditDb::getCreditWithUser($idNo);
        }

        return $resData;

    }

    /**
     * @desc    获取借款人数
     *
     **/
    public static function getCreditUser(){

        $result = CreditDb::getCreditUser();

        return $result?$result:[];

    }

    /**
     * @param array $ids
     * @return bool
     * @throws \Exception
     * @desc 更新已匹配的债权id
     */
    public function resetHadMatchFullByIds($ids=[]){

        if( empty($ids) ){

            throw new \Exception('债权ids为空', self::getFinalCode('resetHadMatchFullByIds'));

        }

        $db = new CreditDisperseDb();

        $result = $db->resetHadMatchFullByIds($ids);

        if( $result === false ){

            throw new \Exception('更新债权失败', self::getFinalCode('resetHadMatchFullByIds'));

        }

        return $result;

    }


}
